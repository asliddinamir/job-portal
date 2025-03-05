<?php
include 'php/config.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';

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

$query .= " ORDER BY date_posted DESC";
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
            <h1>Jobify</h1>
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

        <!-- Search & Filter Form -->
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search for jobs..." value="<?= htmlspecialchars($search) ?>">
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
            <button type="submit">Filter</button>
        </form>

        <!-- Job Listings -->
        <div class="job-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="job-card">';
                    echo '<h3>' . htmlspecialchars($row["job_title"]) . '</h3>';
                    echo '<p><strong>Company:</strong> ' . htmlspecialchars($row["company_name"]) . '</p>';
                    echo '<p><strong>Category:</strong> ' . htmlspecialchars($row["category"]) . '</p>';
                    echo '<p><strong>Location:</strong> ' . htmlspecialchars($row["location"]) . '</p>';
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
