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
if (!isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
    $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    die("CSRF token inválido.");
}

unset($_SESSION['csrf_token']); // opcional: evitar reutilización de CSRF

// Validar reCAPTCHA
$recaptchaSecret = '6LcqwysrAAAAAE5OExizdsYJ5sJ9dpmw0Rdrdlzw';
$recaptchaResponse = $_POST['g-recaptcha-response'];
$recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
$response = file_get_contents("{$recaptchaUrl}?secret={$recaptchaSecret}&response={$recaptchaResponse}");
$responseData = json_decode($response);

if (!$responseData->success) {
    echo "<script>
            localStorage.setItem('recaptchaError', 'true');
            window.location.href = 'contacto/contacto.html';
    
        </script>";
    exit;
}

//Comprobación de aceptación de condiciones
if (!isset($_POST['privacidad'])) {
    die('Debes aceptar la política de privacidad para enviar el formulario.');
}

//Validación básica
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $nombre = htmlspecialchars((trim($_POST['nombre'] )));
    $mail=filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $telefono= htmlspecialchars((trim($_POST['telefono'] )));
    $mensaje= htmlspecialchars((trim($_POST['mensaje'] )));
}

if(!$mail){
    die('El email proporcionado no es válido.');
}

// Validar reCAPTCHA
/*
$recaptchaResponse = $_POST['g-recaptcha-response'];
$secretKey = '6LdznykrAAAAAPCJ2FMDwHlsJItCsqZeGdUSYWob';
$recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';

$response = file_get_contents($recaptchaUrl . '?secret=' . $secretKey . '&response=' . $recaptchaResponse);
$responseKeys = json_decode($response, true);

if (!$responseKeys['success']) {
    die('Error de verificación del reCAPTCHA. Por favor, inténtalo de nuevo.');
}

*/
//Carga de variables de entorno con libreria vlucas/phpdotenv
$dotenv=Dotenv\Dotenv::createImmutable('C:/xampp/webLapuente_env');
$dotenv->load();

//Validar campos del formulario
if (!empty($nombre)&& !empty($mail) && !empty($telefono) && !empty($mensaje)){


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
                    <p><strong>Mensaje:</strong></p>
                    <p>$mensaje</p>";

    // Envío del mensaje
    $email->send();
    echo '<p>El mensaje ha sido enviado correctamente.</p>';
} catch (Exception $e) {
    echo '<p>El mensaje no se ha podido enviar.</p>';
}
}else{
    echo '<p>Por favor rellena todos los campos correctamente.</p>';
}

?>