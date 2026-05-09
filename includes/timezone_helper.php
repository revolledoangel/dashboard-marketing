<?php
/**
 * Funciones helper para manejo de fechas y timezones
 */

require_once __DIR__ . '/../models/Configuracion.php';

/**
 * Obtener timezone configurado del sistema
 */
function getTimezoneConfig() {
    static $timezone = null;
    
    if ($timezone === null) {
        $config = new Configuracion();
        $timezone = $config->getTimezone();
    }
    
    return $timezone;
}

/**
 * Establecer timezone del sistema
 */
function setTimezoneConfig() {
    $timezone = getTimezoneConfig();
    date_default_timezone_set($timezone);
    return $timezone;
}

/**
 * Convertir fecha UTC a timezone configurado
 * 
 * @param string $fechaUTC Fecha en formato UTC (ej: "2026-05-08 03:00:00")
 * @param string $formato Formato de salida (default: "Y-m-d H:i:s")
 * @return string Fecha convertida al timezone configurado
 */
function convertirUTCaLocal($fechaUTC, $formato = 'Y-m-d H:i:s') {
    try {
        if (empty($fechaUTC)) {
            return '';
        }
        
        // Crear objeto DateTime en UTC
        $fecha = new DateTime($fechaUTC, new DateTimeZone('UTC'));
        
        // Convertir al timezone configurado
        $timezone = getTimezoneConfig();
        $fecha->setTimezone(new DateTimeZone($timezone));
        
        return $fecha->format($formato);
    } catch (Exception $e) {
        error_log("Error convirtiendo fecha UTC a local: " . $e->getMessage());
        return $fechaUTC;
    }
}

/**
 * Convertir fecha local a UTC para guardar en base de datos
 * 
 * @param string $fechaLocal Fecha en timezone local
 * @return string Fecha en UTC
 */
function convertirLocalAUTC($fechaLocal) {
    try {
        if (empty($fechaLocal)) {
            return null;
        }
        
        // Crear objeto DateTime en timezone configurado
        $timezone = getTimezoneConfig();
        $fecha = new DateTime($fechaLocal, new DateTimeZone($timezone));
        
        // Convertir a UTC
        $fecha->setTimezone(new DateTimeZone('UTC'));
        
        return $fecha->format('Y-m-d H:i:s');
    } catch (Exception $e) {
        error_log("Error convirtiendo fecha local a UTC: " . $e->getMessage());
        return $fechaLocal;
    }
}

/**
 * Obtener fecha actual en el timezone configurado
 * 
 * @param string $formato Formato de salida
 * @return string Fecha actual
 */
function getFechaActualLocal($formato = 'Y-m-d H:i:s') {
    setTimezoneConfig();
    return date($formato);
}

/**
 * Obtener fecha actual en UTC (para guardar en BD)
 * 
 * @return string Fecha actual en UTC
 */
function getFechaActualUTC() {
    return gmdate('Y-m-d H:i:s');
}

/**
 * Formatear fecha para mostrar en el panel
 * 
 * @param string $fechaUTC Fecha en UTC desde BD
 * @param string $formato Formato legible (default, short, long, full)
 * @return string Fecha formateada
 */
function formatearFecha($fechaUTC, $formato = 'default') {
    $formatos = [
        'default' => 'Y-m-d H:i:s',
        'short' => 'd/m/Y H:i',
        'long' => 'd/m/Y H:i:s',
        'date' => 'd/m/Y',
        'time' => 'H:i:s',
        'datetime' => 'd/m/Y H:i'
    ];
    
    $formatoSalida = $formatos[$formato] ?? $formato;
    return convertirUTCaLocal($fechaUTC, $formatoSalida);
}

/**
 * Obtener lista de zonas horarias comunes
 */
function getTimezonesDisponibles() {
    return [
        'Europe/Madrid' => 'Europa/Madrid (UTC+1/+2)',
        'America/New_York' => 'América/Nueva York (UTC-5/-4)',
        'America/Los_Angeles' => 'América/Los Ángeles (UTC-8/-7)',
        'America/Chicago' => 'América/Chicago (UTC-6/-5)',
        'America/Denver' => 'América/Denver (UTC-7/-6)',
        'America/Mexico_City' => 'América/Ciudad de México (UTC-6/-5)',
        'America/Bogota' => 'América/Bogotá (UTC-5)',
        'America/Lima' => 'América/Lima (UTC-5)',
        'America/Santiago' => 'América/Santiago (UTC-3/-4)',
        'America/Argentina/Buenos_Aires' => 'América/Buenos Aires (UTC-3)',
        'Europe/London' => 'Europa/Londres (UTC+0/+1)',
        'Europe/Paris' => 'Europa/París (UTC+1/+2)',
        'Europe/Berlin' => 'Europa/Berlín (UTC+1/+2)',
        'Europe/Rome' => 'Europa/Roma (UTC+1/+2)',
        'Asia/Dubai' => 'Asia/Dubái (UTC+4)',
        'Asia/Tokyo' => 'Asia/Tokio (UTC+9)',
        'Asia/Shanghai' => 'Asia/Shanghái (UTC+8)',
        'Australia/Sydney' => 'Oceanía/Sídney (UTC+10/+11)',
        'UTC' => 'UTC (Tiempo Universal Coordinado)'
    ];
}
