<?php
session_start();
include 'php/config.php';

$message = "";

// Ensure a valid session reset token exists
if (!isset($_SESSION['reset_token']) || !isset($_SESSION['reset_email'])) {
    die("❌ Invalid or expired reset request.");
}

$email = $_SESSION['reset_email'];
$token = $_SESSION['reset_token'];

// Validate token in database
$query = "SELECT * FROM users WHERE email = ? AND reset_token = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $email, $token);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("❌ Invalid or expired reset request.");
}

// Handle password reset
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if ($new_password !== $confirm_password) {
        $message = "❌ Passwords do not match. Please try again.";
    } else {
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password and clear the token
        $updateQuery = "UPDATE users SET password = ?, reset_token = NULL WHERE email = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ss", $hashedPassword, $email);

        if ($stmt->execute()) {
            $message = "✅ Password has been reset successfully! Redirecting to login...";
            session_destroy();
            header("refresh:3;url=login.php");
        } else {
            $message = "❌ Failed to reset password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Jobify</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1>Jobify</h1>
        </div>
    </header>

    <main class="reset-password-container">
        <h2>Reset Your Password</h2>
        <p class="reset-subtext">Enter a new password for your account.</p>

        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST" class="reset-form" onsubmit="return validatePasswords()">
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="New Password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="confirm_password" id="confirm-password" placeholder="Confirm Password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm-password', this)"></i>
            </div>

            <button type="submit">Reset Password</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
