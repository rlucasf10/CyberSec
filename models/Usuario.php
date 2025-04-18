<?php
// Verificar acceso directo
if (!defined('ACCESO_PERMITIDO')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Acceso directo no permitido');
}
require_once __DIR__ . '/../config/database.php'; 
class Usuario
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Registra un nuevo usuario en el sistema
     */
    public function registrar($datos)
    {
        try {
            $this->db->beginTransaction();

            // Validar email único
            $existeEmail = $this->obtenerPorEmail($datos['email']);
            if ($existeEmail) {
                throw new Exception("El correo electrónico ya está registrado");
            }

            // Generar salt y hash de la contraseña
            $salt = bin2hex(random_bytes(32));
            $password_hash = hash('sha256', $datos['password'] . $salt);

            // Insertar en la tabla usuarios
            $sql = "INSERT INTO usuarios (
                nombre, apellidos, email, telefono, direccion, 
                tipo, empresa, especialidad, password_hash, salt,
                estado, creado, actualizado
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'activo', NOW(), NOW())";

            $params = [
                $datos['nombre'],
                $datos['apellidos'],
                $datos['email'],
                $datos['telefono'] ?? null,
                $datos['direccion'] ?? null,
                $datos['tipo_usuario'],
                $datos['empresa'] ?? null,
                $datos['especialidad'] ?? null,
                $password_hash,
                $salt
            ];

            $this->db->query($sql, $params);
            $this->db->commit();

            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Obtiene un usuario por su email
     * @param string $email
     * @return array|null Retorna el usuario si existe, null si no existe
     */
    public function obtenerPorEmail($email): ?array
    {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        return $this->db->fetchOne($sql, [$email]);
    }

    /**
     * Valida el formato del email
     */
    private function validarEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El formato del correo electrónico no es válido");
        }
        return true;
    }

    /**
     * Valida la fortaleza de la contraseña
     */
    private function validarPassword($password)
    {
        if (strlen($password) < 10) {
            throw new Exception("La contraseña debe tener al menos 10 caracteres");
        }
        if (!preg_match('/[A-Z]/', $password)) {
            throw new Exception("La contraseña debe contener al menos una mayúscula");
        }
        if (!preg_match('/[a-z]/', $password)) {
            throw new Exception("La contraseña debe contener al menos una minúscula");
        }
        if (!preg_match('/[0-9]/', $password)) {
            throw new Exception("La contraseña debe contener al menos un número");
        }
        if (!preg_match('/[!@#$%^&*]/', $password)) {
            throw new Exception("La contraseña debe contener al menos un carácter especial (!@#$%^&*)");
        }
        return true;
    }

    /**
     * Valida los datos del registro
     */
    public function validarDatosRegistro($datos)
    {
        // Validar campos requeridos
        $campos_requeridos = ['nombre', 'apellidos', 'email', 'password', 'tipo_usuario'];
        foreach ($campos_requeridos as $campo) {
            if (empty($datos[$campo])) {
                throw new Exception("El campo {$campo} es requerido");
            }
        }

        // Validar email y contraseña
        $this->validarEmail($datos['email']);
        $this->validarPassword($datos['password']);

        // Validar tipo de usuario
        if (!in_array($datos['tipo_usuario'], ['cliente', 'empleado'])) {
            throw new Exception("Tipo de usuario no válido");
        }

        return true;
    }

    /**
     * Genera un token para recuperación de contraseña y lo guarda en la base de datos
     * @param string $email Correo electrónico
     * @param string $token Token generado
     * @return array Resultado de la operación
     */
    public function generarTokenRecuperacion($email, $token)
    {
        try {
            // Primero verificar si el email existe
            $usuario = $this->obtenerPorEmail($email);
            if (!$usuario) {
                return [
                    'status' => 'error',
                    'message' => 'No existe una cuenta con ese correo electrónico.'
                ];
            }

            $result = $this->db->callProcedure('sp_generar_token_recuperacion', [$email, $token]);

            // Verificar si la actualización fue exitosa
            if ($result && $result->rowCount() > 0) {
                return [
                    'status' => 'success',
                    'message' => 'Token generado correctamente'
                ];
            }

            return [
                'status' => 'error',
                'message' => 'No se pudo generar el token de recuperación.'
            ];

        } catch (Exception $e) {
            error_log("Error al generar token: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error al procesar la solicitud'
            ];
        }
    }

    /**
     * Verifica si un token de recuperación es válido
     * @param string $token Token a verificar
     * @return array Resultado de la verificación
     */
    public function verificarToken($token)
    {
        try {
            if (empty($token)) {
                return [
                    'status' => 'error',
                    'message' => 'Token inválido'
                ];
            }

            $result = $this->db->callProcedure('sp_verificar_token', [$token]);

            if ($row = $result->fetch()) {
                // Verificar que el token no haya expirado
                if (strtotime($row['fecha_token']) < time()) {
                    return [
                        'status' => 'error',
                        'message' => 'El token ha expirado'
                    ];
                }

                return [
                    'status' => 'success',
                    'usuario_id' => $row['id']
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Token inválido o expirado'
            ];
        } catch (Exception $e) {
            error_log("Error al verificar token: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error al verificar el token'
            ];
        }
    }

    /**
     * Cambia la contraseña de un usuario
     * @param int $usuarioId ID del usuario
     * @param string $passwordHash Hash de la nueva contraseña
     * @param string $salt Nuevo salt generado
     * @return array Resultado del cambio de contraseña
     */
    public function cambiarPassword($usuarioId, $passwordHash, $salt)
    {
        try {
            $result = $this->db->callProcedure('sp_cambiar_password', [
                $usuarioId,
                $passwordHash,
                $salt
            ]);

            return [
                'status' => 'success',
                'message' => 'Contraseña actualizada correctamente'
            ];
        } catch (Exception $e) {
            error_log("Error al cambiar contraseña: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error al actualizar la contraseña'
            ];
        }
    }

    /**
     * Elimina el token de recuperación de un usuario
     * @param string $email Correo del usuario
     * @return array Resultado de la operación
     */
    public function eliminarTokenRecuperacion($email)
    {
        try {
            $sql = "UPDATE usuarios SET token_recuperacion = NULL, fecha_token = NULL WHERE email = ?";
            $this->db->query($sql, [$email]);
            return [
                'status' => 'success',
                'message' => 'Token eliminado correctamente'
            ];
        } catch (Exception $e) {
            error_log("Error al eliminar token: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error al eliminar el token'
            ];
        }
    }

    /**
     * Guarda el token de "recordar sesión" para un usuario
     * @param int $userId ID del usuario
     * @param string $token Token generado
     * @return array Resultado de la operación
     */
    public function guardarTokenRecuerdo($userId, $token)
    {
        try {
            $sql = "UPDATE usuarios SET remember_token = ?, token_expiry = DATE_ADD(NOW(), INTERVAL 30 DAY) WHERE id = ?";
            $this->db->query($sql, [$token, $userId]);
            return [
                'status' => 'success',
                'message' => 'Token guardado correctamente'
            ];
        } catch (Exception $e) {
            error_log("Error al guardar token de recuerdo: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error al guardar el token de recuerdo'
            ];
        }
    }

    /**
     * Verifica si un token de recuerdo es válido
     * @param string $userId ID del usuario
     * @param string $token Token a verificar
     * @return bool True si el token es válido, false en caso contrario
     */
    public function verificarTokenRecuerdo($userId, $token)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM usuarios 
                   WHERE id = ? AND remember_token = ? 
                   AND token_expiry > NOW()";
            $result = $this->db->fetchOne($sql, [$userId, $token]);
            return ($result && $result['count'] > 0);
        } catch (Exception $e) {
            error_log("Error al verificar token de recuerdo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Limpia el token de recuerdo de un usuario
     * @param int $userId ID del usuario
     */
    public function limpiarTokenRecuerdo($userId)
    {
        try {
            $sql = "UPDATE usuarios SET remember_token = NULL, token_expiry = NULL WHERE id = ?";
            $this->db->query($sql, [$userId]);
            return [
                'status' => 'success',
                'message' => 'Token eliminado correctamente'
            ];
        } catch (Exception $e) {
            error_log("Error al limpiar token de recuerdo: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error al limpiar el token de recuerdo'
            ];
        }
    }
}
