SET SQL_SAFE_UPDATES = 0;

DELETE FROM usuarios;

SET SQL_SAFE_UPDATES = 1; -- Opcional: vuelve a activarlo si lo deseas

ALTER TABLE usuarios AUTO_INCREMENT = 1;

select * from usuarios;

ALTER TABLE usuarios 
ADD COLUMN remember_token VARCHAR(64) DEFAULT NULL, 
ADD COLUMN token_expiry DATETIME DEFAULT NULL;