<?php
/**
 * Conexión a la base de datos para la empresa de ciberseguridad
 * Incluye funciones básicas para interactuar con la base de datos
 */

// Configuración de la base de datos
$db_config = [
    'host' => 'localhost',
    'dbname' => 'cybersec_db',
    'user' => 'riky',      // Cambiar por un usuario con privilegios limitados en producción
    'password' => '4578',      // Cambiar por una contraseña segura en producción
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];

/**
 * Establece conexión con la base de datos
 * @return PDO objeto de conexión
 */
function getDbConnection()
{
    global $db_config;

    try {
        $dsn = "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset={$db_config['charset']}";
        $pdo = new PDO($dsn, $db_config['user'], $db_config['password'], $db_config['options']);

        return $pdo;
    } catch (PDOException $e) {
        // En producción, registrar el error y mostrar un mensaje genérico
        error_log("Error de conexión a base de datos: " . $e->getMessage());
        throw new Exception("Error al conectar con la base de datos. Por favor, contacte al administrador.");
    }
}

/**
 * Ejecuta una consulta y devuelve múltiples resultados
 * @param string $sql Consulta SQL con placeholders
 * @param array $params Parámetros para la consulta preparada
 * @return array Resultados de la consulta
 */
function queryDb($sql, $params = [])
{
    $pdo = getDbConnection();

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error en consulta: " . $e->getMessage() . " - SQL: " . $sql);
        throw new Exception("Error al ejecutar la consulta en la base de datos.");
    }
}

/**
 * Ejecuta una consulta y devuelve un solo resultado
 * @param string $sql Consulta SQL con placeholders
 * @param array $params Parámetros para la consulta preparada
 * @return array|false Resultado de la consulta o false si no hay resultados
 */
function querySingleDb($sql, $params = [])
{
    $pdo = getDbConnection();

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error en consulta: " . $e->getMessage() . " - SQL: " . $sql);
        throw new Exception("Error al ejecutar la consulta en la base de datos.");
    }
}

/**
 * Inserta datos en una tabla y retorna el ID insertado
 * @param string $table Nombre de la tabla
 * @param array $data Datos a insertar (columna => valor)
 * @return int ID del registro insertado
 */
function insertDb($table, $data)
{
    $pdo = getDbConnection();

    try {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($data));

        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error en inserción: " . $e->getMessage() . " - Tabla: " . $table);
        throw new Exception("Error al insertar datos en la base de datos.");
    }
}

/**
 * Actualiza datos en una tabla
 * @param string $table Nombre de la tabla
 * @param array $data Datos a actualizar (columna => valor)
 * @param string $condition Condición WHERE (sin la palabra WHERE)
 * @param array $params Parámetros para la condición
 * @return int Número de filas afectadas
 */
function updateDb($table, $data, $condition, $params = [])
{
    $pdo = getDbConnection();

    try {
        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = "$column = ?";
        }

        $setClause = implode(', ', $sets);
        $sql = "UPDATE $table SET $setClause WHERE $condition";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_merge(array_values($data), $params));

        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log("Error en actualización: " . $e->getMessage() . " - Tabla: " . $table);
        throw new Exception("Error al actualizar datos en la base de datos.");
    }
}

/**
 * Elimina registros de una tabla
 * @param string $table Nombre de la tabla
 * @param string $condition Condición WHERE (sin la palabra WHERE)
 * @param array $params Parámetros para la condición
 * @return int Número de filas afectadas
 */
function deleteDb($table, $condition, $params = [])
{
    $pdo = getDbConnection();

    try {
        $sql = "DELETE FROM $table WHERE $condition";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log("Error en eliminación: " . $e->getMessage() . " - Tabla: " . $table);
        throw new Exception("Error al eliminar datos de la base de datos.");
    }
}

/**
 * Ejecuta un procedimiento almacenado
 * @param string $procedure Nombre del procedimiento
 * @param array $params Parámetros para el procedimiento
 * @return array Resultados del procedimiento
 */
function callProcedure($procedure, $params = [])
{
    $pdo = getDbConnection();

    try {
        $placeholders = implode(', ', array_fill(0, count($params), '?'));
        $sql = "CALL $procedure($placeholders)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error en procedimiento: " . $e->getMessage() . " - Procedimiento: " . $procedure);
        throw new Exception("Error al ejecutar el procedimiento en la base de datos.");
    }
}

/**
 * Inicia una transacción
 * @return PDO Objeto de conexión
 */
function beginTransaction()
{
    $pdo = getDbConnection();
    $pdo->beginTransaction();
    return $pdo;
}

/**
 * Confirma una transacción
 * @param PDO $pdo Conexión activa
 */
function commitTransaction($pdo)
{
    $pdo->commit();
}

/**
 * Revierte una transacción
 * @param PDO $pdo Conexión activa
 */
function rollbackTransaction($pdo)
{
    $pdo->rollBack();
}