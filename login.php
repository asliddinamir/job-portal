<?php
session_start(); // Start the session
include 'php/config.php'; // Include the database configuration file

$message = ""; // Initialize the message variable

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the request method is POST
    $email = trim($_POST["email"]); // Get and trim the email from the POST request
    $password = trim($_POST["password"]); // Get and trim the password from the POST request

    // Validate user
    $query = "SELECT * FROM users WHERE email = ?"; // Prepare the SQL query to fetch the user by email
    $stmt = $conn->prepare($query); // Prepare the statement
    $stmt->bind_param("s", $email); // Bind the email parameter to the query
    $stmt->execute(); // Execute the query
    $result = $stmt->get_result(); // Get the result of the query
    $user = $result->fetch_assoc(); // Fetch the user data as an associative array

    if ($user && password_verify($password, $user['password'])) { // Verify the password
        $_SESSION['user_id'] = $user['id']; // Set the user ID in the session
        $_SESSION['name'] = $user['name']; // Set the user name in the session
        $_SESSION['email'] = $user['email']; // Set the user email in the session
        $_SESSION['role'] = $user['role']; // Set the user role in the session

        if ($user['role'] == 'admin') { // Check if the user is an admin
            header("Location: manage-jobs.php"); // Redirect to the manage jobs page
        } else {
            header("Location: index.php"); // Redirect to the index page
        }
        exit(); // Exit the script
    } else {
        $message = "❌ Invalid email or password."; // Set the error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Set the character encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Set the viewport for responsive design -->
    <title>Login | Jobify</title> <!-- Set the title of the page -->
    <link rel="stylesheet" href="css/style.css"> <!-- Link to the stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <!-- Link to the Font Awesome stylesheet -->
</head>
<body>
    <header>
        <div class="logo">
            <h1>Jobify</h1> <!-- Display the logo -->
        </div>
    </header>

    <main class="login-container">
        <h2>Welcome Back!</h2> <!-- Display the welcome message -->
        <p class="login-subtext">Login to access your account and start applying for jobs.</p> <!-- Display the subtext -->

        <?php if ($message): ?> <!-- Check if there is a message -->
            <p class="message"><?= $message ?></p> <!-- Display the message -->
        <?php endif; ?>

        <form method="POST" class="login-form"> <!-- Create the login form -->
            <div class="input-group">
                <i class="fas fa-envelope"></i> <!-- Display the email icon -->
                <input type="email" name="email" placeholder="Email Address" required> <!-- Email input field -->
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i> <!-- Display the password icon -->
                <input type="password" name="password" id="password" placeholder="Password" required> <!-- Password input field -->
                <i class="fas fa-eye toggle-password" onclick="togglePassword('password', this)"></i> <!-- Toggle password visibility icon -->
            </div>

            <button type="submit">Login</button> <!-- Submit button -->
        </form>

        <p class="forgot-password"><a href="forgot-password.php">Forgot Your password?</a></p> <!-- Forgot password link -->
        <p class="register-link">Don't have an account? <a href="register.php">Register here</a></p> <!-- Register link -->
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p> <!-- Display the footer text -->
    </footer>
    <script src="js/script.js"></script> <!-- Link to the JavaScript file -->
</body>
</html>
