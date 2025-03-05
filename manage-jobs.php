<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'php/config.php';

// Handle job deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $deleteQuery = "DELETE FROM jobs WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "✅ Job deleted successfully!";
    } else {
        $message = "❌ Failed to delete job.";
    }
}

// Fetch all jobs posted by the employer
$query = "SELECT * FROM jobs ORDER BY date_posted DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard | Jobify</title>
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

    <main id="manage-jobs">
        <h2>Manage Job Listings</h2>

        <?php if (isset($message)): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>

        <a href="add-job.php" class="btn">➕ Add New Job</a>

        <?php if ($result->num_rows > 0): ?>
            <table class="job-table">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Company</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Salary</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['job_title']) ?></td>
                            <td><?= htmlspecialchars($row['company_name']) ?></td>
                            <td><?= htmlspecialchars($row['category']) ?></td>
                            <td><?= htmlspecialchars($row['location']) ?></td>
                            <td>$<?= htmlspecialchars($row['salary']) ?></td>
                            <td class="action-btns">
                                <a href="edit-job.php?id=<?= $row['id'] ?>" class="btn2 btn-edit" >✏️ Edit</a>
                                <a href="employer-dashboard.php?delete_id=<?= $row['id'] ?>" class="btn2 btn-delete" onclick="return confirm('Are you sure you want to delete this job?')">🗑️ Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-jobs">No job listings available.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
</body>
</html>
