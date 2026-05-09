<?php
/**
 * Script para crear usuario admin
 */

require_once __DIR__ . '/../models/Usuario.php';

echo "=== CREANDO USUARIO ADMIN ===\n\n";

$usuarioModel = new Usuario();

// Verificar si ya existe
$existente = $usuarioModel->getByEmail('admin');
if ($existente) {
    echo "⚠️ El usuario 'admin' ya existe\n";
    echo "ID: " . $existente['id'] . "\n";
    echo "Nombre: " . $existente['nombre'] . "\n";
    echo "Email: " . $existente['email'] . "\n";
    exit;
}

// Crear el usuario admin
$resultado = $usuarioModel->crear('Administrador', 'admin', 'admin');

if ($resultado['success']) {
    echo "✅ Usuario admin creado exitosamente\n\n";
    echo "Credenciales:\n";
    echo "  Usuario: admin\n";
    echo "  Contraseña: admin\n\n";
    echo "ID: " . $resultado['data']['id'] . "\n";
    echo "Nombre: " . $resultado['data']['nombre'] . "\n";
    echo "Email: " . $resultado['data']['email'] . "\n";
    echo "Fecha creación: " . $resultado['data']['fecha_creacion'] . "\n";
} else {
    echo "❌ Error: " . $resultado['message'] . "\n";
}
