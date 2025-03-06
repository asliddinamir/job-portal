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
</head>
<body>
    <header>
        <div class="admin-logo">
            <h1>Jobify Admin Panel</h1>
        </div>
        <nav>
            <ul>
                <li><a href="manage-jobs.php">Manage Jobs</a></li>
                <li><a href="manage-applications.php" class="active">Manage Applications</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-container">
        <!-- Display the job seeker's name and cover letter -->
        <h2>Cover Letter - <?= htmlspecialchars($application['job_seeker_name']) ?></h2>
        <p class="cover-letter-text"><?= nl2br(htmlspecialchars($application['cover_letter'])) ?></p>
        <br>
        <a href="manage-applications.php" class="btn">Back to Dashboard</a>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
</body>
</html>
