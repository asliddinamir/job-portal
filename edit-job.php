<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'php/config.php';

$message = "";

// Get job ID from URL
$job_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch job details
$query = "SELECT * FROM jobs WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    die("❌ Job not found.");
}

// Handle job update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_title = trim($_POST['job_title']);
    $company_name = trim($_POST['company_name']);
    $category = trim($_POST['category']);
    $location = trim($_POST['location']);
    $salary = trim($_POST['salary']);
    $description = trim($_POST['description']);

    $updateQuery = "UPDATE jobs SET job_title=?, company_name=?, category=?, location=?, salary=?, description=? WHERE id=?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssssssi", $job_title, $company_name, $category, $location, $salary, $description, $job_id);

    if ($stmt->execute()) {
        $message = "✅ Job updated successfully!";
    } else {
        $message = "❌ Failed to update job.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job | Jobify</title>
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

    <main class="apply-container">
        <h2 style="text-align: center;">Edit Job</h2>

        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST" class="apply-form">
            <label>Job Title:</label>
            <div class="apply-group">
                <input type="text" name="job_title" value="<?= htmlspecialchars($job['job_title']) ?>" required>
            </div>
            
            <label>Company Name:</label>
            <div class="apply-group">
                <input type="text" name="company_name" value="<?= htmlspecialchars($job['company_name']) ?>" required>
            </div>

            <label>Category:</label>
            <div class="apply-group">
                <input type="text" name="category" value="<?= htmlspecialchars($job['category']) ?>" required>
            </div>

            <label>Location:</label>
            <div class="apply-group">
                <input type="text" name="location" value="<?= htmlspecialchars($job['location']) ?>" required>
            </div>

            <label>Salary:</label>
            <div class="apply-group">
                <input type="text" name="salary" value="<?= htmlspecialchars($job['salary']) ?>" required> 
            </div>

            <label>Job Description:</label>
            <div class="apply-group">
                <textarea name="description" rows="10" required><?= htmlspecialchars($job['description']) ?></textarea>
            </div>

            <button type="submit">Update Job</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
</body>
</html>
