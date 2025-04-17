-- Datos iniciales para la base de datos de ciberseguridad
USE cybersec_db;
-- Empleados predefinidos
INSERT INTO empleados (
        nombre,
        apellidos,
        email,
        telefono,
        direccion,
        puesto,
        especialidad,
        certificaciones,
        fecha_contratacion,
        estado
    )
VALUES (
        'Carlos',
        'Martínez López',
        'carlos.martinez@cibersec.com',
        '+34612345001',
        'Calle Principal 1, Madrid',
        'Director de Seguridad',
        'Gestión de seguridad',
        'CISSP, CISM',
        '2020-01-15',
        'activo'
    ),
    (
        'Laura',
        'García Rodríguez',
        'laura.garcia@cibersec.com',
        '+34612345002',
        'Avenida Central 23, Barcelona',
        'Pentester Senior',
        'Pentesting web',
        'OSCP, CEH, eWPT',
        '2020-02-10',
        'activo'
    ),
    (
        'Miguel',
        'Fernández Sánchez',
        'miguel.fernandez@cibersec.com',
        '+34612345003',
        'Plaza Mayor 5, Valencia',
        'Analista de Seguridad',
        'Análisis forense',
        'EnCE, GCFA',
        '2020-03-20',
        'activo'
    ),
    (
        'Ana',
        'López Torres',
        'ana.lopez@cibersec.com',
        '+34612345004',
        'Calle Seguridad 8, Sevilla',
        'Pentester',
        'Pentesting de infraestructura',
        'OSCP, eCPPT',
        '2020-04-15',
        'activo'
    ),
    (
        'Javier',
        'Ruiz Navarro',
        'javier.ruiz@cibersec.com',
        '+34612345005',
        'Avenida Ciber 12, Bilbao',
        'Consultor de Seguridad',
        'Normativas y cumplimiento',
        'ISO 27001 LA, CISA',
        '2020-05-05',
        'activo'
    ),
    (
        'Elena',
        'Moreno García',
        'elena.moreno@cibersec.com',
        '+34612345006',
        'Calle Hacker 7, Zaragoza',
        'Ingeniera DevSecOps',
        'Seguridad en CI/CD',
        'AWS Security, CCSP',
        '2020-06-10',
        'activo'
    ),
    (
        'David',
        'Serrano Martín',
        'david.serrano@cibersec.com',
        '+34612345007',
        'Plaza Segura 3, Málaga',
        'Analista de Malware',
        'Análisis de malware',
        'GREM, GXPN',
        '2020-07-12',
        'activo'
    ),
    (
        'María',
        'Pérez Vázquez',
        'maria.perez@cibersec.com',
        '+34612345008',
        'Avenida Datos 20, Granada',
        'Especialista en GDPR',
        'Protección de datos',
        'CDPSE, DPO certificado',
        '2020-08-18',
        'activo'
    ),
    (
        'Alberto',
        'Díaz Sánchez',
        'alberto.diaz@cibersec.com',
        '+34612345009',
        'Calle Red 15, Murcia',
        'Administrador de Redes',
        'Seguridad en redes',
        'CCNA Security, CCNP',
        '2020-09-05',
        'activo'
    ),
    (
        'Sofía',
        'Hernández Castro',
        'sofia.hernandez@cibersec.com',
        '+34612345010',
        'Plaza Cloud 9, Alicante',
        'Especialista Cloud Security',
        'Seguridad en la nube',
        'Azure Security, GCP Pro Security',
        '2020-10-15',
        'activo'
    );
-- Servicios ofrecidos
INSERT INTO servicios (
        nombre,
        descripcion,
        categoria,
        precio_base,
        duracion_estimada,
        nivel_complejidad
    )
