<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'php/config.php';

$message = "";

// Handle application status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['application_id']) && isset($_POST['status'])) {
    $application_id = intval($_POST['application_id']);
    $new_status = in_array($_POST['status'], ['Shortlisted', 'Rejected', 'Pending']) ? $_POST['status'] : 'Pending';

    $updateQuery = "UPDATE applications SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $new_status, $application_id);

    if ($stmt->execute()) {
        $message = "✅ Application status updated successfully!";
    } else {
        $message = "❌ Failed to update application status.";
    }
}

// Fetch applications with optional filtering
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

$query = "SELECT applications.*, jobs.job_title, jobs.company_name, jobs.location 
          FROM applications 
          INNER JOIN jobs ON applications.job_id = jobs.id";

if (!empty($status_filter)) {
    $query .= " WHERE applications.status = '$status_filter'";
}

$query .= " ORDER BY applications.applied_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Jobify</title>
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

    <main class="admin-container">
        <h2>Manage Job Applications</h2>

        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>

        <!-- Filter by Status -->
        <form method="GET" class="filter-form">
            <label for="status">Filter by Status:</label>
            <select name="status" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="Pending" <?= ($status_filter == 'Pending') ? 'selected' : '' ?>>Pending</option>
                <option value="Shortlisted" <?= ($status_filter == 'Shortlisted') ? 'selected' : '' ?>>Shortlisted</option>
                <option value="Rejected" <?= ($status_filter == 'Rejected') ? 'selected' : '' ?>>Rejected</option>
            </select>
        </form>

        <?php if ($result->num_rows > 0): ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Applicant</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Resume</th>
                        <th>Cover Letter</th>
                        <th>Status</th>
                        <th>Applied On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['job_title']) ?></td>
                            <td><?= htmlspecialchars($row['job_seeker_name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <td>
                                <?php if (!empty($row['resume'])): ?>
                                    <a href="<?= htmlspecialchars($row['resume']) ?>" target="_blank" class="btn-view">View Resume</a>
                                <?php else: ?>
                                    No Resume
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($row['cover_letter'])): ?>
                                    <a href="cover-letter.php?id=<?= $row['id'] ?>" class="btn-view">View Cover Letter</a>
                                <?php else: ?>
                                    No Cover Letter
                                <?php endif; ?>
                            </td>
                            <td class="status-<?= strtolower($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></td>
                            <td><?= date("M d, Y", strtotime($row['applied_at'])) ?></td>
                            <td>
                                <form method="POST" class="status-form">
                                    <input type="hidden" name="application_id" value="<?= $row['id'] ?>">
                                    <select name="status">
                                        <option value="Pending" <?= ($row['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                                        <option value="Shortlisted" <?= ($row['status'] == 'Shortlisted') ? 'selected' : '' ?>>Shortlisted</option>
                                        <option value="Rejected" <?= ($row['status'] == 'Rejected') ? 'selected' : '' ?>>Rejected</option>
                                    </select>
                                    <button type="submit">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-applications">No applications received yet.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
</body>
</html>
