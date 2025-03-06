<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted | Jobify</title>
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
                <li><a href="job-listings.php" class="active">Jobs</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                 <!-- Profile Icon -->
                 <div class="profile-icon" onclick="toggleSidebar()">
                    <img src="assets/images/profile.png" alt="Profile">
                </div>   
            </ul>
        </nav>
    </header>

    <main class="success-container">
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
                    <li><a href="#"><i class="fas fa-user"></i> Your Profile</a></li>
                    <li><a href="#"><i class="fas fa-user-gear"></i> Edit Profile</a></li>
                    <li><a href="#"><i class="fas fa-bookmark"></i> Saved Jobs</a></li>
                    <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
        <div class="success-box">
            <h2>🎉 Thank You for Applying!</h2>
            <p>Your application for the position has been successfully submitted.</p>
            <p>Our team will review your application, and if shortlisted, we will contact you via email.</p>
            <p>We appreciate your interest in joining our network of professionals.</p>
            <p>Meanwhile, feel free to browse more job listings.</p>
            <a href="job-listings.php" class="btn">Browse More Jobs</a>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>