VALUES (
        'Pentesting Web',
        'Análisis exhaustivo de seguridad en aplicaciones web para identificar vulnerabilidades según OWASP Top 10',
        'pentesting',
        3000.00,
        '2 semanas',
        'medio'
    ),
    (
        'Pentesting de Infraestructura',
        'Evaluación de seguridad de servidores, redes y sistemas operativos para identificar brechas de seguridad',
        'pentesting',
        3500.00,
        '2 semanas',
        'alto'
    ),
    (
        'Pentesting de Aplicaciones Móviles',
        'Análisis de seguridad en aplicaciones móviles Android e iOS para identificar vulnerabilidades',
        'pentesting',
        2800.00,
        '1-2 semanas',
        'medio'
    ),
    (
        'Auditoría de Código',
        'Revisión manual y automatizada de código fuente para identificar vulnerabilidades en el desarrollo',
        'auditoria',
        2500.00,
        '1-3 semanas',
        'alto'
    ),
    (
        'Hacking Ético',
        'Simulación de técnicas reales de ataque para evaluar la seguridad global de la organización',
        'pentesting',
        4500.00,
        '3 semanas',
        'alto'
    ),
    (
        'Red Team',
        'Ejercicio completo de simulación de ataques avanzados para evaluar la defensa organizacional',
        'pentesting',
        6000.00,
        '1 mes',
        'alto'
    ),
    (
        'Evaluación de Seguridad en la Nube',
        'Análisis de la configuración y seguridad en entornos AWS, Azure o GCP',
        'auditoria',
        3200.00,
        '2 semanas',
        'medio'
    ),
    (
        'Auditoría de Cumplimiento',
        'Evaluación del cumplimiento normativo en seguridad (ISO 27001, PCI-DSS, GDPR, etc.)',
        'auditoria',
        3000.00,
        '2-3 semanas',
        'medio'
    ),
    (
        'Consultoría CISO Virtual',
        'Asesoramiento ejecutivo en materia de seguridad para organizaciones sin CISO',
        'consultoría',
        1500.00,
        'Mensual',
        'medio'
    ),
    (
        'Plan Director de Seguridad',
        'Desarrollo de un plan estratégico de seguridad adaptado a las necesidades de la organización',
        'consultoría',
        5000.00,
        '1-2 meses',
        'medio'
    ),
    (
        'Formación en Concienciación',
        'Programas de concienciación para empleados sobre seguridad y buenas prácticas',
        'formación',
        1200.00,
        '1 semana',
        'bajo'
    ),
    (
        'Formación Técnica en Seguridad',
        'Capacitación técnica especializada para equipos IT en seguridad ofensiva y defensiva',
        'formación',
        2000.00,
        '2 semanas',
        'medio'
    ),
    (
        'Respuesta a Incidentes',
        'Servicio de respuesta y gestión de incidentes de seguridad',
        'respuesta_incidentes',
        4000.00,
        'Variable',
        'alto'
    ),
    (
        'Análisis Forense Digital',
        'Investigación forense tras un incidente para determinar alcance y origen',
        'respuesta_incidentes',
        3500.00,
        '1-4 semanas',
        'alto'
    ),
    (
        'Evaluación de Proveedores',
        'Análisis de seguridad de proveedores y terceros con acceso a sistemas',
        'auditoria',
        2000.00,
        '1-2 semanas',
        'medio'
    ),
    (
        'Implementación de SOC',
        'Diseño e implementación de un Centro de Operaciones de Seguridad',
        'consultoría',
        7000.00,
        '2-3 meses',
        'alto'
    ),
    (
        'Security Champions Program',
        'Programa para desarrollar líderes de seguridad en diferentes departamentos',
        'formación',
        3000.00,
        '3 meses',
        'medio'
    ),
    (
        'Bug Bounty Program',
        'Configuración y gestión de programas de recompensas por fallos',
        'consultoría',
        2500.00,
        '1 mes + gestión',
        'medio'
    ),
    (
        'Seguridad en IoT',
        'Evaluación de seguridad específica para dispositivos IoT y sistemas embebidos',
        'pentesting',
        3000.00,
        '2 semanas',
        'alto'
    ),
    (
        'Evaluación de Seguridad DevOps',
        'Análisis y mejora de los pipeline de CI/CD para integrar seguridad',
        'consultoría',
        3500.00,
        '2-3 semanas',
        'alto'
    );
