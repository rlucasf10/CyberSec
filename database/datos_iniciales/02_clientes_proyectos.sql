-- Datos iniciales para clientes y proyectos
USE cybersec_db;
-- Clientes predefinidos
INSERT INTO clientes (
        nombre,
        apellidos,
        empresa,
        cargo,
        email,
        telefono,
        direccion,
        estado
    )
VALUES (
        'Fernando',
        'Gómez Hernández',
        'TechSecure S.A.',
        'CIO',
        'fernando.gomez@techsecure.com',
        '+34623456001',
        'Calle Tecnología 45, Madrid',
        'activo'
    ),
    (
        'Isabel',
        'Rodríguez Martín',
        'DataProtect Consulting',
        'CEO',
        'isabel.rodriguez@dataprotect.com',
        '+34623456002',
        'Avenida Datos 78, Barcelona',
        'activo'
    ),
    (
        'Alejandro',
        'Sánchez Pérez',
        'CloudSafe Solutions',
        'CISO',
        'alejandro.sanchez@cloudsafe.com',
        '+34623456003',
        'Plaza Cloud 12, Valencia',
        'activo'
    ),
    (
        'Carmen',
        'Martínez López',
        'SecureBanking España',
        'Director de Seguridad',
        'carmen.martinez@securebanking.es',
        '+34623456004',
        'Avenida Finanzas 34, Madrid',
        'activo'
    ),
    (
        'Pablo',
        'López Torres',
        'RetailSecure',
        'CTO',
        'pablo.lopez@retailsecure.com',
        '+34623456005',
        'Calle Comercio 67, Sevilla',
        'activo'
    ),
    (
        'Lucía',
        'García Navarro',
        'HealthData Systems',
        'Responsable IT',
        'lucia.garcia@healthdata.es',
        '+34623456006',
        'Avenida Salud 23, Barcelona',
        'activo'
    ),
    (
        'Antonio',
        'Fernández Silva',
        'IndustrialTech',
        'Director IT',
        'antonio.fernandez@industrialtech.com',
        '+34623456007',
        'Polígono Industrial 5, Bilbao',
        'activo'
    ),
    (
        'Marta',
        'Díaz Vargas',
        'EduSecurity',
        'Coordinadora de Seguridad',
        'marta.diaz@edusecurity.org',
        '+34623456008',
        'Campus Universitario, Madrid',
        'activo'
    ),
    (
        'Jorge',
        'Moreno Ruiz',
        'LegalSec Abogados',
        'Socio Director',
        'jorge.moreno@legalsec.com',
        '+34623456009',
        'Calle Justicia 9, Valencia',
        'activo'
    ),
    (
        'Beatriz',
        'Santos Ortiz',
        'InsureTech',
        'Directora de Riesgos',
        'beatriz.santos@insuretech.com',
        '+34623456010',
        'Plaza Seguros 45, Madrid',
        'activo'
    ),
    (
        'Raúl',
        'Jiménez Vega',
        'AutomationSec',
        'CIO',
        'raul.jimenez@automationsec.com',
        '+34623456011',
        'Avenida Robótica 67, Barcelona',
        'potencial'
    ),
    (
        'Silvia',
        'Torres Castro',
        'MediaSecure',
        'CISO',
        'silvia.torres@mediasecure.es',
        '+34623456012',
        'Calle Prensa 8, Madrid',
        'potencial'
    ),
    (
        'Daniel',
        'Castro Reyes',
        'GovSecurity',
        'Director de Protección',
        'daniel.castro@govsecurity.gob.es',
        '+34623456013',
        'Plaza Gobierno 1, Madrid',
        'activo'
    ),
    (
        'Ana María',
        'Reyes Campos',
        'TelecomDefense',
        'Security Manager',
        'anamaria.reyes@telecomdefense.com',
        '+34623456014',
        'Torre Comunicaciones, Barcelona',
        'activo'
    ),
    (
        'Roberto',
        'González Marín',
        'StartupSecure',
        'Fundador',
        'roberto.gonzalez@startupsecure.io',
        '+34623456015',
        'Calle Innovación 23, Valencia',
        'potencial'
    );
