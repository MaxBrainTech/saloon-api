<?php
require_once "Mail.php";
$host = "smtp.mailgun.org";
$username = "postmaster@sandbox21a78f8...3eb160ebc79.mailgun.org";
$password = "75b958a6a0b...dd417c80133";
$port = "587";
$to = "recipient@address.com";
$email_from = "example@sender.com";
$email_subject = "Awesome Subject line" ;
$email_body = "This is the message body" ;
$email_address = "replyto@sender.com";
$content = "text/html; charset=utf-8";
$mime = "1.0";
$headers = array ('From' => $email_from,
'To' => $to,
'Subject' => $email_subject,
'Reply-To' => $email_address,
'MIME-Version' => $mime,
'Content-type' => $content);
$params = array  ('host' => $host,
'port' => $port,
'auth' => true,
'username' => $username,
'password' => $password);
$smtp = Mail::factory ('smtp', $params);
$mail = $smtp->send($to, $headers, $email_body);
if (PEAR::isError($mail)) {
echo("<p>" . $mail->getMessage() . "</p>");
} else {
echo("<p>Message sent successfully!</p>");
}