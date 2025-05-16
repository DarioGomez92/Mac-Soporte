function cargarNav() {

    if (window.location.protocol === 'file:') {
        document.getElementById("contenedorNavegador").innerHTML = `
            <header class="header">
                <div class="contenedorNavegador">
                    <nav class="navegador" id="navegador">
                        <div class="contenedorLogo" id="contenedorLogo">
                            <img src="img/logo.png">
                        </div>
                        <div class="contenedorMenu" id="contenedorMenu">
                            <ul class="menu" id="menu">
                                <li><a class="inicio" href="index.html" id="inicio">Inicio</a></li>
                                <li><a class="servicios" href="index.html#contenedorServicios" id="servicios">Servicios</a></li>
                                <li><a class="tienda" href="tienda.html" id="tienda">Tienda</a></li>
                                <li><a class="reparaciones" href="reparaciones.html" id="reparaciones">Reparaciones</a></li>
                                <li><a class="contacto" href="contacto.html" id="contacto">Contacto</a></li>
                                <li><a onclick="abrirModalUsuario()">Register / Sign in</a></li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </header>
        `;
        return;
    }

    fetch('php/estadoSesion.php')
        .then(response => response.json())
        .then(data => {
            let menuHTML = `
                <li><a class="inicio" href="index.html" id="inicio">Inicio</a></li>
                <li><a class="servicios" href="index.html#contenedorServicios" id="servicios">Servicios</a></li>
                <li><a class="tienda" href="tienda.html" id="tienda">Tienda</a></li>
                <li><a class="reparaciones" href="reparaciones.html" id="reparaciones">Reparaciones</a></li>
                <li><a class="contacto" href="contacto.html" id="contacto">Contacto</a></li>
            `;

            if (data.loggedIn) {
                menuHTML += `
                    <li><a href="cliente.php">Área de cliente</a></li>
                    <a href="php/logout.php" class="botonCerrarSesion" title="Cerrar sesión">
                        <img src="img/cerrarSesion.svg" alt="Cerrar sesión" width="24" height="24">
                    </a>
                `;
            } else {
                menuHTML += `
                    <li><a class="registroEnlace" onclick="abrirModalUsuario()">Register / Sign in</a></li>
                `;
            }

            const nav = `
                <header class="header">
                    <div class="contenedorNavegador" id="contenedorNavegador">
                        <nav class="navegador" id="navegador">
                            <div class="contenedorLogo" id="contenedorLogo">
                                <img src="img/logo.png">
                            </div>
                            <div class="contenedorMenu" id="contenedorMenu">
                                <ul class="menu" id="menu">
                                    ${menuHTML}
                                </ul>
                            </div>
                        </nav>
                    </div>
                </header>

                <div class="modalUsuario oculto" id="modalUsuario">
                    <div class="contenidoModal">
                        <span class="cerrar" onclick="cerrarModalUsuario()">&times;</span>

                        <div class="formularioUsuario">
                            <form class="formularioModal" id="formRegistro">
                                <h2>Registro</h2>
                                <input type="text" name="nombre_completo" placeholder="Nombre completo" required>
                                <input type="text" name="usuario" placeholder="Usuario" required>
                                <input type="email" name="correo" placeholder="Correo electrónico" required>
                                <input type="text" name="telefono" placeholder="Teléfono">
                                <input type="password" name="contraseña" placeholder="Contraseña" required>
                                <button type="submit">Registrarse</button>
                                <div class="mensaje" id="mensajeRegistro"></div>
                            </form>
                        </div>

                        <div class="formularioUsuario">
                            <form class="formularioModal" id="formLogin">
                                <h2>Iniciar Sesión</h2>
                                <input type="text" name="usuario" placeholder="Usuario o correo" required>
                                <input type="password" name="contraseña" placeholder="Contraseña" required>
                                <a class="enlaceRecuperar">¿Has olvidado la contraseña?</a>
                                <button type="submit">Entrar</button>
                                <div id="mensajeLogin" class="mensaje"></div>
                            </form>
                        </div>
                    </div>
                </div> 
                <div class="modalRecuperar oculto" id="modalRecuperar">
                    <div class="contenidoModal">
                        <span class="cerrar" onclick="cerrarModalRecuperar()">&times;</span>
                        <form class="formularioModal" id="formRecuperar">
                            <h2>Recuperar contraseña</h2>
                            <p>Introduce tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>
                            <input type="email" name="correo_recuperacion" placeholder="Correo electrónico" required>
                            <button type="submit">Enviar enlace</button>
                            <div id="mensajeRecuperar" class="mensajeRecuperar"></div>
                        </form>
                    </div>
                </div>
            `;

            document.getElementById("contenedorNavegador").innerHTML = nav;

            asignarEventosFormularios();
        });
}

