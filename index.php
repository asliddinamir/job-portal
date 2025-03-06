<?php
    session_start();
    if (!isset($_SESSION['email'])) {
        header("Location: login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobify | Find Your Dream Job</title>
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
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="job-listings.php">Jobs</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                 <!-- Profile Icon -->
                 <div class="profile-icon" onclick="toggleSidebar()">
                    <img src="assets/images/profile.png" alt="Profile">
                </div>
            </ul>
        </nav>
    </header>


    <main>
        <section class="hero">
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
            <div class="hero-content">
                <h1>Find Your Perfect Job with Jobify</h1>
                <p>Jobify connects job seekers with top companies, making job hunting effortless.  
                Browse thousands of opportunities across different industries and take the next step in your career.</p>
                <ul class="benefits-list">
                    <li>✔ Explore jobs from trusted companies</li>
                    <li>✔ Easy application process</li>
                    <li>✔ Personalized job recommendations</li>
                </ul>
                <a href="job-listings.php" class="btn">Browse Jobs</a>
            </div>
        </section>

    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>
