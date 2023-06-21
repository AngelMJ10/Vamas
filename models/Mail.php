<?php
//Load Composer's autoloader
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;




function sendEmail($destino,$ruta, $asunto, $mensaje) {
    $mail = new PHPMailer(true);
    try {
        
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'angelitomasna200410@gmail.com';                     //SMTP username
        $mail->Password   = 'tpfqljbdhikqztwg';            
        $mail->CharSet    = 'UTF-8';                // Codificación                   //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('angelitomasna200410@gmail.com', 'Area de Sistemas');
        $mail->addAddress($destino);               // Destinatario            // Destino
        $mail->addAttachment($ruta, 'Avance.pdf'); 
        //Archivos adjuntos
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $asunto;
        $mail->Body    = $mensaje;
        $mail->AltBody = 'El mensaje requiere soporte HTML';
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
    
        $mail->send();
        echo json_encode(["status" => true]);
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        echo json_encode(["status" => false]);
    }
}

