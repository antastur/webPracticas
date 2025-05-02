

// Al cargar la página, obtener el token CSRF desde el servidor
window.addEventListener('DOMContentLoaded', async () => {
    const response = await fetch('get-csrf-token.php');
    const data = await response.json();
    document.getElementById('csrf_token').value = data.csrf_token;
  });


document.getElementById("contactForm").addEventListener("submit", function (event) {
    const privacidadCheckbox = document.getElementById("privacidad");
    if (!privacidadCheckbox.checked) {
        event.preventDefault(); // Evita que el formulario se envíe
        alert("Debes aceptar las condiciones de privacidad para enviar el formulario.");
    }
});