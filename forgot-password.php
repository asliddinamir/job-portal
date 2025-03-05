<?php
session_start();
include 'php/config.php';

$message = "";

// Handle password reset request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    // Check if email exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Generate a unique reset token
        $reset_token = bin2hex(random_bytes(32));
        $updateQuery = "UPDATE users SET reset_token = ? WHERE email = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ss", $reset_token, $email);
        $stmt->execute();

        // Store token in session and redirect
        $_SESSION['reset_token'] = $reset_token;
        $_SESSION['reset_email'] = $email;

        header("Location: reset-password.php?token=" . $reset_token);
        exit();
    } else {
        $message = "❌ Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Jobify</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1><a href="index.php">Jobify</a></h1>
        </div>
    </header>

    <main class="forgot-password-container">
        <h2>Forgot Password?</h2>
        <p class="forgot-subtext">Enter your email to receive a password reset link.</p>

        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST" class="forgot-form">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email Address" required>
            </div>

            <button type="submit">Send Reset Link</button>
        </form>

        <p class="login-link"><a href="login.php">Back to Login</a></p>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
</body>
</html>
