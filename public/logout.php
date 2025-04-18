<?php
// Definir constante para permitir acceso
define('ACCESO_PERMITIDO', true);

// Incluir archivos necesarios
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/usuario.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    // Si hay un usuario logueado
    if (isset($_SESSION['user_id'])) {
        $usuario = new Usuario();

        // Si existe una cookie de "recordar sesión", eliminarla
        if (isset($_COOKIE['remember_token'])) {
            // Limpiar el token en la base de datos
            $usuario->limpiarTokenRecuerdo($_SESSION['user_id']);

            // Eliminar la cookie
            setcookie('remember_token', '', time() - 3600, '/', '', true, true);
        }

        // Limpiar todas las variables de sesión
        $_SESSION = array();

        // Destruir la cookie de la sesión si existe
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Destruir la sesión
        session_destroy();
    }

    // Redireccionar al login con mensaje de éxito
    header('Location: ' . BASE_URL . 'public/login.php?message=' . urlencode('Sesión cerrada correctamente'));
    exit;

} catch (Exception $e) {
    error_log('Error en logout: ' . $e->getMessage());

    // En caso de error, intentar limpiar la sesión de todos modos
    session_destroy();

    // Redireccionar al login con mensaje de error
    header('Location: ' . BASE_URL . 'public/login.php?error=' . urlencode('Error al cerrar sesión'));
    exit;
}