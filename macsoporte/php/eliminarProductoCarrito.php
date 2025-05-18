<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo "Debes iniciar sesión para modificar el carrito.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $producto_id = $_POST['producto_id'];

    if (isset($_SESSION['carrito'][$producto_id])) {
        unset($_SESSION['carrito'][$producto_id]);
        echo "Producto eliminado del carrito.";
    } else {
        echo "Producto no encontrado en el carrito.";
    }
}
?>

