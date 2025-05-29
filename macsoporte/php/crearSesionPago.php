<?php
/* Se inicia sesion para manejar las variables de sesion */
session_start();

require 'conexion.php';
require_once __DIR__ . '/../stripe-php/init.php';

/* Clave secreta de stripe */
\Stripe\Stripe::setApiKey(''); /* Acuerdate de volver a poner la key despues de hacer el merge en github */

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    http_response_code(400);
    echo "No autenticado o carrito vacío";
    exit;
}

/* Se estraen los ids de los productos del carrito */
$carrito = $_SESSION['carrito'];
$ids = implode(',', array_map('intval', array_keys($carrito)));

$sql = "SELECT id, descripcion, precio FROM productos WHERE id IN ($ids)";
$resultado = $conexion->query($sql);

$items = [];

while ($row = $resultado->fetch_assoc()) {
    $id = $row['id'];
    $descripcion = $row['descripcion'];
    $precio = $row['precio'];
    $cantidad = $carrito[$id];

    $items[] = [
        'price_data' => [
            'currency' => 'eur',
            'product_data' => [
                'name' => $descripcion,
            ],
            'unit_amount' => intval($precio * 100),
        ],
        'quantity' => $cantidad,
    ];
}

/* Sesion de pago con Stripe */
$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => $items,
    'mode' => 'payment',
    'success_url' => 'http://localhost/macsoporte/php/exitoPago.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'http://localhost/macsoporte/tienda.html',
]);

header("Location: " . $session->url);
exit;