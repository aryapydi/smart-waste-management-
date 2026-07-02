<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$to = 'aryapydi@gmail.com'; // Testing email (user's email)
$name = 'Arya Pydi';

echo "<h3>Testing SMTP Email Connection...</h3>";

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPDebug = 2; // Show detailed debug output
    $mail->SMTPAuth = true;

    $mail->Username = 'aryapydi@gmail.com';
    $mail->Password = 'kzhezgwsjzsngvtm'; 

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('aryapydi@gmail.com', 'Smart Waste Management');
    $mail->addAddress($to, $name);

    $mail->isHTML(true);
    $mail->Subject = 'Test Status Update Mail';

    $mail->Body = "
    <h2>Test Mail</h2>
    <p>This is a test mail to check status update SMTP routing.</p>
    ";

    $mail->send();
    echo "<p style='color:green;'>Email sent successfully! Please check your Inbox and Spam folders.</p>";

} catch (Exception $e) {
    echo "<p style='color:red;'>Mail Sending Failed!</p>";
    echo "<pre>Mail Error: " . $mail->ErrorInfo . "</pre>";
    echo "<pre>Exception Message: " . $e->getMessage() . "</pre>";
}
?>
