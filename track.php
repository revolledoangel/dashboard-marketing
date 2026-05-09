<?php

// Headers CORS - Permitir requests desde cualquier dominio
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// LOGGING para debugging (comentar en producción)
$logFile = __DIR__ . '/data/track_log.txt';
$logMsg = date('Y-m-d H:i:s') . ' - ' . $_SERVER['REQUEST_METHOD'] . ' - ' . ($_SERVER['HTTP_USER_AGENT'] ?? 'No UA') . "\n";
file_put_contents($logFile, $logMsg, FILE_APPEND);

// Manejar preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

require_once __DIR__ . '/models/Embudo.php';
require_once __DIR__ . '/models/Evento.php';

// Leer body JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Log del request recibido
file_put_contents($logFile, "Input: " . $input . "\n", FILE_APPEND);
file_put_contents($logFile, "Data: " . json_encode($data) . "\n\n", FILE_APPEND);

// Validar datos requeridos
if (!isset($data['token']) || !isset($data['tipo']) || !isset($data['nombre'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Faltan parámetros requeridos: token, tipo, nombre'
    ]);
    exit;
}

// Validar tipo
if (!in_array($data['tipo'], ['visita', 'evento'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Tipo inválido. Debe ser "visita" o "evento"'
    ]);
    exit;
}

// Validar token contra embudos
$embudoModel = new Embudo();
$embudo = $embudoModel->getByToken($data['token']);

if (!$embudo) {
    http_response_code(403);
    echo json_encode([
        'success' => false, 
        'message' => 'Token inválido'
    ]);
    exit;
}

// Extraer metadata
$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$referrer = $_SERVER['HTTP_REFERER'] ?? '';

// Extraer UTMs de la URL si viene en el campo 'url'
$utmParams = [];
if (isset($data['url']) && !empty($data['url'])) {
    $urlParts = parse_url($data['url']);
    if (isset($urlParts['query'])) {
        parse_str($urlParts['query'], $queryParams);
        
        // Extraer UTMs
        $utmParams['utm_source'] = $queryParams['utm_source'] ?? null;
        $utmParams['utm_medium'] = $queryParams['utm_medium'] ?? null;
        $utmParams['utm_campaign'] = $queryParams['utm_campaign'] ?? null;
        $utmParams['utm_term'] = $queryParams['utm_term'] ?? null;
        $utmParams['utm_content'] = $queryParams['utm_content'] ?? null;
    }
}

// Preparar datos del evento
$eventoData = [
    'embudo_id' => $embudo['id'],
    'tipo' => $data['tipo'],
    'nombre' => $data['nombre'],
    'url' => $data['url'] ?? '',
    'ip' => $ip,
    'user_agent' => $userAgent,
    'referrer' => $referrer,
    'utm_source' => $utmParams['utm_source'] ?? null,
    'utm_medium' => $utmParams['utm_medium'] ?? null,
    'utm_campaign' => $utmParams['utm_campaign'] ?? null,
    'utm_term' => $utmParams['utm_term'] ?? null,
    'utm_content' => $utmParams['utm_content'] ?? null
];

// Guardar evento
$eventoModel = new Evento();
$resultado = $eventoModel->create($eventoData);

if ($resultado['success']) {
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Evento registrado correctamente',
        'evento_id' => $resultado['data']['id']
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al registrar evento'
    ]);
}
