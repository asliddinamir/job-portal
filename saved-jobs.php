<?php
session_start();
include 'php/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch saved jobs
$query = "SELECT jobs.id, jobs.job_title, jobs.company_name, jobs.location 
          FROM saved_jobs 
          INNER JOIN jobs ON saved_jobs.job_id = jobs.id 
          WHERE saved_jobs.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Jobs | Jobify</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Saved Jobs</h1>
    </header>

    <main class="saved-jobs-container">
        <h2>Saved Jobs</h2>

        <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li>
                        <h3><?= htmlspecialchars($row['job_title']) ?></h3>
                        <p><strong>Company:</strong> <?= htmlspecialchars($row['company_name']) ?></p>
                        <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
                        <a href="job-description.php?id=<?= $row['id'] ?>" class="btn">View Job</a>
                        <a href="unsave-job.php?job_id=<?= $row['id'] ?>" class="btn btn-danger">🗑️ Remove</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>You haven't saved any jobs yet.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
</body>
</html>