/* funcion que inicia los eventos del los formularios */
function asignarEventosFormularios() {
    const formRegistro = document.getElementById("formRegistro");
    const formLogin = document.getElementById("formLogin");

    formRegistro?.addEventListener("submit", async function (e) {
        e.preventDefault();
        const formData = new FormData(formRegistro);

        const respuesta = await fetch("php/registro.php", {
            method: "POST",
            body: formData
        });

        const texto = await respuesta.text();
        document.getElementById("mensajeRegistro").innerText = texto;

        formRegistro.reset();
    });

    formLogin?.addEventListener("submit", async function (e) {
        e.preventDefault();
        const formData = new FormData(formLogin);

        const respuesta = await fetch("php/login.php", {
            method: "POST",
            body: formData
        });

        const jsonResponse = await respuesta.json();

        if (jsonResponse.status === 'success') {
            window.location.href = "cliente.php"; 
        } else {
            document.getElementById("mensajeLogin").innerText = jsonResponse.message;
            formLogin.reset();
        }
    });

    formRecuperar?.addEventListener("submit", async function (e) {
        e.preventDefault();
        const formData = new FormData(formRecuperar);

        const respuesta = await fetch("php/enviarRecuperacion.php", {
            method: "POST",
            body: formData
        });

        const texto = await respuesta.text();
        document.getElementById("mensajeRecuperar").innerText = texto;

        formRecuperar.reset();
    });

    const enlaceRecuperar = document.querySelector(".enlaceRecuperar");
    enlaceRecuperar?.addEventListener("click", function (e) {
        e.stopPropagation();
        abrirModalRecuperar();
});

}

function abrirModalUsuario() {
    document.getElementById('modalUsuario').classList.remove('oculto');
}

function cerrarModalUsuario() {
    document.getElementById('modalUsuario').classList.add('oculto');
}

function abrirModalRecuperar() {
    document.getElementById('modalUsuario').classList.add('oculto'); 
    document.getElementById('modalRecuperar').classList.remove('oculto'); 
}

function cerrarModalRecuperar() {
    document.getElementById('modalRecuperar').classList.add('oculto');
    document.getElementById('modalUsuario').classList.remove('oculto'); 
}


document.addEventListener("DOMContentLoaded", function () {
    cargarNav();
});

/* Codigo para cerrar los modales dando click fuera de la ventana */
document.addEventListener("click", function (e) {
    const modalUsuario = document.getElementById("modalUsuario");
    const modalRecuperar = document.getElementById("modalRecuperar");

    if (modalUsuario && !modalUsuario.classList.contains("oculto")) {
        const contenidoUsuario = modalUsuario.querySelector(".contenidoModal");
        if (!contenidoUsuario.contains(e.target) && !e.target.closest(".registroEnlace")) {
            cerrarModalUsuario();
        }
    }

    if (modalRecuperar && !modalRecuperar.classList.contains("oculto")) {
        const contenidoRecuperar = modalRecuperar.querySelector(".contenidoModal");
        if (!contenidoRecuperar.contains(e.target) && !e.target.closest(".enlaceRecuperar")) {
            cerrarModalRecuperar();
        }
    }
});


