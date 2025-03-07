<?php
session_start(); // Start the session
if (!isset($_SESSION['user_id'])) { // Check if the user is logged in
    header("Location: login.php"); // Redirect to login page if not logged in
    exit(); // Exit the script
}

include 'php/config.php'; // Include the database configuration file

$user_id = $_SESSION['user_id']; // Get the user ID from the session
$message = ""; // Initialize the message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form is submitted
    $name = trim($_POST['name']); // Get the name from the form and trim whitespace
    $phone = trim($_POST['phone']); // Get the phone from the form and trim whitespace

    $updateQuery = "UPDATE users SET name = ?, phone = ? WHERE id = ?"; // SQL query to update user details
    $stmt = $conn->prepare($updateQuery); // Prepare the SQL statement
    $stmt->bind_param("ssi", $name, $phone, $user_id); // Bind the parameters

    if ($stmt->execute()) { // Execute the statement
        $_SESSION['name'] = $name; // Update the session name
        $message = "✅ Profile updated successfully!"; // Success message
    } else {
        $message = "❌ Failed to update profile."; // Failure message
    }
}

// Fetch user details
$query = "SELECT name, phone, role FROM users WHERE id = ?"; // SQL query to fetch user details
$stmt = $conn->prepare($query); // Prepare the SQL statement
$stmt->bind_param("i", $user_id); // Bind the user ID parameter
$stmt->execute(); // Execute the statement
$result = $stmt->get_result(); // Get the result
$user = $result->fetch_assoc(); // Fetch the user details as an associative array
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"> <!-- Character encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Viewport settings -->
    <title>Edit Profile | Jobify</title> <!-- Page title -->
    <link rel="stylesheet" href="css/style.css"> <!-- Link to external CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <!-- Link to Font Awesome CSS -->
</head>

<body>
    <?php if ($user['role'] == 'admin'): ?>
    <header>
        <div class="admin-logo">
            <h1 style="font-size: 26px;">Jobify Admin Panel</h1>
        </div>
        <nav>
            <ul>
                <li><a href="manage-jobs.php" class="active">Manage Jobs</a></li>
                <li><a href="manage-applications.php">Manage Applications</a></li>
                <!-- Profile Icon -->
                <div class="profile-icon" onclick="toggleSidebar()">
                    <img src="assets/images/profile.png" alt="Profile">
                </div>
            </ul>
        </nav>
    </header>
    <?php else: ?>
    <header>
        <div class="logo">
            <h1>Jobify</h1> <!-- Logo -->
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li> <!-- Navigation link to Home -->
                <li><a href="job-listings.php">Jobs</a></li> <!-- Navigation link to Jobs -->
                <li><a href="dashboard.php">Dashboard</a></li> <!-- Navigation link to Dashboard -->
                <!-- Profile Icon -->
                <div class="profile-icon" onclick="toggleSidebar()">
                    <img src="assets/images/profile.png" alt="Profile"> <!-- Profile icon image -->
                </div>
            </ul>
        </nav>
    </header>
    <?php endif; ?>

    <main class="apply-container">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <button class="close-btn" onclick="toggleSidebar()">×</button> <!-- Close button for sidebar -->
            </div>

            <div class="sidebar-content">
                <div class="user-info">
                    <img src="assets/images/profile.png" alt="Profile"> <!-- User profile image -->
                    <p><strong><?= $_SESSION['name'] ?></strong></p> <!-- Display user name from session -->
                    <p><?= $_SESSION['email'] ?></p> <!-- Display user email from session -->
                </div>

                <hr> <!-- Horizontal line -->

                <ul class="sidebar-menu">
                    <li><a href="profile.php"><i class="fas fa-user"></i> Your Profile</a></li> <!-- Link to user profile -->
                    <li><a href="edit-profile.php" class="active-sidebar"><i class="fas fa-user-gear"></i> Edit Profile</a></li> <!-- Link to edit profile -->
                    <?php if ($user['role'] == 'job-seeker'): ?>
                    <li><a href="saved-jobs.php"><i class="fas fa-bookmark"></i> Saved Jobs</a></li> <!-- Link to saved jobs -->
                    <?php endif; ?>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li> <!-- Link to logout -->
                </ul>
            </div>
        </div>
        <h2 style="text-align: center;">Edit Profile</h2> <!-- Page heading -->
        <?php if ($message): ?> <!-- Check if there is a message to display -->
            <p class="message"><?= $message ?></p> <!-- Display the message -->
        <?php endif; ?>

        <form method="POST" class="apply-form">
            <label for="name">Full Name:</label> <!-- Label for name input -->
            <div class="apply-group">
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required> <!-- Name input field with current value -->
            </div>

            <label for="phone">Phone:</label> <!-- Label for phone input -->
            <div class="apply-group">
                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required> <!-- Phone input field with current value -->
            </div>

            <button type="submit" style="width: 30%;">Save Changes</button> <!-- Submit button -->
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p> <!-- Footer content -->
    </footer>
    <script src="js/script.js"></script> <!-- Link to external JavaScript file -->
</body>
</html>