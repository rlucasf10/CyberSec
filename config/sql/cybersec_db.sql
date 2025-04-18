CREATE DATABASE IF NOT EXISTS cybersec_db
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE cybersec_db;

-- ============================
-- ENTIDADES PRINCIPALES
-- ============================

-- Usuarios (Clientes, Empleados y Admins)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100),
    email VARCHAR(150) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    tipo ENUM('cliente', 'empleado', 'admin') NOT NULL,
    puesto VARCHAR(100),
    empresa VARCHAR(150),
    especialidad VARCHAR(150),
    password_hash VARCHAR(255) NOT NULL,
    salt VARCHAR(64) NOT NULL,
    intentos_fallidos TINYINT DEFAULT 0,
    bloqueado BOOLEAN DEFAULT FALSE,
    token_recuperacion VARCHAR(64),
    fecha_token DATETIME,
    creado DATETIME DEFAULT CURRENT_TIMESTAMP,
    actualizado DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================
-- PROYECTOS Y SERVICIOS
-- ============================

-- Servicios
CREATE TABLE servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    categoria ENUM('pentesting','auditoria','consultoría','formación','respuesta_incidentes','otro') NOT NULL,
    precio_base DECIMAL(10,2),
    nivel_complejidad ENUM('bajo','medio','alto') DEFAULT 'medio'
);

-- Proyectos
CREATE TABLE proyectos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    estado ENUM('planificacion','en_progreso','finalizado','cancelado') DEFAULT 'planificacion',
    fecha_inicio DATE,
    fecha_fin DATE,
    presupuesto DECIMAL(12,2),
    costo DECIMAL(12,2),
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Servicios en Proyectos
CREATE TABLE proyecto_servicio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    servicio_id INT NOT NULL,
    cantidad INT DEFAULT 1,
    precio_acordado DECIMAL(10,2),
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE RESTRICT
);

-- ============================
-- SEGURIDAD
-- ============================

-- Vulnerabilidades
CREATE TABLE vulnerabilidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    nombre VARCHAR(200),
    tipo ENUM('web','red','sistema','aplicacion','fisica','social','otros'),
    impacto ENUM('bajo','medio','alto','crítico'),
    probabilidad ENUM('baja','media','alta'),
    cvss_score DECIMAL(3,1),
    cve_id VARCHAR(20),
    estado ENUM('abierta','en_remediación','cerrada','aceptada') DEFAULT 'abierta',
    recomendacion TEXT,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE
);

-- Activos
CREATE TABLE activos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    tipo ENUM('servidor','aplicacion','red','dispositivo','cloud','otro'),
    ip VARCHAR(45),
    sistema_operativo VARCHAR(100),
    nivel_criticidad ENUM('bajo','medio','alto','crítico') DEFAULT 'medio',
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE
);

-- ============================
-- INFORMES Y PAGOS
-- ============================

-- Informes
CREATE TABLE informes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    titulo VARCHAR(200),
    tipo ENUM('preliminar','avance','final'),
    ruta_archivo VARCHAR(255),
    estado ENUM('borrador','aprobado','entregado') DEFAULT 'borrador',
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE
);

-- Facturación
CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    monto DECIMAL(12,2),
    fecha DATE,
    estado ENUM('pendiente','completado','rechazado') DEFAULT 'pendiente',
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE
);

-- ========================================
-- CYBERSEC DB - EXTENSIÓN DE TABLAS
-- ========================================

-- ========================================
-- SISTEMA DE MONITOREO Y ESCANEO
-- ========================================

-- Escaneos de Seguridad
CREATE TABLE escaneos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    activo_id INT NOT NULL,
    fecha_inicio DATETIME,
    fecha_fin DATETIME,
    tipo_escaneo ENUM('nmap','openvas','nessus','otro'),
    estado ENUM('programado','en_proceso','completado','error'),
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
    nivel_riesgo ENUM('bajo','medio','alto','crítico'),
    fecha_hallazgo DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('nuevo','en_revision','confirmado','falso_positivo'),
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
    tipo ENUM('pentesting','auditoria','seguimiento','ejecutivo'),
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
    prioridad ENUM('baja','media','alta','urgente'),
    estado ENUM('abierto','en_proceso','pendiente','cerrado'),
    tipo ENUM('incidente','solicitud','consulta','otro'),
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
    estado ENUM('borrador','publicado','archivado') DEFAULT 'borrador',
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
    tipo ENUM('documento','video','presentacion','herramienta'),
    url VARCHAR(255),
    categoria VARCHAR(50),
    acceso ENUM('publico','cliente','empleado','admin'),
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
    nivel ENUM('basico','intermedio','avanzado','experto'),
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
    estado ENUM('activa','expirada','en_renovacion'),
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
DELIMITER //

-- Trigger para actualizar fecha_actualizacion en tickets
CREATE TRIGGER before_ticket_update
BEFORE UPDATE ON tickets
FOR EACH ROW
BEGIN
    SET NEW.fecha_actualizacion = CURRENT_TIMESTAMP;
    IF NEW.estado = 'cerrado' AND OLD.estado != 'cerrado' THEN
        SET NEW.fecha_cierre = CURRENT_TIMESTAMP;
    END IF;
END//

-- Trigger para validar fechas de certificación
CREATE TRIGGER before_certificacion_insert
BEFORE INSERT ON usuario_certificaciones
FOR EACH ROW
BEGIN
    IF NEW.fecha_expiracion <= NEW.fecha_obtencion THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La fecha de expiración debe ser posterior a la fecha de obtención';
    END IF;
END//

DELIMITER ;