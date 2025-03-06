<?php
session_start();
include 'php/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;

// Delete saved job
$deleteQuery = "DELETE FROM saved_jobs WHERE user_id = ? AND job_id = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("ii", $user_id, $job_id);

if ($stmt->execute()) {
    header("Location: saved-jobs.php?message=removed");
} else {
    header("Location: saved-jobs.php?message=error");
}
exit();
?>
