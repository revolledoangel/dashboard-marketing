<?php
require_once __DIR__ . '/../config/database.php';

$db = getDB();
$stmt = $db->query('SHOW TABLES');
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo "\n=== TABLAS EN LA BASE DE DATOS ===\n\n";
foreach ($tables as $table) {
    echo "- $table\n";
}
echo "\n";
