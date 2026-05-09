<?php

require_once __DIR__ . '/../config/database.php';

echo "==============================================\n";
echo "MIGRACIÓN DE EVENTOS JSON → MySQL\n";
echo "==============================================\n\n";

try {
    $pdo = getDB();
    
    // Leer eventos del JSON
    $jsonFile = __DIR__ . '/../data/eventos.json';
    
    if (!file_exists($jsonFile)) {
        echo "❌ No se encontró el archivo eventos.json\n";
        exit(1);
    }
    
    $eventosJson = json_decode(file_get_contents($jsonFile), true);
    
    if (empty($eventosJson)) {
        echo "ℹ️  No hay eventos para migrar\n";
        exit(0);
    }
    
    echo "📊 Encontrados " . count($eventosJson) . " eventos en JSON\n\n";
    
    // Preparar statement
    $stmt = $pdo->prepare("
        INSERT INTO eventos (
            id, embudo_id, tipo, nombre, url, timestamp,
            ip, user_agent, referrer,
            utm_source, utm_medium, utm_campaign, utm_term, utm_content
        ) VALUES (
            :id, :embudo_id, :tipo, :nombre, :url, :timestamp,
            :ip, :user_agent, :referrer,
            :utm_source, :utm_medium, :utm_campaign, :utm_term, :utm_content
        )
    ");
    
    $migrados = 0;
    $errores = 0;
    
    foreach ($eventosJson as $evento) {
        try {
            $stmt->execute([
                ':id' => $evento['id'],
                ':embudo_id' => $evento['embudo_id'],
                ':tipo' => $evento['tipo'],
                ':nombre' => $evento['nombre'],
                ':url' => $evento['url'] ?? '',
                ':timestamp' => $evento['timestamp'],
                ':ip' => $evento['ip'] ?? null,
                ':user_agent' => $evento['user_agent'] ?? null,
                ':referrer' => $evento['referrer'] ?? null,
                ':utm_source' => $evento['utm_source'] ?? null,
                ':utm_medium' => $evento['utm_medium'] ?? null,
                ':utm_campaign' => $evento['utm_campaign'] ?? null,
                ':utm_term' => $evento['utm_term'] ?? null,
                ':utm_content' => $evento['utm_content'] ?? null
            ]);
            $migrados++;
            echo ".";
        } catch (PDOException $e) {
            $errores++;
            echo "X";
        }
    }
    
    echo "\n\n";
    echo "✓ Migración completada\n";
    echo "  - Migrados: {$migrados}\n";
    echo "  - Errores: {$errores}\n\n";
    
    // Hacer backup del JSON
    $backupFile = __DIR__ . '/../data/eventos_backup_' . date('Y-m-d_H-i-s') . '.json';
    copy($jsonFile, $backupFile);
    echo "✓ Backup creado: " . basename($backupFile) . "\n\n";
    
    // Mostrar estadísticas finales
    $count = $pdo->query("SELECT COUNT(*) FROM eventos")->fetchColumn();
    echo "📊 Total de eventos en MySQL: {$count}\n\n";
    
    echo "==============================================\n";
    echo "✓✓✓ MIGRACIÓN COMPLETADA ✓✓✓\n";
    echo "==============================================\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
