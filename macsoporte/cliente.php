<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    // Si no hay sesión, redirigir al inicio
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Área de Cliente</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Contenedor para cargar el nav -->
    <div id="contenedorNavegador"></div>

    <main>
        <section class="areaCliente">
            <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?> 👋</h1>

            <a href="php/logout.php" class="botonCerrarSesion">Cerrar sesión</a>

            <hr>

            <h2>📝 Tus formularios enviados</h2>
            <p>Aquí verás los formularios de contacto que hayas enviado (pendiente de implementar).</p>

            <h2>🛒 Tus compras</h2>
            <p>Aquí aparecerá tu historial de compras (pendiente de implementar).</p>
        </section>
    </main>

    <!-- Contenedor para el footer -->
    <div id="contenedorFooter"></div>

    <script src="js/nav.js"></script>
    <script src="js/footer.js"></script>
</body>
</html>
