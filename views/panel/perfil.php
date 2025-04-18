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

<div class="container-fluid">
    <div class="row">
        <!-- Contenido principal -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Mi Perfil</h1>
            </div>

            <div class="row">
                <!-- Información Personal -->
                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Información Personal</h6>
                            <button class="btn btn-sm btn-primary" id="editarPerfil">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </div>
                        <div class="card-body">
                            <form id="formPerfil" method="post"
                                action="<?php echo BASE_URL; ?>controllers/usuario_controller.php">
                                <input type="hidden" name="action" value="actualizar_perfil">
                                <input type="hidden" name="csrf_token"
                                    value="<?php echo $_SESSION[CSRF_TOKEN_NAME]; ?>">

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
                                    <button type="button" class="btn btn-secondary"
                                        id="cancelarEdicion">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Cambiar Contraseña -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Cambiar Contraseña</h6>
                        </div>
                        <div class="card-body">
                            <form id="formPassword" method="post"
                                action="<?php echo BASE_URL; ?>controllers/usuario_controller.php">
                                <input type="hidden" name="action" value="cambiar_password">
                                <input type="hidden" name="csrf_token"
                                    value="<?php echo $_SESSION[CSRF_TOKEN_NAME]; ?>">

                                <div class="mb-3">
                                    <label for="password_actual" class="form-label">Contraseña Actual</label>
                                    <input type="password" class="form-control" id="password_actual"
                                        name="password_actual" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password_nuevo" class="form-label">Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="password_nuevo"
                                        name="password_nuevo" required>
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
                </div>

                <!-- Sidebar de Perfil -->
                <div class="col-lg-4">
                    <!-- Resumen de Actividad -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Resumen de Actividad</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <i class="fas fa-user-circle fa-3x text-gray-300"></i>
                                </div>
                                <div>
                                    <div class="small text-gray-500">Miembro desde</div>
                                    <div class="font-weight-bold">
                                        <?php echo date('d/m/Y', strtotime($userData['creado'])); ?>
                                    </div>
                                </div>
                            </div>

                            <?php if ($userData['tipo'] === 'cliente'): ?>
                                <div class="mb-3">
                                    <div class="small text-gray-500">Proyectos Activos</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
                                </div>
                            <?php endif; ?>

                            <?php if ($userData['tipo'] === 'empleado'): ?>
                                <div class="mb-3">
                                    <div class="small text-gray-500">Proyectos Asignados</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">5</div>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <div class="small text-gray-500">Último Acceso</div>
                                <div class="font-weight-bold">Hace 2 horas</div>
                            </div>
                        </div>
                    </div>

                    <!-- Preferencias -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Preferencias</h6>
                        </div>
                        <div class="card-body">
                            <form id="formPreferencias">
                                <div class="mb-3">
                                    <label class="form-label d-block">Notificaciones</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="notif_email" checked>
                                        <label class="form-check-label" for="notif_email">
                                            Recibir notificaciones por email
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Idioma</label>
                                    <select class="form-select" id="idioma">
                                        <option value="es" selected>Español</option>
                                        <option value="en">English</option>
                                    </select>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Guardar Preferencias</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Scripts específicos del perfil -->
<script src="<?php echo BASE_URL; ?>public/js/perfil.js"></script>

<?php require_once BASE_PATH . '/views/plantillas/footer.php'; ?>