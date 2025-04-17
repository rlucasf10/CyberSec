-- Datos iniciales para finanzas, formación y conocimiento
USE cybersec_db;
-- Facturas
INSERT INTO facturas (
        cliente_id,
        proyecto_id,
        numero_factura,
        fecha_emision,
        fecha_vencimiento,
        monto_total,
        impuestos,
        estado
    )
VALUES (
        1,
        1,
        '2023-02-001',
        '2023-02-05',
        '2023-03-07',
        4500.00,
        945.00,
        'pagada'
    ),
    (
        1,
        11,
        '2022-11-001',
        '2022-11-07',
        '2022-12-07',
        3500.00,
        735.00,
        'pagada'
    ),
    (
        2,
        2,
        '2023-02-002',
        '2023-02-01',
        '2023-03-03',
        6000.00,
        1260.00,
        'pagada'
    ),
    (
        2,
        2,
        '2023-03-003',
        '2023-03-01',
        '2023-04-01',
        6000.00,
        1260.00,
        'emitida'
    ),
    (
        2,
        12,
        '2022-11-002',
        '2022-11-12',
        '2022-12-12',
        4000.00,
        840.00,
        'pagada'
    ),
    (
        3,
        3,
        '2023-03-004',
        '2023-03-07',
        '2023-04-07',
        5000.00,
        1050.00,
        'pagada'
    ),
    (
        3,
        13,
        '2022-12-003',
        '2022-12-22',
        '2023-01-22',
        5500.00,
        1155.00,
        'pagada'
    ),
    (
        4,
        4,
        '2023-03-005',
        '2023-03-01',
        '2023-04-01',
        3750.00,
        787.50,
        'pagada'
    ),
    (
        4,
        14,
        '2023-01-004',
        '2023-01-22',
        '2023-02-22',
        7000.00,
        1470.00,
        'pagada'
    ),
    (
        5,
        5,
        '2023-04-005',
        '2023-04-01',
        '2023-05-01',
        6000.00,
        1260.00,
        'pagada'
    ),
    (
        5,
        15,
        '2023-03-006',
        '2023-03-07',
        '2023-04-07',
        4500.00,
        945.00,
        'pagada'
    ),
    (
        6,
        6,
        '2023-04-007',
        '2023-04-02',
        '2023-05-02',
        3500.00,
        735.00,
        'emitida'
    ),
    (
        7,
        7,
        '2023-05-008',
        '2023-05-01',
        '2023-06-01',
        4500.00,
        945.00,
        'emitida'
    ),
    (
        8,
        8,
        '2023-05-009',
        '2023-05-05',
        '2023-06-05',
        3000.00,
        630.00,
        'emitida'
    );
-- Pagos
INSERT INTO pagos (
        proyecto_id,
        monto,
        fecha,
        metodo_pago,
        referencia,
        estado,
        notas
    )
VALUES (
        1,
        4500.00,
        '2023-02-20',
        'Transferencia',
        'TRA-20230220-001',
        'completado',
        'Pago completo de auditoría web'
    ),
    (
        2,
        6000.00,
        '2023-02-15',
        'Transferencia',
        'TRA-20230215-001',
        'completado',
        'Primer pago del proyecto SGSI'
    ),
    (
        3,
        5000.00,
        '2023-03-15',
        'Transferencia',
        'TRA-20230315-001',
        'completado',
        'Pago completo evaluación AWS'
    ),
    (
        4,
        3750.00,
        '2023-03-10',
        'Transferencia',
        'TRA-20230310-001',
        'completado',
        'Primer pago (50%) del plan de contingencia'
    ),
    (
        5,
        6000.00,
        '2023-04-10',
        'Transferencia',
        'TRA-20230410-001',
        'completado',
        'Pago completo pentesting e-commerce'
    ),
    (
        6,
        3500.00,
        '2023-04-20',
        'Transferencia',
        'TRA-20230420-001',
        'procesando',
        'Primer pago (50%) auditoría datos de salud'
    ),
    (
        11,
        3500.00,
        '2022-11-20',
        'Transferencia',
        'TRA-20221120-001',
        'completado',
        'Pago completo política de seguridad'
    ),
    (
        12,
        4000.00,
        '2022-11-25',
        'Transferencia',
        'TRA-20221125-001',
        'completado',
        'Pago completo pentesting aplicación'
    ),
    (
        13,
        5500.00,
        '2023-01-05',
        'Transferencia',
        'TRA-20230105-001',
        'completado',
        'Pago completo formación equipo'
    ),
    (
        14,
        7000.00,
        '2023-02-01',
        'Transferencia',
        'TRA-20230201-001',
        'completado',
        'Pago completo análisis forense'
    ),
    (
        15,
        4500.00,
        '2023-03-25',
        'Transferencia',
        'TRA-20230325-001',
        'completado',
        'Pago completo hardening servidores'
    );
