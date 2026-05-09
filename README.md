# Dashboard Marketing

Sistema de gestión de embudos de conversión con tracking GTM integrado y arquitectura MVC en PHP.

## � Características

- ✅ Gestión de clientes y usuarios con autenticación
- ✅ Creación y edición de embudos de conversión  
- ✅ Generación automática de código GTM para tracking
- ✅ Métricas en tiempo real (visitas, eventos, conversiones)
- ✅ Seguimiento de UTM parameters (source, medium, campaign, term, content)
- ✅ Base de datos MySQL optimizada para alto tráfico (10k+ eventos/día)
- ✅ Interfaz moderna con AdminLTE 3.2 y Bootstrap 5

## 📋 Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior (o MariaDB 10.2+)
- Apache/Nginx
- Extensiones PHP: PDO, pdo_mysql, json, mbstring

## 🔧 Instalación

### 1. Clonar repositorio

```bash
git clone [URL_DE_TU_REPO]
cd dashboard-marketing
```

### 2. Configurar archivos de configuración

Copia los archivos de ejemplo:

```bash
cp config.example.php config.php
cp config/database.example.php config/database.php
```

Edita `config/database.php` con tus credenciales:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'dashboard_marketing');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
```

### 3. Crear base de datos

**Opción A: Usando phpMyAdmin**
1. Crea una base de datos llamada `dashboard_marketing`
2. Importa el schema: `config/schema_eventos.sql`

**Opción B: Usando terminal**
```bash
mysql -u root -p -e "CREATE DATABASE dashboard_marketing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p dashboard_marketing < config/schema_eventos.sql
```

**Opción C: Script automático**
```bash
php install/test_connection.php
```

### 4. Verificar instalación

Accede a: `http://tudominio.com/` 

**Login por defecto:**
- Usuario: `admin@dashboard.com`
- Contraseña: `admin123`

⚠️ **Importante**: Cambia la contraseña en producción

## 📊 Uso

### Crear un Embudo

1. Ve a **Embudos** → **Crear Embudo**
2. Asigna un cliente y dale un nombre
3. Click en **"Ver Código GTM"** para obtener el snippet de tracking

### Implementar Tracking

1. Copia el código GTM generado
2. En Google Tag Manager, crea un nuevo Tag tipo "HTML Personalizado"
3. Pega el código
4. Configura el trigger según necesites
5. Personaliza los nombres de eventos:
   - **Visitas**: `home`, `producto`, `checkout`, `gracias`
   - **Eventos**: `click_boton`, `submit_form`, `add_cart`, `compra`

Ejemplo:
```javascript
fetch('https://tudominio.com/track.php', {
  method: 'POST',
  headers: {'Content-Type': 'application/json'},
  body: JSON.stringify({
    token: '5f1949d239c3314bb9e7a5d898b7870a',
    tipo: 'visita',
    nombre: 'checkout',
    url: window.location.href
  })
})
.then(function(response) { return response.json(); })
.then(function(data) { console.log('✅ Visita registrada:', data); })
.catch(function(error) { console.error('❌ Error:', error); });
```

### Ver Métricas

1. Ve a **Métricas**
2. Selecciona un embudo
3. Visualiza:
   - Total de eventos capturados
   - Visitas vs Acciones/Eventos
   - Tasa de conversión
   - Tabla de eventos con UTM campaigns

## 📁 Estructura del Proyecto

```
dashboard-marketing/
├── assets/              # CSS, JS, imágenes
│   ├── css/
│   ├── js/
│   │   └── main.js     # JavaScript principal (1100+ líneas)
│   └── img/
├── config/              # Configuración
│   ├── database.php     # Credenciales DB (NO en Git)
│   ├── database.example.php
│   └── schema_eventos.sql
├── controllers/         # Controladores API
│   ├── ClienteController.php
│   ├── UsuarioController.php
│   ├── EmbudoController.php
│   └── EventoController.php
├── data/               # Datos JSON (NO en Git)
│   ├── clientes.json
│   ├── usuarios.json
│   └── embudos.json
├── install/            # Scripts de instalación y tests
│   ├── test_connection.php
│   ├── migrate_eventos.php
│   └── test_modelo_evento.php
├── models/             # Modelos de datos
│   ├── Cliente.php
│   ├── Usuario.php
│   ├── Embudo.php
│   └── Evento.php      # Modelo con MySQL/PDO
├── views/              # Vistas HTML
│   ├── includes/
│   │   ├── header.php
│   │   ├── sidebar.php
│   │   └── footer.php
│   ├── dashboard.php
│   ├── embudos.php
│   ├── metricas.php
│   └── usuarios.php
├── config.php          # Config general (NO en Git)
├── config.example.php
├── index.php           # Punto de entrada
├── login.php           # Página de login
├── api.php             # API REST
└── track.php           # Endpoint de tracking público (CORS enabled)
```

