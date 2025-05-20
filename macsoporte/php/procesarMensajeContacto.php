<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Debes iniciar sesión para enviar el formulario.']);
    exit;
}

$conexion = new mysqli("localhost", "root", "", "macsoporte_db");
if ($conexion->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión a la base de datos.']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener datos reales del usuario desde la base de datos
$stmtUsuario = $conexion->prepare("SELECT nombre_completo, correo, telefono FROM usuarios WHERE id = ?");
$stmtUsuario->bind_param("i", $usuario_id);
$stmtUsuario->execute();
$stmtUsuario->bind_result($nombre, $correo, $telefono);
$stmtUsuario->fetch();
$stmtUsuario->close();

// Verificar que se hayan recibido los datos necesarios del formulario
if (!isset($_POST['modelo']) || !isset($_POST['mensaje'])) {
    echo json_encode(['status' => 'error', 'message' => 'Faltan campos del formulario.']);
    exit;
}

$modelo  = trim($_POST['modelo']);
$mensaje = trim($_POST['mensaje']);

// Validaciones básicas
if (empty($modelo) || empty($mensaje)) {
    echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
    exit;
}

// Insertar mensaje en la base de datos
$stmt = $conexion->prepare("INSERT INTO formularios_contacto (usuario_id, modelo_dispositivo, mensaje, fecha_envio) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iss", $usuario_id, $modelo, $mensaje);

if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Error al guardar el mensaje en la base de datos.']);
    $stmt->close();
    $conexion->close();
    exit;
}

$stmt->close();
$conexion->close();

// Enviar correo con PHPMailer
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'dariomlg.mlg@gmail.com';
    $mail->Password   = 'dfje ieyu wjrl sdly'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];

    $mail->setFrom($correo, $nombre);
    $mail->addAddress('newmacsoporte@gmail.com', 'Mac Soporte');

    $mail->isHTML(true);
    $mail->Subject = "Peticion de reparacion por parte de $nombre";
    $mail->Body    = "
        <h2>Nueva peticion de reparacion</h2>
        <p><strong>Nombre:</strong> $nombre</p>
        <p><strong>Correo:</strong> $correo</p>
        <p><strong>Teléfono:</strong> $telefono</p>
        <p><strong>Modelo del dispositivo:</strong> $modelo</p>
        <p><strong>Mensaje:</strong><br>$mensaje</p>
        <p><em>Enviado el: " . date("d/m/Y H:i:s") . "</em></p>
    ";
    $mail->AltBody = "Nombre: $nombre\nCorreo: $correo\nTeléfono: $telefono\nModelo: $modelo\nMensaje:\n$mensaje";

    if ($mail->send()) {
        echo json_encode(['status' => 'success', 'message' => 'Formulario enviado correctamente.']);
        exit;
    } else {
        echo json_encode(['status' => 'warning', 'message' => 'Mensaje guardado, pero no se pudo enviar el correo.', 'error' => $mail->ErrorInfo]);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Excepción en PHPMailer.', 'error' => $e->getMessage()]);
    exit;
}



