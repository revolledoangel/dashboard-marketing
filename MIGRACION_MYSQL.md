# Migración a MySQL - Completada ✓

## 📊 Resumen

El sistema de eventos se migró exitosamente de JSON a MySQL para soportar mayor escala (10k+ eventos/día).

## ✅ Lo que se hizo

1. **Base de datos creada**: `dashboard_marketing`
2. **Tabla `eventos` creada** con:
   - Índices optimizados para consultas rápidas
   - Soporte para millones de registros
   - Transacciones seguras (sin race conditions)
3. **Modelo `Evento.php` migrado** a PDO/MySQL
4. **Eventos existentes migrados** del JSON (2 eventos)
5. **Backup automático** del JSON original

## 📁 Archivos creados/modificados

### Nuevos archivos:
- `config/database.php` - Configuración de conexión
- `config/schema_eventos.sql` - Schema de la tabla
- `install/install_db.php` - Script de instalación completo
- `install/migrate_eventos.php` - Migración de JSON a MySQL
- `install/test_connection.php` - Test de conexión
- `install/test_modelo_evento.php` - Tests del modelo

### Modificados:
- `models/Evento.php` - Ahora usa MySQL en lugar de JSON

### Backups:
- `data/eventos_backup_2026-05-09_03-37-53.json` - Backup del JSON original

## 🚀 Configuración en Producción

### 1. Requisitos
- PHP 7.4 o superior
- MySQL 5.7 o superior (o MariaDB 10.2+)

### 2. Configurar credenciales de base de datos

Edita `config/database.php` y cambia las credenciales:

```php
define('DB_HOST', 'localhost');           // Tu servidor MySQL
define('DB_NAME', 'dashboard_marketing'); // Nombre de tu base de datos
define('DB_USER', 'tu_usuario');          // Usuario MySQL
define('DB_PASS', 'tu_contraseña');       // Contraseña MySQL
```

### 3. Crear la base de datos

**Opción A: Ejecutar script de instalación**
```bash
php install/install_db.php
```

**Opción B: Desde phpMyAdmin o MySQL CLI**
1. Crear base de datos:
   ```sql
   CREATE DATABASE dashboard_marketing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. Importar schema:
   ```bash
   mysql -u tu_usuario -p dashboard_marketing < config/schema_eventos.sql
   ```

### 4. Verificar instalación

Ejecuta el test:
```bash
php install/test_modelo_evento.php
```

Deberías ver: `✓✓✓ TODOS LOS TESTS PASARON ✓✓✓`

## 📈 Performance

### Antes (JSON):
- ❌ Lee/escribe todo el archivo en cada operación
- ❌ Sin índices - búsquedas lentas
- ❌ Race conditions con múltiples requests
- ❌ Límite práctico: ~1,000 eventos

### Ahora (MySQL):
- ✅ Operaciones optimizadas con índices
- ✅ Consultas rápidas incluso con millones de registros
- ✅ Transacciones seguras
- ✅ Soporta 10,000+ eventos/día sin problemas

## 🔧 Mantenimiento

### Ver estadísticas de la tabla
```sql
SELECT 
    COUNT(*) as total_eventos,
    COUNT(DISTINCT embudo_id) as embudos_con_eventos,
    MIN(timestamp) as primer_evento,
    MAX(timestamp) as ultimo_evento
FROM eventos;
```

### Limpiar eventos antiguos (opcional)
```sql
-- Eliminar eventos de más de 90 días
DELETE FROM eventos WHERE timestamp < DATE_SUB(NOW(), INTERVAL 90 DAY);
```

### Optimizar tabla periódicamente
```sql
OPTIMIZE TABLE eventos;
```

## ⚠️ Importante para Producción

1. **Backups**: Configura backups automáticos de MySQL
2. **Monitoreo**: Revisa el tamaño de la tabla periódicamente
3. **Índices**: Los índices ya están optimizados, no los elimines
4. **Seguridad**: Usa contraseñas fuertes en producción

## 🆘 Troubleshooting

### Error: "Connection refused"
- Verifica que MySQL esté corriendo: `systemctl status mysql` (Linux) o revisa XAMPP Control Panel (Windows)

### Error: "Access denied"
- Verifica credenciales en `config/database.php`
- Asegúrate de que el usuario tenga permisos en la base de datos

### Error: "Table doesn't exist"
- Ejecuta: `php install/test_connection.php` para crear la tabla

## 📊 Monitoreo recomendado

```sql
-- Eventos por día (últimos 7 días)
SELECT 
    DATE(timestamp) as fecha,
    COUNT(*) as eventos
FROM eventos
WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 7 DAY)
GROUP BY DATE(timestamp)
ORDER BY fecha DESC;

-- Top 10 campañas UTM
SELECT 
    utm_campaign,
    COUNT(*) as eventos
FROM eventos
WHERE utm_campaign IS NOT NULL
GROUP BY utm_campaign
ORDER BY eventos DESC
LIMIT 10;
```

---

## ✅ Sistema listo para producción

El sistema ahora puede manejar miles de eventos diarios sin problemas de performance.
