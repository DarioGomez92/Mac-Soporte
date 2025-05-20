<?php
require_once __DIR__ . '/../stripe-php/init.php';
require 'conexion.php';
session_start();

\Stripe\Stripe::setApiKey(''); /* Acuerdate de volver a poner la key despues de hacer el merge en github */

if (!isset($_GET['session_id'])) {
    echo "Error: sesión de pago no encontrada";
    exit;
}

$session = \Stripe\Checkout\Session::retrieve($_GET['session_id']);

if ($session->payment_status !== 'paid') {
    echo "Pago no realizado";
    exit;
}

// Guardar en la base de datos
$usuario_id = $_SESSION['usuario_id'] ?? null;
$carrito = $_SESSION['carrito'] ?? [];

if (!$usuario_id || empty($carrito)) {
    echo "Error con la sesión del usuario o carrito";
    exit;
}

// Obtener productos de BD
$ids = implode(',', array_map('intval', array_keys($carrito)));
$sql = "SELECT id, precio FROM productos WHERE id IN ($ids)";
$result = $conn->query($sql);

$productos = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $precio = $row['precio'];
    $cantidad = $carrito[$id];
    $subtotal = $precio * $cantidad;
    $total += $subtotal;

    $productos[] = [
        'producto_id' => $id,
        'cantidad' => $cantidad,
        'precio_unitario' => $precio
    ];
}

// Insertar compra
$stmt = $conn->prepare("INSERT INTO compras (usuario_id, total) VALUES (?, ?)");
$stmt->bind_param("id", $usuario_id, $total);
$stmt->execute();
$compra_id = $stmt->insert_id;
$stmt->close();

// Insertar detalles
$stmt = $conn->prepare("INSERT INTO detalle_compra (compra_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
foreach ($productos as $producto) {
    $stmt->bind_param("iiid", $compra_id, $producto['producto_id'], $producto['cantidad'], $producto['precio_unitario']);
    $stmt->execute();
}
$stmt->close();

unset($_SESSION['carrito']);

echo "<h1>¡Gracias por tu compra!</h1><a href='../tienda.html'>Volver a la tienda</a>";
?>
