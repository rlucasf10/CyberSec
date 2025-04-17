<?php
// Definir constante para permitir acceso a archivos de configuración
define('ACCESO_PERMITIDO', true);

// Incluir archivo de constantes
require_once __DIR__ . '/../config/constants.php';

// Incluir archivo de inicialización
require_once INCLUDES_PATH . '/init.php';

// Título de la página
$page_title = "Recuperar Contraseña";

// Si el usuario ya está autenticado, redirigir a index
if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL);
    exit;
}

// Verificar si hay un token en la URL
$token = isset($_GET['token']) ? $_GET['token'] : null;
$mostrarFormularioToken = !empty($token);

// Incluir encabezado
require_once INCLUDES_PATH . '/header.php';
?>

<!-- Estilos personalizados -->
<link rel="stylesheet" href="<?php echo CSS_URL; ?>login_registro.css">

<!-- Partículas de fondo para efectos visuales -->
<div id="particles-js" class="particles-container"></div>

<!-- Contenedor principal con efecto de glassmorphism -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="auth-card">
                <div class="auth-header text-center mb-4">
                    <i class="fas fa-key fa-3x text-primary mb-3"></i>
                    <h2 class="auth-title">Recuperar Contraseña</h2>
                    <p class="auth-subtitle">
                        <?php echo $mostrarFormularioToken ?
                            'Establezca su nueva contraseña' :
                            'Recibirá un correo con instrucciones para restablecer su contraseña'; ?>
                    </p>
                </div>

                <?php if (!$mostrarFormularioToken): ?>
                    <!-- Formulario para solicitar token de recuperación -->
                    <form id="recuperarForm" action="<?php echo BASE_URL; ?>controllers/auth_controller.php?action=recover"
                        method="post" class="needs-validation" novalidate>
                        <!-- Campo oculto para token CSRF -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION[CSRF_TOKEN_NAME]; ?>">

                        <!-- Email -->
                        <div class="form-group mb-4">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="ejemplo@dominio.com" required>
                            </div>
                            <div class="invalid-feedback">Por favor ingrese un correo electrónico válido.</div>
                        </div>

                        <!-- Botón de enviar con efecto cyber -->
                        <div class="form-group mb-4">
                            <button type="submit" class="btn btn-primary btn-block cyber-btn">
                                <i class="fas fa-paper-plane me-2"></i>Enviar Instrucciones
                                <span class="cyber-btn-glitch"></span>
                            </button>
                        </div>

                        <!-- Mensaje de resultado (inicialmente oculto) -->
                        <div id="recuperarMessage" class="alert d-none" role="alert"></div>
                    </form>
                <?php else: ?>
                    <!-- Formulario para restablecer contraseña con token -->
                    <form id="resetForm" action="<?php echo BASE_URL; ?>controllers/auth_controller.php?action=reset"
                        method="post" class="needs-validation" novalidate>
                        <!-- Campo oculto para token CSRF -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION[CSRF_TOKEN_NAME]; ?>">

                        <!-- Campo oculto para token de recuperación -->
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                        <!-- Contraseña -->
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
                            <div class="invalid-feedback">La contraseña debe tener al menos 10 caracteres.</div>
                            <div class="password-strength mt-2 d-none">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small class="text-muted">Fortaleza: <span class="strength-text">Sin
                                        contraseña</span></small>
                            </div>
                        </div>

                        <!-- Confirmar Contraseña -->
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

                        <!-- Botón de restablecer con efecto cyber -->
                        <div class="form-group mb-4">
                            <button type="submit" class="btn btn-primary btn-block cyber-btn">
                                <i class="fas fa-key me-2"></i>Restablecer Contraseña
                                <span class="cyber-btn-glitch"></span>
                            </button>
                        </div>

                        <!-- Mensaje de resultado (inicialmente oculto) -->
                        <div id="resetMessage" class="alert d-none" role="alert"></div>
                    </form>
                <?php endif; ?>

                <!-- Enlace a login -->
                <div class="auth-footer text-center mt-4">
                    <p>
                        <a href="<?php echo BASE_URL; ?>views/login.php" class="text-primary">
                            <i class="fas fa-arrow-left me-1"></i>Volver a iniciar sesión
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Incluir pie de página
require_once INCLUDES_PATH . '/footer.php';
?>