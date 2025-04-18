<?php
// Definir constante para permitir acceso
define('ACCESO_PERMITIDO', true);

// Asegurar que no se envíe ningún output antes de los headers
ob_start();

session_start();

// Incluir el autoloader de Composer y demás archivos necesarios
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/usuario.php';
require_once __DIR__ . '/../src/Auth/GoogleAuth.php';
require_once __DIR__ . '/../src/Mail/Mailer.php';

use CyberSec\Mail\Mailer;
use CyberSec\Auth\GoogleAuth;

// Obtener la acción solicitada
$action = $_GET['action'] ?? '';

// Inicializar el objeto Usuario
$usuario = new Usuario();
$response = ['status' => 'error', 'message' => ''];

try {
    switch ($action) {
        case 'google_auth':
            try {
                $googleAuth = new GoogleAuth();
                $authUrl = $googleAuth->getAuthUrl();
                header('Location: ' . $authUrl);
                exit;
            } catch (Exception $e) {
                error_log("Error en autenticación de Google: " . $e->getMessage());
                header('Location: ' . BASE_URL . 'public/login.php?error=' . urlencode('Error al iniciar sesión con Google'));
                exit;
            }

        case 'google_callback':
            try {
                if (isset($_GET['error'])) {
                    throw new Exception('Error en la autenticación con Google: ' . $_GET['error']);
                }

                if (!isset($_GET['code'])) {
                    throw new Exception('Código de autorización no recibido');
                }

                $googleAuth = new GoogleAuth();
                $userData = $googleAuth->handleCallback($_GET['code']);

                if (empty($userData['email'])) {
                    throw new Exception('No se pudo obtener el email del usuario de Google');
                }

                // Verificar si el usuario ya existe
                $existingUser = $usuario->obtenerPorEmail($userData['email']);

                if (!$existingUser) {
                    // Generar una contraseña aleatoria segura para usuarios de Google
                    $password = bin2hex(random_bytes(16));
                    $salt = bin2hex(random_bytes(32));
                    $password_hash = hash('sha256', $password . $salt);

                    // Preparar datos del usuario
                    $datos = [
                        'nombre' => $userData['nombre'],
                        'apellidos' => $userData['apellidos'],
                        'email' => $userData['email'],
                        'password' => $password_hash,
                        'tipo_usuario' => 'cliente',
                        'salt' => $salt,
                        'estado' => 'activo',
                        'auth_method' => 'google'
                    ];

                    // Registrar el nuevo usuario
                    $resultado = $usuario->registrar($datos);

                    if (!$resultado || (is_array($resultado) && isset($resultado['status']) && $resultado['status'] !== 'success')) {
                        error_log('Error al registrar usuario de Google: ' . json_encode($resultado));
                        throw new Exception('Error al registrar el usuario con Google');
                    }

                    // Obtener el usuario recién creado
                    $existingUser = $usuario->obtenerPorEmail($userData['email']);

                    if (!$existingUser) {
                        throw new Exception('Error al recuperar el usuario después del registro');
                    }
                }

                // Iniciar sesión
                $_SESSION['user_id'] = $existingUser['id'];
                $_SESSION['user_email'] = $existingUser['email'];
                $_SESSION['user_type'] = $existingUser['tipo'];
                $_SESSION['user_name'] = $existingUser['nombre'];
                $_SESSION['auth_method'] = 'google';

                header('Location: ' . BASE_URL . 'public/index.php');
                exit;

            } catch (Exception $e) {
                error_log("Error en callback de Google: " . $e->getMessage());
                header('Location: ' . BASE_URL . 'public/login.php?error=' . urlencode($e->getMessage()));
                exit;
            }

        default:
            // Verificar que sea una petición POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }

            // Verificar token CSRF
            if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
                throw new Exception('Token CSRF inválido');
            }

            // Obtener la acción solicitada
            $action = $_GET['action'] ?? '';

            $usuario = new Usuario();
            $response = ['status' => 'error', 'message' => ''];

            switch ($action) {
                case 'login':
                    // Validar datos de inicio de sesión
                    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
                    $password = $_POST['password'] ?? '';
                    $tipo_usuario = $_POST['tipo_usuario'] ?? '';

                    if (!$email || !$password || !$tipo_usuario) {
                        throw new Exception('Por favor, complete todos los campos.');
                    }

                    // Obtener usuario por email
                    $userData = $usuario->obtenerPorEmail($email);
                    if (!$userData) {
                        throw new Exception('Credenciales incorrectas.');
                    }

                    // Verificar tipo de usuario
                    if ($userData['tipo'] !== $tipo_usuario) {
                        throw new Exception('Tipo de usuario incorrecto.');
                    }

                    // Verificar estado del usuario
                    if ($userData['estado'] !== 'activo') {
                        throw new Exception('La cuenta no está activa. Contacte al administrador.');
                    }

                    // Verificar contraseña
                    $password_hash = hash('sha256', $password . $userData['salt']);
                    if ($password_hash !== $userData['password_hash']) {
                        throw new Exception('Credenciales incorrectas.');
                    }

                    // Iniciar sesión
                    $_SESSION['user_id'] = $userData['id'];
                    $_SESSION['user_email'] = $userData['email'];
                    $_SESSION['user_type'] = $userData['tipo'];
                    $_SESSION['user_name'] = $userData['nombre'];
                    $_SESSION['auth_method'] = 'normal';

                    // Si se marcó "recordar sesión", establecer una cookie
                    if (isset($_POST['remember']) && $_POST['remember'] === 'on') {
                        $token = bin2hex(random_bytes(32));
                        setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
                        $usuario->guardarTokenRecuerdo($userData['id'], $token);
                    }

                    $response = [
                        'status' => 'success',
                        'message' => '¡Bienvenido/a ' . $userData['nombre'] . '!',
                        'redirect' => BASE_URL . 'public/index.php'
                    ];
                    break;

                case 'register':
                    // Validar y sanitizar datos
                    $datos = [
                        'nombre' => sanitize($_POST['nombre'] ?? ''),
                        'apellidos' => sanitize($_POST['apellidos'] ?? ''),
                        'email' => sanitize($_POST['email'] ?? ''),
                        'password' => $_POST['password'] ?? '',
                        'tipo_usuario' => sanitize($_POST['tipo_usuario'] ?? ''),
                        'telefono' => sanitize($_POST['telefono'] ?? ''),
                        'direccion' => sanitize($_POST['direccion'] ?? ''),
                        'empresa' => sanitize($_POST['empresa'] ?? ''),
                        'especialidad' => sanitize($_POST['especialidad'] ?? '')
                    ];

                    // Validar datos
                    $usuario->validarDatosRegistro($datos);

                    // Registrar usuario
                    if ($usuario->registrar($datos)) {
                        $response = [
                            'status' => 'success',
                            'message' => 'Usuario registrado exitosamente',
                            'redirect' => BASE_URL . 'public/login.php'
                        ];
                    }
                    break;

                case 'recovery_request':
                    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
                    if (!$email) {
                        throw new Exception('El correo electrónico no es válido.');
                    }

                    $userData = $usuario->obtenerPorEmail($email);
                    if (!$userData) {
                        $response = [
                            'status' => 'success',
                            'message' => 'Se ha enviado un enlace de recuperación a tu correo electrónico.'
                        ];
                        break;
                    }

                    $token = bin2hex(random_bytes(32));
                    $result = $usuario->generarTokenRecuperacion($email, $token);

                    if ($result['status'] === 'success') {
                        $mailer = new Mailer();
                        $resetLink = BASE_URL . 'views/recuperar_password.php?token=' . $token;

                        $mailResult = $mailer->enviarRecuperacionPassword(
                            $email,
                            $userData['nombre'] ?? 'Usuario',
                            $resetLink
                        );

                        if ($mailResult['status'] === 'success') {
                            $response = [
                                'status' => 'success',
                                'message' => 'Se ha enviado un enlace de recuperación a tu correo electrónico.'
                            ];
                        } else {
                            $usuario->eliminarTokenRecuperacion($email);
                            throw new Exception('Error al enviar el correo de recuperación.');
                        }
                    } else {
                        throw new Exception($result['message'] ?? 'No se pudo procesar la solicitud.');
                    }
                    break;

                case 'reset_password':
                    // Validar token
                    $token = $_POST['token'] ?? '';
                    if (empty($token)) {
                        $response = [
                            'status' => 'error',
                            'message' => 'Token inválido o expirado.'
                        ];
                        break;
                    }

                    // Verificar token
                    $verificacion = $usuario->verificarToken($token);
                    if ($verificacion['status'] !== 'success') {
                        $response = [
                            'status' => 'error',
                            'message' => 'El enlace ha expirado o no es válido.'
                        ];
                        break;
                    }

                    // Validar contraseña
                    $password = $_POST['password'] ?? '';
                    if (strlen($password) < 10) {
                        $response = [
                            'status' => 'error',
                            'message' => 'La contraseña debe tener al menos 10 caracteres.'
                        ];
                        break;
                    }

                    // Generar nuevo salt y hash
                    $salt = bin2hex(random_bytes(16));
                    $passwordHash = hash('sha256', $password . $salt);

                    // Cambiar contraseña
                    $result = $usuario->cambiarPassword($verificacion['usuario_id'], $passwordHash, $salt);

                    if ($result['status'] === 'success') {
                        $response = [
                            'status' => 'success',
                            'message' => 'Contraseña actualizada correctamente.',
                            'redirect' => BASE_URL . 'public/login.php'
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => $result['message'] ?? 'Error al actualizar la contraseña.'
                        ];
                    }
                    break;

                default:
                    throw new Exception('Acción no válida');
            }
    }
} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => DEBUG_MODE ? $e->getMessage() : ERROR_GENERIC
    ];
}

// Asegurar que no haya salida adicional
while (ob_get_level() > 0) {
    ob_end_clean();
}

// Si es una petición AJAX, enviar respuesta JSON
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($response);
} else if (!empty($response['redirect'])) {
    header('Location: ' . $response['redirect']);
}
exit;