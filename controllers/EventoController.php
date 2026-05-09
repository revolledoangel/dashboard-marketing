<?php

require_once __DIR__ . '/../models/Evento.php';

class EventoController {
    private $eventoModel;
    
    public function __construct() {
        $this->eventoModel = new Evento();
    }
    
    // Listar eventos
    public function listar() {
        header('Content-Type: application/json');
        $embudoId = $_GET['embudo_id'] ?? null;
        $fechaInicio = $_GET['fecha_inicio'] ?? null;
        $fechaFin = $_GET['fecha_fin'] ?? null;
        
        // Debug logging
        $logFile = __DIR__ . '/../data/evento_debug.txt';
        $logMsg = date('Y-m-d H:i:s') . " - Listar eventos - embudo_id: " . ($embudoId ?? 'null') . 
                  " - fecha_inicio: " . ($fechaInicio ?? 'null') . 
                  " - fecha_fin: " . ($fechaFin ?? 'null') . "\n";
        file_put_contents($logFile, $logMsg, FILE_APPEND);
        
        $eventos = $this->eventoModel->getAll($embudoId, $fechaInicio, $fechaFin);
        
        // Log resultados
        $logMsg = "Eventos encontrados: " . count($eventos) . "\n" . json_encode($eventos, JSON_PRETTY_PRINT) . "\n\n";
        file_put_contents($logFile, $logMsg, FILE_APPEND);
        
        echo json_encode($eventos);
    }
    
    // Obtener estadísticas de un embudo
    public function estadisticas() {
        header('Content-Type: application/json');
        $embudoId = $_GET['embudo_id'] ?? null;
        
        if (!$embudoId) {
            echo json_encode(['success' => false, 'message' => 'ID de embudo requerido']);
            return;
        }
        
        echo json_encode($this->eventoModel->getStats($embudoId));
    }
    
    // Eliminar evento
    public function eliminar() {
        header('Content-Type: application/json');
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID requerido']);
            return;
        }
        
        $resultado = $this->eventoModel->delete($id);
        echo json_encode($resultado);
    }
}
