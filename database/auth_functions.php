<?php
/**
 * Funciones de autenticación y gestión de usuarios
 * Implementa seguridad para registro, login y gestión de credenciales
 */

require_once 'db_connection.php';

/**
 * Genera un hash seguro de contraseña con salt único
 * @param string $password Contraseña en texto plano
 * @return array Array con el hash y el salt generado
 */
function generatePasswordHash($password)
{
    // Generamos un salt aleatorio de 32 bytes
    $salt = bin2hex(random_bytes(16));

    // Usamos algoritmo de hash seguro - ARGON2ID (recomendado para 2023+)
    // Con coste computacional alto para resistir ataques de fuerza bruta
    $hash = password_hash($password . $salt, PASSWORD_ARGON2ID, [
        'memory_cost' => 65536, // 64MB
        'time_cost' => 4,       // 4 iteraciones
        'threads' => 2          // 2 hilos paralelos
    ]);

    return [
        'hash' => $hash,
        'salt' => $salt
    ];
}

/**
 * Verifica si una contraseña coincide con el hash almacenado
 * @param string $password Contraseña en texto plano
 * @param string $storedHash Hash almacenado
 * @param string $salt Salt almacenado
 * @return bool True si la contraseña es correcta
 */
function verifyPassword($password, $storedHash, $salt)
{
    return password_verify($password . $salt, $storedHash);
}

/**
 * Registra un nuevo usuario en el sistema
 * @param string $email Email del usuario (nombre de usuario)
 * @param string $password Contraseña en texto plano
 * @param string $tipo_usuario 'cliente' o 'empleado'
 * @param int $referencia_id ID del cliente o empleado al que se vincula
 * @return int ID del usuario creado o 0 si falló
 */
