DELIMITER //

-- Crear usuario
CREATE PROCEDURE sp_crear_usuario(
    IN p_email VARCHAR(100),
    IN p_password_hash VARCHAR(255),
    IN p_salt VARCHAR(64),
    IN p_tipo_usuario ENUM('cliente', 'empleado', 'administrador'),
    IN p_referencia_id INT
)
BEGIN
    INSERT INTO usuarios (
        email,
        password_hash,
        salt,
        tipo_usuario,
        referencia_id,
        creado,
        actualizado
    ) VALUES (
        p_email,
        p_password_hash,
        p_salt,
        p_tipo_usuario,
        p_referencia_id,
        NOW(),
        NOW()
    );
END //

-- Autenticar usuario
CREATE PROCEDURE sp_autenticar_usuario(
    IN p_email VARCHAR(100),
    IN p_password_hash VARCHAR(255)
)
BEGIN
    SELECT id,
           email,
           tipo_usuario,
           referencia_id,
           bloqueado
    FROM usuarios
    WHERE email = p_email
      AND password_hash = p_password_hash
      AND bloqueado = 0;
END //

-- Generar token de recuperación
CREATE PROCEDURE sp_generar_token_recuperacion(
    IN p_email VARCHAR(100),
    IN p_token VARCHAR(64)
)
BEGIN
    UPDATE usuarios
    SET token_recuperacion = p_token,
        fecha_token = DATE_ADD(NOW(), INTERVAL 24 HOUR),
        actualizado = NOW()
    WHERE email = p_email;
END //

-- Verificar token de recuperación
CREATE PROCEDURE sp_verificar_token(
    IN p_token VARCHAR(64)
)
BEGIN
    SELECT id,
           fecha_token
    FROM usuarios
    WHERE token_recuperacion = p_token
      AND bloqueado = 0
      AND fecha_token > NOW();
END //

-- Cambiar contraseña
CREATE PROCEDURE sp_cambiar_password(
    IN p_usuario_id INT,
    IN p_password_hash VARCHAR(255),
    IN p_salt VARCHAR(64)
)
BEGIN
    UPDATE usuarios
    SET password_hash = p_password_hash,
        salt = p_salt,
        token_recuperacion = NULL,
        fecha_token = NULL,
        actualizado = NOW()
    WHERE id = p_usuario_id;
END //

-- Crear proyecto
CREATE PROCEDURE sp_crear_proyecto(
    IN p_cliente_id INT,
    IN p_nombre VARCHAR(200),
    IN p_descripcion TEXT,
    IN p_fecha_inicio DATE,
    IN p_fecha_fin_estimada DATE,
    IN p_presupuesto DECIMAL(12,2)
)
BEGIN
    INSERT INTO proyectos (
        cliente_id,
        nombre,
        descripcion,
        fecha_inicio,
        fecha_fin_estimada,
        presupuesto,
        estado
    ) VALUES (
        p_cliente_id,
        p_nombre,
        p_descripcion,
        p_fecha_inicio,
        p_fecha_fin_estimada,
        p_presupuesto,
        'planificacion'
    );
END //

-- Asignar empleado a proyecto
CREATE PROCEDURE sp_asignar_empleado_proyecto(
    IN p_proyecto_id INT,
    IN p_empleado_id INT,
    IN p_rol VARCHAR(100),
    IN p_horas_asignadas INT
)
BEGIN
    INSERT INTO proyecto_empleado (
        proyecto_id,
        empleado_id,
        rol,
        fecha_asignacion,
        horas_asignadas
    ) VALUES (
        p_proyecto_id,
        p_empleado_id,
        p_rol,
        NOW(),
        p_horas_asignadas
    );
END //

-- Agregar servicio a proyecto
CREATE PROCEDURE sp_agregar_servicio_proyecto(
    IN p_proyecto_id INT,
    IN p_servicio_id INT,
    IN p_cantidad INT,
    IN p_precio_acordado DECIMAL(10,2),
    IN p_observaciones TEXT
)
BEGIN
    INSERT INTO proyecto_servicio (
        proyecto_id,
        servicio_id,
        cantidad,
        precio_acordado,
        observaciones
    ) VALUES (
        p_proyecto_id,
        p_servicio_id,
        p_cantidad,
        p_precio_acordado,
        p_observaciones
    );
END //

-- Registrar vulnerabilidad
CREATE PROCEDURE sp_registrar_vulnerabilidad(
    IN p_proyecto_id INT,
    IN p_nombre VARCHAR(200),
    IN p_descripcion TEXT,
    IN p_tipo ENUM('web', 'red', 'sistema', 'aplicacion', 'fisica', 'social', 'otros'),
    IN p_impacto ENUM('bajo', 'medio', 'alto', 'crítico'),
    IN p_probabilidad ENUM('baja', 'media', 'alta'),
    IN p_cvss_score DECIMAL(3,1),
    IN p_cve_id VARCHAR(20),
    IN p_recomendacion TEXT
)
BEGIN
    INSERT INTO vulnerabilidades (
        proyecto_id,
        nombre,
        descripcion,
        tipo,
        impacto,
        probabilidad,
        cvss_score,
        cve_id,
        recomendacion
    ) VALUES (
        p_proyecto_id,
        p_nombre,
        p_descripcion,
        p_tipo,
        p_impacto,
        p_probabilidad,
        p_cvss_score,
        p_cve_id,
        p_recomendacion
    );
END //

DELIMITER ;
