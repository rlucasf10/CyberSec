-- ================================================
-- Base de datos para empresa de ciberseguridad y pentesting
-- ================================================
CREATE DATABASE IF NOT EXISTS cybersec_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cybersec_db;
-- ================================================
-- TABLAS DE ENTIDADES PRINCIPALES
-- ================================================
-- Tabla de clientes
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    empresa VARCHAR(150),
    cargo VARCHAR(100),
    email VARCHAR(150) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    estado ENUM('activo', 'inactivo', 'potencial') DEFAULT 'potencial'
);
-- Tabla de empleados/consultores
CREATE TABLE IF NOT EXISTS empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    puesto VARCHAR(100) NOT NULL,
    especialidad VARCHAR(150),
    certificaciones TEXT,
    fecha_contratacion DATE,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo'
);
-- Tabla para gestionar usuarios y autenticación
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    salt VARCHAR(64) NOT NULL,
    tipo_usuario ENUM('cliente', 'empleado', 'administrador') NOT NULL,
    referencia_id INT NOT NULL,
    -- ID de cliente o empleado según tipo_usuario
    ultimo_acceso DATETIME NULL,
    intentos_fallidos TINYINT UNSIGNED DEFAULT 0,
    -- Contador de intentos fallidos
    bloqueado TINYINT(1) DEFAULT 0,
    -- 0=desbloqueado, 1=bloqueado
    token_recuperacion VARCHAR(64) NULL,
    -- Token para recuperación de contraseña
    fecha_token DATETIME NULL,
    -- Fecha de expiración del token
    creado DATETIME NOT NULL,
    actualizado DATETIME NOT NULL,
    INDEX idx_email (email),
    INDEX idx_tipo_usuario (tipo_usuario),
    INDEX idx_referencia_id (referencia_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- Tabla de servicios ofrecidos
CREATE TABLE IF NOT EXISTS servicios (
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
    duracion_estimada VARCHAR(50),
    nivel_complejidad ENUM('bajo', 'medio', 'alto') DEFAULT 'medio'
);
-- ================================================
-- TABLAS DE PROYECTOS Y RELACIONES
-- ================================================
-- Tabla de proyectos
CREATE TABLE IF NOT EXISTS proyectos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    fecha_inicio DATE,
    fecha_fin_estimada DATE,
    fecha_fin_real DATE,
    estado ENUM(
        'planificacion',
        'en_progreso',
        'finalizado',
        'cancelado',
        'pausa'
    ) DEFAULT 'planificacion',
    presupuesto DECIMAL(12, 2),
    costo_real DECIMAL(12, 2),
    notas TEXT,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
);
-- Tabla de asignación de consultores a proyectos
CREATE TABLE IF NOT EXISTS proyecto_empleado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    empleado_id INT NOT NULL,
    rol VARCHAR(100) NOT NULL,
    fecha_asignacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_fin DATE,
    horas_asignadas INT,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE CASCADE
);
-- Tabla de servicios incluidos en cada proyecto
CREATE TABLE IF NOT EXISTS proyecto_servicio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    servicio_id INT NOT NULL,
    cantidad INT DEFAULT 1,
    precio_acordado DECIMAL(10, 2),
    observaciones TEXT,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE RESTRICT
);
-- ================================================
-- TABLAS DE SEGURIDAD Y EVALUACIONES
-- ================================================
-- Tabla de vulnerabilidades encontradas
CREATE TABLE IF NOT EXISTS vulnerabilidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    tipo ENUM(
        'web',
        'red',
        'sistema',
        'aplicacion',
        'fisica',
        'social',
        'otros'
    ) NOT NULL,
    impacto ENUM('bajo', 'medio', 'alto', 'crítico') NOT NULL,
    probabilidad ENUM('baja', 'media', 'alta') NOT NULL,
    cvss_score DECIMAL(3, 1),
    cve_id VARCHAR(20),
    fecha_descubrimiento DATE,
    estado ENUM(
        'abierta',
        'en_remediación',
        'cerrada',
        'aceptada'
    ) DEFAULT 'abierta',
    recomendacion TEXT,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE
);
-- Tabla de activos evaluados
CREATE TABLE IF NOT EXISTS activos (
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
    ) NOT NULL,
    direccion_ip VARCHAR(45),
    sistema_operativo VARCHAR(100),
    version VARCHAR(50),
    descripcion TEXT,
    nivel_criticidad ENUM('bajo', 'medio', 'alto', 'crítico') DEFAULT 'medio',
    observaciones TEXT,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE
);
-- Tabla para evaluación de riesgos
CREATE TABLE IF NOT EXISTS evaluacion_riesgos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    probabilidad ENUM('baja', 'media', 'alta', 'muy alta') NOT NULL,
    impacto ENUM('bajo', 'medio', 'alto', 'crítico') NOT NULL,
    nivel_riesgo VARCHAR(50),
    recomendacion TEXT,
    estado ENUM(
        'identificado',
        'analizado',
        'tratado',
        'aceptado',
        'transferido'
    ) DEFAULT 'identificado',
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE
);
-- Tabla para registro de eventos de seguridad
CREATE TABLE IF NOT EXISTS eventos_seguridad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    proyecto_id INT,
    fecha_deteccion DATETIME NOT NULL,
    tipo ENUM('incidente', 'brecha', 'alerta', 'anomalia') NOT NULL,
    descripcion TEXT NOT NULL,
    impacto ENUM('bajo', 'medio', 'alto', 'crítico') NOT NULL,
    estado ENUM(
        'detectado',
        'analisis',
        'contencion',
        'resuelto',
        'cerrado'
    ) DEFAULT 'detectado',
    responsable_id INT,
    accion_tomada TEXT,
    fecha_resolucion DATETIME,
    lecciones_aprendidas TEXT,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE
    SET NULL,
        FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE
    SET NULL,
        FOREIGN KEY (responsable_id) REFERENCES empleados(id) ON DELETE
    SET NULL
);
-- ================================================
-- TABLAS DE HERRAMIENTAS Y RECURSOS
-- ================================================
-- Tabla de herramientas utilizadas
CREATE TABLE IF NOT EXISTS herramientas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    version VARCHAR(50),
    tipo ENUM(
        'recon',
        'escaneo',
        'explotacion',
        'analisis_forense',
        'defensa',
        'general'
    ) NOT NULL,
    descripcion TEXT,
    licencia_tipo VARCHAR(100),
    licencia_vencimiento DATE,
    costo_anual DECIMAL(10, 2)
);
-- Tabla de herramientas usadas en proyectos
CREATE TABLE IF NOT EXISTS proyecto_herramienta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    herramienta_id INT NOT NULL,
    fecha_uso DATE,
    resultado TEXT,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE,
    FOREIGN KEY (herramienta_id) REFERENCES herramientas(id) ON DELETE RESTRICT
);
-- Tabla para gestionar conocimientos base
CREATE TABLE IF NOT EXISTS base_conocimiento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    categoria ENUM(
        'procedimiento',
        'vulnerabilidad',
        'solucion',
        'herramienta',
        'normativa',
        'otro'
    ) NOT NULL,
    contenido TEXT NOT NULL,
    tags VARCHAR(255),
    creado_por INT,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (creado_por) REFERENCES empleados(id) ON DELETE
    SET NULL
);
-- ================================================
-- TABLAS DE DOCUMENTACIÓN Y REPORTES
-- ================================================
-- Tabla de informes generados
CREATE TABLE IF NOT EXISTS informes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    tipo ENUM(
        'preliminar',
        'avance',
        'final',
        'ejecutivo',
        'tecnico'
    ) NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    autor_id INT,
    ruta_archivo VARCHAR(255),
    version VARCHAR(10),
    estado ENUM('borrador', 'revision', 'aprobado', 'entregado') DEFAULT 'borrador',
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE,
    FOREIGN KEY (autor_id) REFERENCES empleados(id) ON DELETE
    SET NULL
);
-- ================================================
-- TABLAS DE FACTURACIÓN Y PAGOS
-- ================================================
-- Tabla de pagos recibidos
CREATE TABLE IF NOT EXISTS pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    monto DECIMAL(12, 2) NOT NULL,
    fecha DATE NOT NULL,
    metodo_pago VARCHAR(50),
    referencia VARCHAR(100),
    estado ENUM(
        'pendiente',
        'procesando',
        'completado',
        'rechazado'
    ) DEFAULT 'pendiente',
    notas TEXT,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE
);
-- Tabla de facturas
CREATE TABLE IF NOT EXISTS facturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    proyecto_id INT NOT NULL,
    numero_factura VARCHAR(50) UNIQUE NOT NULL,
    fecha_emision DATE NOT NULL,
    fecha_vencimiento DATE NOT NULL,
    monto_total DECIMAL(12, 2) NOT NULL,
    impuestos DECIMAL(12, 2),
    estado ENUM('emitida', 'pagada', 'vencida', 'anulada') DEFAULT 'emitida',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE RESTRICT
);
-- ================================================
-- TABLAS DE COMUNICACIÓN Y SEGUIMIENTO
-- ================================================
-- Tabla de comunicaciones con clientes
CREATE TABLE IF NOT EXISTS comunicaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    empleado_id INT,
    proyecto_id INT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    tipo ENUM('email', 'llamada', 'reunion', 'otro') NOT NULL,
    asunto VARCHAR(200),
    contenido TEXT,
    seguimiento_requerido BOOLEAN DEFAULT FALSE,
    fecha_seguimiento DATE,
    estado ENUM('pendiente', 'completado', 'no_requiere') DEFAULT 'pendiente',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE
    SET NULL,
        FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE
    SET NULL
);
-- ================================================
-- TABLAS DE FORMACIÓN Y CERTIFICACIONES
-- ================================================
-- Tabla para gestión de formaciones
CREATE TABLE IF NOT EXISTS formaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    duracion INT,
    -- en horas
    nivel ENUM('basico', 'intermedio', 'avanzado', 'experto') NOT NULL,
    materiales TEXT,
    precio DECIMAL(10, 2),
    certificable BOOLEAN DEFAULT FALSE
);
-- Tabla para asignar formaciones a proyectos/clientes
CREATE TABLE IF NOT EXISTS proyecto_formacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    formacion_id INT NOT NULL,
    fecha_inicio DATE,
    fecha_fin DATE,
    instructor_id INT,
    num_participantes INT,
    lugar VARCHAR(200),
    modalidad ENUM('presencial', 'online', 'hibrido') DEFAULT 'presencial',
    estado ENUM(
        'programada',
        'en_curso',
        'completada',
        'cancelada'
    ) DEFAULT 'programada',
    evaluacion_promedio DECIMAL(3, 2),
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE,
    FOREIGN KEY (formacion_id) REFERENCES formaciones(id) ON DELETE RESTRICT,
    FOREIGN KEY (instructor_id) REFERENCES empleados(id) ON DELETE
    SET NULL
);
-- Tabla para seguimiento de certificaciones de empleados
CREATE TABLE IF NOT EXISTS empleado_certificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    entidad_emisora VARCHAR(100) NOT NULL,
    fecha_obtencion DATE NOT NULL,
    fecha_vencimiento DATE,
    codigo_verificacion VARCHAR(100),
    documento_path VARCHAR(255),
    FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE CASCADE
);
-- ================================================
-- ÍNDICES PARA OPTIMIZAR CONSULTAS
-- ================================================
CREATE INDEX idx_clientes_email ON clientes(email);
CREATE INDEX idx_proyectos_cliente ON proyectos(cliente_id);
CREATE INDEX idx_vulnerabilidades_proyecto ON vulnerabilidades(proyecto_id);
CREATE INDEX idx_empleados_especialidad ON empleados(especialidad);
CREATE INDEX idx_informes_proyecto ON informes(proyecto_id);
CREATE INDEX idx_activos_proyecto ON activos(proyecto_id);
CREATE INDEX idx_pagos_proyecto ON pagos(proyecto_id);
CREATE INDEX idx_facturas_cliente ON facturas(cliente_id);
CREATE INDEX idx_eventos_seguridad_cliente ON eventos_seguridad(cliente_id);
CREATE INDEX idx_certificaciones_empleado ON empleado_certificaciones(empleado_id);