-- Certificaciones de empleados
INSERT INTO empleado_certificaciones (
        empleado_id,
        nombre,
        entidad_emisora,
        fecha_obtencion,
        fecha_vencimiento,
        codigo_verificacion
    )
VALUES (
        1,
        'CISSP',
        'ISC2',
        '2019-05-15',
        '2025-05-15',
        'CISSP-123456'
    ),
    (
        1,
        'CISM',
        'ISACA',
        '2020-03-10',
        '2023-12-31',
        'CISM-789012'
    ),
    (
        2,
        'OSCP',
        'Offensive Security',
        '2019-08-20',
        NULL,
        'OSCP-456789'
    ),
    (
        2,
        'CEH',
        'EC-Council',
        '2018-05-10',
        '2024-05-10',
        'CEH-345678'
    ),
    (
        2,
        'eWPT',
        'eLearnSecurity',
        '2020-01-15',
        NULL,
        'eWPT-234567'
    ),
    (
        3,
        'EnCE',
        'Guidance Software',
        '2019-11-05',
        '2022-11-05',
        'EnCE-567890'
    ),
    (
        3,
        'GCFA',
        'GIAC',
        '2020-02-20',
        '2024-02-20',
        'GCFA-678901'
    ),
    (
        4,
        'OSCP',
        'Offensive Security',
        '2018-10-10',
        NULL,
        'OSCP-789012'
    ),
    (
        4,
        'eCPPT',
        'eLearnSecurity',
        '2019-07-15',
        NULL,
        'eCPPT-890123'
    ),
    (
        5,
        'ISO 27001 LA',
        'PECB',
        '2019-09-20',
        '2022-09-20',
        'ISOLA-901234'
    ),
    (
        5,
        'CISA',
        'ISACA',
        '2018-12-15',
        '2022-12-15',
        'CISA-012345'
    ),
    (
        6,
        'AWS Security Specialty',
        'Amazon Web Services',
        '2020-04-25',
        '2023-04-25',
        'AWS-123456'
    ),
    (
        6,
        'CCSP',
        'ISC2',
        '2019-06-10',
        '2025-06-10',
        'CCSP-234567'
    ),
    (
        7,
        'GREM',
        'GIAC',
        '2020-05-05',
        '2024-05-05',
        'GREM-345678'
    ),
    (
        7,
        'GXPN',
        'GIAC',
        '2019-11-15',
        '2023-11-15',
        'GXPN-456789'
    ),
    (
        8,
        'CDPSE',
        'ISACA',
        '2020-01-30',
        '2023-01-30',
        'CDPSE-567890'
    ),
    (
        9,
        'CCNA Security',
        'Cisco',
        '2019-08-10',
        '2022-08-10',
        'CCNAS-678901'
    ),
    (
        9,
        'CCNP Security',
        'Cisco',
        '2020-03-05',
        '2023-03-05',
        'CCNPS-789012'
    ),
    (
        10,
        'Azure Security Engineer',
        'Microsoft',
        '2020-02-15',
        '2022-02-15',
        'AZ500-890123'
    ),
    (
        10,
        'GCP Professional Cloud Security',
        'Google',
        '2020-05-20',
        '2022-05-20',
        'GCP-901234'
    );
-- Proyecto de formación
INSERT INTO proyecto_formacion (
        proyecto_id,
        formacion_id,
        fecha_inicio,
        fecha_fin,
        instructor_id,
        num_participantes,
        lugar,
        modalidad,
        estado,
        evaluacion_promedio
    )
