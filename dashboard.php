<?php
// Include database connection
include 'php/config.php';

$message = "";

// Simulating logged-in user (Replace this with session authentication later)
$user_email = "asa@gmail.com"; // Replace with dynamic session data

// Fetch applications for the logged-in user
$query = "SELECT applications.*, jobs.job_title, jobs.company_name, jobs.location 
          FROM applications 
          INNER JOIN jobs ON applications.job_id = jobs.id 
          WHERE applications.email = ? 
          ORDER BY applications.applied_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Jobify</title>
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
                <li><a href="job-listings.php">Jobs</a></li>
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-container">
        <h2>My Job Applications</h2>

        <?php if ($result->num_rows > 0): ?>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Company</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Applied On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['job_title']) ?></td>
                            <td><?= htmlspecialchars($row['company_name']) ?></td>
                            <td><?= htmlspecialchars($row['location']) ?></td>
                            <td class="status-<?= strtolower($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></td>
                            <td><?= date("M d, Y", strtotime($row['applied_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-applications">You have not applied for any jobs yet.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
</body>
</html>
