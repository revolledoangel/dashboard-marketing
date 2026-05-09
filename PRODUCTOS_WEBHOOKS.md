# Sistema de Productos y Webhooks

## ¿Qué es un Producto?

Un **producto** representa el final del embudo de conversión: **la compra**. Cuando creas un producto en el dashboard, se genera automáticamente un **webhook URL único** que debes configurar en Hotmart para recibir notificaciones de compras aprobadas.

## Características

- ✅ Webhook URL único por producto
- ✅ Integración con Hotmart
- ✅ Logging automático de todas las notificaciones
- ✅ Visualización en el funnel como card especial
- ✅ Endpoint de prueba para debugging

## Cómo Crear un Producto

### 1. Acceder a Métricas

1. Inicia sesión en el dashboard
2. Ve a **Métricas** en el menú lateral
3. Selecciona un embudo del dropdown

### 2. Crear Producto

1. Haz clic en el botón **"+Producto"** (junto al botón "Ver Código GTM")
2. Se abrirá un modal
3. Ingresa el **nombre del producto** (ej: "Curso de Marketing Digital")
4. Haz clic en **"Crear Producto"**

### 3. Copiar Webhook URL

1. El producto aparecerá como un **card verde** al final del funnel
2. El card muestra la URL del webhook
3. Haz clic en el botón de **copiar** (icono de clipboard)
4. La URL se copiará automáticamente al portapapeles

## Configurar en Hotmart

### Paso 1: Acceder a Configuración de Producto

1. Inicia sesión en tu cuenta de Hotmart
2. Ve al producto que deseas configurar
3. Accede a **Configuración > Webhooks**

### Paso 2: Agregar Webhook

1. Haz clic en **"Adicionar novo Postback"** o **"Webhook"**
2. Pega la URL copiada del dashboard
3. Selecciona el evento: **"PURCHASE_APPROVED"** (compra aprobada)
4. Guarda los cambios

### Paso 3: Probar el Webhook

Hotmart te permite enviar una notificación de prueba:

1. En la configuración del webhook, busca **"Enviar teste"** o **"Test"**
2. Haz clic para enviar una notificación de prueba
3. Verifica que llegue correctamente

## Endpoint de Test/Debug

Para ver exactamente qué datos envía Hotmart, puedes usar el endpoint de prueba:

### URL del Endpoint de Test

```
https://tu-dominio.com/webhook/test_hotmart.php
```

### Visualizar Logs

Accede a la siguiente URL en tu navegador:

```
https://tu-dominio.com/webhook/view_log.php
```

Esta página te mostrará:
- 📊 Todas las peticiones recibidas
- 📋 Headers completos
- 📦 Datos en formato JSON
- ⏰ Timestamp de cada petición

### Usar el Endpoint de Test

1. Copia la URL del endpoint de test
2. Configúrala temporalmente en Hotmart
3. Envía una notificación de prueba desde Hotmart
4. Ve a `view_log.php` para ver los datos recibidos
5. Una vez que veas cómo llegan los datos, cambia a la URL real del producto

## URLs Importantes

### Webhook de Producto (URL Real)
```
https://tu-dominio.com/api.php?action=producto&sub=webhook&token=XXXXXXXX
```

Donde `XXXXXXXX` es el token único de 64 caracteres generado automáticamente.

### Endpoint de Test
```
https://tu-dominio.com/webhook/test_hotmart.php
```

### Visualizador de Logs
```
https://tu-dominio.com/webhook/view_log.php
```

## Logs del Sistema

Cada producto tiene su propio archivo de log donde se guardan todas las notificaciones recibidas:

```
data/webhook_producto_PRODUCTO_ID.log
```

El log incluye:
- ✅ Fecha y hora de la notificación
- ✅ Datos completos en formato JSON
- ✅ Headers HTTP
- ✅ Información del producto

## Estructura de Datos de Hotmart

Hotmart envía los datos en formato JSON con la siguiente estructura (ejemplo):

```json
{
  "event": "PURCHASE_APPROVED",
  "product": {
    "id": "123456",
    "name": "Curso de Marketing"
  },
  "buyer": {
    "email": "cliente@ejemplo.com",
    "name": "Juan Pérez"
  },
  "purchase": {
    "transaction": "HP12345678",
    "status": "approved",
    "price": {
      "value": 97.00,
      "currency": "BRL"
    }
  }
}
```

## Ejemplo de Uso Completo

### 1. Crear el Producto

```
Nombre: "Curso Avanzado de SEO"
```

Resultado: Se genera automáticamente el webhook URL.

### 2. Webhook URL Generado

```
https://dashboard.midominio.com/api.php?action=producto&sub=webhook&token=a1b2c3d4e5f6...
```

### 3. Configurar en Hotmart

- Producto: "Curso Avanzado de SEO"
- Evento: PURCHASE_APPROVED
- URL Postback: (pegar la URL generada)

### 4. Cuando alguien compra

1. Hotmart detecta la compra aprobada
2. Envía una notificación POST al webhook
3. El sistema recibe y loggea la información
4. (Próximamente: se guardará en tabla de ventas)

## Próximas Funcionalidades

🔜 **Tabla de Ventas**: Se creará una tabla para almacenar todas las ventas
🔜 **Dashboard de Ventas**: Visualizar ventas, ingresos y métricas
🔜 **Conversión End-to-End**: Ver el funnel completo desde visita hasta compra
🔜 **Reportes de ROI**: Calcular retorno de inversión por fuente de tráfico

## Troubleshooting

### El webhook no recibe notificaciones

1. **Verifica la URL**: Asegúrate de que la URL esté correctamente copiada
2. **Prueba con el endpoint de test**: Usa `test_hotmart.php` primero
3. **Revisa los logs**: Accede a `view_log.php` para ver si llegan peticiones
4. **Verifica HTTPS**: Hotmart puede requerir HTTPS en producción

### Los datos no se guardan

1. **Verifica permisos**: La carpeta `data/` debe tener permisos de escritura
2. **Revisa el log del producto**: Busca el archivo `webhook_producto_*.log`
3. **Comprueba errores de PHP**: Revisa los logs de errores del servidor

### No veo el log en view_log.php

1. **Envía una notificación de prueba** desde Hotmart
2. **Verifica la ruta del archivo**: `data/webhook_test.log`
3. **Comprueba permisos**: El archivo debe poder crearse y escribirse

## Soporte

Si tienes problemas:

1. Revisa los logs del sistema
2. Usa el endpoint de test para debugging
3. Verifica la configuración en Hotmart
4. Comprueba que la URL del webhook sea accesible públicamente

---

**Documentación actualizada**: 2026-05-09
**Versión**: 1.0.0
