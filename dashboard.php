<?php
session_start(); // Start the session
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'job_seeker') {
    header("Location: login.php"); // Redirect to login page if user is not logged in or not a job seeker
    exit();
}

// Include database connection
include 'php/config.php';

$message = "";

// Get the logged-in user's email from session
$user_email = $_SESSION['email']; // Now using session data

// Fetch applications for the logged-in user
$query = "SELECT applications.*, jobs.job_title, jobs.company_name, jobs.location, applications.status, applications.applied_at
          FROM applications 
          INNER JOIN jobs ON applications.job_id = jobs.id 
          WHERE applications.email = ? 
          ORDER BY applications.applied_at DESC";

$stmt = $conn->prepare($query); // Prepare the SQL statement
$stmt->bind_param("s", $user_email); // Bind the user's email to the statement
$stmt->execute(); // Execute the statement
$result = $stmt->get_result(); // Get the result set
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Jobify</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <header>
        <div class="logo">
            <h1>Jobify</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="job-listings.php">Jobs</a></li>
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <!-- Profile Icon -->
                <div class="profile-icon" onclick="toggleSidebar()">
                    <img src="assets/images/profile.png" alt="Profile">
                </div>
            </ul>
        </nav>
    </header>

    <main class="dashboard-container">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <button class="close-btn" onclick="toggleSidebar()">×</button>
            </div>

            <div class="sidebar-content">
                <div class="user-info">
                    <img src="assets/images/profile.png" alt="Profile">
                    <p><strong><?= $_SESSION['name'] ?></strong></p>
                    <p><?= $_SESSION['email'] ?></p>
                </div>
                <hr>
                <ul class="sidebar-menu">
                    <li><a href="profile.php"><i class="fas fa-user"></i> Your Profile</a></li>
                    <li><a href="edit-profile.php"><i class="fas fa-user-gear"></i> Edit Profile</a></li>
                    <li><a href="saved-jobs.php"><i class="fas fa-bookmark"></i> Saved Jobs</a></li>
                    <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>

        <h2>My Job Applications</h2>

        <?php if ($result->num_rows > 0): // Check if there are any applications ?>
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
                    <?php while ($row = $result->fetch_assoc()): // Fetch each application ?>
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
    <script src="js/script.js"></script>
</body>

</html>