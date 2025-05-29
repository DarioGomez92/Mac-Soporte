<?php
/* Se inicia sesion para manejar las variables de sesion */
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}

require_once 'php/conexion.php';

/* Consulta para coger todos los usuarios registrados */
$sqlUsuarios = "SELECT * FROM usuarios";
$resultadoUsuarios = $conexion->query($sqlUsuarios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="paginaAdmin">
    
    <div id="contenedorNavegador"></div>

    <div class="contenedorAdmin aparecer">
        <h1>Panel de Administración</h1>
        <p>Bienvenido, <?php echo $_SESSION['nombre_completo']; ?> (<?php echo $_SESSION['rol']; ?>)</p>

        <!-- Se recorre la lista de usuarios y se reflejan sus datos -->
        <?php while ($usuario = $resultadoUsuarios->fetch_assoc()): ?>
            <div class="usuarioAdmin">
                <h2 class="nombreUsuarioAdmin"><?php echo $usuario['nombre_completo']; ?> (<?php echo $usuario['usuario']; ?>)</h2>
                <p class="datosUsuarioAdmin">
                    <strong>Email:</strong> <?php echo $usuario['correo']; ?> |
                    <strong>Teléfono:</strong> <?php echo $usuario['telefono']; ?> |
                    <strong>Registrado:</strong> <?php 
                        echo date('d-m-Y', strtotime($usuario['fecha_registro'])) . " / " . date('H:i:s', strtotime($usuario['fecha_registro']));
                    ?>
                </p>

                <!-- Sección de Mensajes -->
                <h3 class="subtituloAdmin">Mensajes de Contacto:</h3>
                <?php
                    $idUsuario = $usuario['id'];
                    $sqlMensajes = "SELECT * FROM formularios_contacto WHERE usuario_id = $idUsuario ORDER BY fecha_envio DESC";
                    $mensajes = $conexion->query($sqlMensajes);
                    if ($mensajes->num_rows > 0):
                ?>
                    <table class="tablaMensajes">
                        <thead>
                            <tr>
                                <th>Modelo Dispositivo</th>
                                <th>Mensaje</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Recorre todos los mensajes almacenados y los muestra -->
                            <?php while ($mensaje = $mensajes->fetch_assoc()): ?>
                                <?php 
                                    $fechaHora = $mensaje['fecha_envio'];
                                    $fecha = date('d-m-Y', strtotime($fechaHora));
                                    $hora = date('H:i:s', strtotime($fechaHora));
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($mensaje['modelo_dispositivo']); ?></td>
                                    <td><?php echo htmlspecialchars($mensaje['mensaje']); ?></td>
                                    <td><?php echo $fecha . "<br>" . $hora; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="noHay">No hay mensajes.</p>
                <?php endif; ?>

                <!-- Sección de Compras -->
                <h3 class="subtituloAdmin">Historial de Compras:</h3>
                <?php
                    $sqlCompras = "SELECT * FROM compras WHERE usuario_id = $idUsuario ORDER BY fecha_compra DESC";
                    $compras = $conexion->query($sqlCompras);
                    if ($compras->num_rows > 0):
                ?>
                    <?php while ($compra = $compras->fetch_assoc()): ?>
                        <?php 
                            $fechaHoraCompra = $compra['fecha_compra'];
                            $fechaCompraFormateada = date('d-m-Y', strtotime($fechaHoraCompra)) . " / " . date('H:i:s', strtotime($fechaHoraCompra));
                        ?>
                        <p class="datosComprasAdmin">
                            <strong>Compra del:</strong> <?php echo $fechaCompraFormateada; ?> |
                            <strong>Total:</strong> <?php echo number_format($compra['total'], 2); ?> €
                        </p>
                        <?php
                            $idCompra = $compra['id'];
                            $sqlDetalles = "SELECT * FROM detalle_compra WHERE compra_id = $idCompra";
                            $detalles = $conexion->query($sqlDetalles);
                            if ($detalles->num_rows > 0):
                        ?>
                            <table class="tablaCompras">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Recorre todas las compras realizadas y las muestra -->
                                    <?php while ($detalle = $detalles->fetch_assoc()): ?>
                                        <?php 
                                            $producto_id = $detalle['producto_id'];
                                            $sqlProducto = "SELECT nombre FROM productos WHERE id = $producto_id";
                                            $resultadoProducto = $conexion->query($sqlProducto);
                                            $nombreProducto = ($resultadoProducto && $resultadoProducto->num_rows > 0) ? $resultadoProducto->fetch_assoc()['nombre'] : "Producto no encontrado";
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($nombreProducto); ?></td>
                                            <td><?php echo $detalle['cantidad']; ?></td>
                                            <td><?php echo number_format($detalle['precio_unitario'], 2); ?> €</td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="noHay">No hay compras registradas.</p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>

        <?php $conexion->close(); ?>
        
    </div>
    <div id="contenedorFooter"></div>

    <script src="js/animaciones.js"></script>
    <script src="js/footer.js"></script>
    <script src="js/nav.js"></script>
</body>
</html>





