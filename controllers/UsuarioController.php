<?php

require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    private $usuarioModel;
    
    public function __construct() {
        $this->usuarioModel = new Usuario();
    }
    
    // Login
    public function login() {
        header('Content-Type: application/json');
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Email y contraseña son requeridos']);
            return;
        }
        
        $usuario = $this->usuarioModel->autenticar($email, $password);
        
        if ($usuario) {
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_email'] = $usuario['email'];
            
            echo json_encode(['success' => true, 'message' => 'Login exitoso', 'data' => [
                'nombre' => $usuario['nombre'],
                'email' => $usuario['email']
            ]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
        }
    }
    
    // Logout
    public function logout() {
        session_start();
        session_destroy();
        header('Location: login.php');
        exit;
    }
    
    // Listar todos los usuarios (API JSON)
    public function listar() {
        header('Content-Type: application/json');
        $usuarios = $this->usuarioModel->getAll();
        
        // Eliminar passwords de la respuesta
        $usuarios = array_map(function($usuario) {
            unset($usuario['password']);
            return $usuario;
        }, $usuarios);
        
        echo json_encode($usuarios);
    }
    
    // Obtener usuario específico
    public function obtener($id) {
        header('Content-Type: application/json');
        $usuario = $this->usuarioModel->getById($id);
        if ($usuario) {
            unset($usuario['password']);
            echo json_encode(['success' => true, 'data' => $usuario]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        }
    }
    
    // Crear nuevo usuario
    public function crear() {
        header('Content-Type: application/json');
        
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($nombre) || empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
            return;
        }
        
        $resultado = $this->usuarioModel->crear($nombre, $email, $password);
        echo json_encode($resultado);
    }
    
    // Actualizar usuario
    public function actualizar() {
        header('Content-Type: application/json');
        
        $id = $_POST['id'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($id) || empty($nombre) || empty($email)) {
            echo json_encode(['success' => false, 'message' => 'ID, nombre y email son requeridos']);
            return;
        }
        
        $usuario = $this->usuarioModel->actualizar($id, $nombre, $email, $password);
        if ($usuario) {
            echo json_encode(['success' => true, 'data' => $usuario, 'message' => 'Usuario actualizado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        }
    }
    
    // Eliminar usuario
    public function eliminar() {
        header('Content-Type: application/json');
        
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID es requerido']);
            return;
        }
        
        $this->usuarioModel->eliminar($id);
        echo json_encode(['success' => true, 'message' => 'Usuario eliminado exitosamente']);
    }
}
