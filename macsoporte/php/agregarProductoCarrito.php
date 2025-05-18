<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    echo "Debes iniciar sesión para añadir productos al carrito.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    if (isset($_SESSION['carrito'][$producto_id])) {
        $_SESSION['carrito'][$producto_id] += $cantidad;
    } else {
        $_SESSION['carrito'][$producto_id] = $cantidad;
    }

    echo "Producto añadido al carrito.";
}
?>
