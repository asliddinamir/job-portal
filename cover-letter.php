<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'php/config.php';

// Get application ID
$application_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the cover letter
$query = "SELECT cover_letter, job_seeker_name FROM applications WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $application_id);
$stmt->execute();
$result = $stmt->get_result();
$application = $result->fetch_assoc();

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
                <li><a href="admin-dashboard.php" class="active">Admin Panel</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-container">
        <h2>Cover Letter - <?= htmlspecialchars($application['job_seeker_name']) ?></h2>
        <p class="cover-letter-text"><?= nl2br(htmlspecialchars($application['cover_letter'])) ?></p>
        <br>
        <a href="admin-dashboard.php" class="btn">Back to Dashboard</a>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
</body>
</html>
