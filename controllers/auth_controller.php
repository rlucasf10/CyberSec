<?php
// Definir constante para permitir acceso
define('ACCESO_PERMITIDO', true);

// Asegurar que no se envíe ningún output antes de los headers
ob_start();

session_start();

// Incluir archivos necesarios
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/usuario.php';
require_once __DIR__ . '/../src/Auth/GoogleAuth.php';
require_once __DIR__ . '/../src/Mail/Mailer.php';

use CyberSec\Mail\Mailer;
use CyberSec\Auth\GoogleAuth;

// Configuración de errores para AJAX
if (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
) {
    ini_set('display_errors', '0');
    error_reporting(0);
}

try {
    // Inicializar respuesta por defecto
    $response = ['status' => 'error', 'message' => ''];

    // Verificar método y CSRF para acciones que lo requieren
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $actionsRequiringCSRF = ['register', 'login', 'actualizar_perfil', 'recovery_request', 'reset_password', 'cerrar_cuenta'];
        $action = $_GET['action'] ?? '';

        if (in_array($action, $actionsRequiringCSRF) && (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token']))) {
            throw new Exception('Token CSRF inválido');
        }
    }

    // Inicializar el objeto Usuario
    $usuario = new Usuario();

    // Obtener la acción solicitada
    $action = $_GET['action'] ?? '';

    // Determinar si es una petición AJAX
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

    switch ($action) {
        case 'google_auth':
            try {
                $googleAuth = new GoogleAuth();
                $response = [
                    'status' => 'success',
                    'redirect' => $googleAuth->getAuthUrl()
                ];
            } catch (Exception $e) {
                error_log("Error en autenticación de Google: " . $e->getMessage());
                $response = [
                    'status' => 'error',
                    'message' => 'Error al iniciar sesión con Google',
                    'redirect' => BASE_URL . 'public/login.php'
                ];
            }
            break;

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

                $existingUser = $usuario->obtenerPorEmail($userData['email']);

                if (!$existingUser) {
                    // Crear nuevo usuario
                    $password = bin2hex(random_bytes(16));
                    $salt = bin2hex(random_bytes(32));
                    $password_hash = hash('sha256', $password . $salt);

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

                    $resultado = $usuario->registrar($datos);
                    if (!$resultado || isset($resultado['status']) && $resultado['status'] !== 'success') {
                        throw new Exception('Error al registrar el usuario con Google');
                    }

                    $existingUser = $usuario->obtenerPorEmail($userData['email']);
                }

                // Establecer la sesión
                $_SESSION['user_id'] = $existingUser['id'];
                $_SESSION['user_email'] = $existingUser['email'];
                $_SESSION['user_type'] = $existingUser['tipo'];
                $_SESSION['user_name'] = $existingUser['nombre'];
                $_SESSION['auth_method'] = 'google';

                $response = [
                    'status' => 'success',
                    'redirect' => BASE_URL . 'public/index.php'
                ];
            } catch (Exception $e) {
                error_log("Error en callback de Google: " . $e->getMessage());
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'redirect' => BASE_URL . 'public/login.php'
                ];
            }
            break;

        case 'login':
            try {
                $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
                $password = $_POST['password'] ?? '';
                $tipo_usuario = $_POST['tipo_usuario'] ?? '';

                if (!$email || !$password || !$tipo_usuario) {
                    throw new Exception('Por favor, complete todos los campos.');
                }

                $userData = $usuario->obtenerPorEmail($email);
                if (!$userData) {
                    throw new Exception('Credenciales incorrectas.');
                }

                if ($userData['tipo'] !== $tipo_usuario) {
                    throw new Exception('Tipo de usuario incorrecto.');
                }

                if ($userData['estado'] !== 'activo') {
                    throw new Exception('La cuenta no está activa. Contacte al administrador.');
                }

                $password_hash = hash('sha256', $password . $userData['salt']);
                if ($password_hash !== $userData['password_hash']) {
                    throw new Exception('Credenciales incorrectas.');
                }

                $_SESSION['user_id'] = $userData['id'];
                $_SESSION['user_email'] = $userData['email'];
                $_SESSION['user_type'] = $userData['tipo'];
                $_SESSION['user_name'] = $userData['nombre'];
                $_SESSION['auth_method'] = 'normal';

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
            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }
            break;

        case 'register':
            try {
                $datos = [
                    'nombre' => filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING),
                    'apellidos' => filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_STRING),
                    'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
                    'password' => $_POST['password'] ?? '',
                    'tipo_usuario' => filter_input(INPUT_POST, 'tipo_usuario', FILTER_SANITIZE_STRING),
                    'telefono' => filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING),
                    'direccion' => filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING),
                    'empresa' => filter_input(INPUT_POST, 'empresa', FILTER_SANITIZE_STRING),
                    'especialidad' => filter_input(INPUT_POST, 'especialidad', FILTER_SANITIZE_STRING)
                ];

                $usuario->validarDatosRegistro($datos);
                $resultado = $usuario->registrar($datos);

                if ($resultado['status'] === 'success') {
                    $response = [
                        'status' => 'success',
                        'message' => 'Usuario registrado correctamente',
                        'redirect' => BASE_URL . 'public/login.php'
                    ];
                } else {
                    $response = $resultado;
                }
            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }
            break;

        case 'actualizar_perfil':
            try {
                if (!isset($_SESSION['user_id'])) {
                    throw new Exception('Usuario no autenticado');
                }

                $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

                if (!$userId || $userId != $_SESSION['user_id']) {
                    throw new Exception('ID de usuario no válido');
                }

                // Filtrar y validar los datos de entrada
                $datosActualizacion = array_filter([
                    'nombre' => filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING),
                    'apellidos' => filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_STRING),
                    'telefono' => filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING),
                    'direccion' => filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING),
                    'empresa' => filter_input(INPUT_POST, 'empresa', FILTER_SANITIZE_STRING),
                    'especialidad' => filter_input(INPUT_POST, 'especialidad', FILTER_SANITIZE_STRING)
                ]);

                if (empty($datosActualizacion)) {
                    throw new Exception('No hay datos para actualizar');
                }

                $response = $usuario->actualizarPerfil($userId, $datosActualizacion);

                if ($response['status'] === 'success') {
                    $_SESSION['user_name'] = $datosActualizacion['nombre'] ?? $_SESSION['user_name'];
                }
            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }
            break;

        case 'cerrar_cuenta':
            try {
                if (!isset($_SESSION['user_id'])) {
                    throw new Exception('Usuario no autenticado');
                }

                $resultado = $usuario->cerrarCuenta($_SESSION['user_id']);
                if ($resultado['status'] === 'success') {
                    session_destroy();
                }
                $response = $resultado;
            } catch (Exception $e) {
                error_log("Error al cerrar cuenta: " . $e->getMessage());
                $response = [
                    'status' => 'error',
                    'message' => 'Error al procesar la solicitud'
                ];
            }
            break;

        case 'cambiar_contraseña':
            try {
                if (!isset($_SESSION['user_id'])) {
                    throw new Exception('Usuario no autenticado');
                }

                $password_actual = $_POST['password_actual'] ?? '';
                $password_nuevo = $_POST['password_nuevo'] ?? '';
                $password_confirmar = $_POST['password_confirmar'] ?? '';

                if (!$password_actual || !$password_nuevo || !$password_confirmar) {
                    throw new Exception('Todos los campos son requeridos');
                }

                if ($password_nuevo !== $password_confirmar) {
                    throw new Exception('Las contraseñas no coinciden');
                }

                // Obtener usuario actual
                $userData = $usuario->obtenerPorEmail($_SESSION['user_email']);
                if (!$userData) {
                    throw new Exception('Usuario no encontrado');
                }

                // Verificar contraseña actual
                $password_actual_hash = hash('sha256', $password_actual . $userData['salt']);
                if ($password_actual_hash !== $userData['password_hash']) {
                    throw new Exception('La contraseña actual es incorrecta');
                }

                // Validar nueva contraseña
                $usuario->validarPassword($password_nuevo);

                // Generar nuevo salt y hash
                $new_salt = bin2hex(random_bytes(32));
                $new_password_hash = hash('sha256', $password_nuevo . $new_salt);

                // Cambiar la contraseña
                $resultado = $usuario->cambiarPassword($userData['id'], $new_password_hash, $new_salt);

                $response = $resultado;
            } catch (Exception $e) {
                error_log("Error al cambiar contraseña: " . $e->getMessage());
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }
            break;

        default:
            $response = [
                'status' => 'error',
                'message' => 'Acción no válida'
            ];
            break;
    }
} catch (Exception $e) {
    error_log("Error general: " . $e->getMessage());
    $response = [
        'status' => 'error',
        'message' => DEBUG_MODE ? $e->getMessage() : ERROR_GENERIC
    ];
}

// Limpiar cualquier salida anterior
while (ob_get_level()) {
    ob_end_clean();
}

// Enviar la respuesta apropiada
if ($isAjax) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
} else if (!empty($response['redirect'])) {
    header('Location: ' . $response['redirect']);
} else {
    header('Location: ' . BASE_URL . 'public/index.php');
}
exit;