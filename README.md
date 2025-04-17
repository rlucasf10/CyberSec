# Base de Datos para Empresa de Ciberseguridad y Pentesting

Este directorio contiene los archivos relacionados con la base de datos de la empresa de consultoría en ciberseguridad y pentesting.

## Estructura de la Base de Datos

La base de datos `cybersec_db` está diseñada para gestionar todos los aspectos operativos de una empresa de ciberseguridad:

### Entidades Principales:

1. **Clientes**: Información completa de los clientes corporativos e individuales.
2. **Empleados/Consultores**: Datos de los profesionales que trabajan en la empresa.
3. **Proyectos**: Gestión detallada de los proyectos de ciberseguridad.
4. **Servicios**: Catálogo de servicios ofrecidos por la empresa.
5. **Vulnerabilidades**: Registro de vulnerabilidades descubiertas en los proyectos.
6. **Activos**: Inventario de los activos tecnológicos evaluados.
7. **Informes**: Documentación generada para los clientes.
8. **Herramientas**: Registro de las herramientas utilizadas en los proyectos.
9. **Finanzas**: Gestión de facturas y pagos.
10. **Formaciones**: Cursos y capacitaciones ofrecidas a clientes.
11. **Base de Conocimiento**: Repositorio de información técnica.

## Características Principales

- **Integridad referencial**: Todas las relaciones están protegidas con claves foráneas.
- **Campos de auditoría**: Las tablas incluyen campos para seguimiento de cambios.
- **Enumeraciones**: Uso de tipos ENUM para garantizar consistencia de datos.
- **Índices optimizados**: Índices en campos clave para mejorar el rendimiento.

## Instrucciones de Instalación

Para instalar la base de datos:

1. Asegúrate de tener MySQL instalado (versión 5.7 o superior recomendada).
2. Ejecuta el script `cybersec_db.sql`:

```bash
mysql -u [usuario] -p < cybersec_db.sql
```

## Modelo de Relaciones

Las principales relaciones en la base de datos son:

- Un cliente puede tener múltiples proyectos.
- Un proyecto puede tener múltiples servicios, empleados, vulnerabilidades e informes.
- Los empleados pueden estar asignados a múltiples proyectos.
- Las vulnerabilidades están asociadas a un proyecto específico.
- Los activos están vinculados a proyectos concretos.

## Consideraciones de Seguridad

- Esta base de datos debe estar protegida con medidas de seguridad robustas:
  - Acceso limitado y auditado
  - Cifrado de datos sensibles
  - Copias de seguridad regulares
  - Monitorización de actividad

## Mantenimiento

Se recomienda realizar backups diarios de esta base de datos y revisar regularmente su rendimiento mediante:

```sql
OPTIMIZE TABLE [nombre_tabla];
ANALYZE TABLE [nombre_tabla];
```

## Extensiones Futuras

La estructura está diseñada para ser extensible. Posibles adiciones futuras:
- Integraciones con herramientas de escaneo automatizado
- Módulo de inteligencia de amenazas
- Sistema de gestión de cumplimiento normativo
- Dashboard de KPIs y métricas 