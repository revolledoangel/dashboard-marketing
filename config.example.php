<?php
// ============================================
// ARCHIVO DE CONFIGURACIÓN - EJEMPLO
// ============================================
// Este es un archivo de ejemplo. Para usar el sistema:
// 1. Copia este archivo como "config.php"
// 2. Modifica los valores según tu entorno

// Archivo de Configuración
// Define constantes y configuraciones globales del sistema

// ============================================
// CONFIGURACIÓN DE RUTA BASE
// ============================================
// Opciones:
// 1. AUTO: Detecta automáticamente (usa la carpeta donde está instalado)
// 2. ROOT: Fuerza a usar la raíz del dominio (sin subdirectorios)
// 3. Cualquier ruta custom: Ejemplo '/app', '/sistema', etc.

$BASE_PATH_CONFIG = 'AUTO';  // Detecta automáticamente la ruta correcta

// ============================================

// Detectar o forzar la ruta base
if ($BASE_PATH_CONFIG === 'ROOT') {
    // Forzar raíz - ideal para producción o cuando /gonzalo/ es tu carpeta de desarrollo
    $basePath = '';
} elseif ($BASE_PATH_CONFIG === 'AUTO') {
    // Detección automática
    $scriptPath = isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : '';
    $basePath = ($scriptPath === '/' || $scriptPath === '\\' || $scriptPath === '') ? '' : $scriptPath;
} else {
    // Ruta personalizada
    $basePath = $BASE_PATH_CONFIG;
}

// URL base del sistema
if (!defined('BASE_PATH')) {
    define('BASE_PATH', $basePath);
}

// URLs completas
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';

if (!defined('BASE_URL')) {
    define('BASE_URL', $protocol . '://' . $host . BASE_PATH);
}

// Timezone
date_default_timezone_set('America/Mexico_City');

// Configuración de errores (comentar en producción)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
