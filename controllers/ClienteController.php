<?php

require_once __DIR__ . '/../models/Cliente.php';

class ClienteController {
    private $clienteModel;
    
    public function __construct() {
        $this->clienteModel = new Cliente();
    }
    
    // Listar todos los clientes (API JSON)
    public function listar() {
        header('Content-Type: application/json');
        echo json_encode($this->clienteModel->getAll());
    }
    
    // Obtener cliente específico
    public function obtener($id) {
        header('Content-Type: application/json');
        $cliente = $this->clienteModel->getById($id);
        if ($cliente) {
            echo json_encode(['success' => true, 'data' => $cliente]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cliente no encontrado']);
        }
    }
    
    // Crear nuevo cliente
    public function crear() {
        header('Content-Type: application/json');
        
        $nombre = $_POST['nombre'] ?? '';
        
        if (empty($nombre)) {
            echo json_encode(['success' => false, 'message' => 'El nombre es requerido']);
            return;
        }
        
        $cliente = $this->clienteModel->crear($nombre);
        echo json_encode(['success' => true, 'data' => $cliente, 'message' => 'Cliente creado exitosamente']);
    }
    
    // Actualizar cliente
    public function actualizar() {
        header('Content-Type: application/json');
        
        $id = $_POST['id'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        
        if (empty($id) || empty($nombre)) {
            echo json_encode(['success' => false, 'message' => 'ID y nombre son requeridos']);
            return;
        }
        
        $cliente = $this->clienteModel->actualizar($id, $nombre);
        if ($cliente) {
            echo json_encode(['success' => true, 'data' => $cliente, 'message' => 'Cliente actualizado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cliente no encontrado']);
        }
    }
    
    // Eliminar cliente
    public function eliminar() {
        header('Content-Type: application/json');
        
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID es requerido']);
            return;
        }
        
        $this->clienteModel->eliminar($id);
        echo json_encode(['success' => true, 'message' => 'Cliente eliminado exitosamente']);
    }
}
