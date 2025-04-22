function cargarFooter() {
    const footer = `
    <footer class="footer">
        <div class="contenedorFooter" id="contenedorFooter">
            <p>Copyright 2025 Mac Soporte | Powered by Mac Soporte S.L.</p>
        </div>
    </footer>
    `;

    document.getElementById("contenedorFooter").innerHTML = footer;
}

document.addEventListener("DOMContentLoaded", function() {
    cargarFooter(); 
});