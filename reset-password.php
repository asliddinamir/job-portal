<?php
session_start(); // Start the session
include 'php/config.php'; // Include database configuration

$message = ""; // Initialize the message variable
$errors = []; // Initialize the errors array

// Ensure a valid session reset token exists
if (!isset($_SESSION['reset_token']) || !isset($_SESSION['reset_email'])) {
    die("❌ Invalid or expired reset request."); // Terminate if no valid reset token or email
}

$email = $_SESSION['reset_email']; // Get the email from session
$token = $_SESSION['reset_token']; // Get the reset token from session

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

    // Validate password (minimum 5 characters)
    if (strlen($new_password) < 5) {
        $errors['password'] = "❌ Password must be at least 5 characters long.";
    }

    // Validate password match
    if ($new_password !== $confirm_password) {
        $errors['confirm_password'] = "❌ Passwords do not match.";
    }

    // Proceed only if no errors
    if (empty($errors)) {
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password and clear the reset token
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
            <p class="message"><?= $message ?></p> <!-- Success or Failure Message -->
        <?php endif; ?>

        <form method="POST" class="reset-form">
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="New Password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
            </div>
            <?php if (isset($errors['password'])): ?>
                <p class="message" style="font-size: 14px; text-align: left; margin-block: 0;"><?= $errors['password'] ?></p>
            <?php endif; ?>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="confirm_password" id="confirm-password" placeholder="Confirm Password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm-password', this)"></i>
            </div>
            <?php if (isset($errors['password'])): ?>
                <p class="message" style="font-size: 14px; text-align: left; margin-block: 0;"><?= $errors['confirm_password'] ?></p>
            <?php endif; ?>

            <button type="submit" style="width: 60%">Reset Password</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
