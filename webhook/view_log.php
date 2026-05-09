<?php
/**
 * Visualizador de logs de webhook de test
 */

$logFile = __DIR__ . '/../data/webhook_test.log';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webhook Test Log</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { padding: 20px; background: #f4f6f9; }
        .log-viewer {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            white-space: pre-wrap;
            word-wrap: break-word;
            max-height: 80vh;
            overflow-y: auto;
        }
        .header {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="header">
            <h1><i class="fas fa-bug"></i> Webhook Test Logger</h1>
            <p class="mb-0">
                <strong>URL de prueba:</strong> 
                <code><?php echo (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/webhook/test_hotmart.php'; ?></code>
            </p>
            <div class="mt-3">
                <a href="?refresh=1" class="btn btn-primary">
                    <i class="fas fa-sync"></i> Recargar
                </a>
                <a href="?clear=1" class="btn btn-danger" onclick="return confirm('¿Limpiar todo el log?')">
                    <i class="fas fa-trash"></i> Limpiar Log
                </a>
            </div>
        </div>

        <?php
        // Clear log if requested
        if (isset($_GET['clear'])) {
            file_put_contents($logFile, '');
            echo '<div class="alert alert-success">Log limpiado correctamente</div>';
        }
        ?>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Últimas peticiones recibidas</h3>
            </div>
            <div class="card-body p-0">
                <div class="log-viewer">
<?php
if (file_exists($logFile)) {
    $content = file_get_contents($logFile);
    if (empty(trim($content))) {
        echo "=== NO HAY LOGS AÚN ===\n\n";
        echo "Envía una petición de prueba desde Hotmart a la URL de arriba.\n";
    } else {
        echo htmlspecialchars($content);
    }
} else {
    echo "=== ARCHIVO DE LOG NO EXISTE ===\n\n";
    echo "El archivo se creará automáticamente cuando llegue la primera petición.\n";
}
?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
