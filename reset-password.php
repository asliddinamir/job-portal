<?php
session_start(); // Start the session
include 'php/config.php'; // Include the database configuration file

$message = ""; // Initialize the message variable

// Ensure a valid session reset token exists
if (!isset($_SESSION['reset_token']) || !isset($_SESSION['reset_email'])) {
    die("❌ Invalid or expired reset request."); // Terminate if no valid reset token or email
}

$email = $_SESSION['reset_email']; // Get the email from session
$token = $_SESSION['reset_token']; // Get the reset token from session

// Validate token in database
$query = "SELECT * FROM users WHERE email = ? AND reset_token = ?";
$stmt = $conn->prepare($query); // Prepare the SQL statement
$stmt->bind_param("ss", $email, $token); // Bind parameters
$stmt->execute(); // Execute the statement
$result = $stmt->get_result(); // Get the result
$user = $result->fetch_assoc(); // Fetch the user data

if (!$user) {
    die("❌ Invalid or expired reset request."); // Terminate if no user found
}

// Handle password reset
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = trim($_POST["password"]); // Get the new password
    $confirm_password = trim($_POST["confirm_password"]); // Get the confirm password

    if ($new_password !== $confirm_password) {
        $message = "❌ Passwords do not match. Please try again."; // Set error message if passwords do not match
    } else {
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password

        // Update the password and clear the token
        $updateQuery = "UPDATE users SET password = ?, reset_token = NULL WHERE email = ?";
        $stmt = $conn->prepare($updateQuery); // Prepare the update statement
        $stmt->bind_param("ss", $hashedPassword, $email); // Bind parameters

        if ($stmt->execute()) {
            $message = "✅ Password has been reset successfully! Redirecting to login..."; // Set success message
            session_destroy(); // Destroy the session
            header("refresh:3;url=login.php"); // Redirect to login page after 3 seconds
        } else {
            $message = "❌ Failed to reset password."; // Set error message if update fails
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
    <link rel="stylesheet" href="css/style.css"> <!-- Link to the stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <!-- Link to Font Awesome -->
</head>
<body>
    <header>
        <div class="logo">
            <h1>Jobify</h1> <!-- Website logo -->
        </div>
    </header>

    <main class="reset-password-container">
        <h2>Reset Your Password</h2>
        <p class="reset-subtext">Enter a new password for your account.</p>

        <?php if ($message): ?>
            <p class="message"><?= $message ?></p> <!-- Display message if set -->
        <?php endif; ?>

        <form method="POST" class="reset-form" onsubmit="return validatePasswords()">
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="New Password" required> <!-- New password input -->
                <i class="fas fa-eye toggle-password" onclick="togglePassword('password', this)"></i> <!-- Toggle password visibility -->
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="confirm_password" id="confirm-password" placeholder="Confirm Password" required> <!-- Confirm password input -->
                <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm-password', this)"></i> <!-- Toggle password visibility -->
            </div>

            <button type="submit">Reset Password</button> <!-- Submit button -->
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p> <!-- Footer content -->
    </footer>

    <script src="js/script.js"></script> <!-- Link to the JavaScript file -->
</body>
</html>
