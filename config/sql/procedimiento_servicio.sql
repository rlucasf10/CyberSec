CREATE PROCEDURE sp_crear_proyecto(
    IN p_cliente_id INT,
    IN p_nombre VARCHAR(200),
    IN p_descripcion TEXT,
    IN p_inicio DATE,
    IN p_fin DATE,
    IN p_presupuesto DECIMAL(10,2)
)
BEGIN
    INSERT INTO proyectos (
        cliente_id, nombre, descripcion, fecha_inicio, fecha_fin_estimada, presupuesto, estado
    ) VALUES (
        p_cliente_id, p_nombre, p_descripcion, p_inicio, p_fin, p_presupuesto, 'planificacion'
    );
END //

CREATE PROCEDURE sp_asignar_empleado(
    IN p_proyecto_id INT,
    IN p_empleado_id INT,
    IN p_rol VARCHAR(100),
    IN p_horas INT
)
BEGIN
    INSERT INTO proyecto_empleado (
        proyecto_id, empleado_id, rol, fecha_asignacion, horas_asignadas
    ) VALUES (
        p_proyecto_id, p_empleado_id, p_rol, NOW(), p_horas
    );
END //

CREATE PROCEDURE sp_agregar_servicio(
    IN p_proyecto_id INT,
    IN p_servicio_id INT,
    IN p_precio_acordado DECIMAL(10,2),
    IN p_observaciones TEXT
)
BEGIN
    INSERT INTO proyecto_servicio (
        proyecto_id, servicio_id, precio_acordado, observaciones
    ) VALUES (
        p_proyecto_id, p_servicio_id, p_precio_acordado, p_observaciones
    );
END //
