<?php
/**
 * Configuración de base de datos REMOTA (Hostinger)
 * 
 * Para usar esta configuración:
 * 1. Renombra este archivo a database.php
 * 2. O incluye este archivo en lugar del database.php local
 */

// Credenciales de Hostinger
define('DB_HOST', 'srv1910.hstgr.io');           // O usar: 193.203.168.209
define('DB_PORT', '3306');                        // Puerto estándar MySQL
define('DB_NAME', 'u615891939_dashboard');        // Base de datos de Hostinger
define('DB_USER', 'u615891939_dashboard');        // Usuario (generalmente igual al nombre de DB)
define('DB_PASS', 'TU_PASSWORD_MYSQL_AQUI');     // ← CAMBIAR: Contraseña de Hostinger

/**
 * Conexión PDO a MySQL
 * Crea automáticamente la base de datos si no existe
 */
function getDB() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            // Primero intentar conectar sin especificar base de datos para crearla si no existe
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8mb4";
            $pdoTemp = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            
            // Crear base de datos si no existe
            $pdoTemp->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdoTemp = null;
            
            // Ahora conectar a la base de datos específica
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ]);
            
        } catch (PDOException $e) {
            error_log("Error de conexión a base de datos remota: " . $e->getMessage());
            throw new Exception("No se pudo conectar a la base de datos: " . $e->getMessage());
        }
    }
    
    return $pdo;
}
