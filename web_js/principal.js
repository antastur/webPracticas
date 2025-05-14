document.addEventListener('DOMContentLoaded', () => {
    const menuItems = document.querySelectorAll('.menuNav > li > a'); // Selecciona los elementos principales del menú

    menuItems.forEach(item => {
        item.addEventListener('click', (e) => {
            const parentLi = item.parentElement;
            const submenu = parentLi.querySelector('ul');

            // Si hay un submenú, alterna su visibilidad
            if (submenu) {
                e.preventDefault(); // Evita el comportamiento predeterminado del enlace
                submenu.classList.toggle('open'); // Agrega o quita la clase 'open'
            }

            // Cierra otros submenús abiertos
            menuItems.forEach(otherItem => {
                const otherParentLi = otherItem.parentElement;
                const otherSubmenu = otherParentLi.querySelector('ul');

                if (otherSubmenu && otherSubmenu !== submenu) {
                    otherSubmenu.classList.remove('open');
                }
            });
        });
    });

    // Manejo del botón de hamburguesa para mostrar/ocultar el menú en dispositivos móviles
    document.querySelector('.hamburger').addEventListener('click', function () {
        menuNav.classList.toggle('active'); // Alterna la clase "active" para mostrar/ocultar el menú
    });
});