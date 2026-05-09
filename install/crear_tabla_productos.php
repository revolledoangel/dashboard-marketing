<?php
require_once __DIR__ . '/../config/database.php';

echo "\n=== CREANDO TABLA PRODUCTOS ===\n\n";

try {
    $db = getDB();
    $sql = file_get_contents(__DIR__ . '/crear_tabla_productos.sql');
    $db->exec($sql);
    echo "✅ Tabla productos creada exitosamente\n\n";
    
    // Verificar que se creó
    $stmt = $db->query("SHOW TABLES LIKE 'productos'");
    if ($stmt->fetch()) {
        echo "✅ Tabla verificada en la base de datos\n\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}
