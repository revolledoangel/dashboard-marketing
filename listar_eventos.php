<?php
require_once 'config/database.php';

$db = getDB();
$stmt = $db->query("SELECT nombre, COUNT(*) as cantidad FROM eventos WHERE tipo = 'evento' GROUP BY nombre ORDER BY cantidad DESC");
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "=== EVENTOS TIPO 'EVENTO' EN LA BASE DE DATOS ===\n\n";

foreach ($eventos as $evento) {
    echo sprintf("%-30s : %d eventos\n", $evento['nombre'], $evento['cantidad']);
}

echo "\nTotal tipos de eventos: " . count($eventos) . "\n";
