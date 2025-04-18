DELIMITER //

CREATE PROCEDURE sp_crear_usuario(
    IN p_email VARCHAR(100),
    IN p_password_hash VARCHAR(255),
    IN p_salt VARCHAR(64),
    IN p_tipo ENUM('cliente', 'empleado', 'admin'),
    IN p_referencia_id INT
)
BEGIN
    INSERT INTO usuarios (
        email, password_hash, salt, tipo, referencia_id, creado, actualizado
    ) VALUES (
        p_email, p_password_hash, p_salt, p_tipo, p_referencia_id, NOW(), NOW()
    );
END //

CREATE PROCEDURE sp_autenticar_usuario(
    IN p_email VARCHAR(100),
    IN p_password_hash VARCHAR(255)
)
BEGIN
    SELECT id, email, tipo, referencia_id, bloqueado
    FROM usuarios
    WHERE email = p_email AND password_hash = p_password_hash AND bloqueado = 0;
END //

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

CREATE PROCEDURE sp_verificar_token(
    IN p_token VARCHAR(64)
)
BEGIN
    SELECT id, fecha_token
    FROM usuarios
    WHERE token_recuperacion = p_token AND bloqueado = 0 AND fecha_token > NOW();
END //

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
