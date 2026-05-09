<?php
// API para manejar peticiones AJAX

require_once 'controllers/ClienteController.php';
require_once 'controllers/UsuarioController.php';
require_once 'controllers/EmbudoController.php';
require_once 'controllers/EventoController.php';

// Obtener acción
$action = $_GET['action'] ?? '';

// Rutas de clientes
if ($action === 'listar' || $action === 'obtener' || $action === 'crear' || $action === 'actualizar' || $action === 'eliminar') {
    $controller = new ClienteController();
    
    switch ($action) {
        case 'listar':
            $controller->listar();
            break;
            
        case 'obtener':
            $id = $_GET['id'] ?? '';
            $controller->obtener($id);
            break;
            
        case 'crear':
            $controller->crear();
            break;
            
        case 'actualizar':
            $controller->actualizar();
            break;
            
        case 'eliminar':
            $controller->eliminar();
            break;
    }
}
// Rutas de usuarios
elseif ($action === 'usuario') {
    $controller = new UsuarioController();
    $sub = $_GET['sub'] ?? '';
    
    switch ($sub) {
        case 'login':
            $controller->login();
            break;
            
        case 'logout':
            $controller->logout();
            break;
            
        case 'listar':
            $controller->listar();
            break;
            
        case 'obtener':
            $id = $_GET['id'] ?? '';
            $controller->obtener($id);
            break;
            
        case 'crear':
            $controller->crear();
            break;
            
        case 'actualizar':
            $controller->actualizar();
            break;
            
        case 'eliminar':
            $controller->eliminar();
            break;
            
        default:
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Sub-acción no válida']);
            break;
    }
}
// Rutas de embudos
elseif ($action === 'embudo') {
    $controller = new EmbudoController();
    $sub = $_GET['sub'] ?? '';
    
    switch ($sub) {
        case 'listar':
            $controller->listar();
            break;
            
        case 'obtener':
            $id = $_GET['id'] ?? '';
            $controller->obtener($id);
            break;
            
        case 'crear':
            $controller->crear();
            break;
            
        case 'actualizar':
            $controller->actualizar();
            break;
            
        case 'eliminar':
            $controller->eliminar();
            break;
            
        default:
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Sub-acción no válida']);
            break;
    }
}
// Rutas de eventos
elseif ($action === 'evento') {
    $controller = new EventoController();
    $sub = $_GET['sub'] ?? '';
    
    switch ($sub) {
        case 'listar':
            $controller->listar();
            break;
            
        case 'estadisticas':
            $controller->estadisticas();
            break;
            
        case 'eliminar':
            $controller->eliminar();
            break;
            
        default:
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Sub-acción no válida']);
            break;
    }
}
// Rutas de productos
elseif ($action === 'producto') {
    require_once 'controllers/ProductoController.php';
    $controller = new ProductoController();
    $sub = $_GET['sub'] ?? '';
    
    switch ($sub) {
        case 'listar':
            $controller->listar();
            break;
            
        case 'crear':
            $controller->crear();
            break;
            
        case 'actualizar_orden':
            $controller->actualizarOrden();
            break;
            
        case 'eliminar':
            $controller->eliminar();
            break;
            
        case 'webhook':
            $controller->webhook();
            break;
            
        default:
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Sub-acción no válida']);
            break;
    }
}
// Rutas de orden del funnel
elseif ($action === 'orden_funnel') {
    require_once 'controllers/OrdenFunnelController.php';
    $controller = new OrdenFunnelController();
    $sub = $_GET['sub'] ?? '';
    
    switch ($sub) {
        case 'guardar':
            $controller->guardar();
            break;
            
        case 'obtener':
            $controller->obtener();
            break;
            
        default:
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Sub-acción no válida']);
            break;
    }
}
// Rutas de configuración
elseif ($action === 'configuracion') {
    require_once 'controllers/ConfiguracionController.php';
    $controller = new ConfiguracionController();
    $sub = $_GET['sub'] ?? '';
    
    switch ($sub) {
        case 'obtener':
            $controller->obtener();
            break;
            
        case 'guardar':
            $controller->guardar();
            break;
            
        default:
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Sub-acción no válida']);
            break;
    }
}
else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}

