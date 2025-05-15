<?php

session_start(); // Iniciar sesión para almacenar el token CSRF


//Carga de phpMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__.'/vendor/autoload.php';  

// Verificar método POST añadido por CSRF
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Método no permitido.']));
}

// Verificar token CSRF
if (!isset($_POST['csrf_token'], $_SESSION['csrf_token'], $_SESSION['csrf_token_expiry'])) {
    http_response_code(403);
    die(json_encode(['error' => 'CSRF token inválido. Por favor, recarga la página.']));
}

// Verificar si el token ha expirado
if (time() > $_SESSION['csrf_token_expiry']) {
    unset($_SESSION['csrf_token']);
    unset($_SESSION['csrf_token_expiry']);
    http_response_code(403);
    die(json_encode(['error' => 'CSRF token expirado. Por favor recarga la página.']));
}    

// Verificar si el token enviado coincide con el almacenado
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    die(json_encode(['error' => 'CSRF token inválido. Por favor recarga la página.']));
}

unset($_SESSION['csrf_token']); // opcional: evitar reutilización de CSRF
unset($_SESSION['csrf_token_expiry']);


//Carga de variables de entorno con libreria vlucas/phpdotenv
$dotenv=Dotenv\Dotenv::createImmutable('C:\xampp\htdocs\weblapuente');    
$dotenv->load();

 //Validar reCAPTCHA    
$recaptchaSecret =  $_ENV['RECAPTCHA_SECRET'];
$recaptchaResponse = $_POST['g-recaptcha-response'];
$recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
$response = file_get_contents("{$recaptchaUrl}?secret={$recaptchaSecret}&response={$recaptchaResponse}");
$responseData = json_decode($response);

if (!$responseData->success) {
    http_response_code(400);
    die(json_encode(['error' => 'Error: reCAPTCHA no válido.Recarga la página.']));
}



// Validación básica y sanificación de datos

    $nombre = htmlspecialchars((trim($_POST['nombre'] )));
    $mail=filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $telefono= htmlspecialchars((trim($_POST['telefono'] )));
    $provincia = htmlspecialchars((trim($_POST['provincia'] )));
    $nivelEstudios = htmlspecialchars((trim($_POST["nivelEstudios"])));
    $areaFuncional = htmlspecialchars((trim($_POST["areaFuncional"])));
    $tipoContrato = htmlspecialchars((trim($_POST["tipoContrato"])));

     // Validar archivo adjunto
     if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
        
    
        // Validar tipo de archivo
        $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($_FILES['cv']['type'], $allowedTypes)) {
            http_response_code(400);
            die(json_encode(['error' => 'Error: El archivo debe ser un PDF, DOC o DOCX.', 'redirect' => '/weblapuente/empresa/recursosHumanos/trabajaConNosotros.html']));
 
        }
    
        // Validar tamaño del archivo (máximo 5 MB)
        if ($_FILES['cv']['size'] > 5 * 1024 * 1024) {
            http_response_code(400);
            die(json_encode(['error' => 'Error: El archivo no debe superar los 5 MB.', 'redirect' => '/weblapuente/empresa/recursosHumanos/trabajaConNosotros.html']));
        }

        $cvTmpPath = $_FILES['cv']['tmp_name'];
        $cvName = $_FILES['cv']['name'];
        $cvSize = $_FILES['cv']['size'];
        $cvType = $_FILES['cv']['type'];
    } else {
         // Si no se adjunta un archivo, inicializa las variables como vacías
        $cvTmpPath = null;
        $cvName = null;
        $cvSize = null;
        $cvType = null;

    } 
    


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

    if($cvTmpPath===null && $cvName===null && $cvSize===null && $cvType===null){ 
        $email->send();
    }else{
        $email->addAttachment($cvTmpPath, $cvName);
        $email->send();
        
    }

    // Respuesta de éxito
    echo json_encode(['success' => true, 'redirect' => '/weblapuente/empresa/recursosHumanos/trabajaConNosotros.html']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error: No se pudo enviar el mensaje. ' . $e->getMessage()]);
}


?>