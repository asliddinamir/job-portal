<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'php/config.php';

$message = "";

// Handle job submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_title = trim($_POST['job_title']);
    $company_name = trim($_POST['company_name']);
    $category = trim($_POST['category']);
    $location = trim($_POST['location']);
    $salary = trim($_POST['salary']);
    $description = trim($_POST['description']);

    $query = "INSERT INTO jobs (job_title, company_name, category, location, salary, description, date_posted) 
              VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $job_title, $company_name, $category, $location, $salary, $description);

    if ($stmt->execute()) {
        $message = "✅ Job added successfully!";
    } else {
        $message = "❌ Failed to add job.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Job | Jobify</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <!-- FontAwesome Icons -->
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
        <h2 style="text-align: center;">Add new job</h2>
        
        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST" class="apply-form">
            <div class="apply-group">
                <i class="fas fa-briefcase"></i>
                <input type="text" name="job_title" placeholder="Job Title" required>
            </div>

            <div class="apply-group">
                <i class="fas fa-building"></i>
                <input type="text" name="company_name" placeholder="Company Name" required>
            </div>

            <div class="apply-group">
                <i class="fas fa-tags"></i>
                <input type="text" name="category" placeholder="Category" required>
            </div>

            <div class="apply-group">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" name="location" placeholder="Location" required>
            </div>

            <div class="apply-group">
                <i class="fas fa-money-bill-wave"></i>
                <input type="text" name="salary" placeholder="Salary" required>
            </div>

            <div class="apply-group">
                <i class="fas fa-address-card"></i>
                <textarea name="description" placeholder="Job Description" rows="5" required style="resize: none;"></textarea>
            </div>

            <button type="submit">Add Job</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
</body>
</html>
