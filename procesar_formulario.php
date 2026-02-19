<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

// ==========================
// CONFIGURACIÓN
// ==========================

$SMTP_USER = 'contacto@sonarambiental.com';
$SMTP_PASS = 'Gad33224122#';   // <-- PONER CONTRASEÑA REAL
$RECAPTCHA_SECRET = '6LcwRXEsAAAAACyhGcnkRx71GsRVWzdJ3gr8DRm9';    // <-- PONER SECRET KEY REAL

$DESTINO = 'mariadelapazacosta87@gmail.com';

// ==========================
// ANTI BOTS - HONEYPOT
// ==========================

if (!empty($_POST['empresa_web'])) {
    exit("Spam detectado.");
}

// ==========================
// VALIDACIÓN reCAPTCHA
// ==========================

$responseKey = $_POST['g-recaptcha-response'] ?? '';

if (empty($responseKey)) {
    exit("Debe completar el reCAPTCHA.");
}

$verify = file_get_contents(
    "https://www.google.com/recaptcha/api/siteverify?secret={$RECAPTCHA_SECRET}&response={$responseKey}"
);

$response = json_decode($verify);

if (!$response || !$response->success) {
    exit("Error en verificación reCAPTCHA.");
}

// ==========================
// VALIDACIÓN DE CAMPOS
// ==========================

$nombre   = trim($_POST['nombre']   ?? '');
$empresa  = trim($_POST['empresa']  ?? '');
$email    = trim($_POST['email']    ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$servicio = trim($_POST['servicio'] ?? '');
$mensaje  = trim($_POST['mensaje']  ?? '');

if (!$nombre || !$empresa || !$email || !$telefono || !$servicio || !$mensaje) {
    exit("Faltan completar campos obligatorios.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Email inválido.");
}

// Sanitizar
$nombre   = htmlspecialchars($nombre);
$empresa  = htmlspecialchars($empresa);
$email    = htmlspecialchars($email);
$telefono = htmlspecialchars($telefono);
$servicio = htmlspecialchars($servicio);
$mensaje  = nl2br(htmlspecialchars($mensaje));

// ==========================
// ENVÍO SMTP HOSTINGER
// ==========================

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $SMTP_USER;
    $mail->Password   = $SMTP_PASS;
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

    $mail->CharSet = 'UTF-8';

    $mail->setFrom($SMTP_USER, 'Sonar Ambiental');
    $mail->addAddress($DESTINO);

    $mail->addReplyTo($email, $nombre);

    $mail->isHTML(true);
    $mail->Subject = 'Nueva consulta desde la web';

    $mail->Body = "
        <h2>Nueva consulta recibida</h2>
        <table cellpadding='6' cellspacing='0' border='0'>
