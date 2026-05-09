<?php

class Usuario {
    private $dataFile = __DIR__ . '/../data/usuarios.json';
    
    public function __construct() {
        // Asegurar que el archivo existe
        if (!file_exists($this->dataFile)) {
            file_put_contents($this->dataFile, json_encode([]));
        }
    }
    
    // Obtener todos los usuarios
    public function getAll() {
        $json = file_get_contents($this->dataFile);
        return json_decode($json, true) ?: [];
    }
    
    // Obtener usuario por ID
    public function getById($id) {
        $usuarios = $this->getAll();
        foreach ($usuarios as $usuario) {
            if ($usuario['id'] == $id) {
                return $usuario;
            }
        }
        return null;
    }
    
    // Obtener usuario por email
    public function getByEmail($email) {
        $usuarios = $this->getAll();
        foreach ($usuarios as $usuario) {
            if ($usuario['email'] == $email) {
                return $usuario;
            }
        }
        return null;
    }
    
    // Autenticar usuario
    public function autenticar($email, $password) {
        $usuario = $this->getByEmail($email);
        
        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        
        return null;
    }
    
    // Crear nuevo usuario
    public function crear($nombre, $email, $password) {
        $usuarios = $this->getAll();
        
        // Verificar si el email ya existe
        if ($this->getByEmail($email)) {
            return ['success' => false, 'message' => 'El email ya está registrado'];
        }
        
        // Generar ID único
        $id = $this->generarId();
        
        $nuevoUsuario = [
            'id' => $id,
            'nombre' => $nombre,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'fecha_creacion' => date('Y-m-d H:i:s')
        ];
        
        $usuarios[] = $nuevoUsuario;
        
        // Guardar en archivo
        file_put_contents($this->dataFile, json_encode($usuarios, JSON_PRETTY_PRINT));
        
        // Retornar sin password
        unset($nuevoUsuario['password']);
        return ['success' => true, 'data' => $nuevoUsuario];
    }
    
    // Actualizar usuario
    public function actualizar($id, $nombre, $email, $password = null) {
        $usuarios = $this->getAll();
        
        foreach ($usuarios as &$usuario) {
            if ($usuario['id'] == $id) {
                $usuario['nombre'] = $nombre;
                $usuario['email'] = $email;
                
                // Solo actualizar password si se proporciona
                if ($password && !empty($password)) {
                    $usuario['password'] = password_hash($password, PASSWORD_DEFAULT);
                }
                
                file_put_contents($this->dataFile, json_encode($usuarios, JSON_PRETTY_PRINT));
                
                // Retornar sin password
                unset($usuario['password']);
                return $usuario;
            }
        }
        
        return null;
    }
    
    // Eliminar usuario
    public function eliminar($id) {
        $usuarios = $this->getAll();
        $usuarios = array_filter($usuarios, function($usuario) use ($id) {
            return $usuario['id'] != $id;
        });
        
        // Reindexar array
        $usuarios = array_values($usuarios);
        
        file_put_contents($this->dataFile, json_encode($usuarios, JSON_PRETTY_PRINT));
        return true;
    }
    
    // Generar ID único
    private function generarId() {
        return uniqid('user_', true);
    }
}
