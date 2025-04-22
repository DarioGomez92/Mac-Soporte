const imagenes = document.querySelectorAll('.carruselImagen');
const botonIzquierda = document.querySelector('.izquierda');
const botonDerecha = document.querySelector('.derecha');
let indice = 0;

function mostrarImagen(index) {
  imagenes.forEach(img => img.classList.remove('activa'));
  imagenes[index].classList.add('activa');
}

botonIzquierda.addEventListener('click', () => {
  indice = (indice - 1 + imagenes.length) % imagenes.length;
  mostrarImagen(indice);
});

botonDerecha.addEventListener('click', () => {
  indice = (indice + 1) % imagenes.length;
  mostrarImagen(indice);
});
