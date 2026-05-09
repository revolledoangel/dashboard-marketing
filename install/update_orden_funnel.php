<?php
/**
 * Actualizar tabla orden_funnel para soportar eventos anidados
 */

require_once __DIR__ . '/../config/database.php';

echo "=== ACTUALIZACIÓN DE TABLA orden_funnel ===\n\n";

try {
    $db = getDB();
    echo "✅ Conectado a la base de datos\n";
    
    // Verificar si las columnas ya existen
    $stmt = $db->query("DESCRIBE orden_funnel");
    $columnas = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $necesitaActualizar = !in_array('tipo', $columnas) || !in_array('pagina_padre', $columnas);
    
    if ($necesitaActualizar) {
        echo "🔧 Actualizando estructura de tabla...\n";
        
        // Agregar columna tipo si no existe
        if (!in_array('tipo', $columnas)) {
            $db->exec("ALTER TABLE orden_funnel ADD COLUMN tipo ENUM('visita', 'evento') NOT NULL DEFAULT 'visita' AFTER embudo_id");
            echo "   ✅ Columna 'tipo' agregada\n";
        }
        
        // Agregar columna pagina_padre si no existe
        if (!in_array('pagina_padre', $columnas)) {
            $db->exec("ALTER TABLE orden_funnel ADD COLUMN pagina_padre VARCHAR(255) NULL AFTER pagina_nombre");
            echo "   ✅ Columna 'pagina_padre' agregada\n";
        }
        
        // Agregar índices
        try {
            $db->exec("CREATE INDEX idx_tipo ON orden_funnel(tipo)");
            echo "   ✅ Índice 'idx_tipo' creado\n";
        } catch (Exception $e) {
            // Índice ya existe, ignorar
        }
        
        try {
            $db->exec("CREATE INDEX idx_pagina_padre ON orden_funnel(pagina_padre)");
            echo "   ✅ Índice 'idx_pagina_padre' creado\n";
        } catch (Exception $e) {
            // Índice ya existe, ignorar
        }
        
        echo "\n✅ Tabla actualizada exitosamente\n";
    } else {
        echo "✅ La tabla ya está actualizada\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
