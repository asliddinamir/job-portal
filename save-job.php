<?php
session_start();
include 'php/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;

// Check if the job is already saved
$checkQuery = "SELECT * FROM saved_jobs WHERE user_id = ? AND job_id = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("ii", $user_id, $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // If job is already saved, remove it
    $deleteQuery = "DELETE FROM saved_jobs WHERE user_id = ? AND job_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("ii", $user_id, $job_id);
    $stmt->execute();
} else {
    // If job is not saved, add it
    $insertQuery = "INSERT INTO saved_jobs (user_id, job_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ii", $user_id, $job_id);
    $stmt->execute();
}

// Redirect back to job listings
header("Location: job-listings.php");
exit();
?>
