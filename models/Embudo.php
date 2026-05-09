<?php

class Embudo {
    private $dataFile = __DIR__ . '/../data/embudos.json';
    
    public function __construct() {
        if (!file_exists($this->dataFile)) {
            file_put_contents($this->dataFile, json_encode([]));
        }
    }
    
    // Obtener todos los embudos
    public function getAll($clienteId = null) {
        $json = file_get_contents($this->dataFile);
        $embudos = json_decode($json, true) ?: [];
        
        if ($clienteId) {
            $filtered = array_filter($embudos, function($embudo) use ($clienteId) {
                return $embudo['cliente_id'] == $clienteId;
            });
            return array_values($filtered); // Re-indexar para que JSON lo serialice como array
        }
        
        return $embudos;
    }
    
    // Obtener embudo por ID
    public function getById($id) {
        $embudos = $this->getAll();
        foreach ($embudos as $embudo) {
            if ($embudo['id'] == $id) {
                return $embudo;
            }
        }
        return null;
    }
    
    // Obtener embudo por token
    public function getByToken($token) {
        $embudos = $this->getAll();
        foreach ($embudos as $embudo) {
            if ($embudo['token'] === $token) {
                return $embudo;
            }
        }
        return null;
    }
    
    // Crear nuevo embudo
    public function crear($clienteId, $nombre, $descripcion = '') {
        $embudos = $this->getAll();
        
        $id = $this->generarId();
        $token = $this->generarToken();
        
        $nuevoEmbudo = [
            'id' => $id,
            'token' => $token,
            'cliente_id' => $clienteId,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'pasos' => [], // Array de IDs de landings en orden
            'fecha_creacion' => date('Y-m-d H:i:s'),
            'activo' => true
        ];
        
        $embudos[] = $nuevoEmbudo;
        file_put_contents($this->dataFile, json_encode($embudos, JSON_PRETTY_PRINT));
        
        return $nuevoEmbudo;
    }
    
    // Actualizar embudo
    public function actualizar($id, $nombre, $descripcion, $pasos = null) {
        $embudos = $this->getAll();
        
        foreach ($embudos as &$embudo) {
            if ($embudo['id'] == $id) {
                $embudo['nombre'] = $nombre;
                $embudo['descripcion'] = $descripcion;
                
                if ($pasos !== null) {
                    $embudo['pasos'] = $pasos;
                }
                
                file_put_contents($this->dataFile, json_encode($embudos, JSON_PRETTY_PRINT));
                return $embudo;
            }
        }
        
        return null;
    }
    
    // Agregar landing al embudo
    public function agregarLanding($embudoId, $landingId) {
        $embudos = $this->getAll();
        
        foreach ($embudos as &$embudo) {
            if ($embudo['id'] == $embudoId) {
                if (!in_array($landingId, $embudo['pasos'])) {
                    $embudo['pasos'][] = $landingId;
                    file_put_contents($this->dataFile, json_encode($embudos, JSON_PRETTY_PRINT));
                }
                return $embudo;
            }
        }
        
        return null;
    }
    
    // Eliminar embudo
    public function eliminar($id) {
        $embudos = $this->getAll();
        $embudos = array_filter($embudos, function($embudo) use ($id) {
            return $embudo['id'] != $id;
        });
        
        $embudos = array_values($embudos);
        file_put_contents($this->dataFile, json_encode($embudos, JSON_PRETTY_PRINT));
        return true;
    }
    
    // Listar embudos por cliente (alias de getAll para consistencia)
    public function listarPorCliente($clienteId) {
        return $this->getAll($clienteId);
    }
    
    // Generar ID único
    private function generarId() {
        return uniqid('embudo_', true);
    }
    
    // Generar token único para tracking
    private function generarToken() {
        return bin2hex(random_bytes(16));
    }
}
