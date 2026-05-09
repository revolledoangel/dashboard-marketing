<?php
/**
 * Test de conexión a base de datos REMOTA (Hostinger)
 * 
 * Ejecutar: php install/test_remote_connection.php
 */

echo "=== TEST DE CONEXIÓN REMOTA A HOSTINGER ===\n\n";

// Usar configuración remota
require_once __DIR__ . '/../config/database.remote.php';

echo "📋 Credenciales configuradas:\n";
echo "   Host: " . DB_HOST . "\n";
echo "   Port: " . DB_PORT . "\n";
echo "   Database: " . DB_NAME . "\n";
echo "   User: " . DB_USER . "\n";
echo "   Password: " . (DB_PASS === 'TU_PASSWORD_MYSQL_AQUI' ? '❌ NO CONFIGURADO' : '✅ Configurado') . "\n\n";

if (DB_PASS === 'TU_PASSWORD_MYSQL_AQUI') {
    echo "❌ ERROR: Debes editar config/database.remote.php y poner tu contraseña real\n";
    exit(1);
}

echo "🔌 Intentando conectar a Hostinger...\n";

try {
    $pdo = getDB();
    echo "✅ Conexión exitosa!\n\n";
    
    // Verificar versión de MySQL
    $version = $pdo->query('SELECT VERSION()')->fetchColumn();
    echo "📊 MySQL Version: $version\n\n";
    
    // Verificar si la tabla eventos existe
    echo "🔍 Verificando tabla 'eventos'...\n";
    $tables = $pdo->query("SHOW TABLES LIKE 'eventos'")->fetchAll();
    
    if (count($tables) > 0) {
        echo "✅ Tabla 'eventos' existe\n";
        
        // Contar registros
        $count = $pdo->query("SELECT COUNT(*) FROM eventos")->fetchColumn();
        echo "   Registros actuales: $count\n";
    } else {
        echo "⚠️  Tabla 'eventos' NO existe\n";
        echo "   Ejecuta: php install/test_connection.php para crearla\n";
    }
    
    echo "\n✅ TODO OK - Listo para trabajar con la base de datos remota\n";
    
} catch (Exception $e) {
    echo "❌ ERROR DE CONEXIÓN:\n";
    echo "   " . $e->getMessage() . "\n\n";
    
    echo "💡 Verifica:\n";
    echo "   1. Que agregaste tu IP en Hostinger Remote MySQL\n";
    echo "   2. Que las credenciales en database.remote.php sean correctas\n";
    echo "   3. Que el firewall/antivirus permita conexiones salientes al puerto 3306\n";
    
    exit(1);
}
