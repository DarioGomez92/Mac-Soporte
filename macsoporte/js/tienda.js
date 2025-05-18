async function actualizarContadorCarrito() {
    try {
        const response = await fetch('php/obtenerCarrito.php');
        const carrito = await response.json();

        const totalItems = Array.isArray(carrito)
            ? carrito.reduce((acc, item) => acc + (parseInt(item.cantidad) || 0), 0)
            : 0;

        const contador = document.getElementById('contadorCarrito');
        if (contador) {
            contador.textContent = totalItems;
            contador.style.display = totalItems > 0 ? 'inline-block' : 'none';
        }
    } catch (error) {
        console.error('Error actualizando el contador del carrito:', error);
    }
}


async function cargarProductos() {
    try {
        const response = await fetch('data/productos.json');
        if (!response.ok) {
            throw new Error(`Error al cargar productos.json: ${response.statusText}`);
        }
        const productos = await response.json();
        return productos;
    } catch (error) {
        console.error('Error cargando productos:', error);
        return [];
    }
}

/* Funcion que verifica si hay usuario logeado o no */
async function usuarioAutenticado() {
    try {
        const response = await fetch("php/estadoSesion.php");
        const data = await response.json();
        return data.loggedIn || data.logueado;
    } catch (error) {
        console.error("Error al comprobar sesión:", error);
        return false;
    }
}

/* Funcion para mostrar notificaciones flotantes temporales */
function mostrarToast(mensaje, tipo = 'success') {
    const contenedor = document.getElementById('notificaciones');
    const toast = document.createElement('div');
    toast.className = `toast ${tipo}`;
    toast.textContent = mensaje;

    contenedor.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 2000);
}

/* Funcion para crear las tarjetas de los productos de manera dinamica accediendo al json que las contiene */
function crearTarjetasProductos(productos) {
    const contenedorGeneral = document.getElementById('contenedorProductos');
    contenedorGeneral.innerHTML = '';

    const categorias = {};
    productos.forEach(producto => {
        if (!categorias[producto.categoria]) {
            categorias[producto.categoria] = [];
        }
        categorias[producto.categoria].push(producto);
    });

    for (const categoria in categorias) {
        const seccion = document.createElement('div');
        seccion.className = 'seccionTienda';
        seccion.id = categoria;

        const contenedorTarjetas = document.createElement('div');
        contenedorTarjetas.className = 'contenedorTarjetasTienda';

        categorias[categoria].forEach((producto) => {
            const contenedorTienda = document.createElement('div');
            contenedorTienda.className = 'contenedorTienda aparecer';
            contenedorTienda.id = `contenedorTienda${producto.id}`;

            const tarjeta = document.createElement('div');
            tarjeta.className = 'tarjetaTienda';

            const imgDiv = document.createElement('div');
            imgDiv.className = 'imgTienda';

            const img = document.createElement('img');
            img.src = producto.imagen;
            img.alt = producto.nombre;
            imgDiv.appendChild(img);

            const contenido = document.createElement('div');
            contenido.className = 'contenidoPrincipalTarjeta';

            const descripcion = document.createElement('p');
            descripcion.className = 'contenidoTarjetaTienda';
            descripcion.textContent = producto.nombre;

            const precio = document.createElement('p');
            precio.className = 'precio';
            precio.textContent = producto.precio;

            contenido.appendChild(descripcion);
            contenido.appendChild(precio);

            const divBoton = document.createElement('div');
            const boton = document.createElement('button');
            boton.className = 'botonCarrito';
            boton.dataset.id = producto.id;
            boton.textContent = 'Añadir al carrito';

            boton.addEventListener('click', async (e) => {
                e.preventDefault();
                const loggedIn = await usuarioAutenticado();
                if (!loggedIn) {
                    mostrarToast('Debes iniciar sesión para añadir productos al carrito.', 'error');
                    const modalLogin = document.getElementById('formLogin');
                    if (modalLogin) modalLogin.style.display = 'block';
                } else {
                    const productoId = e.target.dataset.id;
                    const cantidad = 1;

                    fetch('php/agregarProductoCarrito.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `producto_id=${productoId}&cantidad=${cantidad}`
                    })
                    .then(response => response.text())
                    .then(resultado => {
                        mostrarToast(resultado, 'success');
                        actualizarContadorCarrito();
                    });
                }
            });

            divBoton.appendChild(boton);
            tarjeta.appendChild(imgDiv);
            tarjeta.appendChild(contenido);
            tarjeta.appendChild(divBoton);

            contenedorTienda.appendChild(tarjeta);
            contenedorTarjetas.appendChild(contenedorTienda);
        });

        seccion.appendChild(contenedorTarjetas);
        contenedorGeneral.appendChild(seccion);
    }
}

/* Funcion que filtra los productos por categorias */
function filtrarPorCategoria(productos, categoria) {
    if (categoria === 'todo') {
        crearTarjetasProductos(productos);
    } else {
        const productosFiltrados = productos.filter(producto => producto.categoria === categoria);
        crearTarjetasProductos(productosFiltrados);
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    const productos = await cargarProductos();
    crearTarjetasProductos(productos);

    document.getElementById('botonHardware').addEventListener('click', () => filtrarPorCategoria(productos, 'hardware'));
    document.getElementById('botonPerifericos').addEventListener('click', () => filtrarPorCategoria(productos, 'perifericos'));
    document.getElementById('botonAccesorios').addEventListener('click', () => filtrarPorCategoria(productos, 'accesorios'));
    document.getElementById('botonFiguras').addEventListener('click', () => filtrarPorCategoria(productos, 'figuras'));
    document.getElementById('botonTodo').addEventListener('click', () => filtrarPorCategoria(productos, 'todo'));
});


