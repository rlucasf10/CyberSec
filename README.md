# Base de Datos — Empresa de Ciberseguridad y Pentesting

Este proyecto contiene la base de datos y archivos relacionados con la gestión de una empresa de consultoría en ciberseguridad y pentesting.

## 📦 Estructura del Proyecto

```
/cybersec-app
│
├── /public                  # Archivos accesibles públicamente
│   ├── index.php            # Página principal
│   ├── login.php            # Página de inicio de sesión
│   ├── register.php         # Página de registro
│   ├── /css                 # Estilos CSS
│   │   ├── style.css        # Estilos generales
│   │   ├── login.css        # Estilos del login
│   │   └── register.css     # Estilos del registro
│
├── /includes
│   ├── header.php           # Cabecera común
│   └── footer.php           # Pie de página común
│
├── /config
│   └── database.php         # Configuración y conexión a la base de datos
│
├── /sql
│   ├── estructura.sql       # Script con la estructura de la base de datos
│   ├── procedimientos.sql   # Procedimientos almacenados
│   └── datos_prueba.sql     # (Opcional) Datos de ejemplo
│
├── .env                     # Variables de entorno (DB, claves, etc.)
├── README.md                # Documentación del proyecto
└── .htaccess                # Reescritura de URLs (si se usa en Apache)
```

## 🧠 Estructura de la Base de Datos

La base de datos `cybersec_db` incluye las siguientes tablas principales:

- **usuarios**: Manejo de accesos (cliente, empleado o admin).
- **clientes**: Información corporativa de los clientes.
- **empleados**: Datos del personal técnico.
- **proyectos**: Información de proyectos activos e históricos.
- **servicios**: Servicios de ciberseguridad ofrecidos.
- **proyecto_servicio**: Relación entre proyectos y servicios contratados.
- **vulnerabilidades**: Registro de vulnerabilidades encontradas.

## ⚙️ Instalación

1. Asegúrate de tener MySQL instalado (versión 5.7+ o MariaDB).
2. Crea la base de datos e importa la estructura:

```bash
mysql -u tu_usuario -p < sql/estructura.sql
```

3. Importa los procedimientos almacenados:

```bash
mysql -u tu_usuario -p < sql/procedimientos.sql
```

4. (Opcional) Carga datos de ejemplo para pruebas:

```bash
mysql -u tu_usuario -p < sql/datos_prueba.sql
```

## 🔁 Relaciones Clave

- Un **usuario** puede ser un cliente, empleado o administrador.
- Un **cliente** puede tener varios proyectos.
- Un **proyecto** puede tener múltiples servicios y vulnerabilidades.
- Un **empleado** puede estar asignado a múltiples proyectos.

## 🔐 Buenas Prácticas de Seguridad

- Acceso limitado a la base de datos por roles.
- Cifrado de contraseñas con hash y salt.
- Campos `token_recuperacion` y `fecha_token` para recuperación segura.
- Auditoría: uso de campos `creado`, `actualizado`, `bloqueado`.

## 🛠️ Mantenimiento Recomendado

- Realizar backups automáticos y regulares.
- Monitorizar rendimiento y optimizar tablas:

```sql
OPTIMIZE TABLE nombre_tabla;
```

- Analizar el estado de las tablas:

```sql
ANALYZE TABLE nombre_tabla;
```

## 📌 Archivos Relevantes

- `estructura.sql`: Define la estructura de la base de datos.
- `procedimientos.sql`: Procedimientos almacenados útiles (crear usuario, login, gestión de proyectos, etc.).
- `datos_prueba.sql`: Datos iniciales de prueba (si aplica).

## 🚀 Futuras Extensiones

- Panel de control para usuarios y administración.
- Sistema de notificaciones de seguridad.
- Dashboard de KPIs en ciberseguridad.
- Soporte para integraciones con herramientas como Nessus, Burp Suite, etc.

© Ricardo Lucas Fernández — Proyecto CyberSecApp