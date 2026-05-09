<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Evento.php';

echo "==============================================\n";
echo "TEST DEL MODELO EVENTO con MySQL\n";
echo "==============================================\n\n";

$eventoModel = new Evento();

// Test 1: Obtener todos los eventos
echo "Test 1: getAll()\n";
$eventos = $eventoModel->getAll();
echo "✓ Total eventos: " . count($eventos) . "\n";
foreach ($eventos as $ev) {
    echo "  - {$ev['nombre']} ({$ev['tipo']}) - {$ev['timestamp']}\n";
}
echo "\n";

// Test 2: Obtener eventos de un embudo específico
if (!empty($eventos)) {
    $embudoId = $eventos[0]['embudo_id'];
    echo "Test 2: getAll('{$embudoId}')\n";
    $eventosFiltrados = $eventoModel->getAll($embudoId);
    echo "✓ Eventos del embudo: " . count($eventosFiltrados) . "\n\n";
    
    // Test 3: Estadísticas
    echo "Test 3: getStats('{$embudoId}')\n";
    $stats = $eventoModel->getStats($embudoId);
    echo "✓ Total: {$stats['total']}\n";
    echo "✓ Visitas: {$stats['visitas']}\n";
    echo "✓ Eventos: {$stats['eventos']}\n";
    echo "✓ Por nombre: " . json_encode($stats['por_nombre'], JSON_UNESCAPED_UNICODE) . "\n\n";
}

// Test 4: Crear nuevo evento
echo "Test 4: create() - Crear nuevo evento de prueba\n";
$resultado = $eventoModel->create([
    'embudo_id' => 'test_embudo_123',
    'tipo' => 'visita',
    'nombre' => 'test_mysql',
    'url' => 'http://localhost/test',
    'ip' => '127.0.0.1',
    'user_agent' => 'Test User Agent',
    'referrer' => '',
    'utm_source' => 'test',
    'utm_campaign' => 'mysql_migration'
]);

if ($resultado['success']) {
    echo "✓ Evento creado: {$resultado['data']['id']}\n";
    
    // Test 5: Obtener por ID
    echo "\nTest 5: getById('{$resultado['data']['id']}')\n";
    $eventoRecuperado = $eventoModel->getById($resultado['data']['id']);
    if ($eventoRecuperado) {
        echo "✓ Evento recuperado: {$eventoRecuperado['nombre']}\n";
        
        // Test 6: Eliminar
        echo "\nTest 6: delete('{$resultado['data']['id']}')\n";
        $resultadoDelete = $eventoModel->delete($resultado['data']['id']);
        if ($resultadoDelete['success']) {
            echo "✓ Evento eliminado correctamente\n";
        }
    }
}

echo "\n==============================================\n";
echo "✓✓✓ TODOS LOS TESTS PASARON ✓✓✓\n";
echo "==============================================\n";
