function cargarFooter() {
    const footer = `
        <footer class="footer">
            <div class="contenedorFooter">
                <p>Copyright 2025 Mac Soporte | Powered by Mac Soporte S.L.</p>

                <div class="footerLinks">
                    <a href="terminos.html">Términos y Condiciones</a> |
                    <a href="avisosLegales.html">Avisos Legales</a> |
                    <a href="politicaPrivacidad.html">Política de Privacidad</a> |
                    <a href="politicaCookies.html">Política de Cookies</a>
                </div>

                <div class="footerRedes">
                    <span>Síguenos en redes</span>
                    <a href="https://www.instagram.com/macsoporte/?hl=es" target="_blank" aria-label="Instagram">
                        <img src="img/instagram.svg" alt="Instagram" width="24" height="24">
                    </a>
                    <a href="https://x.com/macsoportemlg?lang=es" target="_blank" aria-label="X">
                        <img src="img/twitter.svg" alt="X" width="24" height="24">
                    </a>
                </div>
            </div>
        </footer>
    `;

    const contenedor = document.getElementById("contenedorFooter");
    if (contenedor) {
        contenedor.innerHTML = footer;
    }
}

document.addEventListener("DOMContentLoaded", cargarFooter);