-- Proyectos predefinidos
INSERT INTO proyectos (
        cliente_id,
        nombre,
        descripcion,
        fecha_inicio,
        fecha_fin_estimada,
        fecha_fin_real,
        estado,
        presupuesto,
        costo_real,
        notas
    )
VALUES (
        1,
        'Auditoría de Seguridad Web',
        'Evaluación completa de seguridad del portal corporativo y aplicaciones web internas',
        '2023-01-10',
        '2023-01-31',
        '2023-02-03',
        'finalizado',
        4500.00,
        4600.00,
        'Se encontraron vulnerabilidades críticas en el portal de clientes'
    ),
    (
        2,
        'Implementación SGSI',
        'Desarrollo e implementación de un Sistema de Gestión de Seguridad de la Información según ISO 27001',
        '2023-01-15',
        '2023-04-30',
        NULL,
        'en_progreso',
        12000.00,
        NULL,
        'Fase de análisis completada. Comenzando fase de desarrollo de políticas'
    ),
    (
        3,
        'Evaluación de Seguridad Cloud',
        'Análisis de configuración y controles de seguridad en AWS',
        '2023-02-05',
        '2023-02-28',
        '2023-03-05',
        'finalizado',
        5000.00,
        5200.00,
        'Identificados problemas de configuración en IAM y S3'
    ),
    (
        4,
        'Plan de Contingencia',
        'Desarrollo de plan de contingencia y continuidad de negocio',
        '2023-02-10',
        '2023-04-15',
        NULL,
        'en_progreso',
        7500.00,
        NULL,
        'Análisis de impacto de negocio completado'
    ),
    (
        5,
        'Pentesting E-commerce',
        'Test de intrusión completo a plataforma de comercio electrónico',
        '2023-03-01',
        '2023-03-20',
        '2023-03-25',
        'finalizado',
        6000.00,
        6000.00,
        'Detectadas vulnerabilidades en proceso de pago'
    ),
    (
        6,
        'Auditoría de Seguridad de Datos',
        'Evaluación de controles de seguridad para información de salud',
        '2023-03-10',
        '2023-04-10',
        NULL,
        'en_progreso',
        7000.00,
        NULL,
        'Iniciada fase de revisión de políticas de acceso'
    ),
    (
        7,
        'Seguridad OT/ICS',
        'Evaluación de seguridad en sistemas de control industrial',
        '2023-04-05',
        '2023-05-15',
        NULL,
        'en_progreso',
        9000.00,
        NULL,
        'Iniciado inventario de activos'
    ),
    (
        8,
        'Programa de Concienciación',
        'Implementación de programa de concienciación en seguridad para toda la organización',
        '2023-04-15',
        '2023-07-15',
        NULL,
        'en_progreso',
        6000.00,
        NULL,
        'Desarrollo de contenidos en curso'
    ),
    (
        9,
        'Cumplimiento GDPR',
        'Evaluación y plan de acción para cumplimiento del RGPD',
        '2023-05-02',
        '2023-06-30',
        NULL,
        'planificacion',
        8000.00,
        NULL,
        'Reunión inicial programada'
    ),
    (
        10,
        'Red Team Operation',
        'Ejercicio de Red Team para evaluar capacidades defensivas',
        '2023-06-01',
        '2023-07-15',
        NULL,
        'planificacion',
        12000.00,
        NULL,
        'Definiendo alcance con el cliente'
    ),
    (
        1,
        'Desarrollo Política Seguridad',
        'Creación de política de seguridad corporativa',
        '2022-09-10',
        '2022-10-31',
        '2022-11-05',
        'finalizado',
        3500.00,
        3450.00,
        'Entregado documento final aprobado por dirección'
    ),
    (
        2,
        'Pentesting Aplicación Interna',
        'Test de penetración a aplicación de gestión interna',
        '2022-10-15',
        '2022-11-05',
        '2022-11-10',
        'finalizado',
        4000.00,
        4200.00,
        'Identificadas vulnerabilidades críticas de inyección SQL'
    ),
    (
        3,
        'Formación Equipo IT',
        'Programa de formación en seguridad para personal técnico',
        '2022-11-10',
        '2022-12-15',
        '2022-12-20',
        'finalizado',
        5500.00,
        5500.00,
        'Evaluaciones post-formación muy positivas'
    ),
    (
        4,
        'Análisis Forense',
        'Investigación forense tras incidente de seguridad',
        '2022-12-05',
        '2023-01-15',
        '2023-01-20',
        'finalizado',
        7000.00,
        7500.00,
        'Identificado origen del compromiso y alcance'
    ),
    (
        5,
        'Hardening Servidores',
        'Fortificación de seguridad en servidores críticos',
        '2023-01-20',
        '2023-02-28',
        '2023-03-05',
        'finalizado',
        4500.00,
        4300.00,
        'Implementadas recomendaciones CIS Benchmarks'
    );
