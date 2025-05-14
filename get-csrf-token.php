<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['csrf_token_expiry'] = time() + (30*60); // Token válido por 1/2 hora
}

header('Content-Type: application/json');
echo json_encode(['csrf_token' => $_SESSION['csrf_token']]);  



?>