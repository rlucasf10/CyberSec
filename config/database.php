<?php
// Verificar si se permite el acceso directo
if (!defined('ACCESO_PERMITIDO')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Acceso directo no permitido');
}

// Cargar variables de entorno si no están definidas
if (!function_exists('loadEnv')) {
    function loadEnv()
    {
        $envFile = __DIR__ . '/../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '#') === 0)
                    continue;
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                if (!empty($name)) {
                    putenv(sprintf('%s=%s', $name, $value));
                    $_ENV[$name] = $value;
                }
            }
        }
    }
}

// Cargar variables de entorno
loadEnv();

// Clase para manejar la conexión a la base de datos
class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        try {
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=%s",
                getenv('DB_HOST') ?: 'localhost',
                getenv('DB_NAME') ?: 'cybersec_db',
                getenv('DB_CHARSET') ?: 'utf8mb4'
            );

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->connection = new PDO(
                $dsn,
                getenv('DB_USER'),
                getenv('DB_PASS'),
                $options
            );
        } catch (PDOException $e) {
            error_log("Error de conexión: " . $e->getMessage());
            throw new Exception(DEBUG_MODE ? "Error de conexión: " . $e->getMessage() : ERROR_DB_CONNECTION);
        }
    }

    // Prevenir clonación del objeto
    private function __clone()
    {
        throw new Exception('No se permite clonar esta instancia');
    }

    // Obtener instancia de la conexión (Singleton)
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Obtener la conexión PDO
    public function getConnection()
    {
        return $this->connection;
    }

    private function handleError($e, $customMessage = '')
    {
        error_log($customMessage . ": " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());

        // Verificar si es una petición AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        if ($isAjax) {
            // Limpiar cualquier salida anterior
            while (ob_get_level()) {
                ob_end_clean();
            }
            header('Content-Type: application/json; charset=utf-8');
            $response = [
                'status' => 'error',
                'message' => DEBUG_MODE ? $e->getMessage() : ERROR_GENERIC,
                'sql_error' => DEBUG_MODE ? $customMessage : null
            ];
            error_log("Enviando respuesta JSON de error: " . json_encode($response));
            echo json_encode($response);
            exit;
        }

        throw new Exception(DEBUG_MODE ? $e->getMessage() : ERROR_GENERIC);
    }

    // Ejecutar una consulta
    public function query($sql, $params = [])
    {
        try {
            error_log("SQL Query - Preparando consulta: " . $sql);
            error_log("SQL Params: " . print_r($params, true));

            $stmt = $this->connection->prepare($sql);
            if (!$stmt) {
                error_log("SQL Error - Fallo al preparar la consulta");
                throw new PDOException("Error al preparar la consulta");
            }

            $success = $stmt->execute($params);
            if (!$success) {
                $error = $stmt->errorInfo();
                error_log("SQL Error - Fallo al ejecutar: " . print_r($error, true));
                throw new PDOException("Error al ejecutar la consulta: " . ($error[2] ?? 'Error desconocido'));
            }

            error_log("SQL Success - Consulta ejecutada correctamente");
            error_log("SQL Affected rows: " . $stmt->rowCount());

            return $stmt;
        } catch (PDOException $e) {
            error_log("SQL Exception: " . $e->getMessage());
            error_log("SQL Trace: " . $e->getTraceAsString());
            return $this->handleError($e, "Error en la consulta");
        }
    }

    // Obtener un solo registro
    public function fetchOne($sql, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);

            if (!$stmt) {
                throw new Exception("Error al preparar la consulta");
            }

            if (!$stmt->execute($params)) {
                $error = $stmt->errorInfo();
                throw new Exception("Error al ejecutar la consulta: " . ($error[2] ?? 'Error desconocido'));
            }

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $this->handleError($e, "Error en consulta fetchOne");
        }
    }

    // Obtener todos los registros
    public function fetchAll($sql, $params = [])
    {
        return $this->query($sql, $params)->fetchAll();
    }

    // Obtener el último ID insertado
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    // Iniciar una transacción
    public function beginTransaction()
    {
        if (!$this->connection->inTransaction()) {
            return $this->connection->beginTransaction();
        }
        return false;
    }

    // Confirmar una transacción
    public function commit()
    {
        if ($this->connection->inTransaction()) {
            return $this->connection->commit();
        }
        return false;
    }

    // Revertir una transacción
    public function rollBack()
    {
        if ($this->connection->inTransaction()) {
            return $this->connection->rollBack();
        }
        return false;
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
            $placeholders = str_repeat('?,', count($params) - 1) . '?';
            $sql = "CALL $procedure($placeholders)";

            $stmt = $this->connection->prepare($sql);

            if (!$stmt) {
                throw new Exception("Error al preparar el procedimiento almacenado");
            }

            if (!$stmt->execute($params)) {
                $error = $stmt->errorInfo();
                throw new Exception("Error al ejecutar el procedimiento: " . ($error[2] ?? 'Error desconocido'));
            }

            return $stmt;
        } catch (PDOException $e) {
            error_log("Error en procedimiento almacenado: " . $e->getMessage());
            throw new Exception(DEBUG_MODE ? $e->getMessage() : "Error en la operación con la base de datos");
        }
    }
}
