<?php
// Start the session
session_start();

// Check if the user is not logged in or not an admin, redirect to login page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Include the database configuration file
include 'php/config.php';

// Get the application ID from the URL, default to 0 if not set
$application_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Prepare the SQL query to fetch the cover letter and job seeker name
$query = "SELECT cover_letter, job_seeker_name FROM applications WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $application_id);
$stmt->execute();
$result = $stmt->get_result();
$application = $result->fetch_assoc();

// If no application is found, display an error message and terminate the script
if (!$application) {
    die("❌ Cover letter not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cover Letter | Jobify</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <header>
        <div class="admin-logo">
            <h1 style="font-size: 26px;">Jobify Admin Panel</h1>
        </div>
        <nav>
            <ul>
                <li><a href="manage-jobs.php">Manage Jobs</a></li>
                <li><a href="manage-applications.php" class="active">Manage Applications</a></li>
                <!-- Profile Icon -->
                <div class="profile-icon" onclick="toggleSidebar()">
                    <img src="assets/images/profile.png" alt="Profile">
                </div>
            </ul>
        </nav>
    </header>

    <main class="dashboard-container">
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
                    <li><a href="profile.php"><i class="fas fa-user"></i> Your Profile</a></li>
                    <li><a href="edit-profile.php"><i class="fas fa-user-gear"></i> Edit Profile</a></li>
                    <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
        <!-- Display the job seeker's name and cover letter -->
        <h2>Cover Letter - <?= htmlspecialchars($application['job_seeker_name']) ?></h2>
        <p class="cover-letter-text"><?= nl2br(htmlspecialchars($application['cover_letter'])) ?></p>
        <br>
        <a href="manage-applications.php" class="btn">Back to Dashboard</a>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
    <script src="js/script.js"></script>
</body>

</html>