<?php
// send-email.php

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 403 Forbidden');
    exit('Acceso prohibido');
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('HTTP/1.1 403 Forbidden');
    exit('Token CSRF inválido');
}

unset($_SESSION['csrf_token']);

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$emailConfig = [
    'host' => 'smtp.zoho.com', // Servidor SMTP
    'username' => '',
    'password' => '', // Contraseña de aplicación de Zoho
    'port' => 465, // Cambia a 587 si es necesario
    'contact_email' => '',
    'to_email' => '',
];

if (!validateFormData($_POST)) {
    sendJsonResponse(false, 'Datos del formulario incompletos o incorrectos.');
    exit;
}

// Responder inmediatamente al usuario y luego enviar los correos
sendJsonResponse(true, 'El mensaje ha sido enviado correctamente.');
ob_end_flush();
flush();

try {
    sendUserConfirmationEmail($_POST, $emailConfig);
    sendAdminNotificationEmail($_POST, $emailConfig);
} catch (Exception $e) {
    error_log('Error al enviar el mensaje: ' . $e->getMessage());
}

// Función para validar los datos del formulario
function validateFormData($data)
{
    return isset($data['from_name'], $data['user_lastname'], $data['user_company'], $data['user_email'], $data['user_phone'], $data['message']) &&
        filter_var($data['user_email'], FILTER_VALIDATE_EMAIL) &&
        preg_match('/^\+?\d{7,15}$/', $data['user_phone']);
}

// Función para enviar correo de confirmación al usuario
function sendUserConfirmationEmail($formData, $config)
{
    $mail = new PHPMailer(true);
    setupSMTP($mail, $config);
    $mail->setFrom($config['contact_email'], 'ReserGo');
    $mail->addAddress($formData['user_email']);
    $mail->isHTML(true);
    $mail->Subject = 'Confirmación de recepción de tu mensaje';
    $mail->Body = "<p>Hola {$formData['from_name']},</p>
                   <p>Hemos recibido tu mensaje. Nos pondremos en contacto contigo lo antes posible.</p>
                   <p>Gracias por confiar en ReserGo.</p>
                   <p>Saludos cordiales,<br>El equipo de ReserGo</p>";
    $mail->send();
}

// Función para enviar correo con la consulta completa a tu correo personal
function sendAdminNotificationEmail($formData, $config)
{
    $mail = new PHPMailer(true);
    setupSMTP($mail, $config);
    $mail->setFrom($config['contact_email'], 'ReserGo');
    $mail->addAddress($config['to_email']);
    $mail->addReplyTo($formData['user_email'], $formData['from_name']);
    $mail->isHTML(true);
    $mail->Subject = 'Nuevo mensaje de ' . $formData['from_name'];
    $mail->Body = buildEmailBody($formData);
    $mail->send();
}

// Configurar SMTP en una función reutilizable
function setupSMTP($mail, $config)
{
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Host = $config['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['username'];
    $mail->Password = $config['password'];
    $mail->Port = $config['port'];

    // Configuración de seguridad SMTP
    if ($config['port'] == 465) {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Usa SMTPS con puerto 465
    } else {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Usa STARTTLS con puerto 587
    }

    // Comentado para producción
    // $mail->SMTPDebug = 2; // Cambia a 4 para más detalle si es necesario
    // $mail->Debugoutput = function ($str, $level) {
    //     error_log("Nivel $level: $str");
    // };
}

// Función para construir el cuerpo del correo
function buildEmailBody($data)
{
    $message = "<p>Hola,</p>";
    $message .= "<p>Has recibido un nuevo mensaje de <strong>{$data['from_name']} {$data['user_lastname']}</strong>:</p>";
    $message .= "<p style='padding: 12px; border-left: 4px solid #4f46e5; background-color: #f3f4f6; font-style: italic; font-size: 15px;'>\"{$data['message']}\"</p>";
    $message .= "<p>Empresa: {$data['user_company']}</p>";
    $message .= "<p>Correo electrónico: {$data['user_email']}</p>";
    $message .= "<p>Número de teléfono: {$data['user_phone']}</p>";
    $message .= "<p>Saludos cordiales,<br>El equipo de ReserGo</p>";
    return $message;
}

// Función para enviar una respuesta en formato JSON
function sendJsonResponse($success, $message)
{
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'message' => $message]);
}
