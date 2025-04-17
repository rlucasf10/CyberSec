<?php
/**
 * Controlador de autenticación
 * Gestiona login, registro y recuperación de contraseña
 */

// Definir constante para permitir acceso a archivos de configuración
define('ACCESO_PERMITIDO', true);

// Incluir archivo de constantes y configuración
require_once __DIR__ . '/../config/constants.php';

// Incluir archivo de inicialización
require_once INCLUDES_PATH . '/init.php';

// Incluir modelo de usuario
require_once MODELS_PATH . '/Usuario.php';

// Iniciar o reanudar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Crear instancia del modelo Usuario
$usuarioModel = new Usuario();

// Determinar la acción a realizar
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Verificar token CSRF para todas las solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que el token existe y coincide
    if (
        !isset($_POST['csrf_token']) || !isset($_SESSION[CSRF_TOKEN_NAME]) ||
        $_POST['csrf_token'] !== $_SESSION[CSRF_TOKEN_NAME]
    ) {
        // Token inválido o expirado
        $response = [
            'status' => 'error',
            'message' => ERROR_CSRF
        ];
        echo json_encode($response);
        exit;
    }
}

// Router para las diferentes acciones
switch ($action) {
    case 'login':
        handleLogin();
        break;

    case 'register':
        handleRegister();
        break;

    case 'recover':
        handlePasswordRecovery();
        break;

    case 'reset':
        handlePasswordReset();
        break;

    case 'logout':
        handleLogout();
        break;

    default:
        // Acción no reconocida
        $response = [
            'status' => 'error',
            'message' => 'Acción no válida'
        ];
        echo json_encode($response);
        break;
}

/**
 * Maneja el inicio de sesión de usuarios
 */
function handleLogin()
{
    global $usuarioModel;

    // Verificar solicitud POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('HTTP/1.1 405 Method Not Allowed');
        exit('Método no permitido');
    }

    // Validar datos recibidos
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        $response = [
            'status' => 'error',
            'message' => 'Todos los campos son obligatorios'
        ];
        echo json_encode($response);
        return;
    }

    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $tipoUsuario = isset($_POST['tipo_usuario']) ? $_POST['tipo_usuario'] : 'cliente';

    // Validar email
    if (!$email) {
        $response = [
            'status' => 'error',
            'message' => 'El correo electrónico no es válido'
        ];
        echo json_encode($response);
        return;
    }

    try {
        // Intentar autenticar al usuario
        $result = $usuarioModel->autenticar($email, $password);

        if ($result['status'] === 'success') {
            // Verificar que el tipo de usuario coincida
            if ($result['usuario']['tipo_usuario'] !== $tipoUsuario) {
                $response = [
                    'status' => 'error',
                    'message' => 'Tipo de usuario incorrecto. Por favor, verifique sus credenciales.'
                ];
                echo json_encode($response);
                return;
            }

            // Iniciar sesión del usuario
            $_SESSION['user_id'] = $result['usuario']['id'];
            $_SESSION['user_email'] = $result['usuario']['email'];
            $_SESSION['user_type'] = $result['usuario']['tipo_usuario'];
            $_SESSION['referencia_id'] = $result['usuario']['referencia_id'];
            $_SESSION['ultimo_acceso'] = time();

            // Recordar sesión si se ha solicitado
            if (isset($_POST['remember']) && $_POST['remember'] == 'on') {
                // Extiende la vida de la cookie de sesión a 30 días
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    session_id(),
                    time() + (86400 * 30), // 30 días
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }

            // Regenerar ID de sesión para prevenir ataques de fijación
            session_regenerate_id(true);

            // Determinar URL de redirección según tipo de usuario
            $redirect = BASE_URL;
            if ($tipoUsuario === 'empleado') {
                $redirect = BASE_URL . 'panel/empleado/';
            } elseif ($tipoUsuario === 'administrador') {
                $redirect = BASE_URL . 'panel/admin/';
            }

            $response = [
                'status' => 'success',
                'message' => 'Inicio de sesión exitoso',
                'redirect' => $redirect
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => $result['message']
            ];
        }

        echo json_encode($response);

    } catch (Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
        ];
        echo json_encode($response);
    }
}

