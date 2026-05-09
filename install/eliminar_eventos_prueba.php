<?php
/**
 * Script para eliminar eventos de prueba
 */

require_once __DIR__ . '/../config/database.php';

echo "=== ELIMINANDO EVENTOS DE PRUEBA ===\n\n";

try {
    $db = getDB();
    
    // Nombres de eventos de prueba que creamos
    $eventosPrueba = [
        'Click CTA',
        'Ver Demo', 
        'Scroll 50%',
        'Reproducir Video',
        'Descargar PDF',
        'Click WhatsApp',
        'Click Teléfono',
        'Share Social',
        'Click Email',
        'Click CTA Principal',
        'Click Ver Demo',
        'Scroll 50%',
        'Click CTA Secundario'
    ];
    
    echo "🔍 Buscando eventos de prueba...\n\n";
    
    $db->beginTransaction();
    
    $totalEliminados = 0;
    
    foreach ($eventosPrueba as $nombreEvento) {
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM eventos WHERE nombre = ? AND tipo = 'evento'");
        $stmt->execute([$nombreEvento]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        if ($count > 0) {
            $stmt = $db->prepare("DELETE FROM eventos WHERE nombre = ? AND tipo = 'evento'");
            $stmt->execute([$nombreEvento]);
            $eliminados = $stmt->rowCount();
            $totalEliminados += $eliminados;
            echo "   ❌ Eliminados $eliminados eventos: $nombreEvento\n";
        }
    }
    
    $db->commit();
    
    echo "\n✅ Total eventos eliminados: $totalEliminados\n";
    echo "✅ Proceso completado\n";
    
} catch (Exception $e) {
    if (isset($db)) {
        $db->rollBack();
    }
    echo "❌ Error: " . $e->getMessage() . "\n";
}
