<?php
require 'dbconnect.php';
require 'otp_functions.php';
require 'send_email.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = [
                'id' => $user['user_id'],
                'username' => $user['username'],
                'email' => $user['email']
            ];

            // Generate OTP
            $otp = generateOTP($pdo, $user['user_id']);
            
            // Send OTP to user's email
            if ($otp && sendOTPEmail($user['email'], $otp)) {
                echo json_encode(['status' => 'success', 'message' => 'Login successful. OTP sent to your email.', 'redirect_url' => 'verify-otp.php']);
            } else {
                error_log('Mail Error: Failed to send OTP or generate OTP');
                echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP to your email.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid username or password.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
