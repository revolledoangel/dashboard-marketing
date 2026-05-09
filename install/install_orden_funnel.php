<?php
/**
 * Instalar tabla orden_funnel
 */

require_once __DIR__ . '/../config/database.php';

echo "=== INSTALACIÓN DE TABLA orden_funnel ===\n\n";

try {
    $db = getDB();
    echo "✅ Conectado a la base de datos\n";
    
    $sql = file_get_contents(__DIR__ . '/../config/schema_orden_funnel.sql');
    $db->exec($sql);
    
    echo "✅ Tabla 'orden_funnel' creada exitosamente\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
