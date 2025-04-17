<?php
/**
 * Funciones auxiliares para todo el sistema
 */

// Prevenir acceso directo al archivo
if (!defined('ACCESO_PERMITIDO')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Acceso directo a este archivo no permitido');
}

/**
 * Función para limpiar datos de entrada
 * @param mixed $data Datos a limpiar
 * @return mixed Datos limpios
 */
function limpiarDatos($data)
{
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = limpiarDatos($value);
        }
    } else {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    return $data;
}

/**
 * Función para verificar token CSRF
 * @param string $token Token enviado
 * @return bool True si el token es válido
 */
function verificarCSRF($token)
{
    if (!isset($_SESSION[CSRF_TOKEN_NAME]) || $token !== $_SESSION[CSRF_TOKEN_NAME]) {
        return false;
    }
    return true;
}

/**
 * Redirecciona a una URL específica
 * @param string $url URL de destino
 * @return void
 */
function redirigir($url)
{
    if (!headers_sent()) {
        header('Location: ' . $url);
        exit;
    } else {
        echo '<script type="text/javascript">';
        echo 'window.location.href="' . $url . '";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
        echo '</noscript>';
        exit;
    }
}

/**
 * Establece un mensaje flash en la sesión
 * @param string $tipo Tipo de mensaje (success, danger, warning, info)
 * @param string $mensaje Contenido del mensaje
 * @return void
 */
function setMensaje($tipo, $mensaje)
{
    $_SESSION['mensaje'] = $mensaje;
    $_SESSION['mensaje_tipo'] = $tipo;
}

/**
 * Verifica si el usuario está autenticado
 * @return bool True si está autenticado
 */
function estaAutenticado()
{
    return isset($_SESSION['autenticado']) && $_SESSION['autenticado'] === true;
}

/**
 * Verifica si el usuario tiene un rol específico
 * @param string|array $roles Rol o roles a verificar
 * @return bool True si el usuario tiene el rol
 */
function tieneRol($roles)
{
    if (!estaAutenticado() || !isset($_SESSION['usuario']['tipo_usuario'])) {
        return false;
    }

    if (is_array($roles)) {
        return in_array($_SESSION['usuario']['tipo_usuario'], $roles);
    } else {
        return $_SESSION['usuario']['tipo_usuario'] === $roles;
    }
}

/**
 * Función para verificar permisos y redirigir si no tiene acceso
 * @param string|array $roles Rol o roles permitidos
 * @param string $redirectUrl URL a la que redirigir si no tiene permiso
 * @return void
 */
function verificarPermiso($roles, $redirectUrl = 'login.php')
{
    if (!estaAutenticado() || !tieneRol($roles)) {
        setMensaje('danger', 'No tiene permiso para acceder a esta sección.');
        redirigir($redirectUrl);
    }
}

/**
 * Genera una contraseña aleatoria segura
 * @param int $longitud Longitud de la contraseña
 * @return string Contraseña generada
 */
function generarPassword($longitud = 12)
{
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_-=+;:,.?';
    $password = '';
    $max = strlen($caracteres) - 1;

    for ($i = 0; $i < $longitud; $i++) {
        $password .= $caracteres[random_int(0, $max)];
    }

    return $password;
}

/**
 * Formatea una fecha al formato español
 * @param string $fecha Fecha en formato Y-m-d
 * @param bool $conHora Incluir hora en el formato
 * @return string Fecha formateada
 */
function formatearFecha($fecha, $conHora = false)
{
    if (empty($fecha))
        return '';

    $timestamp = strtotime($fecha);
    if ($conHora) {
        return date('d/m/Y H:i:s', $timestamp);
    } else {
        return date('d/m/Y', $timestamp);
    }
}

/**
 * Recorta un texto a una longitud específica
 * @param string $texto Texto a recortar
 * @param int $longitud Longitud máxima
 * @param string $sufijo Sufijo para indicar truncamiento
 * @return string Texto recortado
 */
function recortarTexto($texto, $longitud = 100, $sufijo = '...')
{
    if (strlen($texto) <= $longitud) {
        return $texto;
    }

    return substr($texto, 0, $longitud) . $sufijo;
}

