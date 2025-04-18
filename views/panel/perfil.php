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
<link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/dashboard.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/perfil.css">

<div class="dashboard-container fade-in">
    <div class="container-fluid">
        <div class="row">
            <!-- Columna Principal -->
            <div class="col-lg-8">
                <!-- Información Personal -->
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="title">Información Personal</h4>
                        <button class="btn btn-primary btn-sm" id="editarPerfil">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                    </div>
                    <form id="formPerfil" method="post"
                        action="<?php echo BASE_URL; ?>controllers/usuario_controller.php">
                        <input type="hidden" name="action" value="actualizar_perfil">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION[CSRF_TOKEN_NAME]; ?>">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                    value="<?php echo htmlspecialchars($userData['nombre']); ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos"
                                    value="<?php echo htmlspecialchars($userData['apellidos']); ?>" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email"
                                    value="<?php echo htmlspecialchars($userData['email']); ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono"
                                    value="<?php echo htmlspecialchars($userData['telefono']); ?>" disabled>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="2"
                                disabled><?php echo htmlspecialchars($userData['direccion']); ?></textarea>
                        </div>

                        <?php if ($userData['tipo'] !== 'admin'): ?>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="empresa" class="form-label">Empresa</label>
                                    <input type="text" class="form-control" id="empresa" name="empresa"
                                        value="<?php echo htmlspecialchars($userData['empresa']); ?>" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="especialidad" class="form-label">Especialidad</label>
                                    <input type="text" class="form-control" id="especialidad" name="especialidad"
                                        value="<?php echo htmlspecialchars($userData['especialidad']); ?>" disabled>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="text-end mt-3" style="display: none;" id="botonesGuardar">
                            <button type="button" class="btn btn-secondary" id="cancelarEdicion">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>

                <!-- Cambiar Contraseña -->
                <div class="stat-card">
                    <h4 class="title">Cambiar Contraseña</h4>
                    <form id="formPassword" method="post"
                        action="<?php echo BASE_URL; ?>controllers/usuario_controller.php">
                        <input type="hidden" name="action" value="cambiar_password">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION[CSRF_TOKEN_NAME]; ?>">

                        <div class="mb-3">
                            <label for="password_actual" class="form-label">Contraseña Actual</label>
                            <input type="password" class="form-control" id="password_actual" name="password_actual"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="password_nuevo" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="password_nuevo" name="password_nuevo"
                                required>
                            <div class="form-text">
                                La contraseña debe tener al menos 10 caracteres, incluir mayúsculas, minúsculas,
                                números y caracteres especiales.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmar" class="form-label">Confirmar Nueva
                                Contraseña</label>
                            <input type="password" class="form-control" id="password_confirmar"
                                name="password_confirmar" required>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Columna Lateral -->
            <div class="col-lg-4">
                <!-- Resumen de Actividad -->
                <div class="stat-card">
                    <h4 class="title">Resumen de Actividad</h4>
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon me-3">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div>
                            <div class="small text-secondary">Miembro desde</div>
                            <div class="value"><?php echo date('d/m/Y', strtotime($userData['creado'])); ?></div>
                        </div>
                    </div>

                    <?php if ($userData['tipo'] === 'cliente'): ?>
                        <div class="mb-3">
                            <div class="small text-secondary">Proyectos Activos</div>
                            <div class="value">3</div>
                        </div>
                    <?php endif; ?>

                    <?php if ($userData['tipo'] === 'empleado'): ?>
                        <div class="mb-3">
                            <div class="small text-secondary">Proyectos Asignados</div>
                            <div class="value">5</div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Botón Dashboard -->
                <div class="stat-card text-center">
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
                    ?>" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Ir al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts específicos -->
<script src="<?php echo BASE_URL; ?>public/js/perfil.js"></script>

<?php require_once BASE_PATH . '/views/plantillas/footer.php'; ?>