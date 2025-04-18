<?php
namespace CyberSec\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        // Verificar que tengamos las credenciales necesarias
        if (empty(SMTP_USER) || empty(SMTP_PASS) || empty(SMTP_FROM_EMAIL)) {
            throw new Exception('Faltan credenciales SMTP en la configuración');
        }

        // Configuración del servidor
        $this->mail->SMTPDebug = SMTP_DEBUG_LEVEL;
        $this->mail->isSMTP();
        $this->mail->Host = SMTP_HOST;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = SMTP_USER;
        $this->mail->Password = SMTP_PASS;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = SMTP_PORT;

        // Configuración del remitente
        $this->mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $this->mail->CharSet = 'UTF-8';
    }

    /**
     * Envía un correo de recuperación de contraseña
     * @param string $to_email Email del destinatario
     * @param string $to_name Nombre del destinatario
     * @param string $reset_link Enlace de recuperación
     * @return array Resultado del envío
     */
    public function enviarRecuperacionPassword($to_email, $to_name, $reset_link)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to_email, $to_name);

            $this->mail->isHTML(true);
            $this->mail->Subject = 'Recuperación de Contraseña - CyberSec';

            // Cuerpo del correo en HTML
            $this->mail->Body = $this->getTemplateRecuperacion([
                'nombre' => $to_name,
                'reset_link' => $reset_link,
                'validez' => '24 horas'
            ]);

            // Versión texto plano
            $this->mail->AltBody = "Hola {$to_name},\n\n" .
                "Has solicitado restablecer tu contraseña.\n" .
                "Haz clic en el siguiente enlace para crear una nueva contraseña:\n" .
                "{$reset_link}\n\n" .
                "Este enlace es válido por 24 horas.\n\n" .
                "Si no solicitaste este cambio, ignora este mensaje.\n\n" .
                "Saludos,\nEquipo CyberSec";

            $this->mail->send();
            return [
                'status' => 'success',
                'message' => 'Correo enviado correctamente'
            ];
        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => DEBUG_MODE ? $e->getMessage() : 'Error al enviar el correo'
            ];
        }
    }

    /**
     * Obtiene la plantilla HTML para el correo de recuperación
     * @param array $data Datos para la plantilla
     * @return string HTML del correo
     */
    private function getTemplateRecuperacion($data)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #4299e1; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f8f9fa; }
                .button { 
                    display: inline-block; 
                    padding: 10px 20px; 
                    background-color: #4299e1; 
                    color: white; 
                    text-decoration: none; 
                    border-radius: 5px; 
                    margin: 20px 0; 
                }
                .footer { text-align: center; padding: 20px; font-size: 0.8em; color: #666; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Recuperación de Contraseña</h1>
                </div>
                <div class="content">
                    <h2>Hola ' . htmlspecialchars($data['nombre']) . '</h2>
                    <p>Has solicitado restablecer tu contraseña.</p>
                    <p>Haz clic en el siguiente botón para crear una nueva contraseña:</p>
                    <p style="text-align: center;">
                        <a href="' . htmlspecialchars($data['reset_link']) . '" class="button">
                            Restablecer Contraseña
                        </a>
                    </p>
                    <p>O copia y pega el siguiente enlace en tu navegador:</p>
                    <p>' . htmlspecialchars($data['reset_link']) . '</p>
                    <p>Este enlace es válido por ' . htmlspecialchars($data['validez']) . '.</p>
                    <p>Si no solicitaste este cambio, puedes ignorar este mensaje.</p>
                </div>
                <div class="footer">
                    <p>Este es un mensaje automático, por favor no respondas a este correo.</p>
                    <p>CyberSec &copy; ' . date('Y') . '</p>
                </div>
            </div>
        </body>
        </html>';
    }
}