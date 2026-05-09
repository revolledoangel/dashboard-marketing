<?php

require_once __DIR__ . '/../config/database.php';

class Evento {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getDB();
    }
    
    // Obtener todos los eventos (con filtro opcional por embudo)
    public function getAll($embudoId = null) {
        if ($embudoId) {
            $stmt = $this->pdo->prepare("
                SELECT * FROM eventos 
                WHERE embudo_id = :embudo_id 
                ORDER BY timestamp DESC
            ");
            $stmt->execute([':embudo_id' => $embudoId]);
        } else {
            $stmt = $this->pdo->query("SELECT * FROM eventos ORDER BY timestamp DESC");
        }
        
        return $stmt->fetchAll();
    }
    
    // Obtener evento por ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM eventos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    // Crear nuevo evento
    public function create($data) {
        try {
            $nuevoEvento = [
                'id' => 'evento_' . uniqid() . '.' . mt_rand(10000000, 99999999),
                'embudo_id' => $data['embudo_id'],
                'tipo' => $data['tipo'], // 'visita' o 'evento'
                'nombre' => $data['nombre'], // 'home', 'click_boton', etc.
                'url' => $data['url'] ?? '',
                'timestamp' => date('Y-m-d H:i:s'),
                'ip' => $data['ip'] ?? null,
                'user_agent' => $data['user_agent'] ?? null,
                'referrer' => $data['referrer'] ?? null,
                'utm_source' => $data['utm_source'] ?? null,
                'utm_medium' => $data['utm_medium'] ?? null,
                'utm_campaign' => $data['utm_campaign'] ?? null,
                'utm_term' => $data['utm_term'] ?? null,
                'utm_content' => $data['utm_content'] ?? null
            ];
            
            $stmt = $this->pdo->prepare("
                INSERT INTO eventos (
                    id, embudo_id, tipo, nombre, url, timestamp,
                    ip, user_agent, referrer,
                    utm_source, utm_medium, utm_campaign, utm_term, utm_content
                ) VALUES (
                    :id, :embudo_id, :tipo, :nombre, :url, :timestamp,
                    :ip, :user_agent, :referrer,
                    :utm_source, :utm_medium, :utm_campaign, :utm_term, :utm_content
                )
            ");
            
            $stmt->execute($nuevoEvento);
            
            return ['success' => true, 'message' => 'Evento registrado', 'data' => $nuevoEvento];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error al registrar evento: ' . $e->getMessage()];
        }
    }
    
    // Eliminar evento
    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM eventos WHERE id = :id");
            $stmt->execute([':id' => $id]);
            
            if ($stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'Evento eliminado'];
            }
            
            return ['success' => false, 'message' => 'Evento no encontrado'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error al eliminar evento: ' . $e->getMessage()];
        }
    }
    
    // Eliminar todos los eventos de un embudo
    public function deleteByEmbudo($embudoId) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM eventos WHERE embudo_id = :embudo_id");
            $stmt->execute([':embudo_id' => $embudoId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Obtener estadísticas de un embudo
    public function getStats($embudoId) {
        // Total de eventos
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM eventos WHERE embudo_id = :embudo_id");
        $stmt->execute([':embudo_id' => $embudoId]);
        $total = $stmt->fetchColumn();
        
        // Visitas
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as visitas FROM eventos WHERE embudo_id = :embudo_id AND tipo = 'visita'");
        $stmt->execute([':embudo_id' => $embudoId]);
        $visitas = $stmt->fetchColumn();
        
        // Eventos (acciones)
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as eventos FROM eventos WHERE embudo_id = :embudo_id AND tipo = 'evento'");
        $stmt->execute([':embudo_id' => $embudoId]);
        $eventos = $stmt->fetchColumn();
        
        // Por nombre
        $stmt = $this->pdo->prepare("
            SELECT nombre, COUNT(*) as cantidad 
            FROM eventos 
            WHERE embudo_id = :embudo_id 
            GROUP BY nombre
            ORDER BY cantidad DESC
        ");
        $stmt->execute([':embudo_id' => $embudoId]);
        $porNombre = [];
        while ($row = $stmt->fetch()) {
            $porNombre[$row['nombre']] = (int)$row['cantidad'];
        }
        
        return [
            'total' => (int)$total,
            'visitas' => (int)$visitas,
            'eventos' => (int)$eventos,
            'por_nombre' => $porNombre
        ];
    }
}
