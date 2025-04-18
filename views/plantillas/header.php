<?php
// Definir constante para permitir acceso a archivos de configuración
if (!defined('ACCESO_PERMITIDO')) {
    define('ACCESO_PERMITIDO', true);
}

// Incluir archivo de inicialización
require_once dirname(dirname(__DIR__)) . '/config/init.php';

// Determinar página actual y versión de caché
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$version = time();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Meta tags requeridos -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Título dinámico de la página -->
    <title><?php echo isset($page_title) ? $page_title . ' | ' . APP_NAME : APP_NAME . ' - Ciberseguridad'; ?></title>

    <!-- Recursos comunes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- AOS (Animate On Scroll) CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- CSS Base -->
    <link rel="stylesheet"
        href="<?php echo htmlspecialchars(BASE_URL . 'views/plantillas/header_footer.css?v=' . $version); ?>">

</head>

<body>
    <!-- Header común -->
    <header class="header">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <!-- Logo con efecto glow -->
                <a class="navbar-brand" href="<?php echo BASE_URL; ?>public/index">
                    <i class="fas fa-shield-alt"></i>
                    <?php echo APP_NAME; ?>
                </a>

                <!-- Botón para menú móvil -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                    aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menú principal -->
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page === 'index' ? 'active' : ''; ?>"
                                href="<?php echo BASE_URL; ?>public/index">
                                <i class="fas fa-home me-1"></i>Inicio
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?php echo $current_page === 'servicios' || strpos($current_page, 'servicios/') === 0 ? 'active' : ''; ?>"
                                href="<?php echo BASE_URL; ?>servicios.php" id="navbarDropdownServicios" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cogs me-1"></i>Servicios
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdownServicios">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>servicios/pentesting.php">
                                        <i class="fas fa-bug me-2"></i>Pentesting
                                    </a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>servicios/auditoria.php">
                                        <i class="fas fa-clipboard-check me-2"></i>Auditoría de Seguridad
                                    </a></li>
                                <li><a class="dropdown-item"
                                        href="<?php echo BASE_URL; ?>servicios/respuesta-incidentes.php">
                                        <i class="fas fa-fire-extinguisher me-2"></i>Respuesta a Incidentes
                                    </a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>servicios/consultoria.php">
                                        <i class="fas fa-user-shield me-2"></i>Consultoría Especializada
                                    </a></li>
                                <li><a class="dropdown-item"
                                        href="<?php echo BASE_URL; ?>servicios/analisis-vulnerabilidades.php">
                                        <i class="fas fa-search me-2"></i>Análisis de Vulnerabilidades
                                    </a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>servicios/formacion.php">
                                        <i class="fas fa-graduation-cap me-2"></i>Formación
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>servicios.php">
                                        <i class="fas fa-list-ul me-2"></i>Todos los servicios
                                    </a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page === 'blog' ? 'active' : ''; ?>"
                                href="<?php echo BASE_URL; ?>blog.php">
                                <i class="fas fa-newspaper me-1"></i>Blog
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page === 'contacto' ? 'active' : ''; ?>"
                                href="<?php echo BASE_URL; ?>contacto.php">
                                <i class="fas fa-envelope me-1"></i>Contacto
                            </a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <!-- Menú desplegable para usuario autenticado -->
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle cyber-glow-effect" type="button"
                                        id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-user-circle me-1"></i>
                                        <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Usuario'); ?>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark"
                                        aria-labelledby="userDropdown">
                                        <?php if ($_SESSION['user_type'] === 'admin'): ?>
                                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>views/panel/usuarios.php">
                                                    <i class="fas fa-users-cog me-2"></i>Gestión de Usuarios
                                                </a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                        <?php endif; ?>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>views/panel/perfil.php">
                                                <i class="fas fa-user-edit me-2"></i>Mi Perfil
                                            </a></li>
                                        <li><a class="dropdown-item"
                                                href="<?php echo BASE_URL; ?>views/panel/<?php echo $_SESSION['user_type']; ?>/dashboard.php">
                                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                            </a></li>
                                        <li><a class="dropdown-item"
                                                href="<?php echo BASE_URL; ?>views/panel/proyectos.php">
                                                <i class="fas fa-project-diagram me-2"></i>Mis Proyectos
                                            </a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger"
                                                href="<?php echo BASE_URL; ?>public/logout.php">
                                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                            </a></li>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <a class="btn btn-outline-primary cyber-glow-effect"
                                    href="<?php echo BASE_URL; ?>public/login">
                                    <i class="fas fa-sign-in-alt me-1"></i>Login
                                </a>
                                <a class="btn btn-primary cyber-glow-effect ms-2"
                                    href="<?php echo BASE_URL; ?>public/registro">
                                    <i class="fas fa-user-plus me-1"></i>Registro
                                </a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <!-- Notificaciones -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="container mt-4">
                <div class="message-alert <?php echo $_SESSION['mensaje_tipo'] ?? 'info'; ?>" data-auto-dismiss="true">
                    <?php
                    $icon = 'info-circle';
                    switch ($_SESSION['mensaje_tipo'] ?? 'info') {
                        case 'success':
                            $icon = 'check-circle';
                            break;
                        case 'danger':
                            $icon = 'exclamation-circle';
                            break;
                        case 'warning':
                            $icon = 'exclamation-triangle';
                            break;
                    }
                    ?>
                    <i class="fas fa-<?php echo $icon; ?> me-2"></i>
                    <div class="message-content">
                        <?php echo $_SESSION['mensaje']; ?>
                    </div>
                    <button class="alert-close" aria-label="Cerrar">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <?php
            unset($_SESSION['mensaje']);
            unset($_SESSION['mensaje_tipo']);
            ?>
        <?php endif; ?>

        <!-- Aquí comienza el contenido específico de cada página -->
    </main>