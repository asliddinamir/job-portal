<?php
session_start(); // Start the session
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); // Redirect to login page if user is not logged in or not an admin
    exit();
}

// Include database connection
include 'php/config.php';

$message = ""; // Initialize message variable

// Handle application status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['application_id']) && isset($_POST['status'])) {
    $application_id = intval($_POST['application_id']); // Get application ID from POST request
    $new_status = in_array($_POST['status'], ['Shortlisted', 'Rejected', 'Pending']) ? $_POST['status'] : 'Pending'; // Validate and get new status

    $updateQuery = "UPDATE applications SET status = ? WHERE id = ?"; // Prepare update query
    $stmt = $conn->prepare($updateQuery); // Prepare statement
    $stmt->bind_param("si", $new_status, $application_id); // Bind parameters

    if ($stmt->execute()) {
        $message = "✅ Application status updated successfully!"; // Success message
    } else {
        $message = "❌ Failed to update application status."; // Failure message
    }
}

// Fetch applications with optional filtering
$status_filter = isset($_GET['status']) ? $_GET['status'] : ''; // Get status filter from GET request

$query = "SELECT applications.*, jobs.job_title, jobs.company_name, jobs.location 
          FROM applications 
          INNER JOIN jobs ON applications.job_id = jobs.id"; // Prepare query to fetch applications

if (!empty($status_filter)) {
    $query .= " WHERE applications.status = '$status_filter'"; // Add status filter to query if set
}

$query .= " ORDER BY applications.applied_at DESC"; // Order results by application date
$result = $conn->query($query); // Execute query
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Jobify</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Link to CSS file -->
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
            <p class="message"><?= $message ?></p> <!-- Display message if set -->
        <?php endif; ?>

        <!-- Filter by Status -->
        <form method="GET" class="filter-form">
            <label for="status">Filter by Status:</label>
            <select name="status" onchange="this.form.submit()"> <!-- Filter form -->
                <option value="">All</option>
                <option value="Pending" <?= ($status_filter == 'Pending') ? 'selected' : '' ?>>Pending</option>
                <option value="Shortlisted" <?= ($status_filter == 'Shortlisted') ? 'selected' : '' ?>>Shortlisted</option>
                <option value="Rejected" <?= ($status_filter == 'Rejected') ? 'selected' : '' ?>>Rejected</option>
            </select>
        </form>

        <?php if ($result->num_rows > 0): ?>
            <form method="POST" action="bulk-update.php"> <!-- Form for bulk update -->
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th> <!-- Select all checkbox -->
                            <th>Job Title</th>
                            <th>Applicant</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Resume</th>
                            <th>Cover Letter</th>
                            <th>Status</th>
                            <th>Applied On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><input type="checkbox" name="selected_applications[]" value="<?= $row['id'] ?>"></td> <!-- Checkbox for each application -->
                                <td><?= htmlspecialchars($row['job_title']) ?></td> <!-- Display job title -->
                                <td><?= htmlspecialchars($row['job_seeker_name']) ?></td> <!-- Display applicant name -->
                                <td><?= htmlspecialchars($row['email']) ?></td> <!-- Display email -->
                                <td><?= htmlspecialchars($row['phone']) ?></td> <!-- Display phone -->
                                <td>
                                    <?php if (!empty($row['resume'])): ?>
                                        <a href="<?= htmlspecialchars($row['resume']) ?>" target="_blank" class="btn-view">View Resume</a> <!-- Link to view resume -->
                                    <?php else: ?>
                                        No Resume
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($row['cover_letter'])): ?>
                                        <a href="cover-letter.php?id=<?= $row['id'] ?>" class="btn-view">View Cover Letter</a> <!-- Link to view cover letter -->
                                    <?php else: ?>
                                        No Cover Letter
                                    <?php endif; ?>
                                </td>
                                <td class="status-<?= strtolower($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></td> <!-- Display status -->
                                <td><?= date("M d, Y", strtotime($row['applied_at'])) ?></td> <!-- Display application date -->
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <div class="bulk-update-section">
                    <label for="bulk-status">Change Status to:</label>
                    <select name="bulk_status">
                        <option value="Pending">Pending</option>
                        <option value="Shortlisted">Shortlisted</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                    <button type="submit" class="btn">Update Selected</button> <!-- Button to update selected applications -->
                </div>
            </form>

        <?php else: ?>
            <p class="no-applications">No applications received yet.</p> <!-- Message if no applications found -->
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p> <!-- Footer -->
    </footer>
    
    <script>
        document.getElementById("select-all").addEventListener("click", function() {
            var checkboxes = document.querySelectorAll('input[name="selected_applications[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked); // Select or deselect all checkboxes
        });
    </script>
</body>
</html>