VALUES (
        8,
        1,
        '2023-05-10',
        '2023-05-11',
        8,
        25,
        'Sede del cliente',
        'presencial',
        'completada',
        4.7
    ),
    (
        8,
        2,
        '2023-06-05',
        '2023-06-05',
        8,
        30,
        'Sede del cliente',
        'presencial',
        'programada',
        NULL
    ),
    (
        13,
        3,
        '2022-11-15',
        '2022-11-19',
        2,
        8,
        'Centro de formación',
        'presencial',
        'completada',
        4.8
    ),
    (
        13,
        4,
        '2022-12-01',
        '2022-12-03',
        4,
        8,
        'Centro de formación',
        'presencial',
        'completada',
        4.6
    ),
    (
        13,
        9,
        '2022-12-10',
        '2022-12-11',
        10,
        8,
        'Centro de formación',
        'presencial',
        'completada',
        4.5
    );
-- Comunicaciones con clientes
INSERT INTO comunicaciones (
        cliente_id,
        empleado_id,
        proyecto_id,
        fecha,
        tipo,
        asunto,
        contenido,
        seguimiento_requerido,
        fecha_seguimiento,
        estado
    )
VALUES (
        1,
        1,
        1,
        '2023-01-05',
        'email',
        'Inicio de proyecto de auditoría web',
        'Estimado cliente, nos complace informarle que iniciaremos el proyecto el próximo 10 de enero. Adjuntamos el plan de trabajo.',
        FALSE,
        NULL,
        'completado'
    ),
    (
        1,
        2,
        1,
        '2023-01-20',
        'email',
        'Informe preliminar - Hallazgos críticos',
        'Hemos encontrado vulnerabilidades críticas que requieren atención inmediata. Adjuntamos informe preliminar.',
        TRUE,
        '2023-01-25',
        'completado'
    ),
    (
        1,
        1,
        1,
        '2023-01-25',
        'reunion',
        'Revisión de hallazgos críticos',
        'Reunión para revisar las vulnerabilidades críticas encontradas y las acciones recomendadas.',
        TRUE,
        '2023-02-01',
        'completado'
    ),
    (
        1,
        1,
        1,
        '2023-02-03',
        'email',
        'Informe final de auditoría',
        'Adjuntamos el informe final con todas las vulnerabilidades encontradas y recomendaciones.',
        FALSE,
        NULL,
        'completado'
    ),
    (
        2,
        1,
        2,
        '2023-01-10',
        'email',
        'Inicio proyecto SGSI',
        'Confirmamos inicio del proyecto para implementación del SGSI según lo acordado.',
        FALSE,
        NULL,
        'completado'
    ),
    (
        2,
        5,
        2,
        '2023-02-15',
        'reunion',
        'Revisión de análisis inicial',
        'Presentación de resultados del análisis inicial y próximos pasos.',
        TRUE,
        '2023-03-01',
        'completado'
    ),
    (
        2,
        5,
        2,
        '2023-03-15',
        'email',
        'Entrega de políticas preliminares',
        'Adjuntamos el primer borrador de políticas de seguridad para su revisión.',
        TRUE,
        '2023-03-30',
        'pendiente'
    ),
    (
        3,
        10,
        3,
        '2023-02-01',
        'email',
        'Preparación evaluación AWS',
        'Solicitamos acceso a los entornos AWS para comenzar la evaluación.',
        TRUE,
        '2023-02-05',
        'completado'
    ),
    (
        3,
        10,
        3,
        '2023-02-25',
        'email',
        'Informe preliminar AWS',
        'Adjuntamos informe preliminar con los hallazgos críticos en la configuración de AWS.',
        TRUE,
        '2023-03-01',
        'completado'
    ),
    (
        3,
        1,
        3,
        '2023-03-05',
        'reunion',
        'Entrega final evaluación AWS',
        'Presentación final de resultados y plan de remediación.',
        FALSE,
        NULL,
        'completado'
    ),
    (
        4,
        5,
        4,
        '2023-02-08',
        'email',
        'Cuestionario análisis impacto',
        'Adjuntamos cuestionario para el análisis de impacto del negocio.',
        TRUE,
        '2023-02-20',
        'completado'
    ),
    (
        5,
        2,
        5,
        '2023-02-25',
        'email',
        'Preparación pentesting e-commerce',
        'Confirmamos inicio del pentesting para el 1 de marzo. Solicitamos datos de acceso.',
        TRUE,
        '2023-03-01',
        'completado'
    ),
    (
        5,
        2,
        5,
        '2023-03-15',
        'llamada',
        'Hallazgos críticos en proceso de pago',
        'Comunicación urgente sobre vulnerabilidades en el proceso de pago.',
        TRUE,
        '2023-03-16',
        'completado'
    ),
    (
        5,
        1,
        5,
        '2023-03-25',
        'reunion',
        'Presentación resultados finales',
        'Reunión para presentar todos los hallazgos y plan de remediación.',
        FALSE,
        NULL,
        'completado'
    );
