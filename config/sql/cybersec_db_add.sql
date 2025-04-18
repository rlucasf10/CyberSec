-- ========================================
-- CYBERSEC DB - EXTENSIÓN DE TABLAS
-- ========================================
USE cybersec_db;
-- ========================================
-- SISTEMA DE MONITOREO Y ESCANEO
-- ========================================
-- Escaneos de Seguridad
CREATE TABLE escaneos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    activo_id INT NOT NULL,
    fecha_inicio DATETIME,
    fecha_fin DATETIME,
    tipo_escaneo ENUM('nmap', 'openvas', 'nessus', 'otro'),
    estado ENUM('programado', 'en_proceso', 'completado', 'error'),
    resultado TEXT,
    creado_por INT,
    FOREIGN KEY (activo_id) REFERENCES activos(id),
    FOREIGN KEY (creado_por) REFERENCES usuarios(id)
);
-- Hallazgos de Escaneos
CREATE TABLE hallazgos_escaneo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    escaneo_id INT NOT NULL,
    vulnerabilidad_id INT,
    descripcion TEXT,
    nivel_riesgo ENUM('bajo', 'medio', 'alto', 'crítico'),
    fecha_hallazgo DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM(
        'nuevo',
        'en_revision',
        'confirmado',
        'falso_positivo'
    ),
    FOREIGN KEY (escaneo_id) REFERENCES escaneos(id),
    FOREIGN KEY (vulnerabilidad_id) REFERENCES vulnerabilidades(id)
);
-- ========================================
-- SISTEMA DE INFORMES Y DOCUMENTACIÓN
-- ========================================
-- Plantillas de Informes
CREATE TABLE plantillas_informe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    contenido_html TEXT,
    variables JSON,
    tipo ENUM(
        'pentesting',
        'auditoria',
        'seguimiento',
        'ejecutivo'
    ),
    creado DATETIME DEFAULT CURRENT_TIMESTAMP,
    actualizado DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    creado_por INT,
    FOREIGN KEY (creado_por) REFERENCES usuarios(id)
);
-- Versiones de Informes
CREATE TABLE versiones_informe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    informe_id INT NOT NULL,
    version INT NOT NULL,
    contenido TEXT,
    cambios TEXT,
    creado_por INT,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (informe_id) REFERENCES informes(id),
    FOREIGN KEY (creado_por) REFERENCES usuarios(id)
);
-- ========================================
-- SISTEMA DE TICKETS Y SEGUIMIENTO
-- ========================================
-- Tickets
CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    creado_por INT NOT NULL,
    asignado_a INT,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    prioridad ENUM('baja', 'media', 'alta', 'urgente'),
    estado ENUM('abierto', 'en_proceso', 'pendiente', 'cerrado'),
    tipo ENUM('incidente', 'solicitud', 'consulta', 'otro'),
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    fecha_cierre DATETIME,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id),
    FOREIGN KEY (creado_por) REFERENCES usuarios(id),
    FOREIGN KEY (asignado_a) REFERENCES usuarios(id)
);
-- Comentarios de Tickets
CREATE TABLE comentarios_ticket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    usuario_id INT NOT NULL,
    comentario TEXT,
    adjuntos JSON,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
-- ========================================
-- SISTEMA DE BLOG Y RECURSOS
-- ========================================
-- Blog Posts
CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    contenido TEXT,
    autor_id INT NOT NULL,
    estado ENUM('borrador', 'publicado', 'archivado') DEFAULT 'borrador',
    fecha_publicacion DATETIME,
    categoria VARCHAR(50),
    tags JSON,
    imagen_destacada VARCHAR(255),
    seo_descripcion TEXT,
    visitas INT DEFAULT 0,
    FOREIGN KEY (autor_id) REFERENCES usuarios(id)
);
-- Recursos Educativos
CREATE TABLE recursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    tipo ENUM('documento', 'video', 'presentacion', 'herramienta'),
    url VARCHAR(255),
    categoria VARCHAR(50),
    acceso ENUM('publico', 'cliente', 'empleado', 'admin'),
    autor_id INT,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    descargas INT DEFAULT 0,
    FOREIGN KEY (autor_id) REFERENCES usuarios(id)
);
-- ========================================
-- SISTEMA DE CERTIFICACIONES
-- ========================================
-- Certificaciones
CREATE TABLE certificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    emisor VARCHAR(100),
    descripcion TEXT,
    nivel ENUM('basico', 'intermedio', 'avanzado', 'experto'),
    validez_meses INT,
    requisitos TEXT,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);
-- Certificaciones de Usuarios
CREATE TABLE usuario_certificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    certificacion_id INT NOT NULL,
    fecha_obtencion DATE,
    fecha_expiracion DATE,
    numero_certificado VARCHAR(100),
    estado ENUM('activa', 'expirada', 'en_renovacion'),
    documento_url VARCHAR(255),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (certificacion_id) REFERENCES certificaciones(id)
);
-- Índices para optimizar búsquedas frecuentes
CREATE INDEX idx_ticket_estado ON tickets(estado);
CREATE INDEX idx_blog_estado_fecha ON blog_posts(estado, fecha_publicacion);
CREATE INDEX idx_cert_usuario_estado ON usuario_certificaciones(usuario_id, estado);
CREATE INDEX idx_escaneo_estado ON escaneos(estado);
-- Triggers para mantener la integridad de los datos
DELIMITER // -- Trigger para actualizar fecha_actualizacion en tickets
CREATE TRIGGER before_ticket_update BEFORE
UPDATE ON tickets FOR EACH ROW BEGIN
SET NEW.fecha_actualizacion = CURRENT_TIMESTAMP;
IF NEW.estado = 'cerrado'
AND OLD.estado != 'cerrado' THEN
SET NEW.fecha_cierre = CURRENT_TIMESTAMP;
END IF;
END // -- Trigger para validar fechas de certificación
CREATE TRIGGER before_certificacion_insert BEFORE
INSERT ON usuario_certificaciones FOR EACH ROW BEGIN IF NEW.fecha_expiracion <= NEW.fecha_obtencion THEN SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'La fecha de expiración debe ser posterior a la fecha de obtención';
END IF;
END // DELIMITER;