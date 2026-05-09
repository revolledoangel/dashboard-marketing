<?php

// Test de conexión a MySQL
echo "Probando conexión a MySQL...\n";

try {
    // Intentar conectar sin especificar base de datos
    $dsn = "mysql:host=localhost;charset=utf8mb4";
    $pdo = new PDO($dsn, 'root', '');
    echo "✓ Conexión a MySQL exitosa\n";
    
    // Crear base de datos si no existe
    echo "Creando base de datos 'dashboard_marketing'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `dashboard_marketing` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Base de datos creada o ya existe\n";
    
    // Reconectar a la base de datos específica
    $dsn = "mysql:host=localhost;dbname=dashboard_marketing;charset=utf8mb4";
    $pdo = new PDO($dsn, 'root', '');
    echo "✓ Conectado a la base de datos 'dashboard_marketing'\n";
    
    // Crear tabla
    echo "Creando tabla 'eventos'...\n";
    $sql = "
    CREATE TABLE IF NOT EXISTS `eventos` (
      `id` VARCHAR(50) PRIMARY KEY,
      `embudo_id` VARCHAR(50) NOT NULL,
      `tipo` ENUM('visita', 'evento') NOT NULL,
      `nombre` VARCHAR(255) NOT NULL,
      `url` TEXT,
      `timestamp` DATETIME NOT NULL,
      `ip` VARCHAR(45),
      `user_agent` TEXT,
      `referrer` TEXT,
      `utm_source` VARCHAR(255),
      `utm_medium` VARCHAR(255),
      `utm_campaign` VARCHAR(255),
      `utm_term` VARCHAR(255),
      `utm_content` VARCHAR(255),
      
      INDEX idx_embudo_id (`embudo_id`),
      INDEX idx_tipo (`tipo`),
      INDEX idx_timestamp (`timestamp`),
      INDEX idx_embudo_tipo (`embudo_id`, `tipo`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($sql);
    echo "✓ Tabla 'eventos' creada exitosamente\n\n";
    
    echo "✓✓✓ TODO LISTO ✓✓✓\n";
    
} catch (PDOException $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
