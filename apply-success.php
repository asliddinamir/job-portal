<?php
// Start the session to access session variables
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags for character set and viewport settings -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted | Jobify</title>
    <!-- Link to external CSS files -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <header>
        <div class="logo">
            <!-- Website logo -->
            <h1>Jobify</h1>
        </div>
        <nav>
            <ul>
                <!-- Navigation links -->
                <li><a href="index.php">Home</a></li>
                <li><a href="job-listings.php" class="active">Jobs</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <!-- Profile Icon with click event to toggle sidebar -->
                <div class="profile-icon" onclick="toggleSidebar()">
                    <img src="assets/images/profile.png" alt="Profile">
                </div>
            </ul>
        </nav>
    </header>

    <main class="success-container">
        <!-- Sidebar section -->
        <div id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <!-- Button to close the sidebar -->
                <button class="close-btn" onclick="toggleSidebar()">×</button>
            </div>

            <div class="sidebar-content">
                <div class="user-info">
                    <!-- Display user profile image and session variables for name and email -->
                    <img src="assets/images/profile.png" alt="Profile">
                    <p><strong><?= $_SESSION['name'] ?></strong></p>
                    <p><?= $_SESSION['email'] ?></p>
                </div>
                <hr>
                <!-- Sidebar menu with links -->
                <ul class="sidebar-menu">
                    <li><a href="profile.php"><i class="fas fa-user"></i> Your Profile</a></li>
                    <li><a href="edit-profile.php"><i class="fas fa-user-gear"></i> Edit Profile</a></li>
                    <li><a href="saved-jobs.php"><i class="fas fa-bookmark"></i> Saved Jobs</a></li>
                    <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
        <div class="success-box">
            <!-- Success message box -->
            <h2>🎉 Thank You for Applying!</h2>
            <p>Your application for the position has been successfully submitted.</p>
            <p>Our team will review your application, and if shortlisted, we will contact you via email.</p>
            <p>We appreciate your interest in joining our network of professionals.</p>
            <p>Meanwhile, feel free to browse more job listings.</p>
            <!-- Button to browse more jobs -->
            <a href="job-listings.php" class="btn">Browse More Jobs</a>
        </div>
    </main>

    <footer>
        <!-- Footer section -->
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
    <!-- Link to external JavaScript file -->
    <script src="js/script.js"></script>
</body>

</html>