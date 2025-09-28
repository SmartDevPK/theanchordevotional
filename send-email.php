<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

header('Content-Type: application/json'); // default response type

// Check if it's a POST request and email is provided
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['email'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request or missing email'
    ]);
    exit;
}

// Validate the email
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
if (!$email) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid email address'
    ]);
    exit;
}

// Initialize PHPMailer
$mail = new PHPMailer(true);

try {
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'emmanuelmichaelpk3@gmail.com';     // Gmail address
    $mail->Password = 'pxcxnyzgjtxwtpux';                 // Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Optional debug logging to file
    $mail->SMTPDebug = 0; // Set to 2 for detailed debug output
    $mail->Debugoutput = function ($str, $level) {
        file_put_contents('smtp.log', gmdate('Y-m-d H:i:s') . "\t$level\t$str\n", FILE_APPEND);
    };

    // Email Details
    $mail->setFrom('emmanuelmichaelpk3@gmail.com', 'The Anchor Devotional'); // Use verified sender
    $mail->addAddress($email); // Recipient

    $subject = 'Welcome to The Anchor Devotional';
    $message = "Thank you for subscribing to The Anchor Devotional.";

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = "<p>$message</p>";
    $mail->AltBody = $message;

    // Send email
    $mail->send();

    // Success response
    echo json_encode([
        'success' => true,
        'message' => "Email sent successfully to $email",
        'redirect' => 'dashboard.php'
    ]);
    exit;

} catch (Exception $e) {
    // Failure response
    error_log("Email failed to $email: " . $mail->ErrorInfo);

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to send email. Please try again later.',
        'error' => $mail->ErrorInfo
    ]);
    exit;
}
