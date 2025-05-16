<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "macsoporte_db");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$usuario = $conexion->real_escape_string($_POST['usuario']);
$contraseñaIngresada = $_POST['contraseña'];

$sql = "SELECT id, nombre_completo, usuario, correo, contraseña FROM usuarios 
        WHERE usuario = '$usuario' OR correo = '$usuario' LIMIT 1";
$resultado = $conexion->query($sql);

$response = [];

if ($resultado->num_rows == 1) {
    $dato = $resultado->fetch_assoc();

    if (password_verify($contraseñaIngresada, $dato['contraseña'])) {
        /* verifica al usuario y mantiene la sesion iniciada */
        $_SESSION['usuario_id'] = $dato['id'];
        $_SESSION['usuario'] = $dato['usuario'];
        $_SESSION['nombre_completo'] = $dato['nombre_completo'];

        $response['status'] = 'success';
        $response['message'] = 'Inicio de sesión exitoso';
    } else {
        /* contraseña incorrecta */
        $response['status'] = 'error';
        $response['message'] = 'Contraseña incorrecta.';
    }
} else {
    /* usuario no encontrado */
    $response['status'] = 'error';
    $response['message'] = 'Usuario o correo no encontrados.';
}

echo json_encode($response);
$conexion->close();
?>