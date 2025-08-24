<?php
require 'dbconnect.php';  // Include the database connection
require 'forgot_password_email.php';  // Include the email sending functionality

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Sanitize email input
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate email format
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        // Check row count
        if ($stmt->rowCount() > 0) {
            // Generate a secure token
            $token = bin2hex(random_bytes(50));

            // Save token in the database
            $updateTokenStmt = $pdo->prepare("UPDATE users SET reset_token = ?, updated_at = NOW() WHERE email = ?");
            $updateTokenStmt->execute([$token, $email]);

            // Send password reset email
            $resetLink = "http://localhost/webpet/reset_password.php?token=" . $token;

            if (sendPasswordResetEmail($email, $resetLink)) {
                // Success message
                $successMessage = "Password reset email sent. Please check your inbox.";
            } else {
                $errorMessage = "Failed to send email. Please try again later.";
            }
        } else {
            $errorMessage = "Email not found. Please enter a valid registered email.";
        }
    } else {
        $errorMessage = "Invalid email format.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Hello Pet</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"  href="login.css">
</head>

<body>

  <nav>
    <div class="top-bar">
      <a href="index.html#home" class="nav-logo">
        <img src="imgs/logo.png" alt="Logo" class="logo-image">
        <b>MY</b>Pet</a>
      <!-- Float links to the right. Hide them on small screens -->
      <div class="nav-right">
          <a href="index.php#about">About</a>
          <a href="index.php#Contact">Contact</a>
          <a href="petdisplay.php">Our Pets</a>
          <a href="services.php">Services</a>
          <a href="learn.php">Learn</a>
          <a href="donation.php">Donate</a>
          <a href="login.html">Login</a>
      </div>

        <div class="hamburger" onclick="toggleMenu()">&#9776;</div>
    </div>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu">
    <a href="index.html#about">About</a>
    <a href="index.php#Contact">Contact</a>
    <a href="petdisplay.php">Our Pets</a>
    <a href="services.php">Services</a>
    <a href="learn.php">Learn</a>
    <a href="donation.php">Donate</a>
    <a href="login.html">Login</a>
</div>

<div class="container">
    <div class="login-container">
<form method="POST">
    <input type="email" name="email" placeholder="Please enter your registered email" required>
    <input type="submit" value="Submit">
    <p>Go back to <a href="login.html">login</a> page.</p>
</form>
<!-- Spinner for processing visual -->
<div class="processing-container">
            <div id="spinner" class="spinner"></div>
        </div>

        <!-- Success or error message -->
        <?php if (isset($successMessage)): ?>
            <p style="color: green;"><?php echo $successMessage; ?></p>
        <?php elseif (isset($errorMessage)): ?>
            <p style="color: red;"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2024 MyPet. All rights reserved.</p>
</footer>

<script src="toggle-index.js"></script>

</body>
</html>
