<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$mail = new PHPMailer(true);

try {
  $mail->isSMTP();
  $mail->Host = 'smtp.hostinger.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'contacto@sonarambiental.com';
  $mail->Password = 'TU_PASSWORD_REAL';
  $mail->SMTPSecure = 'ssl';
  $mail->Port = 465;

  $mail->setFrom('contacto@sonarambiental.com', 'Test Sonar');
  $mail->addAddress('mariadelapazacosta87@gmail.com');

  $mail->Subject = 'TEST SMTP HOSTINGER';
  $mail->Body = 'Si recibís este correo, el SMTP funciona correctamente.';

  $mail->send();
  echo "OK - Mail enviado correctamente";

} catch (Exception $e) {
  echo "ERROR: {$mail->ErrorInfo}";
}
