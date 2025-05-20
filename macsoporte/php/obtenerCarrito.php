<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

if (!isset($_SESSION['carrito'])) {
    echo json_encode([]);
    exit;
}

$json_file = __DIR__ . '/../data/productos.json';

if (!file_exists($json_file)) {
    echo json_encode(['error' => 'Archivo de productos no encontrado']);
    exit;
}

$json_data = file_get_contents($json_file);
$productos_array = json_decode($json_data, true);

if ($productos_array === null) {
    echo json_encode(['error' => 'Error al decodificar JSON']);
    exit;
}

/* Codigo para reorganizar los productos por id's */
$productos = [];
foreach ($productos_array as $producto) {
    $productos[$producto['id']] = $producto;
}

$carrito = [];

foreach ($_SESSION['carrito'] as $producto_id => $cantidad) {
    if (isset($productos[$producto_id])) {
        $carrito[] = [
            'id' => $producto_id,
            'nombre' => $productos[$producto_id]['nombre'],
            'precio' => $productos[$producto_id]['precio'],
            'cantidad' => $cantidad,
            'imagen' => $productos[$producto_id]['imagen'] ?? '',
        ];
    }
}

echo json_encode($carrito);

