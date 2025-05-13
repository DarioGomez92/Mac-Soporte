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
                        <li><a class="inicio" href="index.html" id="inicio">Inicio</a></li>
                        <li><a class="servicios" href="index.html#contenedorServicios" id="servicios">Servicios</a></li>
                        <li><a class="tienda" href="tienda.html" id="tienda">Tienda</a></li>
                        <li><a class="reparaciones" href="reparaciones.html" id="reparaciones">Reparaciones</a></li>
                        <li><a class="contacto" href="contacto.html" id="contacto">Contacto</a></li>
                        <li><button class="botonUsuario" onclick="abrirModalUsuario()">Acceso</button></li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <div class="modalUsuario oculto" id="modalUsuario">
        <div class="contenidoModal">
            <span class="cerrar" onclick="cerrarModalUsuario()">&times;</span>

            <div class="formularioUsuario">
                <form id="formRegistro" class="formulario">
                    <h2>Registro</h2>
                    <input type="text" name="nombre_completo" placeholder="Nombre completo" required>
                    <input type="text" name="usuario" placeholder="Usuario" required>
                    <input type="email" name="correo" placeholder="Correo electrónico" required>
                    <input type="text" name="telefono" placeholder="Teléfono">
                    <input type="password" name="contraseña" placeholder="Contraseña" required>
                    <button type="submit">Registrarse</button>
                    <div id="mensajeRegistro" class="mensaje"></div>
                </form>
            </div>

            <div class="formularioUsuario">
                <form id="formLogin" class="formulario">
                    <h2>Iniciar Sesión</h2>
                    <input type="text" name="usuario" placeholder="Usuario o correo" required>
                    <input type="password" name="contraseña" placeholder="Contraseña" required>
                    <button type="submit">Entrar</button>
                    <div id="mensajeLogin" class="mensaje"></div>
                </form>
            </div>
        </div>
    </div>
    `;
    document.getElementById("contenedorNavegador").innerHTML = nav;
}


function abrirModalUsuario() {
    document.getElementById('modalUsuario').classList.remove('oculto');
}

function cerrarModalUsuario() {
    document.getElementById('modalUsuario').classList.add('oculto');
}

document.addEventListener("DOMContentLoaded", function () {
    cargarNav();

    const formRegistro = document.getElementById("formRegistro");
    const formLogin = document.getElementById("formLogin");

    formRegistro.addEventListener("submit", async function (e) {
        e.preventDefault();

        const formData = new FormData(formRegistro);

        const respuesta = await fetch("php/registro.php", {
            method: "POST",
            body: formData
        });

        const texto = await respuesta.text();
        document.getElementById("mensajeRegistro").innerText = texto;
    });

    formLogin.addEventListener("submit", async function (e) {
        e.preventDefault();

        const formData = new FormData(formLogin);

        const respuesta = await fetch("php/login.php", {
            method: "POST",
            body: formData
        });

        const jsonResponse = await respuesta.json();

        if (jsonResponse.status === 'success') {
            // Redirigir a la página del cliente si el inicio de sesión es exitoso
            window.location.href = "cliente.php"; 
        } else {
            // Mostrar el mensaje de error si el inicio de sesión falló
            document.getElementById("mensajeLogin").innerText = jsonResponse.message;
        }
    });
});






