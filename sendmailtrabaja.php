<?php

session_start(); // Iniciar sesión para almacenar el token CSRF


//Carga de phpMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__.'/vendor/autoload.php';  

// Verificar método POST añadido por CSRF
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die("Método no permitido.");
}



// Verificar token CSRF
if (!isset($_POST['csrf_token'], $_SESSION['csrf_token'], $_SESSION['csrf_token_expiry'])) {
    http_response_code(403);
    die("CSRF token inválido.");
}

// Verificar si el token ha expirado
if (time() > $_SESSION['csrf_token_expiry']) {
    unset($_SESSION['csrf_token']);
    unset($_SESSION['csrf_token_expiry']);
    http_response_code(403);
    die("CSRF token expirado. Por favor, recarga la página.");
}    

// Verificar si el token enviado coincide con el almacenado
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    die("CSRF token inválido.");
}

unset($_SESSION['csrf_token']); // opcional: evitar reutilización de CSRF
unset($_SESSION['csrf_token_expiry']);

// Validar reCAPTCHA    
$recaptchaSecret = '6LcqwysrAAAAAE5OExizdsYJ5sJ9dpmw0Rdrdlzw';
$recaptchaResponse = $_POST['g-recaptcha-response'];
$recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
$response = file_get_contents("{$recaptchaUrl}?secret={$recaptchaSecret}&response={$recaptchaResponse}");
$responseData = json_decode($response);

if (!$responseData->success) {
    echo "<script>
            window.location.href = '/weblapuente/empresa/recuursosHumanos/trabajaConNosotros.html';
    
        </script>";
    exit;
}

//Comprobación de aceptación de condiciones
/*if (!isset($_POST['privacidad'])) {
    die('Debes aceptar la política de privacidad para enviar el formulario.');
}*/

//Validación básica y sanificación de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = htmlspecialchars((trim($_POST['nombre'] )));
    $mail=filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $telefono= htmlspecialchars((trim($_POST['telefono'] )));
    $provincia = htmlspecialchars((trim($_POST['provincia'] )));
    $nivelEstudios = htmlspecialchars((trim($_POST["nivelEstudios"])));
    $areaFuncional = htmlspecialchars((trim($_POST["areaFuncional"])));
    $tipoContrato = htmlspecialchars((trim($_POST["tipoContrato"])));

     // Validar archivo adjunto
 /*    if (isset($_FILES["cv"]) && $_FILES["cv"]["error"] === UPLOAD_ERR_OK) {
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
    }   */
}  

if(!$mail){
    die('El email proporcionado no es válido.');
}


//Carga de variables de entorno con libreria vlucas/phpdotenv
$dotenv=Dotenv\Dotenv::createImmutable('C:/xampp/webLapuente_env');
$dotenv->load();

//Validar campos del formulario
if (!empty($nombre)&& !empty($mail) && !empty($telefono) && !empty($provincia) && !empty($nivelEstudios) && !empty($areaFuncional) && !empty($tipoContrato)){


$email = new PHPMailer(true); 

try {
    //Configuración del servidor SMTP
    $email->isSMTP(); 
    $email->Host = $_ENV['SMTP_HOST']; 
    $email->SMTPAuth = true; 
    $email->Username = $_ENV['SMTP_USERNAME'];
    $email->Password = $_ENV['SMTP_PASSWORD'];
    $email->SMTPSecure =$_ENV['SMTP_SECURE'];
    $email->Port = $_ENV['SMTP_PORT'];

    //Configuración del remitente y destinatario
    $email->setFrom($_ENV['SMTP_USERNAME'], 'Formulario de contacto');
    $email->addAddress($_ENV['SMTP_USERNAME']); 
    
    //Cuerpo del mensaje
    $email->isHTML(true);
    $email->Subject = 'Nuevo mensaje de contacto';  
    $email->Body = "<h1>Nuevo mensaje de contacto</h1>
                    <p><strong>Nombre:</strong> $nombre</p>
                    <p><strong>Email:</strong> $mail</p>
                    <p><strong>Teléfono:</strong> $telefono</p>
                    <p><strong>Provincia:</strong>$provincia</p>
                    <p><strong>Nivel de estudios:</strong>$nivelEstudios</p>
                    <p><strong>Área funcional:</strong>$areaFuncional</p>
                    <p><strong>Tipo de contrato:</strong>$tipoContrato</p>
                    ";

                

    // Envío del mensaje
    $email->send();

     // Después de procesar el formulario
     $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generar un nuevo token
     $_SESSION['csrf_token_expiry'] = time() + (30 * 60); // 30 minutos de expiración 
     echo "<script>
             document.getElementById('csrf_token').value = '{$_SESSION['csrf_token']}';
             window.location.href = '/weblapuente/empresa/recuursosHumanos/trabajaConNosotros.html';
             </script>";      
        exit;
} catch (Exception $e) {
    echo '<p>El mensaje no se ha podido enviar.</p>';
}
}else{
    echo '<p>Por favor rellena todos los campos correctamente.</p>';
}

?>