window.addEventListener('DOMContentLoaded', async () => {
    const avisoDiv = document.getElementById('mensajeAviso');

    function mostrarMensaje(texto, tipo = 'error') {
        avisoDiv.textContent = texto;
        avisoDiv.className = '';
        avisoDiv.classList.add(tipo);
        avisoDiv.style.display = 'block';
    }

    /* Funcion para autocompletar los mdatos del formulario */
    try {
        const res = await fetch('php/obtenerDatosUsuario.php');
        const data = await res.json();

        if (data.status === 'success') {
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('correo').value = data.correo;
            document.getElementById('telefono').value = data.telefono;
            avisoDiv.style.display = 'none'; 
        } else {
            mostrarMensaje('Debes iniciar sesión con una cuenta para poder enviar peticiones de reparación.');
        }
    } catch (error) {
        console.error('Error al cargar los datos del usuario:', error);
        mostrarMensaje('Error al cargar los datos del usuario.');
    }

    document.getElementById('formularioContacto').addEventListener('submit', async (e) => {
        e.preventDefault();
        avisoDiv.style.display = 'none'; 

        const modelo = document.getElementById('modelo').value.trim();
        const mensaje = document.getElementById('mensaje').value.trim();

        if (!modelo || !mensaje) {
            mostrarMensaje('Por favor, completa todos los campos obligatorios.');
            return;
        }

        try {
            const res = await fetch('php/procesarMensajeContacto.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    modelo,
                    mensaje
                })
            });

            const text = await res.text();
            console.log('Respuesta cruda del servidor:', text);

            const data = JSON.parse(text);

            if (data.status === 'success') {
                mostrarMensaje('Mensaje enviado correctamente.', 'success');
                document.getElementById('formularioContacto').reset();

                document.getElementById('nombre').value = data.nombre || '';
                document.getElementById('correo').value = data.correo || '';
                document.getElementById('telefono').value = data.telefono || '';
            } else {
                mostrarMensaje(data.message || 'Ocurrió un error al enviar el formulario.');
                console.warn(data.error);
            }
        } catch (error) {
            console.error('Error al enviar el formulario:', error);
            mostrarMensaje('Ocurrió un error al enviar el formulario.');
        }
    });
});