-- Eventos de seguridad
INSERT INTO eventos_seguridad (
        cliente_id,
        proyecto_id,
        fecha_deteccion,
        tipo,
        descripcion,
        impacto,
        estado,
        responsable_id,
        accion_tomada,
        fecha_resolucion,
        lecciones_aprendidas
    )
VALUES (
        1,
        1,
        '2023-01-15',
        'alerta',
        'Vulnerabilidad SQL Injection encontrada en formulario de login',
        'crítico',
        'cerrado',
        2,
        'Comunicación inmediata al cliente y mitigación aplicando parche temporal',
        '2023-01-20',
        'Necesidad de implementar validación de entrada en todos los formularios'
    ),
    (
        3,
        3,
        '2023-02-20',
        'alerta',
        'Bucket S3 con información sensible expuesto públicamente',
        'alto',
        'cerrado',
        10,
        'Bloqueo inmediato del acceso público al bucket y corrección de permisos',
        '2023-02-21',
        'Implementar revisiones periódicas de configuración de buckets S3'
    ),
    (
        4,
        14,
        '2022-12-10',
        'incidente',
        'Compromiso de cuenta de administrador por phishing',
        'crítico',
        'cerrado',
        3,
        'Análisis forense, reset de credenciales y revisión de actividades',
        '2022-12-20',
        'Importancia de la autenticación de múltiples factores y concienciación sobre phishing'
    ),
    (
        5,
        5,
        '2023-03-10',
        'alerta',
        'Vulnerabilidad CSRF en proceso de pago',
        'alto',
        'cerrado',
        2,
        'Implementación de tokens anti-CSRF como medida de mitigación',
        '2023-03-15',
        'Necesidad de validar todas las operaciones críticas con tokens anti-CSRF'
    );
-- Base de conocimiento
INSERT INTO base_conocimiento (
        titulo,
        categoria,
        contenido,
        tags,
        creado_por,
        fecha_creacion
    )
