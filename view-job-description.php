<?php
session_start();
include 'php/config.php';

// Get job ID from URL
$job_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch job details
$query = "SELECT * FROM jobs WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

// If job doesn't exist, redirect back
if (!$job) {
    header("Location: employer-dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Description | <?= htmlspecialchars($job['job_title']) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <div class="admin-logo">
            <h1>Jobify Admin Panel</h1>
        </div>
        <nav>
            <ul>
                <li><a href="manage-jobs.php" class="active">Manage Jobs</a></li>
                <li><a href="manage-applications.php">Manage Applications</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-container" id="job-description">
        <h2>Job Description</h2>
        <h3><?= htmlspecialchars($job['job_title']) ?></h3>
        <p><strong>Company:</strong> <?= htmlspecialchars($job['company_name']) ?></p>
        <p><strong>Category:</strong> <?= htmlspecialchars($job['category']) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
        <p><strong>Salary:</strong> $<?= htmlspecialchars($job['salary']) ?></p>
        <p class="description"><?= nl2br(htmlspecialchars($job['description'])) ?></p>

        <a href="manage-jobs.php" class="btn">Back to Dashboard</a>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
</body>

</html>