<?php
session_start(); // Start the session
include 'php/config.php'; // Include the database configuration file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit(); // Exit the script
}

$user_id = $_SESSION['user_id']; // Get the user ID from the session
$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0; // Get the job ID from the URL, default to 0 if not set

// Check if the job is already saved
$checkQuery = "SELECT * FROM saved_jobs WHERE user_id = ? AND job_id = ?";
$stmt = $conn->prepare($checkQuery); // Prepare the SQL statement
$stmt->bind_param("ii", $user_id, $job_id); // Bind the parameters
$stmt->execute(); // Execute the statement
$result = $stmt->get_result(); // Get the result

if ($result->num_rows > 0) {
    // If job is already saved, remove it
    $deleteQuery = "DELETE FROM saved_jobs WHERE user_id = ? AND job_id = ?";
    $stmt = $conn->prepare($deleteQuery); // Prepare the delete statement
    $stmt->bind_param("ii", $user_id, $job_id); // Bind the parameters
    $stmt->execute(); // Execute the statement
} else {
    // If job is not saved, add it
    $insertQuery = "INSERT INTO saved_jobs (user_id, job_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery); // Prepare the insert statement
    $stmt->bind_param("ii", $user_id, $job_id); // Bind the parameters
    $stmt->execute(); // Execute the statement
}

// Redirect back to job listings
header("Location: job-listings.php"); // Redirect to job listings page
exit(); // Exit the script
?>
