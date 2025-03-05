<?php
// Include database connection
include 'php/config.php';

// Get job ID from URL
$job_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch job details from the database
$query = "SELECT * FROM jobs WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

// If job not found, redirect to job listings page
if (!$job) {
    header("Location: job-listings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobify | <?= htmlspecialchars($job['job_title']) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1><a href="index.php">Jobify</a></h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="job-listings.php" class="active">Jobs</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="job-detail">
        <div class="job-ctr">
            <h2><?= htmlspecialchars($job['job_title']) ?></h2>
            <p><strong>Company:</strong> <?= htmlspecialchars($job['company_name']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
            <p><strong>Salary:</strong> <?= htmlspecialchars($job['salary']) ?></p>
            <p class="description"><?= nl2br(htmlspecialchars($job['description'])) ?></p>

            <!-- Video Section (Static or Replace with Job-Specific Videos) -->
            <div class="job-video">
                <h3>Job Insights</h3>
                <video width="100%" controls>
                    <source src="assets/video/job-tips.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>

            <!-- Apply Now Button -->
            <a href="apply.php?job_id=<?= $job['id'] ?>" class="apply-btn">Apply Now</a>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
</body>
</html>
