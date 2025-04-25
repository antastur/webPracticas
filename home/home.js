
let currentIndex = 0;

function showNextImage() {
    const images = document.querySelectorAll(".carrusel img");
    images[currentIndex].style.display = "none"; // Oculta la imagen actual
    currentIndex = (currentIndex + 1) % images.length; // Calcula el índice de la siguiente imagen
    images[currentIndex].style.display = "block"; // Muestra la siguiente imagen
}

// Inicializa el carrusel mostrando solo la primera imagen y configura el temporizador
document.addEventListener("DOMContentLoaded", () => {

//Para la gestión de cookies
const cookiePopup = document.getElementById("cookieConsent");
const cookieOverlay = document.getElementById("cookieOverlay");
const acceptButton = document.getElementById("acceptCookies");
const rejectButton = document.getElementById("rejectCookies");

 // Verifica si el usuario ya aceptó o rechazó las cookies
 const cookiesAccepted = sessionStorage.getItem("cookiesAccepted");


// Verifica si el usuario ya aceptó las cookies
if (!cookiesAccepted) {
    cookiePopup.style.display = "flex"; // Muestra la ventana emergente
    cookieOverlay.style.display = "block"; // Muestra el overlay
}

if (cookiesAccepted === "true") {
    // Si las cookies fueron aceptadas, no muestra la ventana ni el overlay
    cookiePopup.style.display = "none";
    cookieOverlay.style.display = "none";

    document.cookie = "cookie_notice_accepted=false; " +
                  "expires=Sat, 03 May 2025 06:54:57 GMT; " +
                  "domain=transporteslapuente.com; " +
                  "path=/; " +
                  "secure; " +
                  "samesite=None";

} else {
    // Si no hay decisión previa o si las cookies fueron rechazadas, muestra la ventana y el overlay
    cookiePopup.style.display = "flex";
    cookieOverlay.style.display = "block";
}

// Maneja el clic en el botón "Aceptar"
acceptButton.addEventListener("click", () => {
    sessionStorage.setItem("cookiesAccepted", "true"); // Guarda el consentimiento en localStorage
    cookiePopup.style.display = "none"; // Oculta la ventana emergente
    cookieOverlay.style.display = "none"; // Oculta el overlay
});

// Maneja el clic en el botón "Rechazar"
rejectButton.addEventListener("click", () => {
    sessionStorage.setItem("cookiesAccepted", "false"); // Guarda el rechazo en localStorage
    cookiePopup.style.display = "flex"; // Oculta la ventana emergente
    cookieOverlay.style.display = "block"; // Oculta el overlay
});


//Gestión del carrusel    
const images = document.querySelectorAll(".carrusel img");
images.forEach((img, index) => img.style.display = index === 0 ? "block" : "none"); // Muestra solo la primera imagen
setInterval(showNextImage, 6000); // Cambia de imagen cada 6 segundos


});

