document.getElementById("contactForm").addEventListener("submit", function (event) {
    const privacidadCheckbox = document.getElementById("privacidad");
    if (!privacidadCheckbox.checked) {
        event.preventDefault(); // Evita que el formulario se envíe
        alert("Debes aceptar las condiciones de privacidad para enviar el formulario.");
    }
});