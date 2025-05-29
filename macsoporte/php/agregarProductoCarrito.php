<?php
/* Se inicia sesion para manejar las variables de sesion */
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo "Debes iniciar sesión para añadir productos al carrito.";
    exit;
}

/* Se obtiene el id del producto y la cantidad, si no hay carrito lo crea y añade, si existe carrito suma la cantidad si el producto existe si no lo añade nuevo */
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
