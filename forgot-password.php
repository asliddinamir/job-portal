<?php
session_start(); // Start the session
include 'php/config.php'; // Include the database configuration file

$message = ""; // Initialize the message variable

// Handle password reset request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]); // Get the email from the POST request and trim any whitespace

    // Check if email exists
    $query = "SELECT * FROM users WHERE email = ?"; // SQL query to select user by email
    $stmt = $conn->prepare($query); // Prepare the SQL statement
    $stmt->bind_param("s", $email); // Bind the email parameter to the SQL query
    $stmt->execute(); // Execute the query
    $result = $stmt->get_result(); // Get the result of the query
    $user = $result->fetch_assoc(); // Fetch the user data as an associative array

    if ($user) {
        // Generate a unique reset token
        $reset_token = bin2hex(random_bytes(32)); // Generate a random token for password reset
        $updateQuery = "UPDATE users SET reset_token = ? WHERE email = ?"; // SQL query to update the reset token
        $stmt = $conn->prepare($updateQuery); // Prepare the SQL statement
        $stmt->bind_param("ss", $reset_token, $email); // Bind the reset token and email parameters to the SQL query
        $stmt->execute(); // Execute the query

        // Store token in session and redirect
        $_SESSION['reset_token'] = $reset_token; // Store the reset token in the session
        $_SESSION['reset_email'] = $email; // Store the email in the session

        header("Location: reset-password.php?token=" . $reset_token); // Redirect to the reset password page with the token
        exit(); // Exit the script
    } else {
        $message = "❌ Email not found."; // Set the message if the email is not found
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Set the character encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Set the viewport for responsive design -->
    <title>Forgot Password | Jobify</title> <!-- Set the title of the page -->
    <link rel="stylesheet" href="css/style.css"> <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <!-- Link to the Font Awesome CSS file -->
</head>
<body>
    <header>
        <div class="logo">
            <h1>Jobify</h1> <!-- Display the logo text -->
        </div>
    </header>

    <main class="forgot-password-container">
        <h2>Forgot Password?</h2> <!-- Heading for the forgot password section -->
        <p class="forgot-subtext">Enter your email to receive a password reset link.</p> <!-- Subtext for the forgot password section -->

        <?php if ($message): ?> <!-- Check if there is a message to display -->
            <p class="message"><?= $message ?></p> <!-- Display the message -->
        <?php endif; ?>

        <form method="POST" class="forgot-form"> <!-- Form for submitting the email -->
            <div class="input-group">
                <i class="fas fa-envelope"></i> <!-- Font Awesome envelope icon -->
                <input type="email" name="email" placeholder="Email Address" required> <!-- Input field for the email address -->
            </div>

            <button type="submit">Send Reset Link</button> <!-- Submit button for the form -->
        </form>

        <p class="login-link"><a href="login.php">Back to Login</a></p> <!-- Link to go back to the login page -->
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p> <!-- Footer text -->
    </footer>
</body>
</html>