/**
 * Maneja el registro de nuevos usuarios
 */
function handleRegister()
{
    global $usuarioModel;

    // Verificar solicitud POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('HTTP/1.1 405 Method Not Allowed');
        exit('Método no permitido');
    }

    // Validar datos recibidos
    $requiredFields = ['nombre', 'apellidos', 'email', 'password', 'confirmar_password', 'tipo_usuario'];
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $response = [
                'status' => 'error',
                'message' => 'Todos los campos marcados son obligatorios'
            ];
            echo json_encode($response);
            return;
        }
    }

    // Verificar términos y condiciones
    if (!isset($_POST['terminos']) || $_POST['terminos'] !== 'on') {
        $response = [
            'status' => 'error',
            'message' => 'Debe aceptar los términos y condiciones para continuar'
        ];
        echo json_encode($response);
        return;
    }

    // Recoger datos del formulario
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $apellidos = filter_var($_POST['apellidos'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $confirmarPassword = $_POST['confirmar_password'];
    $tipoUsuario = $_POST['tipo_usuario'];
    $telefono = isset($_POST['telefono']) ? filter_var($_POST['telefono'], FILTER_SANITIZE_STRING) : '';
    $direccion = isset($_POST['direccion']) ? filter_var($_POST['direccion'], FILTER_SANITIZE_STRING) : '';

    // Validaciones adicionales
    if (!$email) {
        $response = [
            'status' => 'error',
            'message' => 'El correo electrónico no es válido'
        ];
        echo json_encode($response);
        return;
    }

    if (strlen($password) < PASSWORD_MIN_LENGTH) {
        $response = [
            'status' => 'error',
            'message' => 'La contraseña debe tener al menos ' . PASSWORD_MIN_LENGTH . ' caracteres'
        ];
        echo json_encode($response);
        return;
    }

    if ($password !== $confirmarPassword) {
        $response = [
            'status' => 'error',
            'message' => 'Las contraseñas no coinciden'
        ];
        echo json_encode($response);
        return;
    }

    // Campos específicos según tipo de usuario
    $empresa = '';
    $especialidad = '';
    $referenciaId = null;

    try {
        // Crear cliente o empleado según el tipo
        if ($tipoUsuario === 'cliente') {
            $empresa = isset($_POST['empresa']) ? filter_var($_POST['empresa'], FILTER_SANITIZE_STRING) : '';

            // Insertar en la tabla clientes
            $pdo = getDbConnection();
            $sql = "INSERT INTO clientes (nombre, apellidos, empresa, email, telefono, direccion, estado) 
                    VALUES (?, ?, ?, ?, ?, ?, 'potencial')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $apellidos, $empresa, $email, $telefono, $direccion]);

            $referenciaId = $pdo->lastInsertId();
        } elseif ($tipoUsuario === 'empleado') {
            $especialidad = isset($_POST['especialidad']) ? filter_var($_POST['especialidad'], FILTER_SANITIZE_STRING) : '';

            // Insertar en la tabla empleados
            $pdo = getDbConnection();
            $sql = "INSERT INTO empleados (nombre, apellidos, email, telefono, direccion, puesto, especialidad, estado) 
                    VALUES (?, ?, ?, ?, ?, 'Consultor', ?, 'inactivo')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $apellidos, $email, $telefono, $direccion, $especialidad]);

            $referenciaId = $pdo->lastInsertId();
        }

        // Registrar usuario en el sistema de autenticación
        $result = $usuarioModel->registrar($email, $password, $tipoUsuario, $referenciaId);

        if ($result['status'] === 'success') {
            $response = [
                'status' => 'success',
                'message' => 'Registro exitoso. Ahora puede iniciar sesión.',
                'redirect' => BASE_URL . 'views/login.php'
            ];
        } else {
            // Si falla el registro de usuario, eliminar el cliente/empleado creado
            if ($referenciaId) {
                $tabla = ($tipoUsuario === 'cliente') ? 'clientes' : 'empleados';
                $sql = "DELETE FROM $tabla WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$referenciaId]);
            }

            $response = [
                'status' => 'error',
                'message' => $result['message']
            ];
        }

        echo json_encode($response);

    } catch (Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
        ];
        echo json_encode($response);
    }
}

