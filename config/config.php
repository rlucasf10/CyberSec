<?php
// Prevenir acceso directo
if (!defined('ACCESO_PERMITIDO')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Acceso directo no permitido');
}

// Cargar variables de entorno primero
$dotenv = __DIR__ . '/../.env';
if (file_exists($dotenv)) {
    $lines = file($dotenv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0 || empty($line)) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        putenv("$key=$value");
        $_ENV[$key] = $value;
    }
}

// Información básica
define('APP_NAME', getenv('APP_NAME') ?: 'CyberSec');
define('APP_VERSION', getenv('APP_VERSION') ?: '1.0.0');

// Rutas del sistema
define('BASE_PATH', realpath(__DIR__ . '/..'));

// URL base
$protocol = (!empty($_SERVER['HTTPS']) || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domain = $_SERVER['HTTP_HOST'];
$project = '/CyberSec';

define('BASE_URL', $protocol . $domain . $project . '/');

// Seguridad
define('SESSION_TIMEOUT', 1800); // 30 minutos
define('CSRF_TOKEN_NAME', 'csrf_token');

// Debug
define('DEBUG_MODE', filter_var(getenv('DEBUG_MODE'), FILTER_VALIDATE_BOOLEAN));
define('DISPLAY_ERRORS', filter_var(getenv('DISPLAY_ERRORS'), FILTER_VALIDATE_BOOLEAN));
define('LOG_ERRORS', filter_var(getenv('LOG_ERRORS'), FILTER_VALIDATE_BOOLEAN));

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', DISPLAY_ERRORS ? '1' : '0');
ini_set('log_errors', LOG_ERRORS ? '1' : '0');
ini_set('error_log', __DIR__ . '/../logs/error.log');

// Cargar archivo de inicialización
require_once __DIR__ . '/init.php';

// Prevenir cualquier salida accidental
ob_start();

// Mensajes generales
define('ERROR_GENERIC', 'Ha ocurrido un error inesperado. Intente más tarde.');
define('ERROR_DB_CONNECTION', 'Error al conectar con la base de datos.');

// Configuración de base de datos
define('DB_HOST', getenv('DB_HOST'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASS', getenv('DB_PASS'));
define('DB_CHARSET', getenv('DB_CHARSET'));

// Configuración de correo SMTP
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');
define('SMTP_PORT', getenv('SMTP_PORT') ?: 587);
define('SMTP_USER', getenv('SMTP_USER'));
define('SMTP_PASS', getenv('SMTP_PASS'));
define('SMTP_FROM_EMAIL', getenv('SMTP_FROM_EMAIL'));
define('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') ?: 'CyberSec');
define('SMTP_DEBUG_LEVEL', DEBUG_MODE ? 2 : 0);

// Configuración de Google OAuth
define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID') ?? '');
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET') ?? '');
