<?php
define('ACCESO_PERMITIDO', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../middleware/auth_middleware.php';
require_once __DIR__ . '/../../models/usuario.php';

// Proteger ruta - todos los usuarios autenticados pueden acceder
protegerRuta(['admin', 'empleado', 'cliente']);

$page_title = "Mi Perfil";
require_once BASE_PATH . '/views/plantillas/header.php';

// Obtener datos del usuario
$usuario = new Usuario();
$userData = $usuario->obtenerPorEmail($_SESSION['user_email']);
?>

<!-- Estilos específicos -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/perfil.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/dashboard.css">
<!-- Estilos específicos -->


<div class="container-fluid" id="profile-wrapper">
    <div class="row g-4">
        <!-- Columna Principal -->
        <div class="col-lg-8">
            <!-- Información Personal -->
            <section class="stat-card profile-info" id="personal-info">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="title">
                        <i class="fas fa-user-edit me-2"></i>Información Personal
                    </h4>
                    <button class="btn btn-outline-primary btn-sm" id="editarPerfil" aria-label="Editar perfil">
                        <i class="fas fa-edit me-1"></i>Editar
                    </button>
                </div>
                <form id="formPerfil" method="post"
                    action="<?php echo BASE_URL; ?>controllers/auth_controller.php?action=actualizar_perfil"
                    class="profile-form needs-validation" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION[CSRF_TOKEN_NAME]; ?>">
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userData['id']); ?>">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                    value="<?php echo htmlspecialchars($userData['nombre']); ?>" disabled required
                                    pattern="[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+" minlength="2" maxlength="50">
                                <div class="invalid-feedback">
                                    Por favor ingresa un nombre válido.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="apellidos" class="form-label">Apellidos</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="apellidos" name="apellidos"
                                    value="<?php echo htmlspecialchars($userData['apellidos']); ?>" disabled required
                                    pattern="[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+" minlength="2" maxlength="50">
                                <div class="invalid-feedback">
                                    Por favor ingresa apellidos válidos.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo htmlspecialchars($userData['email']); ?>" disabled readonly
                                    aria-describedby="emailHelp">
                                <div class="invalid-feedback">
                                    Por favor ingresa un email válido.
                                </div>
                            </div>
                            <small id="emailHelp" class="form-text text-muted">El email no se puede modificar.</small>
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="tel" class="form-control" id="telefono" name="telefono"
                                    value="<?php echo htmlspecialchars($userData['telefono']); ?>" disabled
                                    pattern="[0-9]{9,15}">
                                <div class="invalid-feedback">
                                    Por favor ingresa un número de teléfono válido.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <textarea class="form-control" id="direccion" name="direccion" rows="2"
                                disabled><?php echo htmlspecialchars($userData['direccion']); ?></textarea>
                        </div>
                    </div>

                    <?php if ($userData['tipo'] !== 'admin'): ?>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="empresa" class="form-label">Empresa</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    <input type="text" class="form-control" id="empresa" name="empresa"
                                        value="<?php echo htmlspecialchars($userData['empresa']); ?>" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="especialidad" class="form-label">Especialidad</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-star"></i></span>
                                    <input type="text" class="form-control" id="especialidad" name="especialidad"
                                        value="<?php echo htmlspecialchars($userData['especialidad']); ?>" disabled>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="text-end mt-4" id="botonesGuardar" style="display: none;">
                        <button type="button" class="btn btn-outline-secondary" id="cancelarEdicion">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary ms-2" id="btnGuardarCambios">
                            <i class="fas fa-save me-1"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </section>

            <!-- Cambiar Contraseña -->
            <section class="stat-card profile-password mt-4" id="change-password">
                <h4 class="title mb-4">
                    <i class="fas fa-key me-2"></i>Cambiar Contraseña
                </h4>
                <form id="formPassword" method="post" action="<?php echo BASE_URL; ?>controllers/usuario_controller.php"
                    class="password-form needs-validation" novalidate>
                    <input type="hidden" name="action" value="cambiar_password">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION[CSRF_TOKEN_NAME]; ?>">

                    <!-- Campo oculto para el nombre de usuario -->
                    <input type="text" id="username" name="username" autocomplete="username"
                        value="<?php echo htmlspecialchars($userData['email']); ?>" style="display:none"
                        aria-hidden="true">

                    <div class="mb-3">
                        <label for="password_actual" class="form-label">Contraseña Actual</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password_actual" name="password_actual"
                                required autocomplete="current-password">
                            <button class="btn btn-outline-secondary toggle-password" type="button"
                                aria-label="Mostrar contraseña">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_nuevo" class="form-label">Nueva Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control" id="password_nuevo" name="password_nuevo"
                                required autocomplete="new-password">
                            <button class="btn btn-outline-secondary toggle-password" type="button"
                                aria-label="Mostrar contraseña">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-text text-muted">
                            La contraseña debe tener al menos 10 caracteres, incluir mayúsculas, minúsculas,
                            números y caracteres especiales.
                        </div>
                        <div class="password-strength mt-2">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="password-requirements mt-2">
                                <div class="requirement invalid" data-requirement="length">
                                    <i class="fas fa-circle"></i> Mínimo 10 caracteres
                                </div>
                                <div class="requirement invalid" data-requirement="uppercase">
                                    <i class="fas fa-circle"></i> Al menos una mayúscula
                                </div>
                                <div class="requirement invalid" data-requirement="lowercase">
                                    <i class="fas fa-circle"></i> Al menos una minúscula
                                </div>
                                <div class="requirement invalid" data-requirement="number">
                                    <i class="fas fa-circle"></i> Al menos un número
                                </div>
                                <div class="requirement invalid" data-requirement="special">
                                    <i class="fas fa-circle"></i> Al menos un carácter especial
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmar" class="form-label">Confirmar Nueva Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control" id="password_confirmar"
                                name="password_confirmar" required autocomplete="new-password">
                            <button class="btn btn-outline-secondary toggle-password" type="button"
                                aria-label="Mostrar contraseña">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback">
                            Las contraseñas no coinciden.
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key me-1"></i>Cambiar Contraseña
                        </button>
                    </div>
                </form>
            </section>

            <!-- Tarjeta de Cierre de Cuenta -->
            <div class="col-12 col-lg-6">
                <div class="stat-card">
                    <div class="card-header">
                        <h4 class="title mb-0 text-danger">
                            <i class="fas fa-user-times me-2"></i>Cerrar Cuenta
                        </h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">
                            Esta acción eliminará permanentemente tu cuenta y todos los datos asociados. Esta acción no
                            se puede deshacer.
                        </p>
                        <div class="d-grid">
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#closeAccountModal">
                                <i class="fas fa-user-times me-2"></i>Cerrar Cuenta
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Columna Lateral -->
        <div class="col-lg-4">
            <!-- Resumen de Actividad -->
            <section class="stat-card profile-summary" id="activity-summary">
                <h4 class="title mb-4">
                    <i class="fas fa-chart-line me-2"></i>Resumen de Actividad
                </h4>
                <div class="profile-stat d-flex align-items-center mb-4">
                    <div class="profile-icon">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Miembro desde</div>
                        <div class="value"><?php echo date('d/m/Y', strtotime($userData['creado'])); ?></div>
                    </div>
                </div>

                <?php if ($userData['tipo'] === 'cliente'): ?>
                    <div class="profile-stat d-flex align-items-center mb-4">
                        <div class="profile-icon">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Proyectos Activos</div>
                            <div class="value">3</div>
                        </div>
                    </div>
                    <div class="profile-stat d-flex align-items-center mb-4">
                        <div class="profile-icon">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Tickets Abiertos</div>
                            <div class="value">2</div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($userData['tipo'] === 'empleado'): ?>
                    <div class="profile-stat d-flex align-items-center mb-4">
                        <div class="profile-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Proyectos Asignados</div>
                            <div class="value">5</div>
                        </div>
                    </div>
                    <div class="profile-stat d-flex align-items-center mb-4">
                        <div class="profile-icon">
                            <i class="fas fa-bug"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Vulnerabilidades Reportadas</div>
                            <div class="value">12</div>
                        </div>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Botones de Acceso Rápido -->
            <section class="stat-card quick-actions mt-4">
                <h4 class="title mb-4">
                    <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                </h4>
                <div class="d-grid gap-3">
                    <a href="<?php
                    switch ($userData['tipo']) {
                        case 'admin':
                            echo BASE_URL . 'views/panel/admin/dashboard.php';
                            break;
                        case 'empleado':
                            echo BASE_URL . 'views/panel/empleado/dashboard.php';
                            break;
                        case 'cliente':
                            echo BASE_URL . 'views/panel/cliente/dashboard.php';
                            break;
                        default:
                            echo BASE_URL . 'views/panel/dashboard.php';
                    }
                    ?>" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt me-2"></i>Ir al Dashboard
                    </a>
                    <?php if ($userData['tipo'] === 'cliente'): ?>
                        <a href="<?php echo BASE_URL; ?>views/panel/cliente/nuevo-proyecto.php"
                            class="btn btn-outline-primary">
                            <i class="fas fa-plus me-2"></i>Nuevo Proyecto
                        </a>
                        <a href="<?php echo BASE_URL; ?>views/panel/cliente/tickets.php" class="btn btn-outline-primary">
                            <i class="fas fa-ticket-alt me-2"></i>Mis Tickets
                        </a>
                    <?php endif; ?>
                    <?php if ($userData['tipo'] === 'empleado'): ?>
                        <a href="<?php echo BASE_URL; ?>views/panel/empleado/mis-proyectos.php"
                            class="btn btn-outline-primary">
                            <i class="fas fa-tasks me-2"></i>Mis Proyectos
                        </a>
                        <a href="<?php echo BASE_URL; ?>views/panel/empleado/reportes.php" class="btn btn-outline-primary">
                            <i class="fas fa-file-alt me-2"></i>Mis Reportes
                        </a>
                    <?php endif; ?>

                    <hr class="mt-2 mb-2" style="border-color: rgba(51, 255, 153, 0.1);">

                    <!-- Botón de Cerrar Cuenta -->
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                        data-bs-target="#closeAccountModal">
                        <i class="fas fa-user-times me-2"></i>Cerrar Cuenta
                    </button>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Cambios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas guardar los cambios?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="confirmSave">
                    <i class="fas fa-save me-1"></i>Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Cierre de Cuenta -->
<div class="modal fade" id="closeAccountModal" tabindex="-1" aria-labelledby="closeAccountModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="closeAccountModalLabel">Confirmar Cierre de Cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>¡Atención!</strong> Esta acción es irreversible.
                </div>
                <p>Al cerrar tu cuenta:</p>
                <ul class="text-muted">
                    <li>Se eliminarán todos tus datos personales</li>
                    <li>Perderás acceso a todos los servicios</li>
                    <li>No podrás recuperar la información posteriormente</li>
                </ul>
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="confirmCloseAccount" required>
                    <label class="form-check-label" for="confirmCloseAccount">
                        Entiendo que esta acción es irreversible y deseo cerrar mi cuenta
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-danger" id="closeAccountButton" disabled>
                    <i class="fas fa-user-times me-1"></i>Cerrar Cuenta
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts específicos -->
<script src="<?php echo BASE_URL; ?>public/js/perfil.js"></script>

<?php require_once BASE_PATH . '/views/plantillas/footer.php'; ?>