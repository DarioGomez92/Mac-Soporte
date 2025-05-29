<?php
/* Se inicia sesion para manejar las variables de sesion */
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit;
}

require_once 'php/conexion.php';

$usuario_id = $_SESSION['usuario_id'];
$nombre_completo = $_SESSION['nombre_completo'];
$usuario = $_SESSION['usuario'];

/* Obtener correo electronico */
$stmt = $conexion->prepare("SELECT correo FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($correo);
$stmt->fetch();
$stmt->close();

/* Obtener historial de compras y se almacena en un array */
$historial_compras = [];
$sqlCompras = "SELECT fecha_compra, total FROM compras WHERE usuario_id = ?";
$stmtCompras = $conexion->prepare($sqlCompras);
$stmtCompras->bind_param("i", $usuario_id);
$stmtCompras->execute();
$resultCompras = $stmtCompras->get_result();
while ($row = $resultCompras->fetch_assoc()) {
    $historial_compras[] = $row;
}
$stmtCompras->close();

/* Obtener historial de mensajes y se almacena en un array */
$historial_mensajes = [];
$sqlMensajes = "SELECT fecha_envio, mensaje, modelo_dispositivo FROM formularios_contacto WHERE usuario_id = ?";
$stmtMensajes = $conexion->prepare($sqlMensajes);
$stmtMensajes->bind_param("i", $usuario_id);
$stmtMensajes->execute();
$resultMensajes = $stmtMensajes->get_result();
while ($row = $resultMensajes->fetch_assoc()) {
    $historial_mensajes[] = $row;
}
$stmtMensajes->close();

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Área de Cliente - Mi Cuenta</title>
    <link rel="stylesheet" href="css/style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="paginaCliente">
    <!-- Contenedor que carga el navegador almacenado en el js -->
    <div id="contenedorNavegador"></div>

    <div class="contenedorClientes aparecer">
        <main class="clienteArea">
            <h1>Bienvenido, <?php echo htmlspecialchars($nombre_completo); ?></h1>

            <section class="datosCliente">
                <h2>Mis Datos</h2>
                <ul>
                    <li><strong>Nombre completo:</strong> <?php echo htmlspecialchars($nombre_completo); ?></li>
                    <li><strong>Usuario:</strong> <?php echo htmlspecialchars($usuario); ?></li>
                    <li><strong>Correo electrónico:</strong> <?php echo htmlspecialchars($correo); ?></li>
                </ul>
            </section>

            <section class="historialCompras">
                <h2>Historial de Compras</h2>
                <?php if (count($historial_compras) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Total (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historial_compras as $compra): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($compra['fecha_compra']); ?></td>
                                <td><?php echo number_format($compra['total'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No tienes compras registradas.</p>
                <?php endif; ?>
            </section>

            <section class="historialMensajes">
                <h2>Historial de Mensajes</h2>
                <?php if (count($historial_mensajes) > 0): ?>
                    <ul>
                        <?php foreach ($historial_mensajes as $mensaje): ?>
                            <li>
                                <div class="fecha">
                                    <time datetime="<?php echo htmlspecialchars($mensaje['fecha_envio']); ?>">
                                        <?php echo htmlspecialchars(str_replace(' ', ' / ', $mensaje['fecha_envio'])); ?>
                                    </time>
                                </div>
                                <div class="modelo">
                                    <strong>Modelo:</strong> <?php echo htmlspecialchars($mensaje['modelo_dispositivo']); ?>
                                </div>
                                <div class="mensajeTexto">
                                    <strong>Mensaje:</strong> <?php echo htmlspecialchars($mensaje['mensaje']); ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No tienes mensajes registrados.</p>
                <?php endif; ?>
            </section>

        </main>
    </div>

    <!-- Contenedor que carga el footer almacenado en su js -->
    <div id="contenedorFooter"></div>

    <script src="js/animaciones.js"></script>
    <script src="js/footer.js"></script>
    <script src="js/nav.js"></script>
</body>
</html>



