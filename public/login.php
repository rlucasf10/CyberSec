<?php
// Definir constante para permitir acceso a archivos de configuración
define('ACCESO_PERMITIDO', true);

// Incluir archivo de configuración
require_once dirname(__DIR__) . '/config/config.php';

// Título de la página
$page_title = "Iniciar Sesión";

// Si el usuario ya está autenticado, redirigir a index
if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "public/index.php");
    exit;
}

// Incluir encabezado
require_once BASE_PATH . '/views/plantillas/header.php';
?>

<!-- Estilos personalizados -->
<link rel="stylesheet" href="<?php echo htmlspecialchars(BASE_URL . 'public/css/login_registro.css'); ?>">


<!-- Partículas de fondo para efectos visuales -->
<div id="particles-js" class="particles-container"></div>

<!-- Contenedor principal con efecto de glassmorphism -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg" data-aos="fade-up" data-aos-delay="200">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3 animate__animated animate__fadeInDown"></i>
                        <h2 class="fw-bold animate__animated animate__fadeInUp">Iniciar Sesión</h2>
                        <p class="text-light animate__animated animate__fadeInUp animate__delay-1s">Accede a tu cuenta
                            para gestionar tus servicios de ciberseguridad</p>
                    </div>

                    <!-- Formulario de inicio de sesión -->
                    <form id="loginForm" action="<?php echo BASE_URL; ?>controllers/auth_controller.php?action=login"
                        method="post" class="needs-validation animate__animated animate__fadeInUp animate__delay-2s"
                        novalidate>
                        <!-- Campo oculto para token CSRF -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION[CSRF_TOKEN_NAME]; ?>">

                        <!-- Email -->
                        <div class="form-group mb-4">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="ejemplo@dominio.com" required autocomplete="username">
                            </div>
                            <div class="invalid-feedback">Por favor ingrese un correo electrónico válido.</div>
                        </div>

                        <!-- Contraseña -->
                        <div class="form-group mb-4">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="••••••••" required autocomplete="current-password">
                                <button type="button" class="btn btn-outline-secondary toggle-password" tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">Por favor ingrese su contraseña.</div>
                        </div>

                        <!-- Selector de tipo de usuario -->
                        <div class="form-group mb-4">
                            <label for="tipo_usuario" class="form-label">Tipo de usuario</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                <select class="form-select" id="tipo_usuario" name="tipo_usuario" required>
                                    <option value="cliente">Cliente</option>
                                    <option value="empleado">Empleado/Consultor</option>
                                    <option value="administrador">Administrador</option>
                                </select>
                            </div>
                        </div>

                        <!-- Recordar sesión y olvidó contraseña -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Recordar sesión</label>
                            </div>
                            <a href="<?php echo BASE_URL; ?>views/recuperar_password.php" class="text-primary">
                                <i class="fas fa-key me-1"></i>¿Has olvidado la contraseña?
                            </a>
                        </div>

                        <!-- Botón de login con efecto cyber -->
                        <div class="form-group mb-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100 shadow-pulse">
                                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                            </button>
                        </div>

                        <!-- Botón de Google con el mismo estilo -->
                        <div class="form-group mb-4">
                            <a href="<?php echo htmlspecialchars(BASE_URL . 'controllers/auth_controller.php?action=google_auth'); ?>"
                                class="btn btn-primary btn-lg w-100 shadow-pulse">
                                <i class="fab fa-google me-2"></i>Iniciar con Google
                            </a>
                        </div>

                        <!-- Mensaje de resultado (inicialmente oculto) -->
                        <div id="loginMessage" class="alert d-none" role="alert"></div>
                    </form>

                    <!-- Enlace a registro -->
                    <div class="text-center mt-4 animate__animated animate__fadeInUp animate__delay-3s">
                        <p>¿No tienes una cuenta?
                            <a href="<?php echo BASE_URL; ?>views/registro.php" class="text-primary">
                                <i class="fas fa-user-plus me-1"></i>Regístrate ahora
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Incluir los scripts JS antes del footer -->
<script src="<?php echo htmlspecialchars(BASE_URL . 'public/js/login_registro.js'); ?>"></script>

<?php
// Incluir pie de página
require_once BASE_PATH . '/views/plantillas/footer.php';
?>