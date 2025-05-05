

// Al cargar la página, obtener el token CSRF desde el servidor
window.addEventListener('DOMContentLoaded', async function() {
    //Obtener el token CSRF desde el servidor
    try{
    const response = await fetch('get-csrf-token.php');
    const data = await response.json();
    document.getElementById('csrf_token').value = data.csrf_token;
}catch (error) {
    console.error('Error al obtener el token CSRF:', error);
    }


     // Verificar si hay un error de reCAPTCHA
    /*validarCaptcha();*/
    try {
        if (localStorage.getItem('recaptchaError') === 'true') {
    Swal.fire({
                icon: 'error',
                title: 'Error de reCAPTCHA',
                text: 'Por favor, completa el reCAPTCHA antes de enviar el formulario.',
                confirmButtonText: 'Aceptar'
            }); 
    
            localStorage.removeItem('recaptchaError');
        }
    } catch (error) {
        console.error('Error al mostrar el SweetAlert:', error);
    }

    document.getElementById("contactForm").addEventListener("submit", function (event) {
         const privacidadCheckbox = document.getElementById("privacidad");
            if (!privacidadCheckbox.checked) {
                 event.preventDefault(); // Evita que el formulario se envíe
                 Swal.fire({
                    icon: 'warning',
                    title: 'Condiciones de privacidad',
                    text: 'Debes aceptar las condiciones de privacidad para enviar el formulario.',
                    confirmButtonText: 'Aceptar'
                });
            }

        });         

});


/*

function validarCaptcha(){
    var respuesta = grecaptcha.getResponse();
    if (respuesta.length === 0) {
        // No se ha completado el reCAPTCHA
        localStorage.setItem('recaptchaError', 'true');
        Swal.fire({
            icon: 'error',
            title: 'Error de reCAPTCHA',
            text: 'Por favor, completa el reCAPTCHA antes de enviar el formulario.',
            confirmButtonText: 'Aceptar'
        });
        return false;
    } else {
        // Se ha completado el reCAPTCHA
       localStorage.removeItem('recaptchaError');
        return true;
    }
}*/