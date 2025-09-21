<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

require 'conexion.php';

$correo = trim($_POST['correo_recuperacion']);

/* Validacion de correo vacio */
if (empty($correo)) {
    echo "Por favor, introduce tu correo electrónico.";
    exit;
}

/* Validacion de correo electronico */
if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $correo)) {
    echo "El correo electrónico no tiene un formato válido.";
    exit;
}

/* Comprueba si el correo existe en la base de datos */
$stmt = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "No existe ningún usuario registrado con ese correo.";
    $stmt->close();
    $conexion->close();
    exit;
}

/* Genera un token y lo guarda en la base de datos */
$token = bin2hex(random_bytes(16));
$fecha = date("Y-m-d H:i:s");

$update = $conexion->prepare("UPDATE usuarios SET token_recuperacion = ?, fecha_token = ? WHERE correo = ?");
$update->bind_param("sss", $token, $fecha, $correo);

/* Crea un enlace para que el usuario cambie su contraseña y lo envia por correo */
if ($update->execute()) {
    $enlace = "http://localhost/macsoporte/restablecer.php?token=$token";

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

        $mail->setFrom('', 'Mac Soporte');
        $mail->addAddress($correo);
        $mail->isHTML(true);
        $mail->Subject = "Recuperacion de contraseña - Mac Soporte";
        $mail->Body    = "Haz clic en este enlace para restablecer tu contraseña:<br><a href='$enlace'>$enlace</a>";
        $mail->AltBody = "Haz clic en este enlace para restablecer tu contraseña:\n$enlace";

        $mail->send();
        echo "Te hemos enviado un correo con instrucciones para restablecer tu contraseña.";
    } catch (Exception $e) {
        echo "Error al enviar el correo. Detalles: {$mail->ErrorInfo}";
    }
} else {
    echo "Error al generar el enlace. Intente lo de nuevo.";
}

$stmt->close();
$update->close();
$conexion->close();
?>

