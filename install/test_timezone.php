<?php
/**
 * Prueba de conversión de timezones
 */

require_once __DIR__ . '/../includes/timezone_helper.php';
require_once __DIR__ . '/../models/Configuracion.php';

echo "=== PRUEBA DE CONVERSIÓN DE TIMEZONES ===\n\n";

// 1. Verificar timezone configurado
echo "1. TIMEZONE CONFIGURADO\n";
echo "   Timezone: " . getTimezoneConfig() . "\n";
setTimezoneConfig();
echo "   PHP timezone activo: " . date_default_timezone_get() . "\n\n";

// 2. Probar conversión UTC a Local
echo "2. CONVERSIÓN UTC → LOCAL\n";
$fechaUTC = '2026-05-08 03:00:00';
$fechaLocal = convertirUTCaLocal($fechaUTC);
echo "   UTC:   $fechaUTC\n";
echo "   Local: $fechaLocal\n";
echo "   Diferencia esperada: +2 horas (Madrid en horario de verano)\n\n";

// 3. Probar conversión Local a UTC
echo "3. CONVERSIÓN LOCAL → UTC\n";
$fechaLocal2 = '2026-05-08 05:00:00';
$fechaUTC2 = convertirLocalAUTC($fechaLocal2);
echo "   Local: $fechaLocal2\n";
echo "   UTC:   $fechaUTC2\n";
echo "   Diferencia esperada: -2 horas\n\n";

// 4. Fechas actuales
echo "4. FECHAS ACTUALES\n";
echo "   UTC actual:   " . getFechaActualUTC() . "\n";
echo "   Local actual: " . getFechaActualLocal() . "\n\n";

// 5. Formatos diferentes
echo "5. FORMATOS DE FECHA\n";
$fechaUTC3 = '2026-05-08 15:30:45';
echo "   Original (UTC): $fechaUTC3\n";
echo "   Default:        " . formatearFecha($fechaUTC3, 'default') . "\n";
echo "   Short:          " . formatearFecha($fechaUTC3, 'short') . "\n";
echo "   Long:           " . formatearFecha($fechaUTC3, 'long') . "\n";
echo "   Date only:      " . formatearFecha($fechaUTC3, 'date') . "\n";
echo "   Time only:      " . formatearFecha($fechaUTC3, 'time') . "\n";
echo "   DateTime:       " . formatearFecha($fechaUTC3, 'datetime') . "\n\n";

// 6. Probar con diferentes timezones
echo "6. DIFERENTES TIMEZONES\n";
$config = new Configuracion();

// Guardar timezone original
$tzOriginal = $config->getTimezone();

// Probar con Nueva York
$config->set('timezone', 'America/New_York', 'Test');
setTimezoneConfig();
echo "   Nueva York: " . convertirUTCaLocal($fechaUTC) . " (desde UTC: $fechaUTC)\n";

// Probar con Tokio
$config->set('timezone', 'Asia/Tokyo', 'Test');
setTimezoneConfig();
echo "   Tokio:      " . convertirUTCaLocal($fechaUTC) . " (desde UTC: $fechaUTC)\n";

// Restaurar timezone original
$config->set('timezone', $tzOriginal, 'Zona horaria para mostrar fechas en el panel');
setTimezoneConfig();
echo "   Madrid:     " . convertirUTCaLocal($fechaUTC) . " (desde UTC: $fechaUTC)\n\n";

echo "✅ Pruebas completadas\n";
