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
    die("CSRF token inválido. Recarga la página");
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
    die("CSRF token inválido. Por favor, recarga la página.");
}

unset($_SESSION['csrf_token']); // opcional: evitar reutilización de CSRF
unset($_SESSION['csrf_token_expiry']);



//Carga de variables de entorno con libreria vlucas/phpdotenv
$dotenv=Dotenv\Dotenv::createImmutable('C:\xampp\htdocs\weblapuente');    
$dotenv->load();




// Validar reCAPTCHA    
$recaptchaSecret = $_ENV['RECAPTCHA_SECRET'];
$recaptchaResponse = $_POST['g-recaptcha-response'];
$recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
$response = file_get_contents("{$recaptchaUrl}?secret={$recaptchaSecret}&response={$recaptchaResponse}");
$responseData = json_decode($response);

if (!$responseData->success) {
    http_response_code(400);
    die(json_encode(['error' => 'Error: reCAPTCHA no válido.Recarga la página.']));
}
                                                                                                                                                               

//Comprobación de aceptación de condiciones
/*if (!isset($_POST['privacidad'])) {
    die('Debes aceptar la política de privacidad para enviar el formulario.');
} */

//Validación básica y sanificación de datos
//if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = htmlspecialchars((trim($_POST['nombre'] )));
    $mail=filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $telefono= htmlspecialchars((trim($_POST['telefono'] )));
    $mensaje= htmlspecialchars((trim($_POST['mensaje'] )));
//}
/*
if(!$mail){
    die('El email proporcionado no es válido.');
}
*/



//Validar campos del formulario
//if (!empty($nombre)&& !empty($mail) && !empty($telefono) && !empty($mensaje)){


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

    
    // Respuesta de éxito
    echo json_encode(['success' => true, 'redirect' => '/webLapuente/contacto/contacto.html']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error: No se pudo enviar el mensaje. ' . $e->getMessage()]);
}
     // Después de procesar el formulario
 /*    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generar un nuevo token
     $_SESSION['csrf_token_expiry'] = time() + (30 * 60); // 30 minutos de expiración 
     echo "<script>
             document.getElementById('csrf_token').value = '{$_SESSION['csrf_token']}';
             window.location.href = '/weblapuente/contacto/contacto.html';
             </script>";      
        exit;
} catch (Exception $e) {
    echo '<p>El mensaje no se ha podido enviar.</p>';
}*/
/*}else{
    echo '<p>Por favor rellena todos los campos correctamente.</p>';
}
*/
?>