<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/timezone_helper.php';

class Venta {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getDB();
    }
    
    /**
     * Crear nueva venta desde webhook
     */
    public function create($productoId, $embudoId, $webhookData) {
        try {
            // Extraer datos relevantes del webhook
            $transactionId = $webhookData['purchase']['transaction'] ?? 
                            $webhookData['transaction'] ?? 
                            uniqid('trans_');
            
            $buyerEmail = $webhookData['buyer']['email'] ?? 
                         $webhookData['email'] ?? 
                         null;
            
            $buyerName = $webhookData['buyer']['name'] ?? 
                        $webhookData['name'] ?? 
                        null;
            
            $precio = $webhookData['purchase']['price']['value'] ?? 
                     $webhookData['price'] ?? 
                     0;
            
            $moneda = $webhookData['purchase']['price']['currency'] ?? 
                     $webhookData['currency'] ?? 
                     'USD';
            
            $status = $webhookData['purchase']['status'] ?? 
                     $webhookData['status'] ?? 
                     'approved';
            
            $nuevaVenta = [
                'id' => 'venta_' . uniqid() . '.' . mt_rand(10000000, 99999999),
                'producto_id' => $productoId,
                'embudo_id' => $embudoId,
                'transaction_id' => $transactionId,
                'status' => $status,
                'buyer_email' => $buyerEmail,
                'buyer_name' => $buyerName,
                'precio' => $precio,
                'moneda' => $moneda,
                'timestamp_compra' => gmdate('Y-m-d H:i:s'), // UTC
                'raw_data' => json_encode($webhookData, JSON_PRETTY_PRINT)
            ];
            
            $sql = "INSERT INTO ventas (id, producto_id, embudo_id, transaction_id, status, 
                    buyer_email, buyer_name, precio, moneda, timestamp_compra, raw_data) 
                    VALUES (:id, :producto_id, :embudo_id, :transaction_id, :status, 
                    :buyer_email, :buyer_name, :precio, :moneda, :timestamp_compra, :raw_data)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($nuevaVenta);
            
            return [
                'success' => true,
                'data' => $this->getById($nuevaVenta['id'])
            ];
        } catch (PDOException $e) {
            error_log("Error creating venta: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Obtener venta por ID
     */
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM ventas WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $venta = $stmt->fetch();
        
        if ($venta && isset($venta['timestamp_compra'])) {
            $venta['timestamp_compra'] = convertirUTCaLocal($venta['timestamp_compra'], 'Y-m-d H:i:s');
        }
        
        return $venta;
    }
    
    /**
     * Contar ventas de un producto
     */
    public function contarPorProducto($productoId, $fechaInicio = null, $fechaFin = null) {
        $sql = "SELECT COUNT(*) as total FROM ventas WHERE producto_id = :producto_id";
        $params = [':producto_id' => $productoId];
        
        if ($fechaInicio) {
            $fechaInicioUTC = convertirLocalAUTC($fechaInicio . ' 00:00:00');
            $sql .= " AND timestamp_compra >= :fecha_inicio";
            $params[':fecha_inicio'] = $fechaInicioUTC;
        }
        
        if ($fechaFin) {
            $fechaFinUTC = convertirLocalAUTC($fechaFin . ' 23:59:59');
            $sql .= " AND timestamp_compra <= :fecha_fin";
            $params[':fecha_fin'] = $fechaFinUTC;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        
        return (int) $result['total'];
    }
    
    /**
     * Obtener todas las ventas de un producto
     */
    public function getPorProducto($productoId, $fechaInicio = null, $fechaFin = null) {
        $sql = "SELECT * FROM ventas WHERE producto_id = :producto_id";
        $params = [':producto_id' => $productoId];
        
        if ($fechaInicio) {
            $fechaInicioUTC = convertirLocalAUTC($fechaInicio . ' 00:00:00');
            $sql .= " AND timestamp_compra >= :fecha_inicio";
            $params[':fecha_inicio'] = $fechaInicioUTC;
        }
        
        if ($fechaFin) {
            $fechaFinUTC = convertirLocalAUTC($fechaFin . ' 23:59:59');
            $sql .= " AND timestamp_compra <= :fecha_fin";
            $params[':fecha_fin'] = $fechaFinUTC;
        }
        
        $sql .= " ORDER BY timestamp_compra DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        $ventas = $stmt->fetchAll();
        
        // Convertir timestamps
        foreach ($ventas as &$venta) {
            if (isset($venta['timestamp_compra'])) {
                $venta['timestamp_compra'] = convertirUTCaLocal($venta['timestamp_compra'], 'Y-m-d H:i:s');
            }
        }
        
        return $ventas;
    }
}
