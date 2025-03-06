<?php
session_start(); // Start the session
include 'php/config.php'; // Include the database configuration file

// Get search, category, and location from GET parameters if they exist
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';

$user_email = $_SESSION['email']; // Get the user's email from the session

// Fetch unique categories for the dropdown
$categoryQuery = "SELECT DISTINCT category FROM jobs";
$categoryResult = $conn->query($categoryQuery);

// Fetch unique locations for the dropdown
$locationQuery = "SELECT DISTINCT location FROM jobs";
$locationResult = $conn->query($locationQuery);

// Build the main job query with filters
$query = "SELECT * FROM jobs WHERE 
          (job_title LIKE '%$search%' 
          OR company_name LIKE '%$search%' 
          OR location LIKE '%$search%')";

if (!empty($category)) {
    $query .= " AND category = '$category'";
}

if (!empty($location)) {
    $query .= " AND location = '$location'";
}

$query .= " ORDER BY date_posted DESC"; // Order by date posted in descending order
$result = $conn->query($query); // Execute the query
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobify | Job Listings</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Link to the stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <!-- Link to Font Awesome for icons -->
</head>

<body>
    <header>
        <div class="logo">
            <h1>Jobify</h1> <!-- Website logo -->
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="job-listings.php" class="active">Jobs</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
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
                <button class="close-btn" onclick="toggleSidebar()">×</button> <!-- Close button for sidebar -->
            </div>

            <div class="sidebar-content">
                <div class="user-info">
                    <img src="assets/images/profile.png" alt="Profile"> <!-- User profile image -->
                    <p><strong><?= $_SESSION['name'] ?></strong></p> <!-- User name from session -->
                    <p><?= $_SESSION['email'] ?></p> <!-- User email from session -->
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
        <h2>Available Jobs</h2>

        <!-- Search & Filter Form -->
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search for jobs..." value="<?= htmlspecialchars($search) ?>"> <!-- Search input -->
            <select name="category">
                <option value="">All Categories</option>
                <?php while ($row = $categoryResult->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($row['category']) ?>" <?= $category == $row['category'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['category']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <select name="location">
                <option value="">All Locations</option>
                <?php while ($row = $locationResult->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($row['location']) ?>" <?= $location == $row['location'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['location']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Filter</button> <!-- Filter button -->
        </form>

        <!-- Job Listings -->
        <div class="job-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="job-card">';
                    echo '<div class="job-info">';
                    echo '<h3>' . htmlspecialchars($row["job_title"]) . '</h3>'; // Job title
                    echo '<p><strong>Company:</strong> ' . htmlspecialchars($row["company_name"]) . '</p>'; // Company name
                    echo '<p><strong>Category:</strong> ' . htmlspecialchars($row["category"]) . '</p>'; // Job category
                    echo '<p><strong>Location:</strong> ' . htmlspecialchars($row["location"]) . '</p>'; // Job location
                    echo '</div>';

                    echo '<div class="job-actions">';

                    // View Details Button
                    echo '<a href="job-description.php?id=' . $row["id"] . '" class="btn">View Details</a>'; // View details button

                    // Check if job is already saved
                    $checkQuery = "SELECT * FROM saved_jobs WHERE user_id = ? AND job_id = ?";
                    $stmtCheck = $conn->prepare($checkQuery);
                    $stmtCheck->bind_param("ii", $_SESSION['user_id'], $row["id"]);
                    $stmtCheck->execute();
                    $isSaved = $stmtCheck->get_result()->num_rows > 0;

                    // Show correct save/unsave icon
                    echo '<a href="save-job.php?job_id=' . $row["id"] . '" class="save-btn">';
                    echo '<i class="' . ($isSaved ? 'fas' : 'far') . ' fa-bookmark"></i>'; // Save/unsave icon
                    echo '</a>';

                    echo '</div>'; // Close job-actions
                    echo '</div>'; // Close job-card
                }
            } else {
                echo "<p>No jobs available.</p>"; // Message if no jobs are available
            }
            ?>
        </div>

    </main>
    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p> <!-- Footer content -->
    </footer>
    <script src="js/script.js"></script> <!-- Link to JavaScript file -->
</body>

</html>