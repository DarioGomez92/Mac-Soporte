<?php
require_once __DIR__ . '/../stripe-php/init.php';
require 'conexion.php';

/* Se inicia sesion para manejar las variables de sesion */
session_start();

/* Clave secreta de stripe */
\Stripe\Stripe::setApiKey(''); /* Acuerdate de volver a poner la key despues de hacer el merge en github */

if (!isset($_GET['session_id'])) {
    echo "Error: sesión de pago no encontrada";
    exit;
}

$session = \Stripe\Checkout\Session::retrieve($_GET['session_id']);

/* Verificamos que el estado de la sesion de pago sea paid */
if ($session->payment_status !== 'paid') {
    echo "Pago no realizado";
    exit;
}

/* Se obtiene el usuario y el carrito */
$usuario_id = $_SESSION['usuario_id'] ?? null;
$carrito = $_SESSION['carrito'] ?? [];

if (!$usuario_id || empty($carrito)) {
    echo "Error con la sesión del usuario o carrito";
    exit;
}

/* Obtenemos los productos del carrito */
$ids = implode(',', array_map('intval', array_keys($carrito)));
$sql = "SELECT id, precio FROM productos WHERE id IN ($ids)";
$result = $conexion->query($sql);

$productos = [];
$total = 0;

/* Se recorren los productos y se almacena los detalles y el total */
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

/* Insertamos la compra */
$stmt = $conexion->prepare("INSERT INTO compras (usuario_id, total) VALUES (?, ?)");
$stmt->bind_param("id", $usuario_id, $total);
$stmt->execute();
$compra_id = $stmt->insert_id;
$stmt->close();

/* Guardamos los detalles de cada producto */
$stmt = $conexion->prepare("INSERT INTO detalle_compra (compra_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
foreach ($productos as $producto) {
    $stmt->bind_param("iiid", $compra_id, $producto['producto_id'], $producto['cantidad'], $producto['precio_unitario']);
    $stmt->execute();
}
$stmt->close();

unset($_SESSION['carrito']);

echo '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Gracias por tu compra</title>
        <link rel="stylesheet" href="../css/style.css" />
    </head>
    <body>
        <div class="paginaGracias">
            <h1 class="mensajeGracias">¡Gracias por tu compra!</h1>
            <a href="../tienda.html" class="botonVolverTienda">Volver a la tienda</a>
        </div>
    </body>
    </html>
';
?>