## 🔐 Seguridad

### Producción

1. **Cambiar credenciales por defecto** del usuario admin
2. **Desactivar errores de PHP** en `config.php`:
   ```php
   // error_reporting(E_ALL);
   // ini_set('display_errors', 1);
   ```
3. **Usar HTTPS** siempre
4. **Backups regulares** de la base de datos
5. **Actualizar contraseñas** periódicamente
6. **Variables de entorno** para credenciales sensibles

### Permisos de archivos

```bash
# Carpetas
chmod 755 assets/ controllers/ models/ views/ config/

# Archivos PHP
chmod 644 *.php

# Carpeta de datos (escritura para JSON backups)
chmod 775 data/
```

## 🛠️ API Endpoints

### Clientes
```
GET  api.php?action=cliente&sub=listar
GET  api.php?action=cliente&sub=obtener&id=X
POST api.php?action=cliente&sub=crear
POST api.php?action=cliente&sub=actualizar
POST api.php?action=cliente&sub=eliminar
```

### Usuarios  
```
POST api.php?action=usuario&sub=login
GET  api.php?action=usuario&sub=logout
GET  api.php?action=usuario&sub=listar
POST api.php?action=usuario&sub=crear
POST api.php?action=usuario&sub=actualizar
POST api.php?action=usuario&sub=eliminar
```

### Embudos
```
GET  api.php?action=embudo&sub=listar&cliente_id=X
POST api.php?action=embudo&sub=crear
POST api.php?action=embudo&sub=actualizar
POST api.php?action=embudo&sub=eliminar
```

### Eventos (Métricas)
```
GET  api.php?action=evento&sub=listar&embudo_id=X
GET  api.php?action=evento&sub=estadisticas&embudo_id=X
POST api.php?action=evento&sub=eliminar
```

### Tracking Público
```
POST track.php
Body: {
  "token": "token_del_embudo",
  "tipo": "visita" | "evento",
  "nombre": "nombre_del_evento",
  "url": "https://ejemplo.com/?utm_source=facebook"
}
```

## 📈 Performance

El sistema está optimizado para manejar:
- **10,000+ eventos/día** sin degradación
- **Millones de registros** en base de datos
- **Consultas indexadas** para búsquedas rápidas
- **Transacciones ACID** seguras (no más race conditions)

### Índices de la tabla eventos
- `embudo_id` - Para filtrar por embudo
- `tipo` - Para separar visitas de eventos
- `timestamp` - Para ordenar cronológicamente
- `embudo_id + tipo` - Para consultas combinadas
- `utm_campaign` - Para análisis de campañas
- `utm_source` - Para análisis de fuentes

## 🧪 Testing

```bash
# Test de conexión a MySQL
php install/test_connection.php

# Test del modelo Evento con MySQL
php install/test_modelo_evento.php

# Test de tracking endpoint
# Visita: http://localhost/gonzalo/test_track.html
```

## 🆘 Troubleshooting

### Error: "Connection refused"
- Verifica que MySQL esté corriendo
- Revisa credenciales en `config/database.php`

### Error: "Table doesn't exist"  
- Ejecuta: `php install/test_connection.php`
- O importa: `config/schema_eventos.sql` en phpMyAdmin

### Eventos no llegan
- Verifica que el token del embudo sea correcto
- Revisa CORS headers en `track.php`
- Consulta logs: `data/track_log.txt`
- Abre consola del navegador (F12) para ver errores

### Métricas no se actualizan
- Refresca la página (F5)
- Verifica que el embudo_id sea correcto
- Revisa logs del navegador (F12 → Console)

## 📝 Changelog

### v1.0.0 (2026-05-09)
- ✅ Sistema completo de tracking con GTM
- ✅ Migración de JSON a MySQL para eventos
- ✅ Métricas en tiempo real con estadísticas
- ✅ Gestión de embudos de conversión
- ✅ Captura de UTM parameters
- ✅ Interfaz optimizada

## 👨‍💻 Desarrollo

### Agregar nuevo modelo

1. Crea `models/NuevoModelo.php`
2. Sigue el patrón de `Evento.php` (con PDO)  
3. Usa **prepared statements** siempre

### Agregar nueva vista

1. Crea `views/nueva_vista.php`
2. Incluye header y footer:
   ```php
   <?php include 'includes/header.php'; ?>
   <!-- Tu contenido -->
   <?php include 'includes/footer.php'; ?>
   ```

### Convenciones de código

- **Camel Case** para funciones JavaScript
- **snake_case** para variables PHP
- **Indentación**: 4 espacios
- **Siempre** usar prepared statements en SQL
- **Validar** inputs tanto en frontend como backend

## 📄 Licencia

Proyecto privado - Todos los derechos reservados

## 📞 Soporte

Para problemas o sugerencias, contacta al equipo de desarrollo.

---

**Dashboard Marketing System** © 2026
