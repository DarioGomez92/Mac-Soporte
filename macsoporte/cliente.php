<?php
session_start();

// Aquí asumimos que la sesión ya tiene los datos básicos del usuario
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit;
}

// Ejemplo de datos cargados de sesión (puedes ampliar con consulta a DB si quieres)
$usuario_id = $_SESSION['usuario_id'];
$nombre_completo = $_SESSION['nombre_completo'];
$usuario = $_SESSION['usuario'];
$correo = ""; // Ideal traerlo de la base con consulta, aquí lo dejamos vacío o lo cargas de sesión si guardas

// Aquí deberías hacer consultas para traer el historial de compras y mensajes, 
// por ahora los dejamos en arrays simulados para ejemplo

$historial_compras = [
    ['fecha' => '2025-05-10', 'total' => 99.99],
    ['fecha' => '2025-04-22', 'total' => 59.50]
];

$historial_mensajes = [
    ['fecha' => '2025-05-14', 'mensaje' => '¿Cuándo llegará mi pedido?'],
    ['fecha' => '2025-04-30', 'mensaje' => 'Problema con la garantía del dispositivo']

];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Área de Cliente - Mi Cuenta</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body class="paginaCliente">
    <!-- Contenedor que carga el navegador almacenado en el js -->
    <div id="contenedorNavegador"></div>

    <div class="contenedorClientes">
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
                                <td><?php echo htmlspecialchars($compra['fecha']); ?></td>
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
                                <time datetime="<?php echo htmlspecialchars($mensaje['fecha']); ?>">
                                    <?php echo htmlspecialchars($mensaje['fecha']); ?>
                                </time> - 
                                <span><?php echo htmlspecialchars($mensaje['mensaje']); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No tienes mensajes registrados.</p>
                <?php endif; ?>
            </section>
        </main>

        <!-- Contenedor que carga el footer almacenado en su js -->
        <div id="contenedorFooter"></div>
    </div>

    <script src="js/footer.js"></script>
    <script src="js/nav.js"></script>
</body>
</html>


