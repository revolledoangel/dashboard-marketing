-- Crear tabla de ventas/conversiones de productos
CREATE TABLE IF NOT EXISTS ventas (
    id VARCHAR(50) PRIMARY KEY,
    producto_id VARCHAR(50) NOT NULL,
    embudo_id VARCHAR(50) NOT NULL,
    transaction_id VARCHAR(255),
    status VARCHAR(50) DEFAULT 'approved',
    buyer_email VARCHAR(255),
    buyer_name VARCHAR(255),
    precio DECIMAL(10,2),
    moneda VARCHAR(10) DEFAULT 'USD',
    timestamp_compra DATETIME NOT NULL,
    raw_data TEXT,
    INDEX idx_producto (producto_id),
    INDEX idx_embudo (embudo_id),
    INDEX idx_transaction (transaction_id),
    INDEX idx_timestamp (timestamp_compra)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