VALUES (
        'Metodología de Pentesting Web',
        'procedimiento',
        'Este documento describe la metodología estándar para realizar pentesting de aplicaciones web siguiendo las mejores prácticas de OWASP.\n\n1. Reconocimiento\n2. Enumeración\n3. Análisis de vulnerabilidades\n4. Explotación\n5. Post-explotación\n6. Documentación\n\nCada fase debe ser documentada adecuadamente incluyendo evidencias...',
        'pentesting, web, metodología, OWASP',
        2,
        '2022-08-10'
    ),
    (
        'SQL Injection: Técnicas de detección y explotación',
        'vulnerabilidad',
        'Las inyecciones SQL son vulnerabilidades que permiten a un atacante interferir con las consultas que una aplicación realiza a su base de datos. Este documento detalla las técnicas para detectar y explotar estas vulnerabilidades de manera ética durante los pentests.\n\nSíntomas de vulnerabilidad:\n- Errores de base de datos en respuestas\n- Comportamiento anómalo al insertar caracteres especiales\n\nTécnicas de explotación:\n- Union-based\n- Error-based\n- Blind\n- Time-based\n\nContramedidas:\n- Consultas parametrizadas\n- ORM\n- Validación de entrada\n- Principio de mínimo privilegio',
        'sql injection, pentesting, explotación, bases de datos',
        2,
        '2022-09-15'
    ),
    (
        'Hardening de Servidores Linux',
        'procedimiento',
        'Esta guía proporciona los pasos detallados para asegurar servidores Linux siguiendo las recomendaciones de CIS Benchmarks.\n\n1. Configuración segura de usuarios y permisos\n2. Configuración de servicios\n3. Configuración de red\n4. Auditoría y logging\n5. Actualización y parcheado\n\nComandos específicos:\n```bash\n# Deshabilitar usuario root\npasswd -l root\n\n# Configurar política de contraseñas\nvi /etc/pam.d/common-password\n\n# Configurar firewall\nufw enable\nufw default deny incoming\nufw default allow outgoing\n```',
        'hardening, linux, seguridad, CIS',
        9,
        '2022-10-20'
    ),
    (
        'Seguridad en AWS: Mejores Prácticas',
        'procedimiento',
        'Documento que detalla las mejores prácticas de seguridad al utilizar servicios de Amazon Web Services.\n\n1. IAM - Gestión de identidades\n   - Usar roles en lugar de claves de acceso\n   - Implementar MFA\n   - Principio de mínimo privilegio\n\n2. Redes\n   - Diseño adecuado de VPC\n   - Security Groups y NACLs\n   - Uso de VPN o Direct Connect\n\n3. Almacenamiento\n   - Cifrado en S3\n   - Políticas de bucket\n   - Control de versiones\n\n4. Monitorización\n   - CloudTrail\n   - CloudWatch\n   - Config\n\n5. Seguridad en instancias\n   - Hardening de AMIs\n   - Systems Manager\n   - Inspector',
        'aws, cloud, seguridad, IAM, S3',
        10,
        '2022-11-05'
    ),
    (
        'Análisis Forense en Windows',
        'procedimiento',
        'Guía detallada para realizar análisis forense en sistemas Windows tras un incidente de seguridad.\n\n1. Preparación\n   - Herramientas necesarias\n   - Documentación inicial\n\n2. Adquisición de evidencias\n   - Memoria RAM\n   - Disco duro\n   - Registros de eventos\n\n3. Análisis\n   - Timeline\n   - Registros de eventos\n   - Artefactos del sistema\n   - Prefetch y USN Journal\n\n4. Detección de persistencia\n   - Registro de Windows\n   - Tareas programadas\n   - Servicios\n\n5. Análisis de malware\n   - Identificación de archivos sospechosos\n   - Análisis estático y dinámico\n\n6. Documentación de hallazgos',
        'forense, windows, incidentes, investigación',
        3,
        '2022-12-10'
    ),
    (
        'GDPR: Requisitos técnicos y organizativos',
        'normativa',
        'Documento que detalla los requisitos técnicos y organizativos necesarios para cumplir con el Reglamento General de Protección de Datos (GDPR).\n\n1. Principios fundamentales\n   - Licitud, lealtad y transparencia\n   - Limitación de finalidad\n   - Minimización de datos\n   - Exactitud\n   - Limitación de conservación\n   - Integridad y confidencialidad\n   - Responsabilidad proactiva\n\n2. Medidas técnicas\n   - Seudonimización y cifrado\n   - Controles de acceso\n   - Registro de actividades\n   - Evaluaciones de impacto\n\n3. Medidas organizativas\n   - Políticas y procedimientos\n   - Formación y concienciación\n   - Gestión de brechas\n   - Auditorías internas\n\n4. Derechos de los interesados\n   - Procedimientos para ejercer derechos\n   - Plazos de respuesta\n   - Documentación',
        'GDPR, protección de datos, normativa, privacidad',
        8,
        '2023-01-15'
    ),
    (
        'Seguridad en DevOps: Integración continua segura',
        'procedimiento',
        'Guía para implementar seguridad en el ciclo de desarrollo DevOps, asegurando que la seguridad sea parte integral del proceso.\n\n1. Seguridad en el código\n   - Análisis estático de código (SAST)\n   - Gestión segura de dependencias\n   - Revisión de código\n\n2. Seguridad en la construcción\n   - Integración de escaneos en CI/CD\n   - Firmas digitales\n   - Entornos seguros\n\n3. Seguridad en contenedores\n   - Escaneo de imágenes\n   - Mínimo privilegio\n   - Configuración segura\n\n4. Seguridad en despliegue\n   - Verificación de integridad\n   - Infraestructura como código segura\n   - Gestión de secretos\n\n5. Seguridad en operación\n   - Monitorización continua\n   - Gestión de vulnerabilidades\n   - Respuesta a incidentes\n\nEjemplos de herramientas:\n- SonarQube\n- OWASP Dependency-Check\n- Trivy\n- HashiCorp Vault\n- Prometheus + Grafana',
        'devops, CI/CD, seguridad, desarrollo, contenedores',
        6,
        '2023-02-20'
    ),
    (
        'Técnicas de Red Team: Evasión de defensas',
        'procedimiento',
        'Documento técnico sobre técnicas avanzadas de evasión utilizadas en ejercicios de Red Team para evaluar defensas.\n\n1. Evasión de antivirus\n   - Ofuscación de código\n   - Técnicas in-memory\n   - Carga útil polimórfica\n\n2. Evasión de EDR\n   - Hooking y unhooking de APIs\n   - DLL side-loading\n   - AMSI bypass\n\n3. Movimiento lateral\n   - Técnicas de pass-the-hash/ticket\n   - WMI y PowerShell remoto\n   - Protocolo alternativo\n\n4. Persistencia sigilosa\n   - WMI event subscription\n   - Scheduled tasks ofuscadas\n   - Registry modifications\n\n5. Comunicación C2\n   - Domain fronting\n   - DNS tunneling\n   - Protocolos legítimos\n\nCada técnica debe ser utilizada únicamente en contextos éticos y legales con autorización explícita del cliente.',
        'red team, evasión, post-explotación, C2, EDR',
        7,
        '2023-03-05'
    ),
    (
        'Seguridad en aplicaciones móviles iOS/Android',
        'procedimiento',
        'Guía completa para evaluar la seguridad en aplicaciones móviles tanto en iOS como Android.\n\n1. Análisis estático\n   - Reverse engineering\n   - Análisis de código\n   - Firmas y permisos\n\n2. Análisis dinámico\n   - Interceptación de tráfico\n   - Instrumentación\n   - Análisis de runtime\n\n3. Vulnerabilidades comunes\n   - Almacenamiento inseguro\n   - Comunicación insegura\n   - Autenticación débil\n   - Lógica de negocio\n   - Protecciones anti-reversing\n\n4. Vectores de ataque específicos\n   - iOS: Jailbreak detection bypass, Keychain\n   - Android: Root detection bypass, Intents\n\n5. Herramientas recomendadas\n   - MobSF\n   - Frida\n   - Objection\n   - Burp Suite + plugins móviles',
        'móvil, Android, iOS, aplicaciones, pentesting',
        4,
        '2023-03-20'
    ),
    (
        'Zero Trust: Implementación práctica',
        'procedimiento',
        'Guía para implementar un modelo de seguridad Zero Trust en organizaciones, abandonando el perímetro tradicional.\n\n1. Principios fundamentales\n   - Verificar siempre, nunca confiar\n   - Principio de mínimo privilegio\n   - Asumir compromiso\n\n2. Componentes clave\n   - Identidad y acceso\n   - Dispositivos\n   - Redes\n   - Aplicaciones\n   - Datos\n\n3. Pasos para implementación\n   - Identificar datos sensibles y flujos\n   - Mapear dependencias\n   - Arquitectura de micro-perímetros\n   - Políticas de acceso\n   - Monitorización continua\n\n4. Tecnologías habilitadoras\n   - MFA y SSO\n   - ZTNA/SDP\n   - CASB y DLP\n   - SASE\n   - EDR/XDR\n\n5. Casos de uso y arquitecturas de referencia\n   - Acceso remoto\n   - Entornos multi-cloud\n   - Shadow IT\n   - BYOD',
        'zero trust, arquitectura, seguridad, acceso',
        1,
        '2023-04-10'
    );