<?php
 
App::uses('Component', 'Controller');
App::import('Vendor', 'phpmailer', array('file' => 'phpmailer'.DS.'PHPMailerAutoload.php'));
App::import('Vendor', 'phpmailer', array('file' => 'phpmailer'.DS.'class.phpmailer.php'));
 
class EmailComponent extends Component {
 
  public function send($to, $subject, $message) {
    $sender = "admin@jtsboard.com"; // this will be overwritten by GMail
 
    $header = "X-Mailer: PHP/".phpversion() . "Return-Path: $sender";
 
    $mail = new PHPMailer();
 
    $mail->IsSMTP();
    $mail->Host = "mail.jtsboard.com"; 
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = false;
    $mail->SMTPSecure = "ssl";
    $mail->Port = 25;
    $mail->SMTPDebug  = 2; // turn it off in production
    $mail->Username   = "admin@jtsboard.com";  
    $mail->Password   = "jtsboard@123";
     
    $mail->From = $sender;
    $mail->FromName = "JTSBoard";
 
    $mail->AddAddress($to);
 
    $mail->IsHTML(true);
    $mail->CreateHeader($header);
 
    $mail->Subject = $subject;
    $mail->Body    = nl2br($message);
    $mail->AltBody = nl2br($message);
 
    // return an array with two keys: error & message
    if(!$mail->Send()) {
      return array('error' => true, 'message' => 'Mailer Error: ' . $mail->ErrorInfo);
    } else {
      return array('error' => false, 'message' =>  "Message sent!");
    }
  }
}
 
?>