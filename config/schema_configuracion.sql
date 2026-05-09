-- Tabla de configuración del sistema
CREATE TABLE IF NOT EXISTS configuracion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(50) NOT NULL UNIQUE,
    valor TEXT NOT NULL,
    descripcion VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_clave (clave)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar timezone por defecto (Madrid)
INSERT INTO configuracion (clave, valor, descripcion) 
VALUES ('timezone', 'Europe/Madrid', 'Zona horaria para mostrar fechas en el panel')
ON DUPLICATE KEY UPDATE valor = 'Europe/Madrid';
