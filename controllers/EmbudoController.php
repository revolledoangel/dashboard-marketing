<?php

require_once __DIR__ . '/../models/Embudo.php';

class EmbudoController {
    private $embudoModel;
    
    public function __construct() {
        $this->embudoModel = new Embudo();
    }
    
    // Listar embudos
    public function listar() {
        header('Content-Type: application/json');
        $clienteId = $_GET['cliente_id'] ?? null;
        echo json_encode($this->embudoModel->getAll($clienteId));
    }
    
    // Obtener embudo específico
    public function obtener($id) {
        header('Content-Type: application/json');
        $embudo = $this->embudoModel->getById($id);
        
        if ($embudo) {
            echo json_encode(['success' => true, 'data' => $embudo]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Embudo no encontrado']);
        }
    }
    
    // Crear embudo
    public function crear() {
        header('Content-Type: application/json');
        
        $clienteId = $_POST['cliente_id'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        
        if (empty($clienteId) || empty($nombre)) {
            echo json_encode(['success' => false, 'message' => 'Cliente y nombre son requeridos']);
            return;
        }
        
        $embudo = $this->embudoModel->crear($clienteId, $nombre, $descripcion);
        echo json_encode(['success' => true, 'data' => $embudo, 'message' => 'Embudo creado exitosamente']);
    }
    
    // Actualizar embudo
    public function actualizar() {
        header('Content-Type: application/json');
        
        $id = $_POST['id'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $pasos = isset($_POST['pasos']) ? json_decode($_POST['pasos'], true) : null;
        
        if (empty($id) || empty($nombre)) {
            echo json_encode(['success' => false, 'message' => 'ID y nombre son requeridos']);
            return;
        }
        
        $embudo = $this->embudoModel->actualizar($id, $nombre, $descripcion, $pasos);
        
        if ($embudo) {
            echo json_encode(['success' => true, 'data' => $embudo, 'message' => 'Embudo actualizado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Embudo no encontrado']);
        }
    }
    
    // Agregar landing al embudo
    public function agregarLanding() {
        header('Content-Type: application/json');
        
        $embudoId = $_POST['embudo_id'] ?? '';
        $landingId = $_POST['landing_id'] ?? '';
        
        if (empty($embudoId) || empty($landingId)) {
            echo json_encode(['success' => false, 'message' => 'Embudo y landing son requeridos']);
            return;
        }
        
        $embudo = $this->embudoModel->agregarLanding($embudoId, $landingId);
        
        if ($embudo) {
            echo json_encode(['success' => true, 'data' => $embudo, 'message' => 'Landing agregada al embudo']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al agregar landing']);
        }
    }
    
    // Eliminar embudo
    public function eliminar() {
        header('Content-Type: application/json');
        
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID es requerido']);
            return;
        }
        
        $this->embudoModel->eliminar($id);
        echo json_encode(['success' => true, 'message' => 'Embudo eliminado exitosamente']);
    }
}
