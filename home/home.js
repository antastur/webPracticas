
let currentIndex = 0;

function showNextImage() {
    const images = document.querySelectorAll(".carousel-images img");
    images[currentIndex].style.display = "none"; // Oculta la imagen actual
    currentIndex = (currentIndex + 1) % images.length; // Calcula el índice de la siguiente imagen
    images[currentIndex].style.display = "block"; // Muestra la siguiente imagen
}

// Inicializa el carrusel mostrando solo la primera imagen y configura el temporizador
document.addEventListener("DOMContentLoaded", () => {
const images = document.querySelectorAll(".carousel-images img");
images.forEach((img, index) => img.style.display = index === 0 ? "block" : "none"); // Muestra solo la primera imagen
setInterval(showNextImage, 3000); // Cambia de imagen cada 3 segundos
});
