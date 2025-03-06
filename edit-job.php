<?php
session_start(); // Start the session
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') { // Check if the user is logged in and is an admin
    header("Location: login.php"); // Redirect to login page if not
    exit(); // Exit the script
}

include 'php/config.php'; // Include the database configuration file

$message = ""; // Initialize the message variable

// Get job ID from URL
$job_id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Get the job ID from the URL, default to 0 if not set

// Fetch job details
$query = "SELECT * FROM jobs WHERE id = ?"; // SQL query to select the job by ID
$stmt = $conn->prepare($query); // Prepare the SQL statement
$stmt->bind_param("i", $job_id); // Bind the job ID parameter
$stmt->execute(); // Execute the statement
$result = $stmt->get_result(); // Get the result set
$job = $result->fetch_assoc(); // Fetch the job details as an associative array

if (!$job) { // Check if the job was found
    die("❌ Job not found."); // Terminate the script with an error message if not found
}

// Handle job update
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form was submitted
    $job_title = trim($_POST['job_title']); // Get and trim the job title from the form
    $company_name = trim($_POST['company_name']); // Get and trim the company name from the form
    $category = trim($_POST['category']); // Get and trim the category from the form
    $location = trim($_POST['location']); // Get and trim the location from the form
    $salary = trim($_POST['salary']); // Get and trim the salary from the form
    $description = trim($_POST['description']); // Get and trim the description from the form

    $updateQuery = "UPDATE jobs SET job_title=?, company_name=?, category=?, location=?, salary=?, description=? WHERE id=?"; // SQL query to update the job
    $stmt = $conn->prepare($updateQuery); // Prepare the SQL statement
    $stmt->bind_param("ssssssi", $job_title, $company_name, $category, $location, $salary, $description, $job_id); // Bind the parameters

    if ($stmt->execute()) { // Execute the statement
        $message = "✅ Job updated successfully! Redirecting to login...";
        header("refresh:1;url=manage-jobs.php"); // Set success message if the update was successful
    } else {
        $message = "❌ Failed to update job."; // Set error message if the update failed
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"> <!-- Character encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Viewport settings for responsive design -->
    <title>Edit Job | Jobify</title> <!-- Page title -->
    <link rel="stylesheet" href="css/style.css"> <!-- Link to external CSS file -->
</head>

<body>
    <header>
        <div class="admin-logo">
            <h1>Jobify Admin Panel</h1> <!-- Admin panel header -->
        </div>
        <nav>
            <ul>
                <li><a href="manage-jobs.php" class="active">Manage Jobs</a></li> <!-- Navigation link to manage jobs -->
                <li><a href="manage-applications.php">Manage Applications</a></li> <!-- Navigation link to manage applications -->
                <li><a href="logout.php">Logout</a></li> <!-- Navigation link to logout -->
            </ul>
        </nav>
    </header>

    <main class="apply-container">
        <h2 style="text-align: center;">Edit Job</h2> <!-- Page heading -->

        <?php if ($message): ?> <!-- Check if there is a message to display -->
            <p class="message"><?= $message ?></p> <!-- Display the message -->
        <?php endif; ?>

        <form method="POST" class="apply-form"> <!-- Form for editing the job -->
            <label>Job Title:</label>
            <div class="apply-group">
                <input type="text" name="job_title" value="<?= htmlspecialchars($job['job_title']) ?>" required> <!-- Input for job title -->
            </div>

            <label>Company Name:</label>
            <div class="apply-group">
                <input type="text" name="company_name" value="<?= htmlspecialchars($job['company_name']) ?>" required> <!-- Input for company name -->
            </div>

            <label>Category:</label>
            <div class="apply-group">
                <input type="text" name="category" value="<?= htmlspecialchars($job['category']) ?>" required> <!-- Input for category -->
            </div>

            <label>Location:</label>
            <div class="apply-group">
                <input type="text" name="location" value="<?= htmlspecialchars($job['location']) ?>" required> <!-- Input for location -->
            </div>

            <label>Salary:</label>
            <div class="apply-group">
                <input type="text" name="salary" value="<?= htmlspecialchars($job['salary']) ?>" required> <!-- Input for salary -->
            </div>

            <label>Job Description:</label>
            <div class="apply-group">
                <textarea name="description" rows="10" required><?= htmlspecialchars($job['description']) ?></textarea> <!-- Textarea for job description -->
            </div>

            <button type="submit">Update Job</button> <!-- Submit button -->
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p> <!-- Footer content -->
    </footer>
</body>

</html>