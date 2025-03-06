<?php
session_start(); // Start the session
include 'php/config.php'; // Include database configuration

$message = ""; // Initialize global message variable

// Handle registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $password = trim($_POST["password"]);
    $role = ($_POST["role"] == "admin") ? "admin" : "job_seeker"; // Default role: job_seeker

    // Validation flags
    $errors = [];

    // Validate phone number (only digits, length 10-15)
    if (!preg_match("/^\d{10,15}$/", $phone)) {
        $errors['phone'] = "❌ Phone number must be 10-15 digits.";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "❌ Invalid email format.";
    }

    // Validate password (minimum 5 characters)
    if (strlen($password) < 5) {
        $errors['password'] = "❌ Password must be at least 5 characters long.";
    }

    // Proceed only if there are no validation errors
    if (empty($errors)) {
        // Check if email already exists
        $checkQuery = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "❌ Email is already registered. Please login instead.";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into the database
            $insertQuery = "INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("sssss", $name, $email, $phone, $hashedPassword, $role);

            if ($stmt->execute()) {
                $message = "✅ Account created successfully! Redirecting to login...";
                header("refresh:2;url=login.php");
            } else {
                $message = "❌ Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Jobify</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <header>
        <div class="logo">
            <h1>Jobify</h1>
        </div>
    </header>

    <main class="register-container">
        <h2>Create an Account</h2>
        <p class="register-subtext">Sign up to start applying for jobs or managing applications.</p>

        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST" class="register-form">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="name" placeholder="Full Name" required>
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email Address" required>
            </div>
            <?php if (isset($errors['email'])): ?>
                <p class="message" style="font-size: 13px; margin-block: 0; text-align: left;"><?= $errors['email'] ?></p>
            <?php endif; ?>

            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="tel" name="phone" placeholder="Phone Number" required>
            </div>
            <?php if (isset($errors['phone'])): ?>
                <p class="message" style="font-size: 13px; margin-block: 0; text-align: left;"><?= $errors['phone'] ?></p>
            <?php endif; ?>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="register-password" placeholder="Password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('register-password', this)"></i>
            </div>
            <?php if (isset($errors['password'])): ?>
                <p class="message" style="font-size: 13px; margin-block: 0; text-align: left;"><?= $errors['password'] ?></p>
            <?php endif; ?>

            <div class="input-group">
                <i class="fas fa-user-tag"></i>
                <select name="role">
                    <option value="job_seeker">Job Seeker</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button type="submit">Register</button>
        </form>

        <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>

    <script src="js/script.js"></script> <!-- Include JavaScript -->
</body>

</html>