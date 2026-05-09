<?php

require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Venta.php';

class ProductoController {
    private $productoModel;
    private $ventaModel;
    
    public function __construct() {
        $this->productoModel = new Producto();
        $this->ventaModel = new Venta();
    }
    
    /**
     * Listar productos de un embudo con estadísticas
     */
    public function listar() {
        $embudoId = $_GET['embudo_id'] ?? null;
        $fechaInicio = $_GET['fecha_inicio'] ?? null;
        $fechaFin = $_GET['fecha_fin'] ?? null;
        
        $productos = $this->productoModel->getAll($embudoId);
        
        // Agregar estadísticas a cada producto
        foreach ($productos as &$producto) {
            $producto['stats'] = $this->productoModel->getStats(
                $producto['id'], 
                $fechaInicio, 
                $fechaFin
            );
        }
        
        echo json_encode([
            'success' => true,
            'data' => $productos
        ]);
    }
    
    /**
     * Crear nuevo producto
     */
    public function crear() {
        $embudoId = $_POST['embudo_id'] ?? null;
        $nombre = $_POST['nombre'] ?? null;
        
        if (!$embudoId || !$nombre) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Faltan parámetros requeridos'
            ]);
            return;
        }
        
        $resultado = $this->productoModel->create($embudoId, $nombre);
        
        if ($resultado['success']) {
            echo json_encode($resultado);
        } else {
            http_response_code(500);
            echo json_encode($resultado);
        }
    }
    
    /**
     * Actualizar orden de productos
     */
    public function actualizarOrden() {
        $orden = json_decode(file_get_contents('php://input'), true);
        
        if (!is_array($orden)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Formato de datos inválido'
            ]);
            return;
        }
        
        $success = true;
        foreach ($orden as $index => $productoId) {
            $resultado = $this->productoModel->updateOrden($productoId, $index);
            if (!$resultado['success']) {
                $success = false;
                break;
            }
        }
        
        echo json_encode(['success' => $success]);
    }
    
    /**
     * Eliminar producto
     */
    public function eliminar() {
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        
        if (!$id) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'ID no proporcionado'
            ]);
            return;
        }
        
        $resultado = $this->productoModel->delete($id);
        echo json_encode($resultado);
    }
    
    /**
     * Webhook para recibir notificaciones de Hotmart
     */
    public function webhook() {
        $token = $_GET['token'] ?? null;
        
        if (!$token) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Token no proporcionado'
            ]);
            return;
        }
        
        // Verificar que el producto existe
        $producto = $this->productoModel->getByToken($token);
        
        if (!$producto) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'Token inválido'
            ]);
            return;
        }
        
        // Obtener datos del webhook
        $rawBody = file_get_contents('php://input');
        $data = json_decode($rawBody, true);
        
        // Log del webhook recibido
        $this->logWebhook($producto, $data, $rawBody);
        
        // Guardar venta en la base de datos
        if ($data && is_array($data)) {
            $resultadoVenta = $this->ventaModel->create(
                $producto['id'], 
                $producto['embudo_id'], 
                $data
            );
            
            if ($resultadoVenta['success']) {
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'message' => 'Webhook recibido y venta registrada',
                    'producto' => $producto['nombre'],
                    'venta_id' => $resultadoVenta['data']['id']
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'error' => 'Error al guardar venta: ' . $resultadoVenta['error']
                ]);
            }
        } else {
            // Sin datos válidos, solo confirmamos recepción
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Webhook recibido (sin datos válidos)',
                'producto' => $producto['nombre']
            ]);
        }
    }
    
    /**
     * Log de webhook recibido
     */
    private function logWebhook($producto, $data, $rawBody) {
        $logFile = __DIR__ . '/../data/webhook_producto_' . $producto['id'] . '.log';
        
        $logMessage = "\n" . str_repeat('=', 80) . "\n";
        $logMessage .= "WEBHOOK RECIBIDO - " . date('Y-m-d H:i:s') . "\n";
        $logMessage .= "Producto: " . $producto['nombre'] . " (ID: " . $producto['id'] . ")\n";
        $logMessage .= str_repeat('=', 80) . "\n\n";
        
        $logMessage .= "RAW DATA:\n";
        $logMessage .= $rawBody . "\n\n";
        
        if ($data) {
            $logMessage .= "PARSED DATA:\n";
            $logMessage .= json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
        }
        
        $logMessage .= str_repeat('=', 80) . "\n";
        
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}
