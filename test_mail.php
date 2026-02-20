<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/Exception.php';
require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';

$SMTP_USER = 'contacto@sonarambiental.com';
$SMTP_PASS = 'Gad130687@';

$DESTINO = 'mariadelapazacosta87@gmail.com';

$mail = new PHPMailer(true);

try {

  $mail->isSMTP();
  $mail->Host = 'smtp.hostinger.com';
  $mail->SMTPAuth = true;
  $mail->Username = $SMTP_USER;
  $mail->Password = $SMTP_PASS;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
  $mail->Port = 465;
  $mail->CharSet = 'UTF-8';

  $mail->SMTPDebug = 2;

  $mail->setFrom($SMTP_USER, 'Sonar Ambiental');
  $mail->addAddress($DESTINO);

  $mail->Subject = 'TEST SMTP Hostinger';
  $mail->Body = 'Prueba SMTP exitosa';

  $mail->send();

  echo "<h2 style='color:green'>✅ Mail enviado correctamente</h2>";

} catch (Exception $e) {
  echo "<h2 style='color:red'>❌ Error SMTP</h2>";
  echo "<pre>" . $mail->ErrorInfo . "</pre>";
}
