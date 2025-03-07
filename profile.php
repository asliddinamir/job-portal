<?php
session_start(); // Start the session
if (!isset($_SESSION['user_id'])) { // Check if the user is not logged in
    header("Location: login.php"); // Redirect to login page
    exit(); // Exit the script
}

include 'php/config.php'; // Include the database configuration file

$user_id = $_SESSION['user_id']; // Get the user ID from the session
$query = "SELECT name, email, phone, role FROM users WHERE id = ?"; // SQL query to fetch user details
$stmt = $conn->prepare($query); // Prepare the SQL statement
$stmt->bind_param("i", $user_id); // Bind the user ID parameter
$stmt->execute(); // Execute the statement
$result = $stmt->get_result(); // Get the result set
$user = $result->fetch_assoc(); // Fetch the user details as an associative array
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"> <!-- Set the character encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Set the viewport for responsive design -->
    <title>Your Profile | Jobify</title> <!-- Page title --> <!-- spell-check-ignore -->
    <link rel="stylesheet" href="css/style.css"> <!-- Link to external CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <!-- Link to Font Awesome CSS -->
</head>

<body>
    <?php if ($user['role'] == 'admin'): ?>
    <header>
        <div class="admin-logo">
            <h1 style="font-size: 26px;">Jobify Admin Panel</h1> <!-- spell-check-ignore -->
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
            <h1>Jobify</h1> <!-- Website logo --> <!-- spell-check-ignore -->
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li> <!-- Navigation link to Home page -->
                <li><a href="job-listings.php">Jobs</a></li> <!-- Navigation link to Jobs page -->
                <li><a href="dashboard.php">Dashboard</a></li>
                <!-- Profile Icon -->
                <div class="profile-icon" onclick="toggleSidebar()">
                    <img src="assets/images/profile.png" alt="Profile">
                </div>
            </ul>
        </nav>
    </header>
    <?php endif; ?>

    <main class="apply-container">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <button class="close-btn" onclick="toggleSidebar()">×</button>
            </div>

            <div class="sidebar-content">
                <div class="user-info">
                    <img src="assets/images/profile.png" alt="Profile">
                    <p><strong><?= $_SESSION['name'] ?></strong></p>
                    <p><?= $_SESSION['email'] ?></p>
                </div>

                <hr>

                <ul class="sidebar-menu">
                    <li><a href="profile.php" class="active-sidebar"><i class="fas fa-user"></i> Your Profile</a></li>
                    <li><a href="edit-profile.php"><i class="fas fa-user-gear"></i> Edit Profile</a></li>
                    <?php if ($user['role'] == 'job_seeker'): ?>
                    <li><a href="saved-jobs.php"><i class="fas fa-bookmark"></i> Saved Jobs</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
        <div class="apply-from user-info">
            <h2 style="text-align: center;">Your Profile</h2>
            <img src="assets/images/profile.png" alt="Profile Picture">
            <br>
            <h2><?= htmlspecialchars($user['name']) ?></h2>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <br>
            <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
            <br>
            <a href="edit-profile.php" class="btn">Edit Profile</a>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p> <!-- spell-check-ignore -->
    </footer>
    <script src="js/script.js"></script>
</body>

</html>