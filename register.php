<?php
session_start(); // Start the session
include 'php/config.php'; // Include the database configuration file

$message = ""; // Initialize the message variable

// Handle registration
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form is submitted
    $name = trim($_POST["name"]); // Get and trim the name
    $email = trim($_POST["email"]); // Get and trim the email
    $phone = trim($_POST["phone"]); // Get and trim the phone number
    $password = trim($_POST["password"]); // Get and trim the password
    $role = ($_POST["role"] == "admin") ? "admin" : "job_seeker"; // Set role, default to job_seeker if invalid

    // Check if email already exists
    $checkQuery = "SELECT * FROM users WHERE email = ?"; // SQL query to check if email exists
    $stmt = $conn->prepare($checkQuery); // Prepare the SQL statement
    $stmt->bind_param("s", $email); // Bind the email parameter
    $stmt->execute(); // Execute the statement
    $result = $stmt->get_result(); // Get the result

    if ($result->num_rows > 0) { // If email already exists
        $message = "❌ Email is already registered. Please login instead."; // Set error message
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password

        // Insert new user into the database
        $insertQuery = "INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)"; // SQL query to insert new user
        $stmt = $conn->prepare($insertQuery); // Prepare the SQL statement
        $stmt->bind_param("sssss", $name, $email, $phone, $hashedPassword, $role); // Bind the parameters

        if ($stmt->execute()) { // If insertion is successful
            $message = "✅ Account created successfully! Redirecting to login..."; // Set success message
            header("refresh:2;url=login.php"); // Redirect to login page after 2 seconds
        } else {
            $message = "❌ Registration failed. Please try again."; // Set error message
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Character encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive design -->
    <title>Register | Jobify</title> <!-- Page title -->
    <link rel="stylesheet" href="css/style.css"> <!-- Link to CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <!-- FontAwesome Icons -->
</head>
<body>
    <header>
        <div class="logo">
            <h1>Jobify</h1> <!-- Website logo -->
        </div>
    </header>

    <main class="register-container">
        <h2>Create an Account</h2> <!-- Page heading -->
        <p class="register-subtext">Sign up to start applying for jobs or managing applications.</p> <!-- Subtext -->

        <?php if ($message): ?> <!-- Display message if it exists -->
            <p class="message"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST" class="register-form"> <!-- Registration form -->
            <div class="input-group">
                <i class="fas fa-user"></i> <!-- User icon -->
                <input type="text" name="name" placeholder="Full Name" required> <!-- Name input -->
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i> <!-- Email icon -->
                <input type="email" name="email" placeholder="Email Address" required> <!-- Email input -->
            </div>

            <div class="input-group">
                <i class="fas fa-phone"></i> <!-- Phone icon -->
                <input type="tel" name="phone" placeholder="Phone Number" required> <!-- Phone input -->
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i> <!-- Password icon -->
                <input type="password" name="password" id="register-password" placeholder="Password" required> <!-- Password input -->
                <i class="fas fa-eye toggle-password" onclick="togglePassword('register-password', this)"></i> <!-- Toggle password visibility -->
            </div>

            <div class="input-group">
                <i class="fas fa-user-tag"></i> <!-- Role icon -->
                <select name="role"> <!-- Role selection -->
                    <option value="job_seeker">Job Seeker</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button type="submit">Register</button> <!-- Submit button -->
        </form>

        <p class="login-link">Already have an account? <a href="login.php">Login here</a></p> <!-- Link to login page -->
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p> <!-- Footer text -->
    </footer>

    <script src="js/script.js"></script> <!-- Link to JavaScript file -->
</body>
</html>
