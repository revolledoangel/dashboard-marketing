<?php

class Cliente {
    private $dataFile = __DIR__ . '/../data/clientes.json';
    
    public function __construct() {
        // Asegurar que el archivo existe
        if (!file_exists($this->dataFile)) {
            file_put_contents($this->dataFile, json_encode([]));
        }
    }
    
    // Obtener todos los clientes
    public function getAll() {
        $json = file_get_contents($this->dataFile);
        return json_decode($json, true) ?: [];
    }
    
    // Obtener cliente por ID
    public function getById($id) {
        $clientes = $this->getAll();
        foreach ($clientes as $cliente) {
            if ($cliente['id'] == $id) {
                return $cliente;
            }
        }
        return null;
    }
    
    // Crear nuevo cliente
    public function crear($nombre) {
        $clientes = $this->getAll();
        
        // Generar ID único
        $id = $this->generarId();
        
        $nuevoCliente = [
            'id' => $id,
            'nombre' => $nombre,
            'fecha_creacion' => date('Y-m-d H:i:s')
        ];
        
        $clientes[] = $nuevoCliente;
        
        // Guardar en archivo
        file_put_contents($this->dataFile, json_encode($clientes, JSON_PRETTY_PRINT));
        
        return $nuevoCliente;
    }
    
    // Actualizar cliente
    public function actualizar($id, $nombre) {
        $clientes = $this->getAll();
        
        foreach ($clientes as &$cliente) {
            if ($cliente['id'] == $id) {
                $cliente['nombre'] = $nombre;
                file_put_contents($this->dataFile, json_encode($clientes, JSON_PRETTY_PRINT));
                return $cliente;
            }
        }
        
        return null;
    }
    
    // Eliminar cliente
    public function eliminar($id) {
        $clientes = $this->getAll();
        $clientes = array_filter($clientes, function($cliente) use ($id) {
            return $cliente['id'] != $id;
        });
        
        // Reindexar array
        $clientes = array_values($clientes);
        
        file_put_contents($this->dataFile, json_encode($clientes, JSON_PRETTY_PRINT));
        return true;
    }
    
    // Generar ID único
    private function generarId() {
        return uniqid('cliente_', true);
    }
}
