<?php
// Verificar que las constantes necesarias estén definidas
if (!defined('SESSION_TIMEOUT') || !defined('DEBUG_MODE')) {
    exit('Error en la configuración del sistema.');
}

// Configuración de sesión (DEBE IR ANTES DE INICIAR LA SESIÓN)
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.gc_maxlifetime', SESSION_TIMEOUT);

// Mantener el control del buffer de salida
if (ob_get_level() == 0) {
    ob_start();
}

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si se permite el acceso directo
if (!defined('ACCESO_PERMITIDO')) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Acceso directo no permitido']);
    } else {
        header('HTTP/1.1 403 Forbidden');
        echo 'Acceso directo no permitido';
    }
    exit;
}

// Incluir archivo de configuración si no está incluido
if (!defined('BASE_PATH')) {
    require_once dirname(dirname(__DIR__)) . '/config/config.php';
}

// Configuración de zona horaria
date_default_timezone_set('Europe/Madrid');

// Configuración de errores en desarrollo
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', DISPLAY_ERRORS);

// Función para sanitizar datos
function sanitize($data)
{
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Función para generar token CSRF si no existe
function generateCSRFToken()
{
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

// Generar token CSRF
generateCSRFToken();

// Función para validar token CSRF
function validateCSRFToken($token)
{
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

// Función para mostrar mensajes de error/éxito
function setMessage($mensaje, $tipo = 'info')
{
    $_SESSION['mensaje'] = $mensaje;
    $_SESSION['mensaje_tipo'] = $tipo;
}

// Función para verificar si el usuario está autenticado
function isAuthenticated()
{
    return isset($_SESSION['user_id']);
}

// Función para verificar el rol del usuario
function hasRole($role)
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
}

// Función para redireccionar
function redirect($url)
{
    if (!headers_sent()) {
        header("Location: " . $url);
    }
    exit();
}

// Función para obtener la URL actual
function getCurrentURL()
{
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") .
        "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

// Variables globales para la plantilla
$current_year = date('Y');
$version = time(); // Para control de caché en archivos CSS/JS

// Configuración SSL/TLS
ini_set('curl.cainfo', __DIR__ . '/../vendor/guzzlehttp/guzzle/src/cacert.pem');
ini_set('openssl.cafile', __DIR__ . '/../vendor/guzzlehttp/guzzle/src/cacert.pem');

// Configuración de codificación
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');