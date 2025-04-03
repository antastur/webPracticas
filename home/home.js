
let currentIndex = 0;

function showNextImage() {
    const images = document.querySelectorAll(".carrusel img");
    images[currentIndex].style.display = "none"; // Oculta la imagen actual
    currentIndex = (currentIndex + 1) % images.length; // Calcula el índice de la siguiente imagen
    images[currentIndex].style.display = "block"; // Muestra la siguiente imagen
}

// Inicializa el carrusel mostrando solo la primera imagen y configura el temporizador
document.addEventListener("DOMContentLoaded", () => {

//Gestión del carrusel    
const images = document.querySelectorAll(".carrusel img");
images.forEach((img, index) => img.style.display = index === 0 ? "block" : "none"); // Muestra solo la primera imagen
setInterval(showNextImage, 6000); // Cambia de imagen cada 6 segundos




//Gestión de cookies

const cookiePopup = document.getElementById("cookieConsent");
const acceptButton = document.getElementById("acceptCookies");

// Verifica si el usuario ya aceptó las cookies
if (!localStorage.getItem("cookiesAccepted")) {
    cookiePopup.style.display = "flex"; // Muestra la ventana emergente
}

// Maneja el clic en el botón "Aceptar"
acceptButton.addEventListener("click", () => {
    localStorage.setItem("cookiesAccepted", "true"); // Guarda el consentimiento en localStorage
    cookiePopup.style.display = "none"; // Oculta la ventana emergente
});






});


