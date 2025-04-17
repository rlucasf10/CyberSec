<?php
/**
 * Archivo de inicialización del sistema
 * Este archivo debe ser incluido al inicio de todos los scripts PHP
 */

// Definir constante para permitir acceso a archivos de configuración
if (!defined('ACCESO_PERMITIDO')) {
    define('ACCESO_PERMITIDO', true);
}

// Configuración de la zona horaria
date_default_timezone_set('Europe/Madrid');

// Configuración de codificación
mb_internal_encoding('UTF-8');

// Configuración de reporte de errores
if (defined('DISPLAY_ERRORS') && DISPLAY_ERRORS) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Manejo de errores personalizado
function errorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // Este error no está incluido en error_reporting
        return false;
    }

    $error_message = "Error [$errno] $errstr en $errfile línea $errline";

    if (defined('LOG_ERRORS') && LOG_ERRORS) {
        error_log($error_message);
    }

    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        echo "<div style='color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo "<h3>Error del Sistema</h3>";
        echo "<p><strong>Tipo:</strong> $errno</p>";
        echo "<p><strong>Mensaje:</strong> $errstr</p>";
        echo "<p><strong>Archivo:</strong> $errfile</p>";
        echo "<p><strong>Línea:</strong> $errline</p>";
        echo "</div>";
    } else {
        echo "<div style='color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo "<p>Se ha producido un error inesperado. Si el problema persiste, contacte con el administrador del sistema.</p>";
        echo "</div>";
    }

    // Si es un error fatal, detenemos la ejecución
    if ($errno == E_USER_ERROR) {
        exit(1);
    }

    // No ejecutamos el manejador de errores interno de PHP
    return true;
}

// Registro del manejador de errores personalizado
set_error_handler("errorHandler");

// Iniciar o reanudar sesión
if (session_status() === PHP_SESSION_NONE) {
    // Configuración de seguridad para las cookies de sesión
    session_set_cookie_params([
        'lifetime' => 0,                  // La sesión expira al cerrar el navegador
        'path' => '/',                    // Disponible en todo el dominio
        'domain' => '',                   // Dominio actual
        'secure' => true,                 // Solo a través de HTTPS
        'httponly' => true,               // No accesible mediante JavaScript
        'samesite' => 'Strict'            // Política de mismo sitio
    ]);

    session_start();
}

// Regenerar ID de sesión para prevenir ataques de fijación de sesión
if (!isset($_SESSION['last_regeneration']) || time() - $_SESSION['last_regeneration'] > 300) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// Verificar tiempo de inactividad si está definido SESSION_TIMEOUT
if (defined('SESSION_TIMEOUT') && isset($_SESSION['ultimo_acceso']) && time() - $_SESSION['ultimo_acceso'] > SESSION_TIMEOUT) {
    // La sesión ha expirado, limpiar datos
    $_SESSION = [];

    // Destruir la cookie de sesión
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Destruir la sesión
    session_destroy();

    // Redireccionar al login con mensaje
    if (!headers_sent()) {
        header("Location: " . BASE_URL . "login.php?mensaje=sesion_expirada");
        exit;
    }
}

// Actualizar tiempo de último acceso
$_SESSION['ultimo_acceso'] = time();

// Cargar constantes
require_once __DIR__ . '/../config/constants.php';

// Cargar funciones auxiliares
require_once __DIR__ . '/funciones.php';

// Configuración de encabezados de seguridad
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("Referrer-Policy: strict-origin-when-cross-origin");
if (defined('APP_ENV') && APP_ENV === 'production') {
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
}

// Generar token CSRF si no existe
if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
    $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
}