<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autenticado']);
    exit;
}

$conexion = new mysqli("localhost", "root", "", "macsoporte_db");
if ($conexion->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$stmt = $conexion->prepare("SELECT nombre_completo, correo, telefono FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($fila = $resultado->fetch_assoc()) {
    echo json_encode([
        'status' => 'success',
        'nombre' => $fila['nombre_completo'],
        'correo' => $fila['correo'],
        'telefono' => $fila['telefono']
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
}

$stmt->close();
$conexion->close();
