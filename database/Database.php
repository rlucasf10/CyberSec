<?php
/**
 * Clase Database para gestionar la conexión a la base de datos
 * Implementa patrón Singleton para eficiencia y seguridad
 */
class Database
{
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $db_name = 'ciberseguridad';
    private $username = 'root';
    private $password = '';

    /**
     * Constructor privado para evitar instanciación directa
     */
    private function __construct()
    {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_PERSISTENT => true
                ]
            );
        } catch (PDOException $e) {
            // En producción, guardar en log y mostrar mensaje genérico
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            die("Error: No se pudo conectar a la base de datos.");
        }
    }

    /**
     * Método para obtener la instancia única de Database
     * @return Database
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Obtiene la conexión PDO
     * @return PDO
     */
    public function getConnection()
    {
        return $this->conn;
    }

    /**
     * Ejecuta una consulta preparada con parámetros
     * @param string $query Consulta SQL
     * @param array $params Parámetros para la consulta
     * @return PDOStatement
     */
    public function executeQuery($query, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            throw new Exception("Error al ejecutar la consulta");
        }
    }

    /**
     * Ejecuta una consulta preparada y devuelve un único registro
     * @param string $query Consulta SQL
     * @param array $params Parámetros para la consulta
     * @return array|null
     */
    public function fetchOne($query, $params = [])
    {
        $stmt = $this->executeQuery($query, $params);
        return $stmt->fetch();
    }

    /**
     * Ejecuta una consulta preparada y devuelve todos los registros
     * @param string $query Consulta SQL
     * @param array $params Parámetros para la consulta
     * @return array
     */
    public function fetchAll($query, $params = [])
    {
        $stmt = $this->executeQuery($query, $params);
        return $stmt->fetchAll();
    }

    /**
     * Inicia una transacción
     */
    public function beginTransaction()
    {
        return $this->conn->beginTransaction();
    }

    /**
     * Confirma una transacción
     */
    public function commit()
    {
        return $this->conn->commit();
    }

    /**
     * Revierte una transacción
     */
    public function rollback()
    {
        return $this->conn->rollBack();
    }

    /**
     * Obtiene el último ID insertado
     * @return string
     */
    public function lastInsertId()
    {
        return $this->conn->lastInsertId();
    }

    /**
     * Ejecuta un procedimiento almacenado
     * @param string $procedure Nombre del procedimiento
     * @param array $params Parámetros para el procedimiento
     * @return PDOStatement|false
     */
    public function callProcedure($procedure, $params = [])
    {
        try {
            $placeholders = implode(',', array_fill(0, count($params), '?'));
            $query = "CALL {$procedure}({$placeholders})";
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error al llamar al procedimiento {$procedure}: " . $e->getMessage());
            throw new Exception("Error al ejecutar el procedimiento almacenado");
        }
    }
}