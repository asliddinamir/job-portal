<?php
session_start(); // Start the session to track user data
if (!isset($_SESSION['email'])) { // Check if the user is not logged in
    header("Location: login.php"); // Redirect to login page if not logged in
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"> <!-- Set character encoding for the document -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Ensure proper rendering on mobile devices -->
    <title>Jobify | Find Your Dream Job</title> <!-- Title of the webpage -->
    <link rel="stylesheet" href="css/style.css"> <!-- Link to external CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <!-- Link to Font Awesome for icons -->
</head>

<body>
    <header>
        <div class="logo">
            <h1>Jobify</h1> <!-- Website logo -->
        </div>
        <nav>
            <ul>
                <li><a href="index.php" class="active">Home</a></li> <!-- Navigation link to Home page -->
                <li><a href="job-listings.php">Jobs</a></li> <!-- Navigation link to Jobs page -->
                <li><a href="dashboard.php">Dashboard</a></li> <!-- Navigation link to Dashboard page -->
                <!-- Profile Icon -->
                <div class="profile-icon" onclick="toggleSidebar()"> <!-- Profile icon with click event to toggle sidebar -->
                    <img src="assets/images/profile.png" alt="Profile"> <!-- Profile image -->
                </div>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <!-- Sidebar -->
            <div id="sidebar" class="sidebar">
                <div class="sidebar-header">
                    <button class="close-btn" onclick="toggleSidebar()">×</button> <!-- Button to close the sidebar -->
                </div>

                <div class="sidebar-content">
                    <div class="user-info">
                        <img src="assets/images/profile.png" alt="Profile"> <!-- User profile image -->
                        <p><strong><?= $_SESSION['name'] ?></strong></p> <!-- Display user's name from session -->
                        <p><?= $_SESSION['email'] ?></p> <!-- Display user's email from session -->
                    </div>

                    <hr> <!-- Horizontal line -->

                    <ul class="sidebar-menu">
                        <li><a href="profile.php"><i class="fas fa-user"></i> Your Profile</a></li> <!-- Link to user's profile -->
                        <li><a href="edit-profile.php"><i class="fas fa-user-gear"></i> Edit Profile</a></li> <!-- Link to edit profile -->
                        <li><a href="saved-jobs.php"><i class="fas fa-bookmark"></i> Saved Jobs</a></li> <!-- Link to saved jobs -->
                        <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li> <!-- Link to logout -->
                    </ul>

                </div>
            </div>
            <div class="hero-content">
                <h1>Find Your Perfect Job with Jobify</h1> <!-- Main heading -->
                <p class="hero-p">Jobify connects job seekers with top companies, making job hunting effortless.
                    Browse thousands of opportunities across different industries and take the next step in your career.</p> <!-- Description paragraph -->
                <ul class="benefits-list">
                    <li>✔ Explore jobs from trusted companies</li> <!-- Benefit item -->
                    <li>✔ Easy application process</li> <!-- Benefit item -->
                    <li>✔ Personalized job recommendations</li> <!-- Benefit item -->
                </ul>
                <a href="job-listings.php" class="btn">Browse Jobs</a> <!-- Button to browse jobs -->
            </div>
        </section>

    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p> <!-- Footer content -->
    </footer>
    <script src="js/script.js"></script> <!-- Link to external JavaScript file -->
</body>

</html>