-- Asignación de empleados a proyectos
INSERT INTO proyecto_empleado (
        proyecto_id,
        empleado_id,
        rol,
        fecha_asignacion,
        fecha_fin,
        horas_asignadas
    )
VALUES (
        1,
        2,
        'Líder Técnico',
        '2023-01-10',
        '2023-02-03',
        80
    ),
    (
        1,
        4,
        'Pentester',
        '2023-01-10',
        '2023-02-03',
        60
    ),
    (
        2,
        1,
        'Director de Proyecto',
        '2023-01-15',
        NULL,
        120
    ),
    (2, 5, 'Consultor', '2023-01-15', NULL, 200),
    (
        3,
        10,
        'Especialista Principal',
        '2023-02-05',
        '2023-03-05',
        80
    ),
    (
        3,
        6,
        'Consultor',
        '2023-02-05',
        '2023-03-05',
        40
    ),
    (
        4,
        5,
        'Consultor Principal',
        '2023-02-10',
        NULL,
        150
    ),
    (
        5,
        2,
        'Líder Técnico',
        '2023-03-01',
        '2023-03-25',
        60
    ),
    (
        5,
        4,
        'Pentester',
        '2023-03-01',
        '2023-03-25',
        70
    ),
    (6, 8, 'Especialista', '2023-03-10', NULL, 100),
    (
        7,
        9,
        'Especialista en Redes',
        '2023-04-05',
        NULL,
        120
    ),
    (
        8,
        8,
        'Consultor Principal',
        '2023-04-15',
        NULL,
        90
    ),
    (
        9,
        8,
        'Consultor Principal',
        '2023-05-02',
        NULL,
        100
    ),
    (10, 2, 'Líder Red Team', '2023-06-01', NULL, 150),
    (10, 4, 'Red Team', '2023-06-01', NULL, 150),
    (10, 7, 'Red Team', '2023-06-01', NULL, 150);
-- Servicios asignados a proyectos
INSERT INTO proyecto_servicio (
        proyecto_id,
        servicio_id,
        cantidad,
        precio_acordado,
        observaciones
    )
