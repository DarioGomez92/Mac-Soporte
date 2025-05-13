<?php
session_start();

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "macsoporte_db");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Recoger y limpiar datos del formulario
$usuario = $conexion->real_escape_string($_POST['usuario']);
$contraseña_ingresada = $_POST['contraseña'];

// Buscar usuario por nombre de usuario o correo
$sql = "SELECT id, nombre_completo, usuario, correo, contraseña FROM usuarios 
        WHERE usuario = '$usuario' OR correo = '$usuario' LIMIT 1";
$resultado = $conexion->query($sql);

$response = [];

if ($resultado->num_rows == 1) {
    $fila = $resultado->fetch_assoc();

    // Verificar contraseña
    if (password_verify($contraseña_ingresada, $fila['contraseña'])) {
        // Autenticación exitosa → guardar sesión
        $_SESSION['usuario_id'] = $fila['id'];
        $_SESSION['usuario'] = $fila['usuario'];
        $_SESSION['nombre_completo'] = $fila['nombre_completo'];

        // Responder con un JSON indicando éxito
        $response['status'] = 'success';
        $response['message'] = 'Inicio de sesión exitoso';
    } else {
        // Contraseña incorrecta
        $response['status'] = 'error';
        $response['message'] = 'Contraseña incorrecta.';
    }
} else {
    // Usuario no encontrado
    $response['status'] = 'error';
    $response['message'] = 'Usuario o correo no encontrados.';
}

echo json_encode($response);
$conexion->close();
?>




