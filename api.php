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
else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}

