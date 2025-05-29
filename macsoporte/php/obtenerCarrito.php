<?php
/* Se inicia sesion para manejar las variables de sesion */
session_start();

require 'conexion.php';

header('Content-Type: application/json');

/* Verifica si hay carrito y que no este vacio */
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo json_encode([]);
    exit;
}

/* Guardo el carrito y se extrae los ids de los productos del carrito */
$carrito = $_SESSION['carrito'];
$ids = implode(',', array_map('intval', array_keys($carrito)));

$sql = "SELECT id, descripcion, precio, imagen FROM productos WHERE id IN ($ids)";
$result = $conexion->query($sql);

$productos = [];

/* Se recorre cada id y se guarda en productos sus datos */
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

