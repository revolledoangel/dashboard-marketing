<?php
/**
 * Script para generar eventos de prueba (tipo 'evento')
 * Esto permite probar la funcionalidad de eventos anidados
 */

require_once __DIR__ . '/../models/Embudo.php';
require_once __DIR__ . '/../models/Evento.php';

echo "=== GENERANDO EVENTOS DE PRUEBA ===\n\n";

try {
    $embudoModel = new Embudo();
    $eventoModel = new Evento();
    
    // Obtener primer embudo disponible
    $embudos = $embudoModel->getAll();
    
    if (empty($embudos)) {
        echo "❌ No hay embudos registrados. Crea un embudo primero.\n";
        exit(1);
    }
    
    $embudo = $embudos[0];
    echo "📊 Usando embudo: {$embudo['nombre']} (ID: {$embudo['id']})\n\n";
    
    // Eventos de prueba
    $eventos = [
        // Eventos para Home/Registro
        ['tipo' => 'evento', 'nombre' => 'Click CTA Principal', 'cantidad' => 15],
        ['tipo' => 'evento', 'nombre' => 'Click Ver Demo', 'cantidad' => 8],
        ['tipo' => 'evento', 'nombre' => 'Scroll 50%', 'cantidad' => 12],
        
        // Eventos para Checkout
        ['tipo' => 'evento', 'nombre' => 'Click Continuar', 'cantidad' => 5],
        ['tipo' => 'evento', 'nombre' => 'Error Formulario', 'cantidad' => 3],
        
        // Eventos para Confirmación
        ['tipo' => 'evento', 'nombre' => 'Click Descargar', 'cantidad' => 2],
    ];
    
    echo "📝 Insertando eventos de prueba...\n";
    
    $insertados = 0;
    foreach ($eventos as $ev) {
        for ($i = 0; $i < $ev['cantidad']; $i++) {
            $data = [
                'embudo_id' => $embudo['id'],
                'tipo' => $ev['tipo'],
                'nombre' => $ev['nombre'],
                'url' => 'https://ejemplo.com/test',
                'ip' => '127.0.0.1',
                'user_agent' => 'Test Agent',
                'referrer' => '',
                'utm_source' => 'test',
                'utm_medium' => 'test',
                'utm_campaign' => 'test'
            ];
            
            $resultado = $eventoModel->create($data);
            if ($resultado['success']) {
                $insertados++;
            }
        }
        
        echo "   ✅ {$ev['nombre']}: {$ev['cantidad']} eventos\n";
    }
    
    echo "\n✅ Total insertados: $insertados eventos\n";
    echo "\n💡 Ahora ve a Métricas y selecciona el embudo '{$embudo['nombre']}'\n";
    echo "   Deberías ver los eventos como cards pequeños que puedes arrastrar dentro de las páginas.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
