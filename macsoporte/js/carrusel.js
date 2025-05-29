const carrusel = document.querySelector('.carruselImagenes');
let imagenes = document.querySelectorAll('.carruselImagen');
const botonIzquierda = document.querySelector('.izquierda');
const botonDerecha = document.querySelector('.derecha');

/* Se duplican la primera y ultima imagen para crear un falso bucle */
const primera = imagenes[0].cloneNode(true);
const ultima = imagenes[imagenes.length - 1].cloneNode(true);

carrusel.appendChild(primera);
carrusel.insertBefore(ultima, imagenes[0]);

imagenes = document.querySelectorAll('.carruselImagen');
let indice = 1;
const total = imagenes.length;

/* funcion para actualizar el carrusel */
function actualizarCarrusel(animar = true) {
  const ancho = imagenes[0].clientWidth;
  carrusel.style.transition = animar ? 'transform 0.5s ease' : 'none';
  carrusel.style.transform = `translateX(-${indice * ancho}px)`;
}

/* posicion inicial */
window.addEventListener('load', () => {
  actualizarCarrusel(false);
});

botonDerecha.addEventListener('click', () => {
  if (indice >= total - 1) return;
  indice++;
  actualizarCarrusel();
});

botonIzquierda.addEventListener('click', () => {
  if (indice <= 0) return;
  indice--;
  actualizarCarrusel();
});

/* Cuando llega a las imagenes duplicadas hace el salto para crear el efecto de bucle infinito */
carrusel.addEventListener('transitionend', () => {
  if (indice === total - 1) {
    indice = 1;
    actualizarCarrusel(false);
  } else if (indice === 0) {
    indice = total - 2;
    actualizarCarrusel(false);
  }
});

setInterval(() => {
  indice++;
  actualizarCarrusel();
}, 5000);

window.addEventListener('resize', () => actualizarCarrusel(false));