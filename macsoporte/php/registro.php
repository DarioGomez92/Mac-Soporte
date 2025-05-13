<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "macsoporte_db");

// Verifica la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Recoger y limpiar datos del formulario
$nombre_completo = $conexion->real_escape_string($_POST['nombre_completo']);
$usuario = $conexion->real_escape_string($_POST['usuario']);
$correo = $conexion->real_escape_string($_POST['correo']);
$telefono = $conexion->real_escape_string($_POST['telefono']);
$contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // Encriptar la contraseña

// Verificar si el usuario o correo ya existen
$sql_verificar = "SELECT id FROM usuarios WHERE usuario = '$usuario' OR correo = '$correo'";
$resultado = $conexion->query($sql_verificar);

if ($resultado->num_rows > 0) {
    echo "El usuario o correo ya esta registrado.";
} else {
    // Insertar el nuevo usuario
    $sql_insertar = "INSERT INTO usuarios (nombre_completo, usuario, correo, telefono, contraseña)
                     VALUES ('$nombre_completo', '$usuario', '$correo', '$telefono', '$contraseña')";

    if ($conexion->query($sql_insertar) === TRUE) {
        echo "Registro exitoso. Ahora puedes iniciar sesión.";
    } else {
        echo "Error al registrar: " . $conexion->error;
    }
}

$conexion->close();
?>
