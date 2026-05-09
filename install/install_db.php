<?php

/**
 * Script de instalación de la base de datos
 * Ejecuta este archivo UNA VEZ para crear las tablas necesarias
 */

require_once __DIR__ . '/../config/database.php';

echo "==============================================\n";
echo "INSTALACIÓN DE BASE DE DATOS\n";
echo "==============================================\n\n";

try {
    $pdo = getDB();
    echo "✓ Conexión a base de datos exitosa\n\n";
    
    // Leer y ejecutar el schema
    $sql = file_get_contents(__DIR__ . '/../config/schema_eventos.sql');
    
    echo "Creando tabla 'eventos'...\n";
    $pdo->exec($sql);
    echo "✓ Tabla 'eventos' creada exitosamente\n\n";
    
    // Migrar datos existentes del JSON
    echo "Migrando eventos desde JSON...\n";
    
    $jsonFile = __DIR__ . '/../data/eventos.json';
    if (file_exists($jsonFile)) {
        $eventosJson = json_decode(file_get_contents($jsonFile), true);
        
        if (!empty($eventosJson)) {
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
            foreach ($eventosJson as $evento) {
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
            }
            
            echo "✓ {$migrados} eventos migrados exitosamente\n\n";
            
            // Hacer backup del JSON
            $backupFile = __DIR__ . '/../data/eventos_backup_' . date('Y-m-d_H-i-s') . '.json';
            copy($jsonFile, $backupFile);
            echo "✓ Backup creado en: " . basename($backupFile) . "\n\n";
        } else {
            echo "- No hay eventos para migrar\n\n";
        }
    } else {
        echo "- No se encontró archivo eventos.json\n\n";
    }
    
    echo "==============================================\n";
    echo "INSTALACIÓN COMPLETADA EXITOSAMENTE\n";
    echo "==============================================\n\n";
    
    // Mostrar estadísticas
    $count = $pdo->query("SELECT COUNT(*) FROM eventos")->fetchColumn();
    echo "Total de eventos en la base de datos: {$count}\n\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
