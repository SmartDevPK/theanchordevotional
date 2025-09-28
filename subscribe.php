<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Set JSON response header
header('Content-Type: application/json');

// Database config
$host = "localhost";
$port = 3307;
$username = "root";
$password = "";
$database = "prayer_db";

// Check POST and email
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['email'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request or missing email']);
    exit;
}

$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
if (!$email) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// Connect to DB
$conn = new mysqli($host, $username, $password, $database, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'] ?? '';

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM subscribers WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "This email is already subscribed!";
    } else {
        // Proceed with insert
        $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            echo "Thank you for subscribing to The Anchor Devotional! You will receive your first devotional tomorrow morning.";
        } else {
            echo "Database error: " . $stmt->error;
        }

        $stmt->close();
    }

    $check->close();
} else {
    echo "Invalid email address!";
}

$conn->close();

// Send welcome email with PHPMailer
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'emmanuelmichaelpk3@gmail.com';
    $mail->Password = 'pxcxnyzgjtxwtpux'; // Use app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    //eW8_c.G,lGCfH3(S

    $mail->setFrom('emmanuelmichaelpk3@gmail.com', 'The Anchor Devotional');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Welcome to The Anchor Devotional';
    $mail->Body = '<p>Thank you for subscribing to The Anchor Devotional. You will receive your first devotional tomorrow morning.</p>';
    $mail->AltBody = 'Thank you for subscribing to The Anchor Devotional. You will receive your first devotional tomorrow morning.';

    $mail->send();

} catch (Exception $e) {
    // Email sending failed but subscription succeeded
    error_log("Email sending failed: " . $mail->ErrorInfo);
    echo json_encode(['success' => true, 'message' => 'Subscription successful, but failed to send welcome email.']);
}

exit;
