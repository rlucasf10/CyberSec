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
        proyecto_id, nombre, descripcion, tipo, impacto, probabilidad,
        cvss_score, cve_id, recomendacion
    ) VALUES (
        p_proyecto_id, p_nombre, p_descripcion, p_tipo, p_impacto, p_probabilidad,
        p_cvss_score, p_cve_id, p_recomendacion
    );
END //