/**
 * Formatea un número como moneda
 * @param float $numero Número a formatear
 * @param string $simbolo Símbolo de moneda
 * @return string Número formateado
 */
function formatearMoneda($numero, $simbolo = '€')
{
    return number_format($numero, 2, ',', '.') . ' ' . $simbolo;
}

/**
 * Valida una dirección de email
 * @param string $email Email a validar
 * @return bool True si el email es válido
 */
function validarEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Genera un slug a partir de un texto
 * @param string $texto Texto a convertir
 * @return string Slug generado
 */
function generarSlug($texto)
{
    // Convertir a minúsculas y reemplazar espacios con guiones
    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $texto), '-'));
    // Eliminar caracteres duplicados
    $slug = preg_replace('/-+/', '-', $slug);
    return $slug;
}

/**
 * Registra una actividad en el log
 * @param string $accion Acción realizada
 * @param string $descripcion Descripción detallada
 * @param int|null $usuario_id ID del usuario que realiza la acción
 * @return bool True si se registró correctamente
 */
function registrarActividad($accion, $descripcion, $usuario_id = null)
{
    if (!defined('LOGS_PATH')) {
        return false;
    }

    if ($usuario_id === null && isset($_SESSION['usuario']['id'])) {
        $usuario_id = $_SESSION['usuario']['id'];
    }

    $fecha = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Desconocida';
    $navegador = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido';

    $log = "[{$fecha}] Usuario ID: {$usuario_id} | IP: {$ip} | Acción: {$accion} | Descripción: {$descripcion} | Navegador: {$navegador}\n";

    return error_log($log, 3, LOGS_PATH . '/actividad.log');
}

/**
 * Genera un identificador único
 * @param string $prefijo Prefijo para el identificador
 * @return string Identificador generado
 */
function generarId($prefijo = '')
{
    return uniqid($prefijo) . bin2hex(random_bytes(8));
}

/**
 * Comprueba si una cadena contiene texto en formato JSON válido
 * @param string $string Cadena a validar
 * @return bool True si es JSON válido
 */
function esJson($string)
{
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

/**
 * Obtiene la extensión de un archivo
 * @param string $filename Nombre del archivo
 * @return string Extensión del archivo
 */
function obtenerExtension($filename)
{
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Verifica si un archivo es una imagen válida
 * @param string $file Ruta del archivo
 * @return bool True si es una imagen válida
 */
function esImagen($file)
{
    $info = getimagesize($file);
    if ($info === false) {
        return false;
    }

    if (!in_array($info['mime'], ALLOWED_IMAGE_TYPES)) {
        return false;
    }

    return true;
}

/**
 * Valida datos de un formulario según reglas específicas
 * @param array $datos Datos a validar
 * @param array $reglas Reglas de validación
 * @return array Errores encontrados
 */
function validarFormulario($datos, $reglas)
{
    $errores = [];

    foreach ($reglas as $campo => $reglasDelCampo) {
        $valor = $datos[$campo] ?? '';

        foreach ($reglasDelCampo as $regla) {
            if ($regla === 'required' && empty($valor)) {
                $errores[$campo] = 'Este campo es obligatorio';
                break;
            }

            if (strpos($regla, 'min:') === 0) {
                $min = (int) substr($regla, 4);
                if (strlen($valor) < $min) {
                    $errores[$campo] = "Este campo debe tener al menos {$min} caracteres";
                    break;
                }
            }

            if (strpos($regla, 'max:') === 0) {
                $max = (int) substr($regla, 4);
                if (strlen($valor) > $max) {
                    $errores[$campo] = "Este campo no debe tener más de {$max} caracteres";
                    break;
                }
            }

            if ($regla === 'email' && !empty($valor) && !validarEmail($valor)) {
                $errores[$campo] = 'El formato del email no es válido';
                break;
            }

            if ($regla === 'numeric' && !empty($valor) && !is_numeric($valor)) {
                $errores[$campo] = 'Este campo debe ser numérico';
                break;
            }
        }
    }

    return $errores;
}