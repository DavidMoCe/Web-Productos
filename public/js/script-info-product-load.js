window.addEventListener('DOMContentLoaded', function() {
    const carritoForm = document.getElementById('carrito-form');
    // Si no se encuentra el formulario, salir
    if (!carritoForm) return;
    const buyButton = document.getElementById('buyButton');
    const loadingIcon = document.getElementById('loadingIcon');
    const links = carritoForm.querySelectorAll('a');

    // Función para aplicar los efectos al hacer clic en un enlace
    function applyClickEffects(link) {
        // Agregar clase para mostrar el icono de carga
        loadingIcon.classList.remove('hidden');
        loadingIcon.classList.add('bg-slate-100/50');
        buyButton.disabled = true;

        // Eliminar la clase después de 2 segundos
        setTimeout(function() {
            loadingIcon.classList.add('hidden');
            loadingIcon.classList.remove('bg-slate-100/50');
            buyButton.disabled = false;
        }, 3000);
    }
    
    // Agregar clase para mostrar el icono de carga
    loadingIcon.classList.remove('hidden');
    loadingIcon.classList.add('bg-slate-100/50');
    buyButton.disabled = true;

    // Eliminar la clase después de 2 segundos
    setTimeout(function() {
        loadingIcon.classList.add('hidden');
        loadingIcon.classList.remove('bg-slate-100/50');
        buyButton.disabled = false;
    }, 3000);

    // Aplicar los efectos al hacer clic en un enlace
    links.forEach(function(link) {
        link.addEventListener('click', function(event) {
            // Aplicar los efectos al hacer clic en el enlace
            applyClickEffects(link);
        });
    });
});