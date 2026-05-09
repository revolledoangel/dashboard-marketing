<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/timezone_helper.php';

class Producto {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getDB();
    }
    
    /**
     * Obtener todos los productos de un embudo
     */
    public function getAll($embudoId = null) {
        $sql = "SELECT * FROM productos WHERE 1=1";
        $params = [];
        
        if ($embudoId) {
            $sql .= " AND embudo_id = :embudo_id";
            $params[':embudo_id'] = $embudoId;
        }
        
        $sql .= " ORDER BY orden ASC, timestamp_creacion DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        $productos = $stmt->fetchAll();
        
        // Convertir timestamps de UTC a timezone configurado
        foreach ($productos as &$producto) {
            if (isset($producto['timestamp_creacion'])) {
                $producto['timestamp_creacion'] = convertirUTCaLocal($producto['timestamp_creacion'], 'Y-m-d H:i:s');
            }
        }
        
        return $productos;
    }
    
    /**
     * Obtener producto por ID
     */
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM productos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $producto = $stmt->fetch();
        
        if ($producto && isset($producto['timestamp_creacion'])) {
            $producto['timestamp_creacion'] = convertirUTCaLocal($producto['timestamp_creacion'], 'Y-m-d H:i:s');
        }
        
        return $producto;
    }
    
    /**
     * Obtener producto por webhook token
     */
    public function getByToken($token) {
        $stmt = $this->pdo->prepare("SELECT * FROM productos WHERE webhook_token = :token");
        $stmt->execute([':token' => $token]);
        return $stmt->fetch();
    }
    
    /**
     * Crear nuevo producto
     */
    public function create($embudoId, $nombre) {
        try {
            $nuevoProducto = [
                'id' => 'producto_' . uniqid() . '.' . mt_rand(10000000, 99999999),
                'embudo_id' => $embudoId,
                'nombre' => $nombre,
                'webhook_token' => bin2hex(random_bytes(32)), // Token seguro de 64 caracteres
                'timestamp_creacion' => gmdate('Y-m-d H:i:s') // Guardar en UTC
            ];
            
            $sql = "INSERT INTO productos (id, embudo_id, nombre, webhook_token, timestamp_creacion) 
                    VALUES (:id, :embudo_id, :nombre, :webhook_token, :timestamp_creacion)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($nuevoProducto);
            
            return [
                'success' => true,
                'data' => $this->getById($nuevoProducto['id'])
            ];
        } catch (PDOException $e) {
            error_log("Error creating product: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Actualizar orden de producto
     */
    public function updateOrden($id, $orden) {
        try {
            $stmt = $this->pdo->prepare("UPDATE productos SET orden = :orden WHERE id = :id");
            $stmt->execute([
                ':id' => $id,
                ':orden' => $orden
            ]);
            
            return ['success' => true];
        } catch (PDOException $e) {
            error_log("Error updating product order: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Eliminar producto
     */
    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM productos WHERE id = :id");
            $stmt->execute([':id' => $id]);
            
            return ['success' => true];
        } catch (PDOException $e) {
            error_log("Error deleting product: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Obtener estadísticas de ventas de un producto con conversiones por página
     */
    public function getStats($productoId, $fechaInicio = null, $fechaFin = null) {
        try {
            $producto = $this->getById($productoId);
            if (!$producto) {
                return null;
            }
            
            $embudoId = $producto['embudo_id'];
            
            // Contar total de ventas
            $sqlVentas = "SELECT COUNT(*) as total, 
                          SUM(precio) as ingreso_total,
                          AVG(precio) as ticket_promedio
                          FROM ventas WHERE producto_id = :producto_id";
            $paramsVentas = [':producto_id' => $productoId];
            
            if ($fechaInicio) {
                $fechaInicioUTC = convertirLocalAUTC($fechaInicio . ' 00:00:00');
                $sqlVentas .= " AND timestamp_compra >= :fecha_inicio";
                $paramsVentas[':fecha_inicio'] = $fechaInicioUTC;
            }
            
            if ($fechaFin) {
                $fechaFinUTC = convertirLocalAUTC($fechaFin . ' 23:59:59');
                $sqlVentas .= " AND timestamp_compra <= :fecha_fin";
                $paramsVentas[':fecha_fin'] = $fechaFinUTC;
            }
            
            $stmt = $this->pdo->prepare($sqlVentas);
            $stmt->execute($paramsVentas);
            $ventasData = $stmt->fetch();
            
            $totalVentas = (int) $ventasData['total'];
            $ingresoTotal = (float) ($ventasData['ingreso_total'] ?? 0);
            $ticketPromedio = (float) ($ventasData['ticket_promedio'] ?? 0);
            
            // Obtener conteo de visitas por página
            $sqlVisitas = "SELECT nombre, COUNT(*) as total 
                          FROM eventos 
                          WHERE embudo_id = :embudo_id AND tipo = 'visita'";
            $paramsVisitas = [':embudo_id' => $embudoId];
            
            if ($fechaInicio) {
                $fechaInicioUTC = convertirLocalAUTC($fechaInicio . ' 00:00:00');
                $sqlVisitas .= " AND timestamp >= :fecha_inicio";
                $paramsVisitas[':fecha_inicio'] = $fechaInicioUTC;
            }
            
            if ($fechaFin) {
                $fechaFinUTC = convertirLocalAUTC($fechaFin . ' 23:59:59');
                $sqlVisitas .= " AND timestamp <= :fecha_fin";
                $paramsVisitas[':fecha_fin'] = $fechaFinUTC;
            }
            
            $sqlVisitas .= " GROUP BY nombre ORDER BY total DESC";
            
            $stmt = $this->pdo->prepare($sqlVisitas);
            $stmt->execute($paramsVisitas);
            $visitasPorPagina = $stmt->fetchAll();
            
            // Calcular conversión por cada página
            $conversiones = [];
            foreach ($visitasPorPagina as $visitaData) {
                $nombrePagina = $visitaData['nombre'];
                $totalVisitas = (int) $visitaData['total'];
                
                if ($totalVisitas > 0) {
                    $porcentaje = ($totalVentas / $totalVisitas) * 100;
                    $conversiones[] = [
                        'pagina' => $nombrePagina,
                        'visitas' => $totalVisitas,
                        'porcentaje' => round($porcentaje, 2)
                    ];
                }
            }
            
            return [
                'total_ventas' => $totalVentas,
                'ingreso_total' => $ingresoTotal,
                'ticket_promedio' => $ticketPromedio,
                'conversiones_por_pagina' => $conversiones
            ];
            
        } catch (PDOException $e) {
            error_log("Error getting product stats: " . $e->getMessage());
            return [
                'total_ventas' => 0,
                'ingreso_total' => 0,
                'ticket_promedio' => 0,
                'conversiones_por_pagina' => []
            ];
        }
    }
}
