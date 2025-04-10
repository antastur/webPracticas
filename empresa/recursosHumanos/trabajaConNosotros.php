<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validar campos obligatorios
    $nombre = htmlspecialchars($_POST["nombre"]);
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $telefono = htmlspecialchars($_POST["telefono"]);
    $provincia = htmlspecialchars($_POST["provincia"]);
    $nivelEstudios = htmlspecialchars($_POST["nivelEstudios"]);
    $areaFuncional = htmlspecialchars($_POST["areaFuncional"]);
    $tipoContrato = htmlspecialchars($_POST["tipoContrato"]);

    // Validar archivo adjunto
    if (isset($_FILES["cv"]) && $_FILES["cv"]["error"] === UPLOAD_ERR_OK) {
        $cv = $_FILES["cv"];
        $cvNombre = $cv["name"];
        $cvTmpNombre = $cv["tmp_name"];
        $cvDestino = "uploads/" . basename($cvNombre);

        // Mover el archivo a la carpeta "uploads"
        if (!is_dir("uploads")) {
            mkdir("uploads", 0777, true);
        }
        move_uploaded_file($cvTmpNombre, $cvDestino);
    } else {
        echo "Error al subir el archivo.";
        exit;
    }

     // Validar checkbox de privacidad
     if (!isset($_POST["privacidad"])) {
        echo "Debes aceptar las condiciones de privacidad.";
        exit;
    }

    // Procesar los datos (por ejemplo, enviarlos por correo o guardarlos en una base de datos)
    echo "Formulario enviado correctamente. Gracias, $nombre.";
} else {
    echo "Método de solicitud no permitido.";
}
?>