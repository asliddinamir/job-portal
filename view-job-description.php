<?php
session_start(); // Start the session
include 'php/config.php'; // Include the database configuration file

// Get job ID from URL
$job_id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Retrieve the job ID from the URL, default to 0 if not set

// Fetch job details
$query = "SELECT * FROM jobs WHERE id = ?"; // SQL query to fetch job details based on job ID
$stmt = $conn->prepare($query); // Prepare the SQL statement
$stmt->bind_param("i", $job_id); // Bind the job ID parameter to the SQL query
$stmt->execute(); // Execute the SQL query
$result = $stmt->get_result(); // Get the result set from the executed query
$job = $result->fetch_assoc(); // Fetch the job details as an associative array

// If job doesn't exist, redirect back
if (!$job) { // Check if the job details were not found
    header("Location: employer-dashboard.php"); // Redirect to the employer dashboard
    exit(); // Exit the script
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"> <!-- Set the character encoding for the document -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Set the viewport for responsive design -->
    <title>Job Description | <?= htmlspecialchars($job['job_title']) ?></title> <!-- Set the page title dynamically based on the job title -->
    <link rel="stylesheet" href="css/style.css"> <!-- Link to the external CSS stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <!-- Link to Font Awesome CSS -->
</head>

<body>
    <header>
        <div class="admin-logo">
            <h1>Jobify Admin Panel</h1> <!-- Display the admin panel logo -->
        </div>
        <nav>
            <ul>
                <li><a href="manage-jobs.php" class="active">Manage Jobs</a></li> <!-- Navigation link to manage jobs -->
                <li><a href="manage-applications.php">Manage Applications</a></li> <!-- Navigation link to manage applications -->
                <!-- Profile Icon -->
                <div class="profile-icon" onclick="toggleSidebar()">
                    <img src="assets/images/profile.png" alt="Profile">
                </div>
            </ul>
        </nav>
    </header>

    <main class="dashboard-container" id="job-description">
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
        <h2>Job Description</h2> <!-- Section heading for job description -->
        <h3><?= htmlspecialchars($job['job_title']) ?></h3> <!-- Display the job title -->
        <p><strong>Company:</strong> <?= htmlspecialchars($job['company_name']) ?></p> <!-- Display the company name -->
        <p><strong>Category:</strong> <?= htmlspecialchars($job['category']) ?></p> <!-- Display the job category -->
        <p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p> <!-- Display the job location -->
        <p><strong>Salary:</strong> $<?= htmlspecialchars($job['salary']) ?></p> <!-- Display the job salary -->
        <p class="description"><?= nl2br(htmlspecialchars($job['description'])) ?></p> <!-- Display the job description with line breaks -->

        <a href="manage-jobs.php" class="btn">Back to Dashboard</a> <!-- Link to go back to the dashboard -->
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p> <!-- Footer content -->
    </footer>
    <script src="js/script.js"></script>
</body>

</html>