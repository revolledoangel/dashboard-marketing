<?php
/**
 * Modelo para manejar la configuración del sistema
 */

class Configuracion {
    private $db;
    
    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->db = getDB();
    }
    
    /**
     * Obtener un valor de configuración
     */
    public function get($clave, $valorPorDefecto = null) {
        try {
            $stmt = $this->db->prepare("SELECT valor FROM configuracion WHERE clave = ?");
            $stmt->execute([$clave]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $resultado ? $resultado['valor'] : $valorPorDefecto;
        } catch (Exception $e) {
            error_log("Error obteniendo configuración: " . $e->getMessage());
            return $valorPorDefecto;
        }
    }
    
    /**
     * Guardar un valor de configuración
     */
    public function set($clave, $valor, $descripcion = '') {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO configuracion (clave, valor, descripcion) 
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE valor = ?, descripcion = ?
            ");
            $stmt->execute([$clave, $valor, $descripcion, $valor, $descripcion]);
            return true;
        } catch (Exception $e) {
            error_log("Error guardando configuración: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener todas las configuraciones
     */
    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT * FROM configuracion ORDER BY clave");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error obteniendo configuraciones: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener la zona horaria configurada
     */
    public function getTimezone() {
        return $this->get('timezone', 'Europe/Madrid');
    }
    
    /**
     * Establecer la zona horaria del sistema
     */
    public function setTimezoneActivo() {
        $timezone = $this->getTimezone();
        date_default_timezone_set($timezone);
        return $timezone;
    }
}
