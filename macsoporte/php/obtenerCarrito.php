<?php
session_start();
require 'conexion.php';

header('Content-Type: application/json');

if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo json_encode([]);
    exit;
}

$carrito = $_SESSION['carrito'];
$ids = implode(',', array_map('intval', array_keys($carrito)));

$sql = "SELECT id, descripcion, precio, imagen FROM productos WHERE id IN ($ids)";
$result = $conn->query($sql);

$productos = [];

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $productos[] = [
        'id' => $id,
        'descripcion' => $row['descripcion'],
        'precio' => number_format($row['precio'], 2, ',', '.') . ' €',
        'cantidad' => $carrito[$id],
        'imagen' => $row['imagen']
    ];
}

echo json_encode($productos);

