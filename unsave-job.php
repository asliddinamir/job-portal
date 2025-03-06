<?php
session_start(); // Start the session
include 'php/config.php'; // Include the database configuration file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit(); // Exit the script
}

$user_id = $_SESSION['user_id']; // Get the user ID from the session
$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0; // Get the job ID from the URL parameter and convert it to an integer

// Delete saved job
$deleteQuery = "DELETE FROM saved_jobs WHERE user_id = ? AND job_id = ?"; // SQL query to delete the saved job
$stmt = $conn->prepare($deleteQuery); // Prepare the SQL statement
$stmt->bind_param("ii", $user_id, $job_id); // Bind the user ID and job ID parameters to the SQL statement

if ($stmt->execute()) { // Execute the SQL statement
    header("Location: saved-jobs.php?message=removed"); // Redirect to saved jobs page with a success message
} else {
    header("Location: saved-jobs.php?message=error"); // Redirect to saved jobs page with an error message
}
exit(); // Exit the script
?>
