<?php

// ============================================
// CONFIGURACIÓN DE BASE DE DATOS - EJEMPLO
// ============================================
// Este es un archivo de ejemplo. Para usar el sistema:
// 1. Copia este archivo como "database.php"
// 2. Modifica las credenciales según tu servidor

// ============================================
// DESARROLLO LOCAL (XAMPP)
// ============================================
// define('DB_HOST', 'localhost');
// define('DB_NAME', 'dashboard_marketing');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_CHARSET', 'utf8mb4');

// ============================================
// PRODUCCIÓN (Hostinger u otro hosting)
// ============================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'u123456_dashboard_marketing');  // 👈 Cambia con tu nombre de BD
define('DB_USER', 'u123456_usuario');               // 👈 Cambia con tu usuario
define('DB_PASS', 'tu_contraseña_segura');         // 👈 Cambia con tu contraseña
define('DB_CHARSET', 'utf8mb4');

/**
 * Obtener conexión PDO a la base de datos
 */
function getDB() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // En caso de error, intentar crear la base de datos
            try {
                $dsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
                $tempPdo = new PDO($dsn, DB_USER, DB_PASS);
                $tempPdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                
                // Reconectar con la base de datos creada
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e2) {
                die("Error de conexión a base de datos: " . $e2->getMessage());
            }
        }
    }
    
    return $pdo;
}
