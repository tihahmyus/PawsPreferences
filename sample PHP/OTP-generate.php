<?php
// Include database connection
include 'dbconnect.php';

// Get email from POST data
$email = $_POST['email'] ?? null;

if (!$email) {
    echo "Email is required.";
    exit;
}

// Check if the email exists in the database
$sql = "SELECT * FROM caregivers WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    echo "Email not found in our records.";
    exit;
}

// Generate a 6-digit OTP
$otp = random_int(100000, 999999);

// Insert or update OTP in the database (optional: store OTP to verify later)
$otpSql = "UPDATE caregivers SET otp = :otp, otp_created_at = NOW() WHERE email = :email";
$otpStmt = $pdo->prepare($otpSql);
$otpStmt->bindParam(':otp', $otp);
$otpStmt->bindParam(':email', $email);

try {
    $otpStmt->execute();
} catch (PDOException $e) {
    echo "Failed to store OTP: " . $e->getMessage();
    exit;
}

// Send the OTP via email using `mail()`
$subject = "Your OTP Code";
$message = "Your OTP is: $otp. It is valid for 5 minutes.";
$headers = "From: no-reply@yourdomain.com\r\n";

if (mail($email, $subject, $message, $headers)) {
    echo "OTP sent successfully to $email!";
} else {
    echo "Failed to send OTP.";
}
