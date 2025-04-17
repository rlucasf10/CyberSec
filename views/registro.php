<?php
// Definir constante para permitir acceso a archivos de configuración
define('ACCESO_PERMITIDO', true);

// Incluir archivo de constantes
require_once __DIR__ . '/../config/constants.php';

// Incluir archivo de inicialización
require_once INCLUDES_PATH . '/init.php';

// Título de la página
$page_title = "Registro de Usuario";

// Si el usuario ya está autenticado, redirigir a index
if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL);
    exit;
}

// Incluir encabezado
require_once INCLUDES_PATH . '/header.php';
?>

<!-- Estilos personalizados -->
<link rel="stylesheet" href="<?php echo CSS_URL; ?>login_registro.css">

<!-- Partículas de fondo para efectos visuales -->
<div id="particles-js" class="particles-container"></div>

<!-- Contenedor principal -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg" data-aos="fade-up" data-aos-delay="200">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-plus fa-3x text-primary mb-3 animate__animated animate__fadeInDown"></i>
                        <h2 class="fw-bold animate__animated animate__fadeInUp">Crear Cuenta</h2>
                        <p class="text-muted animate__animated animate__fadeInUp animate__delay-1s">Únete a nuestra
                            plataforma de ciberseguridad</p>
                    </div>

                    <!-- Formulario de registro -->
                    <form id="registroForm"
                        action="<?php echo BASE_URL; ?>controllers/auth_controller.php?action=register" method="post"
                        class="needs-validation animate__animated animate__fadeInUp animate__delay-2s" novalidate>
                        <!-- Campo oculto para token CSRF -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION[CSRF_TOKEN_NAME]; ?>">

                        <!-- Tipo de usuario -->
                        <div class="form-group mb-4">
                            <label for="tipo_usuario" class="form-label">Tipo de usuario</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                <select class="form-select" id="tipo_usuario" name="tipo_usuario" required>
                                    <option value="cliente">Cliente</option>
                                    <option value="empleado">Empleado/Consultor (requiere aprobación)</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Nombre -->
                            <div class="col-md-6 mb-4">
                                <label for="nombre" class="form-label">Nombre</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                        placeholder="Tu nombre" required>
                                </div>
                                <div class="invalid-feedback">Por favor ingrese su nombre.</div>
                            </div>

                            <!-- Apellidos -->
                            <div class="col-md-6 mb-4">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos"
                                        placeholder="Tus apellidos" required>
                                </div>
                                <div class="invalid-feedback">Por favor ingrese sus apellidos.</div>
                            </div>
                        </div>

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

                        <div class="row">
                            <!-- Teléfono -->
                            <div class="col-md-6 mb-4">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="tel" class="form-control" id="telefono" name="telefono"
                                        placeholder="Número de teléfono">
                                </div>
                            </div>

                            <!-- Empresa (solo para clientes) -->
                            <div class="col-md-6 mb-4 cliente-field">
                                <label for="empresa" class="form-label">Empresa (opcional)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    <input type="text" class="form-control" id="empresa" name="empresa"
                                        placeholder="Nombre de su empresa">
                                </div>
                            </div>

                            <!-- Especialidad (solo para empleados) -->
                            <div class="col-md-6 mb-4 empleado-field d-none">
                                <label for="especialidad" class="form-label">Especialidad</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-laptop-code"></i></span>
                                    <input type="text" class="form-control" id="especialidad" name="especialidad"
                                        placeholder="Ej: Pentesting, Auditor, etc.">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Contraseña -->
                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Mínimo 10 caracteres" minlength="10" required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password"
                                        tabindex="-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">La contraseña debe tener al menos 10 caracteres.</div>
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div class="col-md-6 mb-4">
                                <label for="confirmar_password" class="form-label">Confirmar Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirmar_password"
                                        name="confirmar_password" placeholder="Repita la contraseña" required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password"
                                        tabindex="-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">Las contraseñas no coinciden.</div>
                            </div>
                        </div>

                        <!-- Dirección (opcional) -->
                        <div class="form-group mb-4">
                            <label for="direccion" class="form-label">Dirección (opcional)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <textarea class="form-control" id="direccion" name="direccion" rows="2"
                                    placeholder="Su dirección"></textarea>
                            </div>
                        </div>

                        <!-- Términos y condiciones -->
                        <div class="form-group mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terminos" name="terminos" required>
                                <label class="form-check-label" for="terminos">
                                    Acepto los <a href="<?php echo BASE_URL; ?>terminos.php" target="_blank">términos y
                                        condiciones</a> y la
                                    <a href="<?php echo BASE_URL; ?>privacidad.php" target="_blank">política de
                                        privacidad</a>
                                </label>
                                <div class="invalid-feedback">Debe aceptar los términos y condiciones para continuar.
                                </div>
                            </div>
                        </div>

                        <!-- Botón de registro con efecto cyber -->
                        <div class="form-group mb-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100 shadow-pulse">
                                <i class="fas fa-user-plus me-2"></i>Crear Cuenta
                            </button>
                        </div>

                        <!-- Mensaje de resultado (inicialmente oculto) -->
                        <div id="registroMessage" class="alert d-none" role="alert"></div>
                    </form>

                    <!-- Enlace a login -->
                    <div class="text-center mt-4 animate__animated animate__fadeInUp animate__delay-3s">
                        <p>¿Ya tienes una cuenta?
                            <a href="<?php echo BASE_URL; ?>views/login.php" class="text-primary">
                                <i class="fas fa-sign-in-alt me-1"></i>Inicia sesión
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para manejar formulario -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Gestión de los botones para mostrar/ocultar contraseña
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function () {
                const input = this.previousElementSibling;
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // Manejo de cambio de tipo de usuario en registro
        const tipoUsuarioSelect = document.getElementById('tipo_usuario');
        if (tipoUsuarioSelect) {
            tipoUsuarioSelect.addEventListener('change', function () {
                // Mostrar/ocultar campos específicos según el tipo de usuario
                const tipoUsuario = this.value;
                const clienteFields = document.querySelectorAll('.cliente-field');
                const empleadoFields = document.querySelectorAll('.empleado-field');

                if (tipoUsuario === 'cliente') {
                    clienteFields.forEach(field => field.classList.remove('d-none'));
                    empleadoFields.forEach(field => field.classList.add('d-none'));
                } else if (tipoUsuario === 'empleado') {
                    clienteFields.forEach(field => field.classList.add('d-none'));
                    empleadoFields.forEach(field => field.classList.remove('d-none'));
                }
            });
        }

        // Validación del formulario
        const registroForm = document.getElementById('registroForm');
        if (registroForm) {
            registroForm.addEventListener('submit', function (e) {
                e.preventDefault();

                // Validación básica
                let isValid = true;
                const password = document.getElementById('password');
                const confirmarPassword = document.getElementById('confirmar_password');

                // Verificar que las contraseñas coincidan
                if (password.value !== confirmarPassword.value) {
                    isValid = false;
                    confirmarPassword.classList.add('is-invalid');
                    const alertElement = document.getElementById('registroMessage');
                    alertElement.classList.remove('d-none', 'alert-success');
                    alertElement.classList.add('alert-danger');
                    alertElement.textContent = 'Las contraseñas no coinciden.';
                    return;
                }

                // Si todo está bien, enviamos el formulario
                if (isValid) {
                    this.submit();
                }
            });
        }
    });
</script>

<?php
// Incluir pie de página
require_once INCLUDES_PATH . '/footer.php';
?>