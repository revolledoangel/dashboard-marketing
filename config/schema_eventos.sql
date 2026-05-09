-- ============================================
-- TABLA: eventos
-- ============================================
-- Almacena todos los eventos de tracking (visitas y acciones)
-- de los embudos de conversión

CREATE TABLE IF NOT EXISTS `eventos` (
  `id` VARCHAR(50) PRIMARY KEY,
  `embudo_id` VARCHAR(50) NOT NULL,
  `tipo` ENUM('visita', 'evento') NOT NULL,
  `nombre` VARCHAR(255) NOT NULL,
  `url` TEXT,
  `timestamp` DATETIME NOT NULL,
  `ip` VARCHAR(45),
  `user_agent` TEXT,
  `referrer` TEXT,
  `utm_source` VARCHAR(255),
  `utm_medium` VARCHAR(255),
  `utm_campaign` VARCHAR(255),
  `utm_term` VARCHAR(255),
  `utm_content` VARCHAR(255),
  
  -- Índices para optimizar consultas
  INDEX idx_embudo_id (`embudo_id`),
  INDEX idx_tipo (`tipo`),
  INDEX idx_timestamp (`timestamp`),
  INDEX idx_embudo_tipo (`embudo_id`, `tipo`),
  INDEX idx_utm_campaign (`utm_campaign`),
  INDEX idx_utm_source (`utm_source`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
