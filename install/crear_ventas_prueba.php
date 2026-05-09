<?php
/**
 * Script para crear ventas de prueba
 * Útil para probar las estadísticas antes de configurar Hotmart
 */

require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Venta.php';

echo "\n=== CREANDO VENTAS DE PRUEBA ===\n\n";

$productoModel = new Producto();
$ventaModel = new Venta();

// Obtener todos los productos
$productos = $productoModel->getAll();

if (empty($productos)) {
    echo "❌ No hay productos creados. Crea un producto primero.\n\n";
    exit;
}

// Usar el primer producto
$producto = $productos[0];

echo "📦 Producto seleccionado: {$producto['nombre']}\n";
echo "   ID: {$producto['id']}\n";
echo "   Embudo: {$producto['embudo_id']}\n\n";

// Crear 5 ventas de prueba
$numVentas = 5;

echo "💰 Creando {$numVentas} ventas de prueba...\n\n";

for ($i = 1; $i <= $numVentas; $i++) {
    // Simular datos de webhook de Hotmart
    $webhookData = [
        'event' => 'PURCHASE_APPROVED',
        'product' => [
            'id' => '123456',
            'name' => $producto['nombre']
        ],
        'buyer' => [
            'email' => "cliente{$i}@ejemplo.com",
            'name' => "Cliente Prueba {$i}"
        ],
        'purchase' => [
            'transaction' => 'HP' . str_pad($i, 8, '0', STR_PAD_LEFT),
            'status' => 'approved',
            'price' => [
                'value' => rand(50, 200) + (rand(0, 99) / 100), // Precio aleatorio entre 50-200
                'currency' => 'USD'
            ]
        ]
    ];
    
    $resultado = $ventaModel->create(
        $producto['id'],
        $producto['embudo_id'],
        $webhookData
    );
    
    if ($resultado['success']) {
        echo "   ✅ Venta {$i}/{$numVentas} creada - \${$webhookData['purchase']['price']['value']}\n";
    } else {
        echo "   ❌ Error en venta {$i}: {$resultado['error']}\n";
    }
}

echo "\n" . str_repeat('=', 50) . "\n";
echo "✅ VENTAS DE PRUEBA CREADAS\n";
echo str_repeat('=', 50) . "\n\n";

// Mostrar estadísticas
echo "📊 ESTADÍSTICAS DEL PRODUCTO:\n\n";
$stats = $productoModel->getStats($producto['id']);

echo "   Total de ventas: {$stats['total_ventas']}\n";
echo "   Ingreso total: \${$stats['ingreso_total']}\n";
echo "   Ticket promedio: \${$stats['ticket_promedio']}\n\n";

if (!empty($stats['conversiones_por_pagina'])) {
    echo "   Conversiones por página:\n";
    foreach ($stats['conversiones_por_pagina'] as $conv) {
        echo "   - {$conv['pagina']}: {$conv['porcentaje']}% ({$conv['visitas']} visitas)\n";
    }
} else {
    echo "   (No hay datos de visitas para calcular conversiones)\n";
}

echo "\n";
