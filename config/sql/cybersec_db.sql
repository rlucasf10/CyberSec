CREATE DATABASE IF NOT EXISTS cybersec_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
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
    estado ENUM('activo', 'inactivo', 'pendiente', 'bloqueado') DEFAULT 'activo',
    token_recuperacion VARCHAR(64),
    fecha_token DATETIME,
    remember_token VARCHAR(64),
    token_expiry DATETIME,
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
    categoria ENUM(
        'pentesting',
        'auditoria',
        'consultoría',
        'formación',
        'respuesta_incidentes',
        'otro'
    ) NOT NULL,
    precio_base DECIMAL(10, 2),
    nivel_complejidad ENUM('bajo', 'medio', 'alto') DEFAULT 'medio'
);
-- Proyectos
CREATE TABLE proyectos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    estado ENUM(
        'planificacion',
        'en_progreso',
        'finalizado',
        'cancelado'
    ) DEFAULT 'planificacion',
    fecha_inicio DATE,
    fecha_fin DATE,
    presupuesto DECIMAL(12, 2),
    costo DECIMAL(12, 2),
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
-- Servicios en Proyectos
CREATE TABLE proyecto_servicio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    servicio_id INT NOT NULL,
    cantidad INT DEFAULT 1,
    precio_acordado DECIMAL(10, 2),
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
    tipo ENUM(
        'web',
        'red',
        'sistema',
        'aplicacion',
        'fisica',
        'social',
        'otros'
    ),
    impacto ENUM('bajo', 'medio', 'alto', 'crítico'),
    probabilidad ENUM('baja', 'media', 'alta'),
    cvss_score DECIMAL(3, 1),
    cve_id VARCHAR(20),
    estado ENUM(
        'abierta',
        'en_remediación',
        'cerrada',
        'aceptada'
    ) DEFAULT 'abierta',
    recomendacion TEXT,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE
);
-- Activos
CREATE TABLE activos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    tipo ENUM(
        'servidor',
        'aplicacion',
        'red',
        'dispositivo',
        'cloud',
        'otro'
    ),
    ip VARCHAR(45),
    sistema_operativo VARCHAR(100),
    nivel_criticidad ENUM('bajo', 'medio', 'alto', 'crítico') DEFAULT 'medio',
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
    tipo ENUM('preliminar', 'avance', 'final'),
    ruta_archivo VARCHAR(255),
    estado ENUM('borrador', 'aprobado', 'entregado') DEFAULT 'borrador',
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE
);
-- Facturación
CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    monto DECIMAL(12, 2),
    fecha DATE,
    estado ENUM('pendiente', 'completado', 'rechazado') DEFAULT 'pendiente',
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE
);