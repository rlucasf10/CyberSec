<?php
if (!defined('ACCESO_PERMITIDO')) {
    define('ACCESO_PERMITIDO', true);
}

// Incluir archivo de configuración
require_once dirname(__DIR__) . '/config/config.php';

/**
 * Verifica si el usuario está autenticado
 * @return bool
 */
function verificarAutenticacion()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['user_id']);
}

/**
 * Verifica si el usuario tiene el rol requerido
 * @param array|string $roles_permitidos Roles que tienen acceso
 * @return bool
 */
function verificarRol($roles_permitidos)
{
    if (!verificarAutenticacion()) {
        return false;
    }

    if (!is_array($roles_permitidos)) {
        $roles_permitidos = [$roles_permitidos];
    }

    return in_array($_SESSION['user_type'], $roles_permitidos);
}

/**
 * Redirecciona según el rol del usuario
 */
function redireccionarSegunRol()
{
    $tipo_usuario = $_SESSION['user_type'] ?? '';

    switch ($tipo_usuario) {
        case 'admin':
            header('Location: ' . BASE_URL . 'views/panel/admin/dashboard.php');
            break;
        case 'empleado':
            header('Location: ' . BASE_URL . 'views/panel/empleado/dashboard.php');
            break;
        case 'cliente':
            header('Location: ' . BASE_URL . 'views/panel/cliente/dashboard.php');
            break;
        default:
            header('Location: ' . BASE_URL . 'public/login.php');
    }
    exit;
}

// Verificar si es una petición AJAX
function isAjaxRequest()
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

/**
 * Protege una ruta verificando la autenticación del usuario
 */
function protegerRuta($roles = [])
{
    // Si no hay sesión activa
    if (!isset($_SESSION['user_id'])) {
        if (isAjaxRequest()) {
            // Limpiar cualquier salida anterior
            while (ob_get_level()) {
                ob_end_clean();
            }
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status' => 'error',
                'message' => 'Sesión no válida'
            ]);
            exit;
        }
        header('Location: ' . BASE_URL . 'public/login.php');
        exit;
    }

    // Si se especifican roles y el usuario no tiene el rol adecuado
    if (!empty($roles) && !in_array($_SESSION['user_type'], $roles)) {
        if (isAjaxRequest()) {
            // Limpiar cualquier salida anterior
            while (ob_get_level()) {
                ob_end_clean();
            }
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status' => 'error',
                'message' => 'No tienes permisos para acceder a esta sección'
            ]);
            exit;
        }
        header('Location: ' . BASE_URL . 'public/index.php');
        exit;
    }
}