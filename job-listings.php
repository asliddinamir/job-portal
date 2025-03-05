<?php
include 'php/config.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Search functionality
$query = "SELECT * FROM jobs WHERE 
          job_title LIKE '%$search%' 
          OR company_name LIKE '%$search%' 
          OR location LIKE '%$search%'
          ORDER BY date_posted DESC";

$result = $conn->query($query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobify | Job Listings</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1><a href="index.php">Jobify</a></h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="job-listings.php" class="active">Jobs</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>


    <main class="job-listings">
        <h2>Available Jobs</h2>

        <!-- Search Bar -->
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search for jobs..." value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
            <button type="submit">Search</button>
        </form>


        <!-- Job Listings -->
        <div class="job-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="job-card">';
                    echo '<h3>' . $row["job_title"] . '</h3>';
                    echo '<p><strong>Company:</strong> ' . $row["company_name"] . '</p>';
                    echo '<p><strong>Location:</strong> ' . $row["location"] . '</p>';
                    echo '<a href="job-description.php?id=' . $row["id"] . '" class="btn">View Details</a>';
                    echo '</div>';
                }
            } else {
                echo "<p>No jobs available.</p>";
            }
            ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
</body>
</html>
