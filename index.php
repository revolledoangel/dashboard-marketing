<?php
session_start();

// Verificar autenticación
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Configurar timezone del sistema
require_once __DIR__ . '/includes/timezone_helper.php';
setTimezoneConfig();

// Determinar qué página mostrar
$page = $_GET['page'] ?? 'dashboard';

// Páginas permitidas
$allowedPages = ['dashboard', 'configuracion', 'usuarios', 'embudos', 'metricas'];

// Validar página
if (!in_array($page, $allowedPages)) {
    $page = 'dashboard';
}

// Configurar título
$pageTitles = [
    'dashboard' => 'Dashboard - Marketing Panel',
    'configuracion' => 'Configuración - Marketing Panel',
    'usuarios' => 'Usuarios - Marketing Panel',
    'embudos' => 'Embudos - Marketing Panel',
    'metricas' => 'Métricas - Marketing Panel'
];

$pageTitle = $pageTitles[$page] ?? 'Marketing Panel';

// Incluir header y sidebar
include 'views/includes/header.php';
include 'views/includes/sidebar.php';

// Incluir contenido de la página
include "views/{$page}.php";

// Incluir footer
include 'views/includes/footer.php';
?>
