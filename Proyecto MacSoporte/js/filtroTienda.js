const botonHardware = document.getElementById('botonHardware');
const botonPerifericos = document.getElementById('botonPerifericos');
const botonAccesorios = document.getElementById('botonAccesorios');
const botonFiguras = document.getElementById('botonFiguras');
const botonTodo = document.getElementById('botonTodo');

const seccionHardware = document.getElementById('hardware');
const seccionPerifericos = document.getElementById('perifericos');
const seccionAccesorios = document.getElementById('accesorios');
const seccionFiguras = document.getElementById('figuras');

/* Esta funcion va a recibir una categoria como parametro, primero ocultara todas las categorias y luego mostrara la indicada */
function mostrarCategoria(categoria) {
    seccionHardware.style.display = 'none';
    seccionPerifericos.style.display = 'none';
    seccionAccesorios.style.display = 'none';
    seccionFiguras.style.display = 'none';
    
    if (categoria === 'hardware') {
        seccionHardware.style.display = 'block';
    } else if (categoria === 'perifericos') {
        seccionPerifericos.style.display = 'block';
    } else if (categoria === 'accesorios') {
        seccionAccesorios.style.display = 'block';
    } else if (categoria === 'figuras') {
        seccionFiguras.style.display = 'block';
    } else if (categoria === 'todo') {
        seccionHardware.style.display = 'block';
        seccionPerifericos.style.display = 'block';
        seccionAccesorios.style.display = 'block';
        seccionFiguras.style.display = 'block';
    }
}

/* Eventos de escucha para todos los botones del filtro */
botonHardware.addEventListener('click', () => {
    mostrarCategoria('hardware');
});

botonPerifericos.addEventListener('click', () => {
    mostrarCategoria('perifericos');
});

botonAccesorios.addEventListener('click', () => {
    mostrarCategoria('accesorios');
});

botonFiguras.addEventListener('click', () => {
    mostrarCategoria('figuras');
});

botonTodo.addEventListener('click', () => {
    mostrarCategoria('todo');
});

/* Cuando se recargue la pagina se mostrara todos los articulos por defecto */
document.addEventListener('DOMContentLoaded', () => {
    mostrarCategoria('todo');
});
