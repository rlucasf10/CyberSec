<?php
require_once __DIR__ . '/../database/Database.php';

/**
 * Clase Usuario para gestionar los usuarios del sistema
 */
class Usuario
{
    private $db;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Registra un nuevo usuario en el sistema
     * @param string $email Correo electrónico
     * @param string $password Contraseña sin encriptar
     * @param string $tipoUsuario Tipo de usuario (admin, cliente, etc.)
     * @param int|null $referenciaId ID de referencia para tipos específicos
     * @return array Resultado del registro
     */
    public function registrar($email, $password, $tipoUsuario = 'cliente', $referenciaId = null)
    {
        try {
            // Generamos un salt único
            $salt = bin2hex(random_bytes(16));

            // Hasheamos la contraseña con el salt
            $passwordHash = hash('sha256', $password . $salt);

            // Parámetros para el procedimiento almacenado
            $params = [$email, $passwordHash, $salt, $tipoUsuario, $referenciaId];

            // Llamamos al procedimiento almacenado
            $result = $this->db->callProcedure('sp_registrar_usuario', $params);

            // Procesamos el resultado
            if ($row = $result->fetch()) {
                if (isset($row['resultado']) && $row['resultado'] === 'error') {
                    return [
                        'status' => 'error',
                        'message' => $row['mensaje'] ?? 'Error al registrar el usuario'
                    ];
                }

                return [
                    'status' => 'success',
                    'message' => 'Usuario registrado correctamente',
                    'usuario_id' => $row['usuario_id'] ?? null
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Error al procesar el registro'
            ];
        } catch (Exception $e) {
            error_log("Error al registrar usuario: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Ocurrió un error al procesar la solicitud'
            ];
        }
    }

    /**
     * Autentica a un usuario con sus credenciales
     * @param string $email Correo electrónico
     * @param string $password Contraseña sin encriptar
     * @return array Resultado de la autenticación con datos del usuario si es exitosa
     */
    public function autenticar($email, $password)
    {
        try {
            // Llamamos al procedimiento para obtener la información del usuario
            $result = $this->db->callProcedure('sp_autenticar_usuario', [$email]);

            // Verificamos si el usuario existe
            if ($usuario = $result->fetch()) {
                // Verificamos si el usuario está bloqueado
                if (isset($usuario['bloqueado']) && $usuario['bloqueado'] == 1) {
                    $this->registrarIntentoFallido($usuario['id']);
                    return [
                        'status' => 'error',
                        'message' => 'La cuenta ha sido bloqueada por múltiples intentos fallidos'
                    ];
                }

                // Verificamos la contraseña
                $passwordHash = hash('sha256', $password . $usuario['salt']);

                if ($passwordHash === $usuario['password_hash']) {
                    // Actualizamos último acceso y reiniciamos intentos fallidos
                    $this->actualizarUltimoAcceso($usuario['id']);

                    // Eliminamos información sensible
                    unset($usuario['password_hash'], $usuario['salt']);

                    return [
                        'status' => 'success',
                        'message' => 'Autenticación exitosa',
                        'usuario' => $usuario
                    ];
                } else {
                    // Incrementamos contador de intentos fallidos
                    $this->registrarIntentoFallido($usuario['id']);

                    return [
                        'status' => 'error',
                        'message' => 'Credenciales incorrectas'
                    ];
                }
            }

            return [
                'status' => 'error',
                'message' => 'Credenciales incorrectas'
            ];
        } catch (Exception $e) {
            error_log("Error al autenticar usuario: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Ocurrió un error al procesar la solicitud'
            ];
        }
    }

    /**
     * Registra un intento fallido de inicio de sesión
     * @param int $usuarioId ID del usuario
     * @return bool
     */
    private function registrarIntentoFallido($usuarioId)
    {
        try {
            $this->db->callProcedure('sp_actualizar_intentos_fallidos', [$usuarioId]);
            return true;
        } catch (Exception $e) {
            error_log("Error al registrar intento fallido: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza la fecha de último acceso del usuario
     * @param int $usuarioId ID del usuario
     * @return bool
     */
    private function actualizarUltimoAcceso($usuarioId)
    {
        try {
            $this->db->callProcedure('sp_actualizar_ultimo_acceso', [$usuarioId]);
            return true;
        } catch (Exception $e) {
            error_log("Error al actualizar último acceso: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Genera un token para recuperación de contraseña
     * @param string $email Correo electrónico
     * @return array Resultado con token generado si es exitoso
     */
    public function generarTokenRecuperacion($email)
    {
        try {
            $result = $this->db->callProcedure('sp_generar_token_recuperacion', [$email]);

            if ($row = $result->fetch()) {
                if (isset($row['resultado']) && $row['resultado'] === 'error') {
                    return [
                        'status' => 'error',
                        'message' => $row['mensaje'] ?? 'Email no encontrado'
                    ];
                }

                return [
                    'status' => 'success',
                    'message' => 'Token generado correctamente',
                    'token' => $row['token'] ?? null,
                    'email' => $email
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Error al generar el token'
            ];
        } catch (Exception $e) {
            error_log("Error al generar token: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Ocurrió un error al procesar la solicitud'
            ];
        }
    }

    /**
     * Verifica si un token de recuperación es válido
     * @param string $token Token de recuperación
     * @return array Resultado con estado de validez
     */
    public function verificarToken($token)
    {
        try {
            $result = $this->db->callProcedure('sp_verificar_token', [$token]);

            if ($row = $result->fetch()) {
                if (isset($row['es_valido']) && $row['es_valido'] == 1) {
                    return [
                        'status' => 'success',
                        'message' => 'Token válido',
                        'email' => $row['email'] ?? null
                    ];
                }

                return [
                    'status' => 'error',
                    'message' => 'Token inválido o expirado'
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Token no encontrado'
            ];
        } catch (Exception $e) {
            error_log("Error al verificar token: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Ocurrió un error al procesar la solicitud'
            ];
        }
    }

    /**
     * Cambia la contraseña de un usuario usando un token de recuperación
     * @param string $token Token de recuperación
     * @param string $newPassword Nueva contraseña sin encriptar
     * @return array Resultado del cambio de contraseña
     */
    public function cambiarPassword($token, $newPassword)
    {
        try {
            // Primero verificamos que el token sea válido
            $verificacion = $this->verificarToken($token);

            if ($verificacion['status'] !== 'success') {
                return $verificacion;
            }

            // Generamos un nuevo salt
            $salt = bin2hex(random_bytes(16));

            // Hasheamos la nueva contraseña
            $passwordHash = hash('sha256', $newPassword . $salt);

            // Cambiamos la contraseña
            $result = $this->db->callProcedure('sp_cambiar_password', [
                $token,
                $passwordHash,
                $salt
            ]);

            if ($row = $result->fetch()) {
                if (isset($row['resultado']) && $row['resultado'] === 'success') {
                    return [
                        'status' => 'success',
                        'message' => 'Contraseña actualizada correctamente'
                    ];
                }

                return [
                    'status' => 'error',
                    'message' => $row['mensaje'] ?? 'Error al cambiar la contraseña'
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Error al procesar la solicitud'
            ];
        } catch (Exception $e) {
            error_log("Error al cambiar contraseña: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Ocurrió un error al procesar la solicitud'
            ];
        }
    }

    /**
     * Obtiene la información de un usuario por su ID
     * @param int $id ID del usuario
     * @return array|null Datos del usuario o null si no existe
     */
    public function obtenerPorId($id)
    {
        try {
            $query = "SELECT id, email, tipo_usuario, referencia_id, ultimo_acceso, 
                      bloqueado, creado, actualizado 
                      FROM usuarios WHERE id = ?";

            return $this->db->fetchOne($query, [$id]);
        } catch (Exception $e) {
            error_log("Error al obtener usuario por ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene la información de un usuario por su email
     * @param string $email Email del usuario
     * @return array|null Datos del usuario o null si no existe
     */
    public function obtenerPorEmail($email)
    {
        try {
            $query = "SELECT id, email, tipo_usuario, referencia_id, ultimo_acceso, 
                      bloqueado, creado, actualizado 
                      FROM usuarios WHERE email = ?";

            return $this->db->fetchOne($query, [$email]);
        } catch (Exception $e) {
            error_log("Error al obtener usuario por email: " . $e->getMessage());
            return null;
        }
    }
}