VALUES (
        1,
        1,
        1,
        3200.00,
        'Incluye análisis de 3 aplicaciones web'
    ),
    (
        1,
        4,
        1,
        1300.00,
        'Revisión de código JavaScript del frontend'
    ),
    (2, 8, 1, 3500.00, 'Auditoría completa ISO 27001'),
    (
        2,
        10,
        1,
        5000.00,
        'Desarrollo de plan director completo'
    ),
    (
        2,
        11,
        2,
        2400.00,
        'Dos sesiones de concienciación para distintos departamentos'
    ),
    (
        3,
        7,
        1,
        3200.00,
        'Análisis completo de la infraestructura AWS'
    ),
    (
        4,
        10,
        1,
        5000.00,
        'Plan director enfocado en continuidad de negocio'
    ),
    (
        5,
        1,
        1,
        3000.00,
        'Pentesting de aplicación e-commerce'
    ),
    (
        5,
        3,
        1,
        2800.00,
        'Análisis de app móvil complementaria'
    ),
    (
        6,
        8,
        1,
        3000.00,
        'Auditoría centrada en protección de datos de salud'
    ),
    (
        7,
        2,
        1,
        3500.00,
        'Pentesting de infraestructura industrial'
    ),
    (
        7,
        8,
        1,
        3000.00,
        'Cumplimiento con normativas de infraestructuras críticas'
    ),
    (
        8,
        11,
        4,
        4800.00,
        'Programa completo para 4 divisiones de la empresa'
    ),
    (9, 8, 1, 3300.00, 'Auditoría GDPR específica'),
    (
        10,
        6,
        1,
        6000.00,
        'Red Team completo con todas las fases'
    );
-- Activos para proyectos activos
INSERT INTO activos (
        proyecto_id,
        nombre,
        tipo,
        direccion_ip,
        sistema_operativo,
        version,
        descripcion,
        nivel_criticidad,
        observaciones
    )
VALUES (
        1,
        'Servidor Web Principal',
        'servidor',
        '192.168.1.10',
        'Ubuntu Server',
        '20.04 LTS',
        'Servidor principal que aloja el portal corporativo',
        'crítico',
        'Expuesto a Internet'
    ),
    (
        1,
        'Servidor de Base de Datos',
        'servidor',
        '192.168.1.20',
        'CentOS',
        '8',
        'Servidor de base de datos principal',
        'crítico',
        'Contiene datos de clientes'
    ),
    (
        1,
        'Aplicación Intranet',
        'aplicacion',
        'N/A',
        'N/A',
        '2.5',
        'Aplicación web interna para gestión',
        'medio',
        'Solo accesible desde la red corporativa'
    ),
    (
        3,
        'Instancia EC2 Producción',
        'servidor',
        '10.0.1.10',
        'Amazon Linux',
        '2',
        'Servidor principal de aplicación en AWS',
        'crítico',
        'Expuesto a Internet a través de ELB'
    ),
    (
        3,
        'Instancia RDS',
        'servidor',
        'db-prod.aws.internal',
        'MySQL',
        '8.0',
        'Base de datos RDS de producción',
        'crítico',
        'Contiene datos de clientes'
    ),
    (
        3,
        'Bucket S3 Public',
        'cloud',
        'N/A',
        'N/A',
        'N/A',
        'Bucket S3 para contenido público',
        'alto',
        'Expuesto públicamente'
    ),
    (
        5,
        'Servidor E-commerce',
        'servidor',
        '192.168.5.10',
        'Ubuntu Server',
        '22.04 LTS',
        'Servidor principal tienda online',
        'crítico',
        'Procesa transacciones de pago'
    ),
    (
        5,
        'Aplicación Móvil Android',
        'aplicacion',
        'N/A',
        'Android',
        'v2.3',
        'App de compras para Android',
        'alto',
        'Más de 50.000 usuarios'
    ),
    (
        5,
        'Aplicación Móvil iOS',
        'aplicacion',
        'N/A',
        'iOS',
        'v2.3',
        'App de compras para iOS',
        'alto',
        'Más de 30.000 usuarios'
    ),
    (
        6,
        'Servidor de Historiales',
        'servidor',
        '192.168.10.50',
        'Windows Server',
        '2019',
        'Servidor con datos médicos de pacientes',
        'crítico',
        'Contiene datos sensibles de salud'
    ),
    (
        6,
        'Base de Datos Pacientes',
        'servidor',
        '192.168.10.51',
        'SQL Server',
        '2019',
        'Base de datos principal de pacientes',
        'crítico',
        'Datos altamente sensibles'
    ),
    (
        7,
        'PLC Principal',
        'dispositivo',
        '10.100.1.5',
        'Siemens OS',
        '7.5',
        'Controlador lógico programable principal',
        'crítico',
        'Controla procesos críticos'
    ),
    (
        7,
        'HMI Supervisión',
        'dispositivo',
        '10.100.1.10',
        'Windows Embedded',
        '7',
        'Interfaz hombre-máquina para supervisión',
        'alto',
        'Permite control de procesos'
    ),
    (
        7,
        'Gateway OT/IT',
        'red',
        '10.100.1.1',
        'Custom Linux',
        '4.2',
        'Pasarela entre red industrial y corporativa',
        'crítico',
        'Punto crítico de segregación'
    );
