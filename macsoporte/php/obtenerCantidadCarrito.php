<?php
/* Se inicia sesion para manejar las variables de sesion */
session_start();

header('Content-Type: application/json');

/* Si el carrito existe lo convierte en un json, si no existe devuelve un json vacio */
if (isset($_SESSION['carrito'])) {
    echo json_encode($_SESSION['carrito']);
} else {
    echo json_encode([]);
}
?>

