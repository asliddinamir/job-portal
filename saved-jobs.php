<?php
session_start(); // Start the session
include 'php/config.php'; // Include the database configuration file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get the user ID from the session

// Fetch saved jobs for the logged-in user
$query = "SELECT jobs.id, jobs.job_title, jobs.company_name, jobs.location 
          FROM saved_jobs 
          INNER JOIN jobs ON saved_jobs.job_id = jobs.id 
          WHERE saved_jobs.user_id = ?";
$stmt = $conn->prepare($query); // Prepare the SQL statement
$stmt->bind_param("i", $user_id); // Bind the user ID parameter
$stmt->execute(); // Execute the query
$result = $stmt->get_result(); // Get the result set
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Jobs | Jobify</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Link to the CSS stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <!-- Link to Font Awesome icons -->
</head>

<body>
    <header>
        <div class="logo">
            <h1>Jobify</h1> <!-- Website logo -->
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li> <!-- Navigation link to home page -->
                <li><a href="job-listings.php">Jobs</a></li> <!-- Navigation link to job listings page -->
                <li><a href="dashboard.php">Dashboard</a></li> <!-- Navigation link to dashboard page -->
                <!-- Profile Icon -->
                <div class="profile-icon" onclick="toggleSidebar()">
                    <img src="assets/images/profile.png" alt="Profile"> <!-- Profile icon image -->
                </div>
            </ul>
        </nav>
    </header>

    <main class="job-listings">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <button class="close-btn" onclick="toggleSidebar()">×</button> <!-- Button to close the sidebar -->
            </div>

            <div class="sidebar-content">
                <div class="user-info">
                    <img src="assets/images/profile.png" alt="Profile"> <!-- User profile image -->
                    <p><strong><?= $_SESSION['name'] ?></strong></p> <!-- Display user's name -->
                    <p><?= $_SESSION['email'] ?></p> <!-- Display user's email -->
                </div>

                <hr>

                <ul class="sidebar-menu">
                    <li><a href="profile.php"><i class="fas fa-user"></i> Your Profile</a></li> <!-- Link to user's profile -->
                    <li><a href="edit-profile.php"><i class="fas fa-user-gear"></i> Edit Profile</a></li> <!-- Link to edit profile page -->
                    <li><a href="saved-jobs.php" class="active-sidebar"><i class="fas fa-bookmark"></i> Saved Jobs</a></li> <!-- Link to saved jobs page -->
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li> <!-- Link to logout -->
                </ul>
            </div>
        </div>
        <h2>Saved Jobs</h2> <!-- Heading for saved jobs section -->

        <?php if ($result->num_rows > 0): ?> <!-- Check if there are any saved jobs -->
            <ul class="job-container" style="margin-block: 20px;">
                <?php while ($row = $result->fetch_assoc()): ?> <!-- Loop through each saved job -->
                    <li class="job-card" style="list-style: none;">
                        <h3><?= htmlspecialchars($row['job_title']) ?></h3> <!-- Display job title -->
                        <p><strong>Company:</strong> <?= htmlspecialchars($row['company_name']) ?></p> <!-- Display company name -->
                        <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p> <!-- Display job location -->
                        <a href="job-description.php?id=<?= $row['id'] ?>" class="btn">View Job</a> <!-- Link to view job description -->
                        <a href="unsave-job.php?job_id=<?= $row['id'] ?>" class="btn btn-danger">🗑️ Remove</a> <!-- Link to remove saved job -->
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>You haven't saved any jobs yet.</p> <!-- Message if no jobs are saved -->
        <?php endif; ?>
        <a href="job-listings.php" class="apply-btn" style="width: 15%;">Back to Jobs</a> <!-- Link to go back to job listings page -->
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p> <!-- Footer content -->
    </footer>
    <script src="js/script.js"></script> <!-- Link to JavaScript file -->
</body>

</html>