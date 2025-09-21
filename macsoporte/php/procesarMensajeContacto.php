<?php
/* Se inicia sesion para manejar las variables de sesion */
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

require_once 'conexion.php';

/* Se verifica si el usuario a iniciado sesion o no */
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Debes iniciar sesión para enviar el formulario.']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

/* Se obtienen los datos del usuario almacenados en la base de datos */
$stmtUsuario = $conexion->prepare("SELECT nombre_completo, correo, telefono FROM usuarios WHERE id = ?");
$stmtUsuario->bind_param("i", $usuario_id);
$stmtUsuario->execute();
$stmtUsuario->bind_result($nombre, $correo, $telefono);
$stmtUsuario->fetch();
$stmtUsuario->close();

/* Verifica que se haya recibido los datos del formulario */
if (!isset($_POST['modelo']) || !isset($_POST['mensaje'])) {
    echo json_encode(['status' => 'error', 'message' => 'Faltan campos del formulario.']);
    exit;
}

$modelo  = trim($_POST['modelo']);
$mensaje = trim($_POST['mensaje']);

/* Verificacion de que los datos no esten vacios */
if (empty($modelo) || empty($mensaje)) {
    echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
    exit;
}

/* Se insertan todos los datos enviados en la base de datos */
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

/* Se envia un correo con PHPMailer al correo añadido cuando el usuario mande un formulario */
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = '';
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
} catch (Exception $data) {
    echo json_encode(['status' => 'error', 'message' => 'Excepción en PHPMailer.', 'error' => $data->getMessage()]);
    exit;
}



