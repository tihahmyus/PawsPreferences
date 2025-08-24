
<?php
require 'dbconnect.php';

function generateOTP($pdo, $user_id)
{
    try {
        // Generate a random 6-digit OTP
        $otp = random_int(100000, 999999);

        // Set OTP expiration time (e.g., 15 minutes) in Singapore timezone
        $expires_at = new DateTime('now', new DateTimeZone('Asia/Singapore'));
        $expires_at->modify('+15 minutes');
        $expiration_time = $expires_at->format('Y-m-d H:i:s');

        // Insert OTP into the database
        $stmt = $pdo->prepare("INSERT INTO otps (user_id, otp_code, expiration_time) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $otp, $expiration_time]);

        return $otp; // Return the generated OTP
    } catch (PDOException $e) {
        error_log("Error generating OTP: " . $e->getMessage());
        return null;
    }
}



function verifyOTP($pdo, $user_id, $otp)
{
    try {
        // Fetch the OTP data for the user
        $stmt = $pdo->prepare("SELECT otp_code, expiration_time FROM otps WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $otpData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$otpData) {
            // No OTP found for this user
            return false;
        }

        // Set timezone for expiration check (assuming expiration_time is stored in Asia/Singapore)
        $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Singapore'));
        $expirationDateTime = new DateTime($otpData['expiration_time'], new DateTimeZone('Asia/Singapore'));

        // Check if the OTP has expired
        if ($expirationDateTime < $currentDateTime) {
            // Delete expired OTP
            $deleteStmt = $pdo->prepare("DELETE FROM otps WHERE user_id = ?");
            $deleteStmt->execute([$user_id]);
            return false; // Expired OTP
        }

        // Verify the provided OTP against the stored one
        if ($otpData['otp_code'] === $otp) {
            // Delete used OTP
            $deleteStmt = $pdo->prepare("DELETE FROM otps WHERE user_id = ?");
            $deleteStmt->execute([$user_id]);
            return true; // Successful verification
        }

        return false; // Invalid OTP
    } catch (PDOException $e) {
        error_log("Error verifying OTP: " . $e->getMessage());
        return false; // Consider returning an error message or code
    }
}

?>