function registrarUsuario($email, $password, $tipo_usuario, $referencia_id)
{
    // Validación básica
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("El correo electrónico no es válido");
    }

    if (strlen($password) < 10) {
        throw new Exception("La contraseña debe tener al menos 10 caracteres");
    }

    if ($tipo_usuario !== 'cliente' && $tipo_usuario !== 'empleado') {
        throw new Exception("Tipo de usuario no válido");
    }

    try {
        $pdo = beginTransaction();

        // Verificamos si el email ya existe
        $checkEmail = querySingleDb("SELECT id FROM usuarios WHERE email = ?", [$email]);
        if ($checkEmail) {
            rollbackTransaction($pdo);
            throw new Exception("El correo electrónico ya está registrado");
        }

        // Generamos hash y salt
        $passwordData = generatePasswordHash($password);

        // Preparamos datos
        $userData = [
            'email' => $email,
            'password_hash' => $passwordData['hash'],
            'salt' => $passwordData['salt'],
            'tipo_usuario' => $tipo_usuario,
            'referencia_id' => $referencia_id,
            'intentos_fallidos' => 0,
            'bloqueado' => 0,
            'creado' => date('Y-m-d H:i:s'),
            'actualizado' => date('Y-m-d H:i:s')
        ];

        // Insertamos el nuevo usuario
        $userId = insertDb('usuarios', $userData);

        commitTransaction($pdo);
        return $userId;
    } catch (Exception $e) {
        if (isset($pdo)) {
            rollbackTransaction($pdo);
        }
        error_log("Error en registro de usuario: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Autentica un usuario en el sistema
 * @param string $email Email del usuario
 * @param string $password Contraseña en texto plano
 * @return array|false Datos del usuario si la autenticación es exitosa o false si falla
 */
function autenticarUsuario($email, $password)
{
    try {
        // Obtenemos datos del usuario
        $user = querySingleDb(
            "SELECT id, email, password_hash, salt, tipo_usuario, referencia_id, 
                    ultimo_acceso, intentos_fallidos, bloqueado 
             FROM usuarios 
             WHERE email = ?",
            [$email]
        );

        if (!$user) {
            // Por seguridad, no indicamos si el usuario existe o no
            return false;
        }

        // Verificamos si la cuenta está bloqueada
        if ($user['bloqueado'] == 1) {
            throw new Exception("La cuenta está bloqueada. Por favor, contacte al administrador");
        }

        // Verificamos la contraseña
        if (verifyPassword($password, $user['password_hash'], $user['salt'])) {
            // Restablecemos los intentos fallidos y actualizamos último acceso
            updateDb(
                'usuarios',
                [
                    'ultimo_acceso' => date('Y-m-d H:i:s'),
                    'intentos_fallidos' => 0,
                    'actualizado' => date('Y-m-d H:i:s')
                ],
                'id = ?',
                [$user['id']]
            );

            // Eliminamos datos sensibles antes de retornar
            unset($user['password_hash']);
            unset($user['salt']);
            unset($user['intentos_fallidos']);

            return $user;
        } else {
            // Incrementamos los intentos fallidos
            $intentosFallidos = $user['intentos_fallidos'] + 1;
            $bloquear = ($intentosFallidos >= 5) ? 1 : 0;

            updateDb(
                'usuarios',
                [
                    'intentos_fallidos' => $intentosFallidos,
                    'bloqueado' => $bloquear,
                    'actualizado' => date('Y-m-d H:i:s')
                ],
                'id = ?',
                [$user['id']]
            );

            return false;
        }
    } catch (Exception $e) {
        error_log("Error en autenticación: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Genera un token de recuperación de contraseña
 * @param string $email Email del usuario
 * @return string|false Token generado o false si falla
 */
function generarTokenRecuperacion($email)
{
    try {
        // Verificamos si el email existe
        $user = querySingleDb("SELECT id FROM usuarios WHERE email = ?", [$email]);
        if (!$user) {
            // Por seguridad, no indicamos si el usuario existe o no,
            // pero retornamos false para control interno
            return false;
        }

        // Generamos token seguro
        $token = bin2hex(random_bytes(32));
        $fechaExpiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Actualizamos en base de datos
        updateDb(
            'usuarios',
            [
                'token_recuperacion' => $token,
                'fecha_token' => $fechaExpiracion,
                'actualizado' => date('Y-m-d H:i:s')
            ],
            'id = ?',
            [$user['id']]
        );

        return $token;
    } catch (Exception $e) {
        error_log("Error generando token: " . $e->getMessage());
        return false;
    }
}

/**
 * Verifica si un token de recuperación es válido
 * @param string $token Token a verificar
 * @return int|false ID del usuario si el token es válido o false si no lo es
 */
function verificarTokenRecuperacion($token)
{
    try {
        $user = querySingleDb(
            "SELECT id, fecha_token FROM usuarios 
             WHERE token_recuperacion = ? AND bloqueado = 0",
            [$token]
        );

        if (!$user) {
            return false;
        }

        // Verificamos si el token no ha expirado
        $ahora = new DateTime();
        $fechaToken = new DateTime($user['fecha_token']);

        if ($ahora > $fechaToken) {
            return false; // Token expirado
        }

        return $user['id'];
    } catch (Exception $e) {
        error_log("Error verificando token: " . $e->getMessage());
        return false;
    }
}

/**
 * Cambia la contraseña de un usuario
 * @param int $userId ID del usuario
 * @param string $newPassword Nueva contraseña
 * @return bool True si se cambió correctamente
 */
function cambiarPassword($userId, $newPassword)
{
    if (strlen($newPassword) < 10) {
        throw new Exception("La contraseña debe tener al menos 10 caracteres");
    }

    try {
        // Generamos nuevo hash y salt
        $passwordData = generatePasswordHash($newPassword);

        // Actualizamos en la base de datos
        updateDb(
            'usuarios',
            [
                'password_hash' => $passwordData['hash'],
                'salt' => $passwordData['salt'],
                'token_recuperacion' => null,
                'fecha_token' => null,
                'actualizado' => date('Y-m-d H:i:s')
            ],
            'id = ?',
            [$userId]
        );

        return true;
    } catch (Exception $e) {
        error_log("Error cambiando contraseña: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Bloquea o desbloquea una cuenta de usuario
 * @param int $userId ID del usuario
 * @param bool $bloquear True para bloquear, False para desbloquear
 * @return bool True si se realizó la operación correctamente
 */
function cambiarEstadoBloqueo($userId, $bloquear)
{
    try {
        updateDb(
            'usuarios',
            [
                'bloqueado' => $bloquear ? 1 : 0,
                'intentos_fallidos' => 0,
                'actualizado' => date('Y-m-d H:i:s')
            ],
            'id = ?',
            [$userId]
        );

        return true;
    } catch (Exception $e) {
        error_log("Error cambiando estado de bloqueo: " . $e->getMessage());
        return false;
    }
}