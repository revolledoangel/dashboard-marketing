<?php
/**
 * Controlador para manejar el orden personalizado del funnel
 */

require_once __DIR__ . '/../config/database.php';

class OrdenFunnelController {
    
    /**
     * Guardar el orden del funnel con eventos anidados
     * POST: embudo_id, estructura (array con visitas y eventos anidados)
     * Formato: [
     *   {tipo: 'visita', nombre: 'Home', eventos: [
     *     {tipo: 'evento', nombre: 'Click CTA'}
     *   ]},
     *   {tipo: 'visita', nombre: 'Checkout', eventos: []}
     * ]
     */
    public function guardar() {
        header('Content-Type: application/json');
        
        try {
            // Obtener datos del POST
            $embudoId = $_POST['embudo_id'] ?? '';
            $estructura = json_decode($_POST['estructura'] ?? '[]', true);
            
            if (!$embudoId || !is_array($estructura)) {
                echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
                return;
            }
            
            $db = getDB();
            
            // Iniciar transacción
            $db->beginTransaction();
            
            // Eliminar orden anterior de este embudo
            $stmt = $db->prepare("DELETE FROM orden_funnel WHERE embudo_id = ?");
            $stmt->execute([$embudoId]);
            
            // Insertar nuevo orden
            $stmt = $db->prepare("
                INSERT INTO orden_funnel (embudo_id, tipo, pagina_nombre, pagina_padre, orden) 
                VALUES (?, ?, ?, ?, ?)
            ");
            
            $ordenGlobal = 0;
            
            foreach ($estructura as $item) {
                $tipo = $item['tipo'] ?? 'visita';
                $nombre = $item['nombre'] ?? '';
                $eventos = $item['eventos'] ?? [];
                
                // Insertar visita principal
                $stmt->execute([$embudoId, $tipo, $nombre, null, $ordenGlobal++]);
                
                // Insertar eventos hijos si existen
                foreach ($eventos as $evento) {
                    $nombreEvento = $evento['nombre'] ?? '';
                    $stmt->execute([$embudoId, 'evento', $nombreEvento, $nombre, $ordenGlobal++]);
                }
            }
            
            // Confirmar transacción
            $db->commit();
            
            echo json_encode([
                'success' => true, 
                'message' => 'Orden guardado correctamente',
                'data' => [
                    'embudo_id' => $embudoId,
                    'items' => $ordenGlobal
                ]
            ]);
            
        } catch (Exception $e) {
            if (isset($db)) {
                $db->rollBack();
            }
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Obtener el orden guardado del funnel con estructura anidada
     * GET: embudo_id
     */
    public function obtener() {
        header('Content-Type: application/json');
        
        try {
            $embudoId = $_GET['embudo_id'] ?? '';
            
            if (!$embudoId) {
                echo json_encode(['success' => false, 'message' => 'embudo_id requerido']);
                return;
            }
            
            $db = getDB();
            
            $stmt = $db->prepare("
                SELECT tipo, pagina_nombre, pagina_padre, orden 
                FROM orden_funnel 
                WHERE embudo_id = ? 
                ORDER BY orden ASC
            ");
            $stmt->execute([$embudoId]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Construir estructura anidada
            $estructura = [];
            $visitaActualIndex = -1;
            
            foreach ($items as $item) {
                if ($item['tipo'] === 'visita' && $item['pagina_padre'] === null) {
                    // Nueva visita principal
                    $estructura[] = [
                        'tipo' => 'visita',
                        'nombre' => $item['pagina_nombre'],
                        'eventos' => []
                    ];
                    $visitaActualIndex = count($estructura) - 1;
                } elseif ($item['tipo'] === 'evento' && $visitaActualIndex >= 0) {
                    // Evento hijo de la visita actual
                    $estructura[$visitaActualIndex]['eventos'][] = [
                        'tipo' => 'evento',
                        'nombre' => $item['pagina_nombre']
                    ];
                }
            }
            
            echo json_encode([
                'success' => true,
                'data' => $estructura
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
