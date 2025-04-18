<?php
// Solo definir la constante si no está definida previamente
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

/**
 * Protege una ruta verificando que el usuario esté autenticado y tenga el rol correcto
 * @param string|array $roles Rol o roles permitidos
 */
function protegerRuta($roles)
{
    // Verificar si hay una sesión activa
    if (!isset($_SESSION['user_id'])) {
        // Guardar la URL actual para redirigir después del login
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header('Location: ' . BASE_URL . 'public/login.php');
        exit;
    }

    // Si $roles es un string, convertirlo a array
    if (!is_array($roles)) {
        $roles = [$roles];
    }

    // Verificar si el usuario tiene el rol adecuado
    if (!in_array($_SESSION['user_type'], $roles)) {
        // Redirigir al dashboard correspondiente según el rol del usuario
        header('Location: ' . BASE_URL . 'views/panel/' . $_SESSION['user_type'] . '/dashboard.php');
        exit;
    }
}