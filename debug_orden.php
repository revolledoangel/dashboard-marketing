<?php
require_once 'config/database.php';

$db = getDB();
$stmt = $db->query('SELECT * FROM orden_funnel ORDER BY embudo_id, orden ASC');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "=== REGISTROS EN orden_funnel ===\n\n";

$currentEmbudo = null;
foreach($rows as $r) {
    if ($currentEmbudo !== $r['embudo_id']) {
        $currentEmbudo = $r['embudo_id'];
        echo "\n📊 EMBUDO: " . substr($r['embudo_id'], -15) . "\n";
        echo str_repeat('-', 80) . "\n";
    }
    
    $indent = $r['pagina_padre'] ? '  └─ ' : '';
    echo sprintf(
        "%s[%d] %s: %s (padre: %s)\n",
        $indent,
        $r['orden'],
        strtoupper($r['tipo']),
        $r['pagina_nombre'],
        $r['pagina_padre'] ?? 'NULL'
    );
}

echo "\n\n=== TOTAL REGISTROS: " . count($rows) . " ===\n";