-- Herramientas utilizadas
INSERT INTO herramientas (
        nombre,
        version,
        tipo,
        descripcion,
        licencia_tipo,
        licencia_vencimiento,
        costo_anual
    )
VALUES (
        'Burp Suite Professional',
        '2023.1.0',
        'explotacion',
        'Plataforma integrada para realizar pruebas de seguridad en aplicaciones web',
        'Comercial',
        '2023-12-31',
        399.00
    ),
    (
        'Nessus Professional',
        '10.0.0',
        'escaneo',
        'Escáner de vulnerabilidades para sistemas y redes',
        'Comercial',
        '2023-12-31',
        2390.00
    ),
    (
        'Metasploit Pro',
        '6.0.0',
        'explotacion',
        'Framework para pruebas de penetración y desarrollo de exploits',
        'Comercial',
        '2023-12-31',
        14000.00
    ),
    (
        'Acunetix Premium',
        '14.7.0',
        'escaneo',
        'Escáner de vulnerabilidades para aplicaciones web',
        'Comercial',
        '2023-12-31',
        6950.00
    ),
    (
        'Wireshark',
        '3.6.0',
        'analisis_forense',
        'Analizador de protocolos de red',
        'Open Source',
        NULL,
        0.00
    ),
    (
        'Nmap',
        '7.92',
        'recon',
        'Escáner de puertos y descubrimiento de red',
        'Open Source',
        NULL,
        0.00
    ),
    (
        'OWASP ZAP',
        '2.12.0',
        'escaneo',
        'Proxy de intercepción para encontrar vulnerabilidades en aplicaciones web',
        'Open Source',
        NULL,
        0.00
    ),
    (
        'Hashcat',
        '6.2.5',
        'explotacion',
        'Herramienta de recuperación de contraseñas',
        'Open Source',
        NULL,
        0.00
    ),
    (
        'Splunk Enterprise',
        '9.0.0',
        'defensa',
        'Plataforma para búsqueda, monitorización y análisis de datos de máquina',
        'Comercial',
        '2023-12-31',
        11000.00
    ),
    (
        'Tenable.io',
        '2023.1.0',
        'escaneo',
        'Plataforma de gestión de vulnerabilidades basada en la nube',
        'Comercial',
        '2023-12-31',
        3400.00
    ),
    (
        'Aircrack-ng',
        '1.6.0',
        'explotacion',
        'Suite para auditoría de redes WiFi',
        'Open Source',
        NULL,
        0.00
    ),
    (
        'Autopsy',
        '4.19.0',
        'analisis_forense',
        'Plataforma forense digital',
        'Open Source',
        NULL,
        0.00
    ),
    (
        'Snort',
        '3.1.0',
        'defensa',
        'Sistema de detección y prevención de intrusiones',
        'Open Source',
        NULL,
        0.00
    ),
    (
        'Maltego',
        '4.3.0',
        'recon',
        'Software para minería de datos y visualización de información',
        'Comercial',
        '2023-12-31',
        999.00
    ),
    (
        'OSSEC',
        '3.7.0',
        'defensa',
        'Sistema de detección de intrusiones basado en host',
        'Open Source',
        NULL,
        0.00
    ),
    (
        'Cobalt Strike',
        '4.5.0',
        'explotacion',
        'Software para operaciones de Red Team y simulación de adversarios',
        'Comercial',
        '2023-12-31',
        3500.00
    ),
    (
        'Kali Linux',
        '2022.4',
        'general',
        'Distribución Linux para pruebas de penetración',
        'Open Source',
        NULL,
        0.00
    ),
    (
        'Qualys Cloud Platform',
        '10.0',
        'escaneo',
        'Plataforma de seguridad y cumplimiento en la nube',
        'Comercial',
        '2023-12-31',
        5000.00
    ),
    (
        'Ghidra',
        '10.1.2',
        'analisis_forense',
        'Framework de ingeniería inversa',
        'Open Source',
        NULL,
        0.00
    ),
    (
        'CrowdStrike Falcon',
        '6.45',
        'defensa',
        'Plataforma de protección de endpoints',
        'Comercial',
        '2023-12-31',
        7500.00
    );
