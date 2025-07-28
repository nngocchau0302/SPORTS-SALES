<?php
require (__DIR__ .'/../vendor/autoload.php');

//  include  "src/PHPMailer.php";
// include  "src/Exception1.php";
// // include  "src/OAuth.php";
// include  "src/POP3.php";
// include  "src/SMTP.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// require 'src/OAuthTokenProvider.php';
 require 'PHPMailer/src/Exception1.php';
 require 'PHPMailer/src/PHPMailer.php';
 require 'PHPMailer/src/SMTP.php';

 class Mailer {
    public function dathangmail($tieude,$noidung,$maildathang) {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8'; // Đặt mã hóa ký tự của email
//Create an instance; passing `true` enables exceptions


try {
    //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'dcsport323@gmail.com';                     //SMTP username
    $mail->Password   = 'dgfu mlff kewh gbgg';    
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                             //SMTP password
    $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
  
    $mail->setFrom('nngocchau0302@gmail.com', 'Mailer');
    // $mail->addAddress('chaub2103453@student.ctu.edu.vn', 'Joe User');     //Add a recipient
    $mail->addAddress($maildathang, 'Joe User');  
   //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
     $mail->addCC('chaub2103453@student.ctu.edu.vn');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $tieude;
    $mail->Body    = $noidung;
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    return true;
} catch (Exception $e) {
   return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}
}

// Tạo một đối tượng Mailer và gọi hàm dathangmail
// $mailer = new Mailer();
// $mailer->dathangmail();
?>