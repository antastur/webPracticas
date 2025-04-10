document.getElementById("privacidad").addEventListener("change", function () {
    const submitButton = document.getElementById("submitButton");
    submitButton.disabled = !this.checked; // Habilita o deshabilita el botón según el estado del checkbox
});