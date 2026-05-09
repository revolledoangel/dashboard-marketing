<?php
/**
 * Controlador para configuración del sistema
 */

require_once __DIR__ . '/../models/Configuracion.php';

class ConfiguracionController {
    private $model;
    
    public function __construct() {
        $this->model = new Configuracion();
    }
    
    /**
     * Obtener configuración
     */
    public function obtener() {
        header('Content-Type: application/json');
        
        try {
            $clave = $_GET['clave'] ?? '';
            
            if (empty($clave)) {
                // Devolver toda la configuración
                $config = $this->model->getAll();
                echo json_encode(['success' => true, 'data' => $config]);
            } else {
                // Devolver valor específico
                $valor = $this->model->get($clave);
                echo json_encode(['success' => true, 'data' => ['clave' => $clave, 'valor' => $valor]]);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Guardar configuración
     */
    public function guardar() {
        header('Content-Type: application/json');
        
        try {
            $clave = $_POST['clave'] ?? '';
            $valor = $_POST['valor'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            
            if (empty($clave)) {
                echo json_encode(['success' => false, 'message' => 'Clave requerida']);
                return;
            }
            
            $resultado = $this->model->set($clave, $valor, $descripcion);
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Configuración guardada correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al guardar configuración']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
