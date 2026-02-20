<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/Exception.php';
require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';

// ==========================
// CONFIGURACIÓN
// ==========================

$SMTP_USER = 'contacto@sonarambiental.com';
$SMTP_PASS = 'Gad130687@';

$RECAPTCHA_SECRET = '6LcwRXEsAAAAACyhGcnkRx71GsRVWzdJ3gr8DRm9';

$DESTINO = 'mariadelapazacosta87@gmail.com';

// ==========================
// CONFIGURACIÓN (Ocultar errores en salida para no romper el JSON)
// ==========================
error_reporting(0);
ini_set('display_errors', 0);

function sendJsonResponse($success, $message = '')
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => (bool) $success, 'message' => $message]);
    exit;
}

// ==========================
// ANTI BOTS
// ==========================

if (!empty($_POST['empresa_web'])) {
    sendJsonResponse(false, 'Spam detectado.');
}

// ==========================
// VALIDACIÓN reCAPTCHA
// ==========================

$responseKey = $_POST['g-recaptcha-response'] ?? '';

if (!$responseKey) {
    sendJsonResponse(false, 'Debe completar el reCAPTCHA.');
}

$verify = file_get_contents(
    "https://www.google.com/recaptcha/api/siteverify?secret={$RECAPTCHA_SECRET}&response={$responseKey}"
);

$response = json_decode($verify);

if (!$response || !$response->success) {
    sendJsonResponse(false, 'Error en verificación reCAPTCHA.');
}

// ==========================
// VALIDACIÓN CAMPOS
// ==========================

$nombre = trim($_POST['nombre'] ?? '');
$empresa = trim($_POST['empresa'] ?? '');
if ($empresa === '') {
    $empresa = '- No especificada -';
}
$email = trim($_POST['email'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$servicio = trim($_POST['servicio'] ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');

if (!$nombre || !$email || !$telefono || !$servicio || !$mensaje) {
    sendJsonResponse(false, 'Faltan completar campos obligatorios.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendJsonResponse(false, 'Email inválido.');
}

// ==========================
// ARMADO MAIL
// ==========================

$body = "
<h2>Nueva consulta web</h2>
<table cellpadding='6'>
<tr><td><b>Nombre:</b></td><td>$nombre</td></tr>
<tr><td><b>Empresa:</b></td><td>$empresa</td></tr>
<tr><td><b>Email:</b></td><td>$email</td></tr>
<tr><td><b>Teléfono:</b></td><td>$telefono</td></tr>
<tr><td><b>Servicio:</b></td><td>$servicio</td></tr>
<tr><td><b>Mensaje:</b></td><td>$mensaje</td></tr>
</table>
";

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = 0; // Ensure no debug output leaks into JSON
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = $SMTP_USER;
    $mail->Password = $SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom($SMTP_USER, 'Sonar Ambiental');
    $mail->addAddress($DESTINO);
    $mail->addReplyTo($email, $nombre);

    $mail->isHTML(true);
    $mail->Subject = 'Nueva consulta desde la web';
    $mail->Body = $body;

    $mail->send();

    sendJsonResponse(true);

} catch (Exception $e) {
    sendJsonResponse(false, "Error al enviar: " . $mail->ErrorInfo);
}
