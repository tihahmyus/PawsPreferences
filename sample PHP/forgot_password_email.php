<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send password reset email
function sendPasswordResetEmail($email, $resetLink) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                           
        $mail->Host       = 'smtp.gmail.com';                     
        $mail->SMTPAuth   = true;                                  
        $mail->Username   = 'caremypet4@gmail.com';                
        $mail->Password   = 'gtsdwhjlkhycpfva';                   
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                                 
        $mail->Port       = 587;                                  

        //Recipients
        $mail->setFrom('caremypet4@gmail.com', 'CareMyPet');        
        $mail->addAddress($email);                                 

        //Content
        $mail->isHTML(true);                                  
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = 'Click this link to reset your password: <a href="' . $resetLink . '">Reset Password</a>';

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