-- Formaciones ofrecidas
INSERT INTO formaciones (
        titulo,
        descripcion,
        duracion,
        nivel,
        materiales,
        precio,
        certificable
    )
VALUES (
        'Fundamentos de Ciberseguridad',
        'Curso introductorio a los conceptos básicos de ciberseguridad para no técnicos',
        16,
        'basico',
        'Presentaciones, ejercicios prácticos, material de lectura',
        600.00,
        FALSE
    ),
    (
        'Concienciación en Seguridad para Empleados',
        'Formación para crear cultura de seguridad en la organización',
        8,
        'basico',
        'Vídeos, simulaciones de phishing, material interactivo',
        400.00,
        FALSE
    ),
    (
        'Hacking Ético: Metodologías y Herramientas',
        'Formación práctica en técnicas de hacking ético y pentesting',
        40,
        'intermedio',
        'Laboratorios virtuales, máquinas vulnerables, material teórico',
        1800.00,
        TRUE
    ),
    (
        'Análisis de Vulnerabilidades Web',
        'Técnicas para identificar y explotar vulnerabilidades en aplicaciones web',
        32,
        'intermedio',
        'Entorno de laboratorio, aplicaciones vulnerables',
        1500.00,
        TRUE
    ),
    (
        'Respuesta a Incidentes de Seguridad',
        'Metodología y procesos para gestionar incidentes de seguridad',
        24,
        'intermedio',
        'Estudios de caso, plantillas de documentación, simulaciones',
        1200.00,
        FALSE
    ),
    (
        'Seguridad en Desarrollo Seguro',
        'Integración de la seguridad en el ciclo de vida del desarrollo de software',
        32,
        'intermedio',
        'Ejemplos de código, herramientas de análisis estático',
        1400.00,
        FALSE
    ),
    (
        'Análisis Forense Digital Avanzado',
        'Técnicas avanzadas de investigación forense en sistemas digitales',
        40,
        'avanzado',
        'Imágenes forenses, herramientas especializadas, casos prácticos',
        2000.00,
        TRUE
    ),
    (
        'Red Team Operations',
        'Simulación avanzada de ataques y técnicas de adversarios',
        40,
        'avanzado',
        'Infraestructura de laboratorio, herramientas de emulación',
        2200.00,
        TRUE
    ),
    (
        'Seguridad en Arquitecturas Cloud',
        'Diseño y evaluación de arquitecturas seguras en entornos cloud',
        24,
        'avanzado',
        'Entornos cloud de práctica, plantillas de arquitectura',
        1600.00,
        FALSE
    ),
    (
        'Normativas y Cumplimiento en Ciberseguridad',
        'Marco regulatorio y normativo en seguridad de la información',
        16,
        'intermedio',
        'Documentación legal, casos de estudio, plantillas',
        900.00,
        FALSE
    ),
    (
        'Criptografía Aplicada',
        'Fundamentos y aplicaciones prácticas de la criptografía moderna',
        24,
        'avanzado',
        'Ejercicios prácticos, implementaciones de algoritmos',
        1300.00,
        FALSE
    ),
    (
        'Hardening de Sistemas',
        'Técnicas de aseguramiento y fortificación de sistemas operativos',
        24,
        'intermedio',
        'Máquinas virtuales, scripts de configuración, checklists',
        1100.00,
        FALSE
    ),
    (
        'Seguridad en Dispositivos Móviles',
        'Análisis de seguridad en aplicaciones y dispositivos móviles',
        24,
        'intermedio',
        'Dispositivos de laboratorio, aplicaciones de ejemplo',
        1200.00,
        TRUE
    ),
    (
        'Threat Hunting',
        'Técnicas proactivas para la detección de amenazas en sistemas',
        32,
        'avanzado',
        'Datasets de análisis, herramientas SIEM, casos prácticos',
        1800.00,
        TRUE
    ),
    (
        'Desarrollo de Exploits',
        'Técnicas avanzadas para el desarrollo de exploits y análisis de vulnerabilidades',
        40,
        'experto',
        'Entorno de desarrollo, binarios vulnerables, debuggers',
        2500.00,
        TRUE
    );