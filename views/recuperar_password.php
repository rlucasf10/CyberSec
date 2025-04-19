<?php
// Definir la constante de acceso
define('ACCESO_PERMITIDO', true);

// Incluir archivos necesarios en orden correcto
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/init.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/models/usuario.php';

// Título de la página
$page_title = "Recuperar Contraseña";

// Si el usuario ya está autenticado, redirigir a index
if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "public/index.php");
    exit;
}

// Verificar si hay un token en la URL
$token = isset($_GET['token']) ? $_GET['token'] : null;
$modo = $token ? 'reset' : 'request';

// Incluir encabezado
require_once dirname(__DIR__) . '/views/plantillas/header.php';
?>

<!-- Estilos personalizados -->
<link rel="stylesheet" href="<?php echo htmlspecialchars(BASE_URL . 'public/css/login_registro.css'); ?>">

<!-- Partículas de fondo para efectos visuales -->
<div id="particles-js" class="particles-container"></div>

<!-- Contenedor principal -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg" data-aos="fade-up" data-aos-delay="200">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-key fa-3x text-primary mb-3 animate__animated animate__fadeInDown"></i>
                        <h2 class="fw-bold animate__animated animate__fadeInUp">
                            <?php echo $modo === 'reset' ? 'Restablecer Contraseña' : 'Recuperar Contraseña'; ?>
                        </h2>
                        <p class="text-light animate__animated animate__fadeInUp animate__delay-1s">
                            <?php echo $modo === 'reset'
                                ? 'Ingresa tu nueva contraseña'
                                : 'Te enviaremos un enlace para restablecer tu contraseña'; ?>
                        </p>
                    </div>

                    <?php if ($modo === 'request'): ?>
                        <!-- Formulario para solicitar recuperación -->
                        <form id="recuperarForm"
                            action="<?php echo htmlspecialchars(BASE_URL . 'controllers/auth_controller.php?action=recovery_request'); ?>"
                            method="post" class="needs-validation animate__animated animate__fadeInUp animate__delay-2s"
                            novalidate>

                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION[CSRF_TOKEN_NAME]; ?>">

                            <div class="form-group mb-4">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="ejemplo@dominio.com" required>
                                </div>
                                <div class="invalid-feedback">Por favor ingrese un correo electrónico válido.</div>
                            </div>

                            <div class="form-group mb-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100 shadow-pulse">
                                    <i class="fas fa-paper-plane me-2"></i>Enviar Enlace
                                </button>
                            </div>

                            <div id="recuperarMessage" class="alert d-none" role="alert"></div>
                        </form>

                    <?php else: ?>
                        <!-- Formulario para restablecer contraseña -->
                        <form id="resetForm"
                            action="<?php echo htmlspecialchars(BASE_URL . 'controllers/auth_controller.php?action=reset_password'); ?>"
                            method="post" class="needs-validation animate__animated animate__fadeInUp animate__delay-2s"
                            novalidate>

                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION[CSRF_TOKEN_NAME]; ?>">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                            <input type="hidden" name="username" value="" id="password_username" aria-hidden="true"
                                autocomplete="username">

                            <div class="form-group mb-4">
                                <label for="password" class="form-label">Nueva Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Mínimo 10 caracteres" minlength="10" required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password" tabindex="-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength mt-2">
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="password-feedback text-white mt-1">
                                        La contraseña debe tener al menos 10 caracteres
                                    </small>
                                </div>
                                <div class="password-requirements small text-white mt-1">
                                    <div class="req-length"><i class="fas fa-circle me-1"></i> Mínimo 10 caracteres</div>
                                    <div class="req-upper"><i class="fas fa-circle me-1"></i> Al menos una mayúscula</div>
                                    <div class="req-lower"><i class="fas fa-circle me-1"></i> Al menos una minúscula</div>
                                    <div class="req-number"><i class="fas fa-circle me-1"></i> Al menos un número</div>
                                    <div class="req-special"><i class="fas fa-circle me-1"></i> Al menos un carácter
                                        especial (!@#$%^&*)</div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label for="confirmar_password" class="form-label">Confirmar Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirmar_password"
                                        name="confirmar_password" placeholder="Repita la contraseña" required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password" tabindex="-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">Las contraseñas no coinciden.</div>
                            </div>

                            <div class="form-group mb-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100 shadow-pulse">
                                    <i class="fas fa-key me-2"></i>Restablecer Contraseña
                                </button>
                            </div>

                            <div id="resetMessage" class="alert d-none" role="alert"></div>
                        </form>
                    <?php endif; ?>

                    <!-- Enlaces adicionales -->
                    <div class="text-center mt-4 animate__animated animate__fadeInUp animate__delay-3s">
                        <p>
                            <a href="<?php echo htmlspecialchars(BASE_URL . 'public/login.php'); ?>"
                                class="text-primary">
                                <i class="fas fa-arrow-left me-1"></i>Volver al inicio de sesión
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script personalizado para recuperar contraseña -->
<script src="<?php echo htmlspecialchars(BASE_URL . 'public/js/recuperar_password.js'); ?>"></script>

<?php
// Incluir pie de página
require_once BASE_PATH . '/views/plantillas/footer.php';
?>