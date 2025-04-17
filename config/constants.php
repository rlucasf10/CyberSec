<?php
/**
 * Archivo de configuración con constantes globales para el proyecto
 * Se debe incluir en todos los archivos que requieran rutas absolutas
 */

// Prevenir acceso directo al archivo
if (!defined('ACCESO_PERMITIDO')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Acceso directo a este archivo no permitido');
}

// Configuración de la aplicación
define('APP_NAME', 'CyberSec');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // 'development' o 'production'

// Rutas del sistema
define('BASE_PATH', realpath(dirname(__FILE__) . '/..'));
define('VIEWS_PATH', BASE_PATH . '/views');
define('CONTROLLERS_PATH', BASE_PATH . '/controllers');
define('MODELS_PATH', BASE_PATH . '/models');
define('CONFIG_PATH', BASE_PATH . '/config');
define('INCLUDES_PATH', BASE_PATH . '/includes');
define('ASSETS_PATH', BASE_PATH . '/assets');
define('UPLOADS_PATH', BASE_PATH . '/uploads');
define('LOGS_PATH', BASE_PATH . '/logs');

// URLs base
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'];

// Obtener la ruta base del proyecto
$projectPath = dirname($_SERVER['SCRIPT_FILENAME']);
$projectName = 'CyberSec'; // Nombre de tu carpeta de proyecto
$baseDir = '/' . $projectName;

define('BASE_URL', $protocol . $domainName . $baseDir . '/');
define('ASSETS_URL', BASE_URL . 'assets/');
define('CSS_URL', BASE_URL . 'assets/css/');
define('JS_URL', BASE_URL . 'assets/js/');
define('IMG_URL', BASE_URL . 'assets/img/');

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'cybersec_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Configuración de correo electrónico
define('MAIL_HOST', 'smtp.example.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'notifications@example.com');
define('MAIL_PASSWORD', 'your_password');
define('MAIL_ENCRYPTION', 'tls');
define('MAIL_FROM_ADDRESS', 'no-reply@cybersec.com');
define('MAIL_FROM_NAME', 'CyberSec');

// Configuración de seguridad
define('CSRF_TOKEN_NAME', 'csrf_token');
define('SESSION_TIMEOUT', 1800); // 30 minutos en segundos
define('LOGIN_ATTEMPTS', 5); // Número máximo de intentos fallidos 
define('TOKEN_LIFETIME', 3600); // Vida útil de los tokens en segundos (1 hora)
define('PASSWORD_MIN_LENGTH', 10);
define('PASSWORD_REQUIRE_SPECIAL', true);

// Configuración de API
define('API_KEY', 'your-api-key-here');
define('API_URL', 'https://api.example.com/v1/');

// Opciones de depuración
define('DEBUG_MODE', APP_ENV === 'development');
define('LOG_ERRORS', true);
define('DISPLAY_ERRORS', DEBUG_MODE);

// Mensajes de error personalizados
define('ERROR_DB_CONNECTION', 'Error en la conexión a la base de datos. Por favor, inténtelo de nuevo más tarde.');
define('ERROR_GENERIC', 'Se ha producido un error inesperado. Por favor, inténtelo de nuevo más tarde.');
define('ERROR_AUTHENTICATION', 'Credenciales incorrectas. Por favor, verifique su email y contraseña.');
define('ERROR_AUTHORIZATION', 'No tiene permisos para acceder a esta sección.');
define('ERROR_VALIDATION', 'Por favor, revise el formulario. Hay campos con errores.');
define('ERROR_CSRF', 'La sesión ha expirado o la solicitud no es válida. Por favor, inténtelo de nuevo.');

// Configuración de imágenes y archivos
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);
define('ALLOWED_DOC_TYPES', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);

// Configuración de paginación
define('ITEMS_PER_PAGE', 10);
define('MAX_PAGINATION_LINKS', 5);