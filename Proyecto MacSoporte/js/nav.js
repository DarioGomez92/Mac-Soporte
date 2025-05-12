function cargarNav() {
    const nav = `
    <header class="header">
        <div class="contenedorNavegador" id="contenedorNavegador">
            <nav class="navegador" id="navegador">
                <!-- contenedor para el logo y futuro menu hamburguesa -->
                <div class="contenedorLogo" id="contenedorLogo">
                    <img src="img/logo.png">
                </div>

                <!-- contenedor para los enlaces del menu -->
                <div class="contenedorMenu" id="contenedorMenu">
                    <ul class="menu" id="menu">
                        <li>
                            <a class="inicio" href="index.html" id="inicio">Inicio</a>
                        </li>
                        <li>
                            <a class="servicios" href="index.html#contenedorServicios" id="servicios">Servicios</a>
                        </li>
                        <li>
                            <a class="tienda" href="tienda.html" id="tienda">Tienda</a>
                        </li>
                        <li>
                            <a class="reparaciones" href="reparaciones.html" id="reparaciones">Reparaciones</a>
                        </li>
                        <li>
                            <a class="contacto" href="contacto.html" id="contacto">Contacto</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
    `;

    document.getElementById("contenedorNavegador").innerHTML = nav;
}

document.addEventListener("DOMContentLoaded", function() {
    cargarNav(); 
});