/**
 * Maneja la recuperación de contraseña (solicitud de token)
 */
function handlePasswordRecovery()
{
    global $usuarioModel;

    // Verificar solicitud POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('HTTP/1.1 405 Method Not Allowed');
        exit('Método no permitido');
    }

    // Validar email
    if (!isset($_POST['email']) || empty($_POST['email'])) {
        $response = [
            'status' => 'error',
            'message' => 'El correo electrónico es obligatorio'
        ];
        echo json_encode($response);
        return;
    }

    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $response = [
            'status' => 'error',
            'message' => 'El correo electrónico no es válido'
        ];
        echo json_encode($response);
        return;
    }

    try {
        // Generar token de recuperación
        $result = $usuarioModel->generarTokenRecuperacion($email);

        if ($result['status'] === 'success') {
            // En un entorno real, aquí enviaríamos el email con el enlace de recuperación
            // Por ahora, solo simulamos que lo hemos enviado

            $response = [
                'status' => 'success',
                'message' => 'Se ha enviado un correo con instrucciones para restablecer su contraseña'
            ];
        } else {
            // Por seguridad, no indicamos si el email existe o no
            $response = [
                'status' => 'success',
                'message' => 'Si el correo existe en nuestra base de datos, recibirá instrucciones para restablecer su contraseña'
            ];
        }

        echo json_encode($response);

    } catch (Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Error al procesar la solicitud'
        ];
        echo json_encode($response);
    }
}

/**
 * Maneja el restablecimiento de contraseña con token
 */
function handlePasswordReset()
{
    global $usuarioModel;

    // Verificar solicitud POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('HTTP/1.1 405 Method Not Allowed');
        exit('Método no permitido');
    }

    // Validar datos recibidos
    if (!isset($_POST['token']) || !isset($_POST['password']) || !isset($_POST['confirmar_password'])) {
        $response = [
            'status' => 'error',
            'message' => 'Todos los campos son obligatorios'
        ];
        echo json_encode($response);
        return;
    }

    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirmarPassword = $_POST['confirmar_password'];

    // Validar contraseña
    if (strlen($password) < PASSWORD_MIN_LENGTH) {
        $response = [
            'status' => 'error',
            'message' => 'La contraseña debe tener al menos ' . PASSWORD_MIN_LENGTH . ' caracteres'
        ];
        echo json_encode($response);
        return;
    }

    if ($password !== $confirmarPassword) {
        $response = [
            'status' => 'error',
            'message' => 'Las contraseñas no coinciden'
        ];
        echo json_encode($response);
        return;
    }

    try {
        // Verificar token y cambiar contraseña
        $result = $usuarioModel->cambiarPassword($token, $password);

        if ($result['status'] === 'success') {
            $response = [
                'status' => 'success',
                'message' => 'Contraseña actualizada correctamente',
                'redirect' => BASE_URL . 'views/login.php'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => $result['message']
            ];
        }

        echo json_encode($response);

    } catch (Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
        ];
        echo json_encode($response);
    }
}

/**
 * Maneja el cierre de sesión
 */
function handleLogout()
{
    // Destruir todas las variables de sesión
    $_SESSION = [];

    // Destruir la cookie de sesión
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Destruir la sesión
    session_destroy();

    // Redireccionar a la página de inicio
    if (!isset($_GET['ajax'])) {
        header("Location: " . BASE_URL);
        exit;
    } else {
        $response = [
            'status' => 'success',
            'message' => 'Sesión cerrada correctamente',
            'redirect' => BASE_URL
        ];
        echo json_encode($response);
    }
}

/**
 * Función auxiliar para obtener conexión a base de datos
 */
function getDbConnection()
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            throw new Exception(ERROR_DB_CONNECTION);
        }
    }

    return $pdo;
}