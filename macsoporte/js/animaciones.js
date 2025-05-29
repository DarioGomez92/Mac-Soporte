function aplicarAnimaciones(selector, claseAnimacion) {
    const elementos = document.querySelectorAll(selector);

    const observer = new IntersectionObserver((entradas) => {
        entradas.forEach(entrada => {
            if (entrada.isIntersecting) {
                entrada.target.classList.remove(claseAnimacion); // reinicia
                void entrada.target.offsetWidth; // forzar reflow
                entrada.target.classList.add(claseAnimacion);
            }
        });
    }, {
        threshold: 0.1 // cuando el 10% del elemento esté visible
    });

    elementos.forEach(el => {
        observer.observe(el);
    });
}

// Aplica a cada animación que quieras
document.addEventListener('DOMContentLoaded', () => {
    aplicarAnimaciones('.aparecer', 'aparecer');
    aplicarAnimaciones('.aparecerIzquierda', 'aparecerIzquierda');
    aplicarAnimaciones('.aparecerDerecha', 'aparecerDerecha');
});