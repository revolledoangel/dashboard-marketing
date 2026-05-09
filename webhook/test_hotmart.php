<?php
/**
 * Endpoint de prueba para webhooks de Hotmart
 * 
 * Este archivo sirve para debuggear y ver exactamente qué datos envía Hotmart
 * URL: /webhook/test_hotmart.php
 */

// Log file
$logFile = __DIR__ . '/../data/webhook_test.log';

// Obtener timestamp
$timestamp = date('Y-m-d H:i:s');

// Obtener método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Obtener headers
$headers = getallheaders();

// Obtener datos del body (raw)
$rawBody = file_get_contents('php://input');

// Intentar parsear como JSON
$jsonData = json_decode($rawBody, true);

// Obtener $_GET y $_POST
$getData = $_GET;
$postData = $_POST;

// Preparar mensaje de log
$logMessage = "\n" . str_repeat('=', 80) . "\n";
$logMessage .= "WEBHOOK TEST - $timestamp\n";
$logMessage .= str_repeat('=', 80) . "\n\n";

$logMessage .= "METHOD: $method\n\n";

$logMessage .= "HEADERS:\n";
$logMessage .= json_encode($headers, JSON_PRETTY_PRINT) . "\n\n";

$logMessage .= "RAW BODY:\n";
$logMessage .= $rawBody . "\n\n";

if ($jsonData) {
    $logMessage .= "PARSED JSON:\n";
    $logMessage .= json_encode($jsonData, JSON_PRETTY_PRINT) . "\n\n";
}

if (!empty($getData)) {
    $logMessage .= "GET PARAMS:\n";
    $logMessage .= json_encode($getData, JSON_PRETTY_PRINT) . "\n\n";
}

if (!empty($postData)) {
    $logMessage .= "POST PARAMS:\n";
    $logMessage .= json_encode($postData, JSON_PRETTY_PRINT) . "\n\n";
}

$logMessage .= str_repeat('=', 80) . "\n";

// Guardar en archivo
file_put_contents($logFile, $logMessage, FILE_APPEND);

// Responder con 200 OK y un mensaje
http_response_code(200);
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'message' => 'Webhook recibido y loggeado correctamente',
    'timestamp' => $timestamp,
    'saved_to' => $logFile
], JSON_PRETTY_PRINT);
