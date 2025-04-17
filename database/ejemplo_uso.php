<?php
/**
 * Ejemplo de uso de las funciones de la base de datos
 * Este archivo muestra ejemplos prácticos de cómo utilizar las funciones
 * definidas en db_connection.php
 */

require_once 'db_connection.php';

// Función para mostrar resultados de manera legible
function mostrarResultado($titulo, $datos)
{
    echo "<h3>$titulo</h3>";
    echo "<pre>";
    print_r($datos);
    echo "</pre>";
    echo "<hr>";
}

// Función para manejar errores
function manejarError($e)
{
    echo "<div style='color: red; padding: 10px; border: 1px solid red;'>";
    echo "<strong>Error:</strong> " . $e->getMessage();
    echo "</div>";
}

try {
    // Ejemplo 1: Insertar un nuevo cliente
    $nuevoCliente = [
        'nombre' => 'Juan',
        'apellidos' => 'Pérez López',
        'empresa' => 'Tecnologías Seguras, S.L.',
        'cargo' => 'Director de Seguridad',
        'email' => 'juan.perez@tecnoseguras.com',
        'telefono' => '+34612345678',
        'direccion' => 'Calle Seguridad, 123, 28001 Madrid',
        'estado' => 'potencial'
    ];

    $clienteId = insertDb('clientes', $nuevoCliente);
    mostrarResultado("Cliente insertado con ID:", $clienteId);

    // Ejemplo 2: Obtener datos de un cliente
    $cliente = querySingleDb("SELECT * FROM clientes WHERE id = ?", [$clienteId]);
    mostrarResultado("Datos del cliente:", $cliente);

    // Ejemplo 3: Actualizar datos de un cliente
    $datosActualizados = [
        'cargo' => 'CISO',
        'estado' => 'activo'
    ];

    $filasActualizadas = updateDb('clientes', $datosActualizados, "id = ?", [$clienteId]);
    mostrarResultado("Filas actualizadas:", $filasActualizadas);

    // Ejemplo 4: Insertar un nuevo servicio
    $nuevoServicio = [
        'nombre' => 'Pentesting Web Completo',
        'descripcion' => 'Análisis exhaustivo de seguridad en aplicaciones web con recomendaciones',
        'categoria' => 'pentesting',
        'precio_base' => 2500.00,
        'duracion_estimada' => '2 semanas',
        'nivel_complejidad' => 'alto'
    ];

    $servicioId = insertDb('servicios', $nuevoServicio);
    mostrarResultado("Servicio insertado con ID:", $servicioId);

    // Ejemplo 5: Crear un nuevo proyecto
    $nuevoProyecto = [
        'cliente_id' => $clienteId,
        'nombre' => 'Evaluación de Seguridad Web',
        'descripcion' => 'Pentesting completo de la aplicación web corporativa',
        'fecha_inicio' => date('Y-m-d'),
        'fecha_fin_estimada' => date('Y-m-d', strtotime('+30 days')),
        'estado' => 'planificacion',
        'presupuesto' => 3500.00
    ];

    $proyectoId = insertDb('proyectos', $nuevoProyecto);
    mostrarResultado("Proyecto insertado con ID:", $proyectoId);

    // Ejemplo 6: Asignar un servicio al proyecto
    $proyectoServicio = [
        'proyecto_id' => $proyectoId,
        'servicio_id' => $servicioId,
        'cantidad' => 1,
        'precio_acordado' => 2800.00
    ];

    $asignacionId = insertDb('proyecto_servicio', $proyectoServicio);
    mostrarResultado("Servicio asignado con ID:", $asignacionId);

    // Ejemplo 7: Consulta compleja para obtener detalles del proyecto con cliente y servicios
    $sql = "
        SELECT p.*, c.nombre as cliente_nombre, c.empresa, 
               s.nombre as servicio_nombre, ps.precio_acordado
        FROM proyectos p
        JOIN clientes c ON p.cliente_id = c.id
        JOIN proyecto_servicio ps ON p.id = ps.proyecto_id
        JOIN servicios s ON ps.servicio_id = s.id
        WHERE p.id = ?
    ";

    $detallesProyecto = queryDb($sql, [$proyectoId]);
    mostrarResultado("Detalles completos del proyecto:", $detallesProyecto);

    // Ejemplo 8: Uso de transacciones para operaciones múltiples
    $pdo = beginTransaction();

    try {
        // Registrar una vulnerabilidad
        $nuevaVulnerabilidad = [
            'proyecto_id' => $proyectoId,
            'nombre' => 'SQL Injection en formulario de login',
            'descripcion' => 'Se detectó una vulnerabilidad de inyección SQL en el formulario de inicio de sesión',
            'tipo' => 'web',
            'impacto' => 'alto',
            'probabilidad' => 'media',
            'cvss_score' => 7.5,
            'fecha_descubrimiento' => date('Y-m-d'),
            'recomendacion' => 'Implementar prepared statements y validación de entrada'
        ];

        $stmt = $pdo->prepare("INSERT INTO vulnerabilidades 
            (proyecto_id, nombre, descripcion, tipo, impacto, probabilidad, cvss_score, fecha_descubrimiento, recomendacion) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $nuevaVulnerabilidad['proyecto_id'],
            $nuevaVulnerabilidad['nombre'],
            $nuevaVulnerabilidad['descripcion'],
            $nuevaVulnerabilidad['tipo'],
            $nuevaVulnerabilidad['impacto'],
            $nuevaVulnerabilidad['probabilidad'],
            $nuevaVulnerabilidad['cvss_score'],
            $nuevaVulnerabilidad['fecha_descubrimiento'],
            $nuevaVulnerabilidad['recomendacion']
        ]);

        $vulnerabilidadId = $pdo->lastInsertId();

        // Actualizar el estado del proyecto
        $stmt = $pdo->prepare("UPDATE proyectos SET estado = 'en_progreso' WHERE id = ?");
        $stmt->execute([$proyectoId]);

        // Confirmar transacción
        commitTransaction($pdo);
        mostrarResultado("Transacción completada. ID de vulnerabilidad:", $vulnerabilidadId);
    } catch (Exception $e) {
        // Revertir en caso de error
        rollbackTransaction($pdo);
        throw $e;
    }

    // Ejemplo 9: Obtener todas las vulnerabilidades del proyecto
    $vulnerabilidades = queryDb("SELECT * FROM vulnerabilidades WHERE proyecto_id = ?", [$proyectoId]);
    mostrarResultado("Vulnerabilidades registradas:", $vulnerabilidades);

    // Ejemplo 10: Eliminar datos (normalmente no se eliminarían en producción, solo se marcarían como inactivos)
    // Este es solo un ejemplo para mostrar la función
    $eliminados = deleteDb('vulnerabilidades', "id = ?", [$vulnerabilidadId]);
    mostrarResultado("Registros eliminados:", $eliminados);

} catch (Exception $e) {
    manejarError($e);
}