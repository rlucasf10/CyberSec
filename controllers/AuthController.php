<?php
require_once __DIR__ . '/../models/Usuario.php';

/**
 * Controlador para gestionar la autenticación
 */
class AuthController
{
    private $usuario;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuario = new Usuario();
    }

    /**
     * Procesa el registro de un nuevo usuario
     * @return array Resultado del registro
     */
    public function procesarRegistro()
    {
        try {
            // Validamos datos recibidos
            if (!isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['confirmar_password'])) {
                return [
                    'status' => 'error',
                    'message' => 'Todos los campos son obligatorios'
                ];
            }

            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'];
            $confirmarPassword = $_POST['confirmar_password'];
            $tipoUsuario = isset($_POST['tipo_usuario']) ? $_POST['tipo_usuario'] : 'cliente';
            $referenciaId = isset($_POST['referencia_id']) ? (int) $_POST['referencia_id'] : null;

            // Validar email
            if (!$email) {
                return [
                    'status' => 'error',
                    'message' => 'El correo electrónico no es válido'
                ];
            }

            // Validar que las contraseñas coincidan
            if ($password !== $confirmarPassword) {
                return [
                    'status' => 'error',
                    'message' => 'Las contraseñas no coinciden'
                ];
            }

            // Validar complejidad de la contraseña (mínimo 8 caracteres, al menos una letra y un número)
            if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
                return [
                    'status' => 'error',
                    'message' => 'La contraseña debe tener al menos 8 caracteres, incluyendo letras y números'
                ];
            }

            // Registrar usuario
            return $this->usuario->registrar($email, $password, $tipoUsuario, $referenciaId);

        } catch (Exception $e) {
            error_log("Error en procesarRegistro: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Ocurrió un error al procesar la solicitud'
            ];
        }
    }

    /**
     * Procesa el inicio de sesión
     * @return array Resultado del inicio de sesión
     */
    public function procesarLogin()
    {
        try {
            // Validamos datos recibidos
            if (!isset($_POST['email']) || !isset($_POST['password'])) {
                return [
                    'status' => 'error',
                    'message' => 'Todos los campos son obligatorios'
                ];
            }

            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'];

            // Validar email
            if (!$email) {
                return [
                    'status' => 'error',
                    'message' => 'El correo electrónico no es válido'
                ];
            }

            // Autenticar usuario
            $resultado = $this->usuario->autenticar($email, $password);

            // Si la autenticación es exitosa, iniciamos sesión
            if ($resultado['status'] === 'success') {
                $this->iniciarSesion($resultado['usuario']);
            }

            return $resultado;

        } catch (Exception $e) {
            error_log("Error en procesarLogin: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Ocurrió un error al procesar la solicitud'
            ];
        }
    }

    /**
     * Inicia la sesión del usuario
     * @param array $datosUsuario Datos del usuario autenticado
     */
    private function iniciarSesion($datosUsuario)
    {
        // Iniciamos o reanudamos la sesión
        if (session_status() === PHP_SESSION_NONE) {
            // Configuramos cookies seguras
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            session_start();
        }

        // Regeneramos el ID de sesión para prevenir ataques de fijación
        session_regenerate_id(true);

        // Guardamos los datos del usuario en la sesión
        $_SESSION['usuario'] = $datosUsuario;
        $_SESSION['autenticado'] = true;
        $_SESSION['ultimo_acceso'] = time();

        // Generamos un token CSRF para formularios
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    /**
     * Cierra la sesión del usuario
     * @return array Resultado del cierre de sesión
     */
    public function cerrarSesion()
    {
        try {
            // Iniciamos la sesión si no está activa
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Destruimos todas las variables de sesión
            $_SESSION = [];

            // Destruimos la cookie de sesión si existe
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

            // Destruimos la sesión
            session_destroy();

            return [
                'status' => 'success',
                'message' => 'Sesión cerrada correctamente'
            ];

        } catch (Exception $e) {
            error_log("Error en cerrarSesion: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Ocurrió un error al cerrar la sesión'
            ];
        }
    }

    /**
     * Procesa la solicitud de recuperación de contraseña
     * @return array Resultado de la solicitud
     */
    public function procesarRecuperacion()
    {
        try {
            // Validamos datos recibidos
            if (!isset($_POST['email'])) {
                return [
                    'status' => 'error',
                    'message' => 'El correo electrónico es obligatorio'
                ];
            }

            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

            // Validar email
            if (!$email) {
                return [
                    'status' => 'error',
                    'message' => 'El correo electrónico no es válido'
                ];
            }

            // Generar token de recuperación
            $resultado = $this->usuario->generarTokenRecuperacion($email);

            // Aquí se implementaría el envío del correo con el token
            // Por razones de seguridad, siempre devolvemos éxito aunque el email no exista
            if ($resultado['status'] === 'success') {
                // Aquí se enviaría el correo con el enlace de recuperación
                // $this->enviarCorreoRecuperacion($resultado['email'], $resultado['token']);

                // Eliminamos el token del resultado para no exponerlo
                unset($resultado['token']);
            }

            return [
                'status' => 'success',
                'message' => 'Si el correo existe en nuestra base de datos, recibirás instrucciones para restablecer tu contraseña'
            ];

        } catch (Exception $e) {
            error_log("Error en procesarRecuperacion: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Ocurrió un error al procesar la solicitud'
            ];
        }
    }

    /**
     * Procesa el cambio de contraseña
     * @return array Resultado del cambio de contraseña
     */
    public function procesarCambioPassword()
    {
        try {
            // Validamos datos recibidos
            if (!isset($_POST['token']) || !isset($_POST['password']) || !isset($_POST['confirmar_password'])) {
                return [
                    'status' => 'error',
                    'message' => 'Todos los campos son obligatorios'
                ];
            }

            $token = $_POST['token'];
            $password = $_POST['password'];
            $confirmarPassword = $_POST['confirmar_password'];

            // Validar que las contraseñas coincidan
            if ($password !== $confirmarPassword) {
                return [
                    'status' => 'error',
                    'message' => 'Las contraseñas no coinciden'
                ];
            }

            // Validar complejidad de la contraseña
            if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
                return [
                    'status' => 'error',
                    'message' => 'La contraseña debe tener al menos 8 caracteres, incluyendo letras y números'
                ];
            }

            // Cambiar contraseña
            return $this->usuario->cambiarPassword($token, $password);

        } catch (Exception $e) {
            error_log("Error en procesarCambioPassword: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Ocurrió un error al procesar la solicitud'
            ];
        }
    }

    /**
     * Verifica si un usuario está autenticado
     * @return bool True si está autenticado, false en caso contrario
     */
    public function estaAutenticado()
    {
        // Iniciamos la sesión si no está activa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verificamos si el usuario está autenticado
        return isset($_SESSION['autenticado']) && $_SESSION['autenticado'] === true;
    }

    /**
     * Obtiene los datos del usuario autenticado
     * @return array|null Datos del usuario o null si no está autenticado
     */
    public function getUsuarioAutenticado()
    {
        // Verificamos si el usuario está autenticado
        if (!$this->estaAutenticado()) {
            return null;
        }

        return $_SESSION['usuario'] ?? null;
    }

    /**
     * Verifica si el usuario tiene un rol específico
     * @param string $rol Rol a verificar
     * @return bool True si tiene el rol, false en caso contrario
     */
    public function tieneRol($rol)
    {
        $usuario = $this->getUsuarioAutenticado();

        if (!$usuario) {
            return false;
        }

        return $usuario['tipo_usuario'] === $rol;
    }
}