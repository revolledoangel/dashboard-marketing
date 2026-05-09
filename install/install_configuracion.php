<?php
/**
 * Instalar tabla de configuración
 */

require_once __DIR__ . '/../config/database.php';

echo "=== INSTALACIÓN DE TABLA configuracion ===\n\n";

try {
    $db = getDB();
    echo "✅ Conectado a la base de datos\n";
    
    $sql = file_get_contents(__DIR__ . '/../config/schema_configuracion.sql');
    $db->exec($sql);
    
    echo "✅ Tabla 'configuracion' creada exitosamente\n";
    echo "✅ Timezone configurado: Europe/Madrid\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
