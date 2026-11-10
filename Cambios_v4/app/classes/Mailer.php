<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    
    private $mail;
    
    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->configurarSMTP();
    }
    
    private function configurarSMTP() {
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp-relay.brevo.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = '9ad073001@smtp-brevo.com';
        $this->mail->Password = 'HUFyKk2PfMt4g7D1';
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587;
        $this->mail->CharSet = 'UTF-8';
        
        $this->mail->setFrom('sanplacidoprueba@gmail.com', 'San Plácido');
    }
    
    /**
     * @param string $destinatario 
     * @param string $codigo
     * @return array
     */
    public function enviarCodigoVerificacion($destinatario, $codigo) {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($destinatario);
            
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Código de verificación - San Plácido';
            $this->mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #333;'>Verificación de correo</h2>
                    <p>Hola,</p>
                    <p>Tu código de verificación es:</p>
                    <div style='background: #f4f4f4; padding: 20px; text-align: center; margin: 20px 0;'>
                        <h1 style='color: #007bff; font-size: 32px; margin: 0;'>$codigo</h1>
                    </div>
                    <p>Este código expira en 15 minutos.</p>
                    <p>Si no solicitaste este código, podés ignorar este mensaje.</p>
                    <hr style='margin: 30px 0;'>
                    <p style='color: #666; font-size: 12px;'>San Plácido - Sistema de Registro</p>
                </div>
            ";
            
            $this->mail->send();
            
            return [
                'success' => true,
                'error' => null
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $this->mail->ErrorInfo
            ];
        }
    }
    
    public function enviarGenerico($destinatario, $nombreDestinatario, $asunto, $titulo, $contenidoHtml, $ctaTexto = null, $ctaUrl = null) {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($destinatario, $nombreDestinatario ?: '');
            $this->mail->isHTML(true);
            $this->mail->Subject = $asunto;
            $this->mail->Body = $this->plantilla($titulo, $contenidoHtml, $ctaTexto, $ctaUrl);
            $this->mail->send();

            return ['success' => true, 'error' => null];
        } catch (Exception $e) {
            error_log('Mailer::enviarGenerico() - ' . $e->getMessage());
            return ['success' => false, 'error' => $this->mail->ErrorInfo];
        }
    }

    private function plantilla($titulo, $contenidoHtml, $ctaTexto = null, $ctaUrl = null) {
        $boton = '';
        if ($ctaTexto && $ctaUrl) {
            $boton = "
            <div style='text-align:center;margin:24px 0 8px;'>
                <a href='{$ctaUrl}' style='background:#5c2d0a;color:#fff;text-decoration:none;
                   padding:12px 28px;border-radius:8px;font-weight:700;display:inline-block;font-family:Georgia,serif;'>
                   {$ctaTexto}
                </a>
            </div>";
        }

        return "
        <div style='font-family:Georgia,serif;max-width:560px;margin:0 auto;background:#fdfaf6;'>
            <div style='background:#5c2d0a;padding:20px 24px;'>
                <span style='color:#fff;font-size:20px;font-weight:700;'>San Plácido</span>
            </div>
            <div style='padding:28px 24px;color:#2c1a0e;'>
                <h2 style='color:#5c2d0a;margin:0 0 16px;font-size:20px;'>{$titulo}</h2>
                <div style='font-size:15px;line-height:1.6;'>{$contenidoHtml}</div>
                {$boton}
            </div>
            <div style='padding:16px 24px;background:#f3ebe0;color:#8a7560;font-size:12px;text-align:center;'>
                San Plácido — Carpintería · Córdoba
            </div>
        </div>";
    }

    /**
     * @return string 
     */
    public static function generarCodigoVerificacion() {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}