<?php
$conexion = new mysqli("localhost", "root", "", "macsoporte_db");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$token = $_GET['token'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nuevaContrasena = $_POST['nueva_contrasena'];
    $confirmarContrasena = $_POST['confirmar_contrasena'];
    $token = $_POST['token'];

    if (empty($nuevaContrasena) || empty($confirmarContrasena)) {
        $mensaje = "Todos los campos son obligatorios.";
    } elseif ($nuevaContrasena !== $confirmarContrasena) {
        $mensaje = "Las contraseñas no coinciden.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $nuevaContrasena)) {
        $mensaje = "La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula y un número.";
    } else {
        $stmt = $conexion->prepare("SELECT id, fecha_token FROM usuarios WHERE token_recuperacion = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $mensaje = "Token inválido.";
        } else {
            $stmt->bind_result($id_usuario, $fecha_token);
            $stmt->fetch();

            $fechaActual = new DateTime();
            $fechaGuardada = new DateTime($fecha_token);
            $intervalo = $fechaGuardada->diff($fechaActual);

            if ($intervalo->days >= 1 || $intervalo->h >= 1) {
                $mensaje = "El enlace de recuperación ha expirado.";
            } else {
                $nuevaContrasenaCifrada = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
                $actualizar = $conexion->prepare("UPDATE usuarios SET contraseña = ?, token_recuperacion = NULL, fecha_token = NULL WHERE id = ?");
                $actualizar->bind_param("si", $nuevaContrasenaCifrada, $id_usuario);
                if ($actualizar->execute()) {
                    $mensaje = "Tu contraseña ha sido restablecida correctamente.";
                } else {
                    $mensaje = "Error al actualizar la contraseña.";
                }
                $actualizar->close();
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="formularioUsuario" style="max-width: 600px; margin: 60px auto;">
        <form class="formularioModal" method="POST">
            <h2>Restablecer Contraseña</h2>

            <?php if (!empty($mensaje)): ?>
                <p class="mensajeRecuperacion <?= (strpos($mensaje, 'correctamente') !== false) ? 'exito' : '' ?>">
                    <?= $mensaje ?>
                </p>
            <?php endif; ?>

            <input type="password" name="nueva_contrasena" placeholder="Nueva contraseña" required>
            <input type="password" name="confirmar_contrasena" placeholder="Confirmar contraseña" required>
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <button type="submit">Restablecer</button>
        </form>
    </div>
</body>
</html>

