-- Tabla para guardar el orden personalizado del funnel
CREATE TABLE IF NOT EXISTS orden_funnel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    embudo_id INT NOT NULL,
    tipo ENUM('visita', 'evento') NOT NULL DEFAULT 'visita',
    pagina_nombre VARCHAR(255) NOT NULL,
    pagina_padre VARCHAR(255) NULL,
    orden INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Índices para optimizar búsquedas
    INDEX idx_embudo_id (embudo_id),
    INDEX idx_embudo_orden (embudo_id, orden),
    INDEX idx_tipo (tipo),
    INDEX idx_pagina_padre (pagina_padre),
    
    -- Evitar duplicados: cada item solo tiene un registro por embudo
    UNIQUE KEY unique_embudo_item (embudo_id, pagina_nombre, COALESCE(pagina_padre, ''))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
