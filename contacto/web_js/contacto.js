

// Al cargar la página, obtener el token CSRF desde el servidor
document.addEventListener('DOMContentLoaded', async function() {
    //Obtener el token CSRF desde el servidor
    try{
    const response = await fetch('get-csrf-token.php');
    const data = await response.json();
    document.getElementById('csrf_token').value = data.csrf_token;
    }catch (error) {
    console.error('Error al obtener el token CSRF:', error);
    }

    //Gestion de habilitación del botón
    const privacidadCheckbox = document.getElementById('privacidad');
    const botonEnviar = document.getElementById('botonEnviar');

     // Función para verificar si el reCAPTCHA está completado
     function isRecaptchaCompleted() {
        return grecaptcha && grecaptcha.getResponse().length > 0;
    }

    // Función para habilitar o deshabilitar el botón
    function toggleSubmitButton() {
        if (privacidadCheckbox.checked && isRecaptchaCompleted()) {
            botonEnviar.disabled = false;
        } else {
            botonEnviar.disabled = true;
        }
    }

    // Evento para verificar el estado del checkbox
    privacidadCheckbox.addEventListener('change', toggleSubmitButton);

    // Evento para verificar el estado del reCAPTCHA
    window.recaptchaCallback = function () {
        toggleSubmitButton();
    };


     document.getElementById("contactForm").addEventListener("submit", async function (event) {
       
       // Evita la recarga de la página
        event.preventDefault(); 
    
        const formData = new FormData(this);
    
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData
            });
    
            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Mensaje enviado',
                    text: 'Tu mensaje ha sido enviado correctamente.',
                    confirmButtonText: 'Aceptar'
                });

                
                // Borrar todos los campos del formulario
                this.reset();

                // Reiniciar el estado del reCAPTCHA
                grecaptcha.reset();

                // Deshabilitar el botón de enviar nuevamente
                toggleSubmitButton();

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al enviar el formulario. Por favor, inténtalo de nuevo.',
                    confirmButtonText: 'Aceptar'
                });
            }
        } catch (error) {
            console.error('Error al enviar el formulario:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al enviar el formulario. Por favor, inténtalo de nuevo.',
                confirmButtonText: 'Aceptar'
            });
        }
    }); 

});


