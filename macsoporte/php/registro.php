<?php
$conexion = new mysqli("localhost", "root", "", "macsoporte_db");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$nombre_completo = trim($_POST['nombre_completo']);
$usuario = trim($_POST['usuario']);
$correo = trim($_POST['correo']);
$telefono = trim($_POST['telefono']);
$contraseña = $_POST['contraseña'];

/* Verificar que no hay campos vacios */
if (empty($nombre_completo) || empty($usuario) || empty($correo) || empty($contraseña)) {
    echo "Todos los campos obligatorios deben ser completados.";
    exit;
}

/* Filtro para evitar datos gigantes */
if (strlen($usuario) > 50 || strlen($correo) > 100 || strlen($contraseña) > 30) {
    echo "Los datos ingresados exceden la longitud permitida.";
    exit;
}

/* Filtro para evitar caracteres no deseados en el nombre */
if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $nombre_completo)) {
    echo "El nombre solo debe contener letras y espacios.";
    exit;
}

/* Validar email */
if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $correo)) {
    echo "El correo electrónico no tiene un formato válido.";
    exit;
}

/* Validar teléfono */
if (!empty($telefono) && !preg_match('/^[0-9]{9,}$/', $telefono)) {
    echo "El teléfono debe contener al menos 9 dígitos numéricos.";
    exit;
}

/* Validar seguridad de la contraseña */
if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $contraseña)) {
    echo "La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula y un número.";
    exit;
}

/* Verificar si el usuario o correo ya existen */
$verificarUsuario = "SELECT id FROM usuarios WHERE usuario = ? OR correo = ?";
$verificarStmt = $conexion->prepare($verificarUsuario);
$verificarStmt->bind_param("ss", $usuario, $correo);
$verificarStmt->execute();
$verificarStmt->store_result();

if ($verificarStmt->num_rows > 0) {
    echo "El usuario o correo ya está registrado.";
    $verificarStmt->close();
    $conexion->close();
    exit;
}
$verificarStmt->close();

/* Insertar el nuevo usuario */
$contraseñaProtegida = password_hash($contraseña, PASSWORD_DEFAULT);
$insertarUsuario = "INSERT INTO usuarios (nombre_completo, usuario, correo, telefono, contraseña)
                 VALUES (?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($insertarUsuario);
$stmt->bind_param("sssss", $nombre_completo, $usuario, $correo, $telefono, $contraseñaProtegida);

if ($stmt->execute()) {
    echo "Registro exitoso. Ahora puedes iniciar sesión.";
} else {
    echo "Error al registrar: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>