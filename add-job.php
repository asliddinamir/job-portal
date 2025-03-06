<?php
session_start(); // Start the session
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') { // Check if the user is logged in and is an admin
    header("Location: login.php"); // Redirect to login page if not
    exit(); // Exit the script
}

include 'php/config.php'; // Include the database configuration file

$message = ""; // Initialize the message variable

// Handle job submission
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form is submitted
    $job_title = trim($_POST['job_title']); // Get and trim the job title from the form
    $company_name = trim($_POST['company_name']); // Get and trim the company name from the form
    $category = trim($_POST['category']); // Get and trim the category from the form
    $location = trim($_POST['location']); // Get and trim the location from the form
    $salary = trim($_POST['salary']); // Get and trim the salary from the form
    $description = trim($_POST['description']); // Get and trim the description from the form

    $query = "INSERT INTO jobs (job_title, company_name, category, location, salary, description, date_posted) 
              VALUES (?, ?, ?, ?, ?, ?, NOW())"; // Prepare the SQL query to insert the job
    $stmt = $conn->prepare($query); // Prepare the statement
    $stmt->bind_param("ssssss", $job_title, $company_name, $category, $location, $salary, $description); // Bind the parameters

    if ($stmt->execute()) { // Execute the statement
        $message = "✅ Job added successfully!"; // Set success message
    } else {
        $message = "❌ Failed to add job."; // Set failure message
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Set the character encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Set the viewport for responsive design -->
    <title>Add Job | Jobify</title> <!-- Set the title of the page -->
    <link rel="stylesheet" href="css/style.css"> <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <!-- FontAwesome Icons -->
</head>
<body>
    <header>
        <div class="admin-logo">
            <h1>Jobify Admin Panel</h1> <!-- Admin panel header -->
        </div>
        <nav>
            <ul>
                <li><a href="manage-jobs.php" class="active">Manage Jobs</a></li> <!-- Link to manage jobs -->
                <li><a href="manage-applications.php">Manage Applications</a></li> <!-- Link to manage applications -->
                <li><a href="logout.php">Logout</a></li> <!-- Link to logout -->
            </ul>
        </nav>
    </header>

    <main class="apply-container">
        <h2 style="text-align: center;">Add new job</h2> <!-- Page heading -->
        
        <?php if ($message): ?> <!-- Display message if set -->
            <p class="message"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST" class="apply-form"> <!-- Job submission form -->
            <div class="apply-group">
                <i class="fas fa-briefcase"></i> <!-- Icon for job title -->
                <input type="text" name="job_title" placeholder="Job Title" required> <!-- Input for job title -->
            </div>

            <div class="apply-group">
                <i class="fas fa-building"></i> <!-- Icon for company name -->
                <input type="text" name="company_name" placeholder="Company Name" required> <!-- Input for company name -->
            </div>

            <div class="apply-group">
                <i class="fas fa-tags"></i> <!-- Icon for category -->
                <input type="text" name="category" placeholder="Category" required> <!-- Input for category -->
            </div>

            <div class="apply-group">
                <i class="fas fa-map-marker-alt"></i> <!-- Icon for location -->
                <input type="text" name="location" placeholder="Location" required> <!-- Input for location -->
            </div>

            <div class="apply-group">
                <i class="fas fa-money-bill-wave"></i> <!-- Icon for salary -->
                <input type="text" name="salary" placeholder="Salary" required> <!-- Input for salary -->
            </div>

            <div class="apply-group">
                <i class="fas fa-address-card"></i> <!-- Icon for description -->
                <textarea name="description" placeholder="Job Description" rows="5" required style="resize: none;"></textarea> <!-- Textarea for description -->
            </div>

            <button type="submit">Add Job</button> <!-- Submit button -->
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p> <!-- Footer content -->
    </footer>
</body>
</html>