-- Vulnerabilidades detectadas
INSERT INTO vulnerabilidades (
        proyecto_id,
        nombre,
        descripcion,
        tipo,
        impacto,
        probabilidad,
        cvss_score,
        cve_id,
        fecha_descubrimiento,
        estado,
        recomendacion
    )
VALUES (
        1,
        'SQL Injection en formulario de login',
        'Vulnerabilidad de inyección SQL en la validación del formulario de inicio de sesión',
        'web',
        'crítico',
        'alta',
        9.8,
        'CVE-2021-45631',
        '2023-01-15',
        'cerrada',
        'Implementar consultas parametrizadas y validación de entrada'
    ),
    (
        1,
        'Cross-Site Scripting en comentarios',
        'Vulnerabilidad XSS reflejado en sistema de comentarios',
        'web',
        'medio',
        'media',
        6.1,
        NULL,
        '2023-01-16',
        'cerrada',
        'Implementar escape de salida y Content Security Policy'
    ),
    (
        1,
        'Exposición de información sensible',
        'Información sensible expuesta en respuestas HTTP',
        'web',
        'medio',
        'baja',
        5.3,
        NULL,
        '2023-01-18',
        'cerrada',
        'Revisar headers HTTP y eliminar información no necesaria'
    ),
    (
        3,
        'Permisos excesivos en IAM',
        'Roles IAM con permisos excesivos que violan el principio de privilegio mínimo',
        'sistema',
        'alto',
        'media',
        7.6,
        NULL,
        '2023-02-18',
        'cerrada',
        'Aplicar principio de mínimo privilegio y revisar permisos'
    ),
    (
        3,
        'Bucket S3 con acceso público',
        'Bucket S3 configurado para permitir acceso público',
        'sistema',
        'alto',
        'alta',
        8.2,
        NULL,
        '2023-02-20',
        'cerrada',
        'Bloquear acceso público y revisar políticas de acceso'
    ),
    (
        3,
        'Secretos en código (hardcoded)',
        'Credenciales y claves API en código fuente',
        'aplicacion',
        'crítico',
        'media',
        9.1,
        NULL,
        '2023-02-22',
        'cerrada',
        'Utilizar servicios de gestión de secretos como AWS Secrets Manager'
    ),
    (
        5,
        'CSRF en proceso de compra',
        'No validación de token CSRF en proceso de compra',
        'web',
        'alto',
        'media',
        8.0,
        NULL,
        '2023-03-10',
        'cerrada',
        'Implementar tokens anti-CSRF en todos los formularios'
    ),
    (
        5,
        'Falta de validación de entrada en API',
        'Parámetros de API no validados adecuadamente',
        'web',
        'medio',
        'alta',
        6.8,
        NULL,
        '2023-03-12',
        'cerrada',
        'Implementar validación de entrada para todos los parámetros'
    ),
    (
        5,
        'Almacenamiento inseguro en app móvil',
        'Credenciales almacenadas de forma insegura en app móvil',
        'aplicacion',
        'alto',
        'media',
        7.5,
        NULL,
        '2023-03-14',
        'en_remediación',
        'Utilizar almacenamiento seguro como Android Keystore/iOS Keychain'
    );
