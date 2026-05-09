<?php
require_once __DIR__ . '/../config/database.php';

echo "\n=== CREANDO TABLA VENTAS ===\n\n";

try {
    $db = getDB();
    $sql = file_get_contents(__DIR__ . '/crear_tabla_ventas.sql');
    $db->exec($sql);
    echo "✅ Tabla ventas creada exitosamente\n\n";
    
    // Verificar que se creó
    $stmt = $db->query("SHOW TABLES LIKE 'ventas'");
    if ($stmt->fetch()) {
        echo "✅ Tabla verificada en la base de datos\n\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}
