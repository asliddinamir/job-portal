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
                <li><a href="dashboard.php">Dashboard</a></li>
                <!-- Profile Icon -->
                <div class="profile-icon" onclick="toggleSidebar()">
                    <img src="assets/images/profile.png" alt="Profile">
                </div>
            </ul>
        </nav>
    </header>

    <main class="job-listings">
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
                    <li><a href="saved-jobs.php" class="active-sidebar"><i class="fas fa-bookmark"></i> Saved Jobs</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
        <h2>Saved Jobs</h2>

        <?php if ($result->num_rows > 0): ?>
            <ul class="job-container" style="margin-block: 20px;">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="job-card" style="list-style: none;">
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
        <a href="job-listings.php" class="apply-btn" style="width: 15%;">Back to Jobs</a>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
    <script src="js/script.js"></script>
</body>

</html>