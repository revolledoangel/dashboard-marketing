-- Crear tabla de productos
CREATE TABLE IF NOT EXISTS productos (
    id VARCHAR(50) PRIMARY KEY,
    embudo_id VARCHAR(50) NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    webhook_token VARCHAR(100) UNIQUE NOT NULL,
    orden INT DEFAULT 999,
    activo TINYINT(1) DEFAULT 1,
    timestamp_creacion DATETIME NOT NULL,
    INDEX idx_embudo (embudo_id),
    INDEX idx_webhook (webhook_token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
