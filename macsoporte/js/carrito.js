document.addEventListener('DOMContentLoaded', async () => {
  const cuerpoTabla = document.getElementById('cuerpoTabla');
  const totalCarrito = document.getElementById('totalCarrito');
  const tablaCarrito = document.getElementById('tablaCarrito');
  const carritoVacio = document.getElementById('carritoVacio');

  try {
    const response = await fetch('php/obtenerCarrito.php');
    const carrito = await response.json();

    if (!Array.isArray(carrito) || carrito.length === 0) {
      carritoVacio.style.display = 'block';
      tablaCarrito.style.display = 'none';
      return;
    }

    carritoVacio.style.display = 'none';
    tablaCarrito.style.display = 'table';

    let total = 0;

    carrito.forEach((producto, index) => {
      const fila = document.createElement('tr');
      const precioNumerico = parseFloat(
        producto.precio.replace(/[^\d,.-]/g, '').replace(',', '.')
      );
      const subtotal = precioNumerico * producto.cantidad;
      total += subtotal;

      fila.innerHTML = `
        <td><img src="${producto.imagen}" style="width: 70px; height: auto; vertical-align: middle; margin-right: 10px;">
        ${producto.nombre}</td>
        <td>${producto.precio}</td>
        <td>${producto.cantidad}</td>
        <td>${subtotal.toFixed(2)}€</td>
        <td>
            <button class="botonEliminar" data-id="${producto.id}" aria-label="Eliminar">
                <img src="img/x.svg" alt="Eliminar" class="iconoEliminar" width="24" height="24">
            </button>
        </td>
      `;

      fila.querySelector('.botonEliminar').addEventListener('click', async (e) => {
        e.preventDefault();
        const productoId = e.currentTarget.getAttribute('data-id');

        const eliminar = await fetch('php/eliminarProductoCarrito.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `producto_id=${encodeURIComponent(productoId)}`
        });

        const respuestaTexto = await eliminar.text();
        console.log(respuestaTexto);

        window.location.reload();
      });

      cuerpoTabla.appendChild(fila);
    });

    totalCarrito.textContent = `${total.toFixed(2)} €`;

  } catch (error) {
    console.error("Error al cargar el carrito:", error);
  }
});
