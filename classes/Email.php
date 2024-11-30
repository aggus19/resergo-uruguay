<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email
{
    private PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->setupMailer();
    }

    private function setupMailer(): void
    {
        $this->mailer->isSMTP();
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->Host = 'smtp.zoho.com'; // Cambiado a smtp.zoho.com
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = '';
        $this->mailer->Password = ''; // Contraseña de aplicación
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mailer->Port = 465;
        $this->mailer->setFrom('', 'ReserGo Uruguay - Bienvenido');

        // Comentado para producción
        // $this->mailer->SMTPDebug = 2; // Activa depuración SMTP si es necesario
    }

    public function enviarBienvenidaRegistro(string $emailUsuario, string $nombreUsuario, string $apellidoUsuario, string $nombreBarberia, string $password): bool
    {
        try {
            $this->mailer->addAddress($emailUsuario, "$nombreUsuario $apellidoUsuario");
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Bienvenido a ReserGo - Gestión de tu Barbería';

            // Cuerpo del correo de bienvenida
            $this->mailer->Body = $this->crearCuerpoBienvenida($emailUsuario, $nombreUsuario, $apellidoUsuario, $nombreBarberia, $password);

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar el correo de bienvenida: {$this->mailer->ErrorInfo}");
            return false;
        }
    }

    // Función para construir el cuerpo del correo de bienvenida
    private function crearCuerpoBienvenida(string $emailUsuario, string $nombreUsuario, string $apellidoUsuario, string $nombreBarberia, string $password): string
    {
        $logoUrl = 'https://resergo.uy/assets/media/resergo/logo1.svg';

        return "
        <div style='font-family: \"Poppins\", Arial, sans-serif; color: #333; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;'>
            <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet' type='text/css'>
            
            <h2 style='font-family: \"Poppins\", Arial, sans-serif; color: #4CAF50; font-weight: 600;'>¡Bienvenido a ReserGo, $nombreUsuario!</h2>
            <p>Hola <strong>$nombreUsuario $apellidoUsuario</strong>,</p>
            <p>Gracias por registrarte en <strong>ReserGo</strong> para gestionar tu barbería <strong>$nombreBarberia</strong>. Ahora puedes acceder al panel de administración para gestionar citas, clientes y servicios.</p>
            <p style='margin-top: 20px;'>Aquí tienes tus datos de inicio de sesión:</p>
            <table style='width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 15px;'>
                <tr><td style='padding: 8px; background-color: #f2f2f2;'><strong>Correo Electrónico:</strong></td><td style='padding: 8px;'>$emailUsuario</td></tr>
                <tr><td style='padding: 8px; background-color: #f2f2f2;'><strong>Contraseña:</strong></td><td style='padding: 8px;'>$password</td></tr>
                <tr><td style='padding: 8px; background-color: #f2f2f2;'><strong>Nombre de la Barbería:</strong></td><td style='padding: 8px;'>$nombreBarberia</td></tr>
            </table>
            <p style='margin-top: 20px;'>Puedes iniciar sesión en tu cuenta usando el siguiente enlace:</p>
            <p style='text-align: center; margin-top: 20px;'>
                <a href='https://admin.resergo.uy/' style='display: inline-block; padding: 12px 25px; background-color: #883FFF; color: #fff; text-decoration: none; border-radius: 5px; font-weight: 500;'>Iniciar Sesión</a>
            </p>
            <p style='color: #888; margin-top: 20px;'>Saludos,<br>El equipo de ReserGo</p>
            <div style='text-align: center; margin-top: 30px;'>
                <img src='$logoUrl' alt='Logo de ReserGo' style='width: 100px; height: auto; margin-bottom: 10px;'>
                <p style='color: #888; font-size: 12px;'>© " . date("Y") . " ReserGo. Todos los derechos reservados.</p>
            </div>
        </div>
        ";
    }
}
