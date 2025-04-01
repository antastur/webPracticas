 // Selecciona todos los enlaces con la clase "desp"
 const enlaces = document.querySelectorAll(".desp");

 enlaces.forEach((enlace) => {
     enlace.addEventListener("click", (event) => {
         event.preventDefault(); // Evita el comportamiento predeterminado del enlace

      // Encuentra la lista `<ul>` siguiente al enlace
         const lista = enlace.nextElementSibling;

         // Alterna la visibilidad de la lista
         if (lista.style.display === "block") {
             lista.style.display = "none";
         } else {
             lista.style.display = "block";
         }  
     }); 
 });