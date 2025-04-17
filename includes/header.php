<?php
// Definir constante para permitir acceso a archivos de configuración
if (!defined('ACCESO_PERMITIDO')) {
    define('ACCESO_PERMITIDO', true);
}

// Incluir archivo de inicialización
require_once dirname(__DIR__) . '/includes/init.php';

// Determinar qué CSS y JS cargar según la página actual
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Generar un valor de versión para evitar caché
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

    <!-- Favicon -->
    <link rel="icon" href="<?php echo IMG_URL; ?>favicon.ico" type="image/x-icon">

    <!-- Meta tags SEO -->
    <meta name="description"
        content="Servicios profesionales de ciberseguridad y hacking ético para empresas y organizaciones">
    <meta name="keywords" content="ciberseguridad, hacking ético, penetration testing, seguridad informática">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>header_footer.css?v=<?php echo $version; ?>">

    <?php if ($current_page === 'index'): ?>
        <link rel="stylesheet" href="<?php echo CSS_URL; ?>index.css?v=<?php echo $version; ?>">
    <?php endif; ?>
</head>

<body>
    <!-- Header principal con efecto cyberpunk -->
    <header class="header">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <!-- Logo con efecto glow -->
                <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
                    <i class="fas fa-shield-alt"></i>
                    <?php echo APP_NAME; ?>
                </a>

                <!-- Botón para menú móvil -->
                <button class="navbar-toggler" type="button" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <i class="fas fa-bars"></i>
                    </span>
                </button>

                <!-- Menú principal -->
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page === 'index' ? 'active' : ''; ?>"
                                href="<?php echo BASE_URL; ?>">
                                <i class="fas fa-home me-1"></i>Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page === 'servicios' ? 'active' : ''; ?>"
                                href="<?php echo BASE_URL; ?>servicios.php">
                                <i class="fas fa-cogs me-1"></i>Servicios
                            </a>
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
                                <a class="btn btn-primary cyber-glow-effect" href="<?php echo BASE_URL; ?>logout.php">
                                    <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                                </a>
                            <?php else: ?>
                                <a class="btn btn-outline-primary cyber-glow-effect"
                                    href="<?php echo BASE_URL; ?>views/login.php">
                                    <i class="fas fa-sign-in-alt me-1"></i>Login
                                </a>
                                <a class="btn btn-primary cyber-glow-effect ms-2"
                                    href="<?php echo BASE_URL; ?>views/registro.php">
                                    <i class="fas fa-user-plus me-1"></i>Registro
                                </a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <!-- Contenido principal -->
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
</body>

</html>