-- Informes generados
INSERT INTO informes (
        proyecto_id,
        titulo,
        tipo,
        fecha_creacion,
        autor_id,
        ruta_archivo,
        version,
        estado
    )
VALUES (
        1,
        'Informe Preliminar - Auditoría Web TechSecure',
        'preliminar',
        '2023-01-20',
        2,
        '/informes/2023/TS_Preliminar_20230120.pdf',
        '0.8',
        'aprobado'
    ),
    (
        1,
        'Informe Ejecutivo - Auditoría Web TechSecure',
        'ejecutivo',
        '2023-02-02',
        1,
        '/informes/2023/TS_Ejecutivo_20230202.pdf',
        '1.0',
        'entregado'
    ),
    (
        1,
        'Informe Técnico - Auditoría Web TechSecure',
        'tecnico',
        '2023-02-02',
        2,
        '/informes/2023/TS_Tecnico_20230202.pdf',
        '1.0',
        'entregado'
    ),
    (
        3,
        'Informe Preliminar - Seguridad AWS CloudSafe',
        'preliminar',
        '2023-02-25',
        10,
        '/informes/2023/CS_Preliminar_20230225.pdf',
        '0.9',
        'aprobado'
    ),
    (
        3,
        'Informe Ejecutivo - Seguridad AWS CloudSafe',
        'ejecutivo',
        '2023-03-04',
        1,
        '/informes/2023/CS_Ejecutivo_20230304.pdf',
        '1.0',
        'entregado'
    ),
    (
        3,
        'Informe Técnico - Seguridad AWS CloudSafe',
        'tecnico',
        '2023-03-04',
        10,
        '/informes/2023/CS_Tecnico_20230304.pdf',
        '1.0',
        'entregado'
    ),
    (
        5,
        'Informe Preliminar - Pentesting E-commerce',
        'preliminar',
        '2023-03-15',
        2,
        '/informes/2023/RS_Preliminar_20230315.pdf',
        '0.9',
        'aprobado'
    ),
    (
        5,
        'Informe Ejecutivo - Pentesting E-commerce',
        'ejecutivo',
        '2023-03-24',
        1,
        '/informes/2023/RS_Ejecutivo_20230324.pdf',
        '1.0',
        'entregado'
    ),
    (
        5,
        'Informe Técnico - Pentesting E-commerce',
        'tecnico',
        '2023-03-24',
        2,
        '/informes/2023/RS_Tecnico_20230324.pdf',
        '1.0',
        'entregado'
    );
-- Herramientas usadas en proyectos
INSERT INTO proyecto_herramienta (
        proyecto_id,
        herramienta_id,
        fecha_uso,
        resultado
    )
VALUES (
        1,
        1,
        '2023-01-12',
        'Identificadas vulnerabilidades en aplicación web'
    ),
    (
        1,
        3,
        '2023-01-14',
        'Explotación de vulnerabilidades para prueba de concepto'
    ),
    (
        1,
        7,
        '2023-01-11',
        'Análisis inicial automatizado'
    ),
    (
        3,
        10,
        '2023-02-10',
        'Escaneo completo de infraestructura AWS'
    ),
    (
        3,
        18,
        '2023-02-12',
        'Análisis de conformidad cloud'
    ),
    (
        3,
        6,
        '2023-02-08',
        'Descubrimiento de servicios'
    ),
    (
        5,
        1,
        '2023-03-05',
        'Análisis de aplicación web de e-commerce'
    ),
    (
        5,
        4,
        '2023-03-06',
        'Escaneo automatizado de vulnerabilidades'
    ),
    (5, 7, '2023-03-04', 'Análisis preliminar');