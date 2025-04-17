# Instrucciones de Instalación - Base de Datos de Ciberseguridad

Este documento explica cómo instalar y configurar correctamente la base de datos para la empresa de consultoría en ciberseguridad y pentesting.

## Requisitos Previos

* MySQL Server 5.7 o superior (recomendado 8.0+)
* Cliente MySQL (línea de comandos o herramienta gráfica como MySQL Workbench)
* Permisos de administrador en la base de datos MySQL

## Proceso de Instalación

### 1. Crear la Base de Datos y Estructura

Primero debemos crear la estructura de la base de datos con todas las tablas, relaciones e índices:

```bash
# Conectar al servidor MySQL
mysql -u [usuario] -p

# Alternativamente, puedes ejecutar el script directamente sin entrar al cliente MySQL
mysql -u [usuario] -p < cybersec_db.sql
```

Si estás usando el cliente MySQL interactivo:

```sql
SOURCE cybersec_db.sql;
```

### 2. Cargar los Datos Iniciales

Una vez creada la estructura, podemos cargar los datos predefinidos:

```bash
# Desde línea de comandos
mysql -u [usuario] -p cybersec_db < cargar_datos_iniciales.sql

# O desde el cliente MySQL interactivo
SOURCE cargar_datos_iniciales.sql;
```

El script `cargar_datos_iniciales.sql` cargará automáticamente todos los datos en el siguiente orden:

1. Empleados y servicios
2. Clientes y proyectos
3. Finanzas y formación
4. Procedimientos almacenados

### 3. Verificar la Instalación

Para verificar que la instalación se ha completado correctamente, puedes ejecutar algunas consultas de prueba:

```sql
-- Verificar número de empleados
SELECT COUNT(*) FROM empleados;

-- Verificar número de clientes
SELECT COUNT(*) FROM clientes;

-- Verificar número de proyectos
SELECT COUNT(*) FROM proyectos;

-- Probar un procedimiento almacenado
CALL obtener_resumen_proyecto(1);
```

## Configuración de Conexión para PHP

Para configurar la conexión desde PHP, debes editar el archivo `db_connection.php` y modificar los siguientes parámetros:

```php
$db_config = [
    'host' => 'localhost',      // Cambia a la dirección del servidor MySQL
    'dbname' => 'cybersec_db',  // Nombre de la base de datos
    'user' => 'root',           // Cambia por un usuario con privilegios limitados
    'password' => '',           // Cambia por una contraseña segura
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
```

## Mantenimiento

Se recomienda realizar las siguientes tareas de mantenimiento:

1. **Backups diarios** de la base de datos:
   ```bash
   mysqldump -u [usuario] -p --opt cybersec_db > backup_cybersec_$(date +%Y%m%d).sql
   ```

2. **Optimización periódica** de tablas:
   ```sql
   OPTIMIZE TABLE clientes, proyectos, vulnerabilidades, facturas;
   ```

3. **Análisis de rendimiento** de consultas:
   ```sql
   EXPLAIN SELECT * FROM vulnerabilidades WHERE proyecto_id = 1;
   ```

## Seguridad

Para mejorar la seguridad de la base de datos:

1. **Crear un usuario específico** con permisos limitados para la aplicación:
   ```sql
   CREATE USER 'cybersec_app'@'localhost' IDENTIFIED BY 'password_segura';
   GRANT SELECT, INSERT, UPDATE, DELETE ON cybersec_db.* TO 'cybersec_app'@'localhost';
   FLUSH PRIVILEGES;
   ```

2. **Encriptar datos sensibles**:
   - Usar funciones de encriptación para almacenar datos confidenciales
   - Considerar el uso de AES_ENCRYPT() y AES_DECRYPT() para información sensible

3. **Habilitar logs de auditoría** para monitorizar el acceso y cambios

## Solución de Problemas

Si encuentras algún problema durante la instalación:

1. **Error de acceso**: Verifica las credenciales y permisos del usuario MySQL
2. **Error en scripts SQL**: Revisa la sintaxis y asegúrate de que la versión de MySQL es compatible
3. **Problemas de rendimiento**: Analiza las consultas con EXPLAIN y revisa la configuración de índices

## Contacto y Soporte

Para cualquier problema o consulta sobre la base de datos, contacta al administrador de sistemas.

---

Esperamos que la base de datos sea de gran utilidad para la gestión de tu empresa de ciberseguridad. La estructura está diseñada para ser robusta, escalable y adaptable a las necesidades cambiantes del sector. 