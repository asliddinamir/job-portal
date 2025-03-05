<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'php/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_applications']) && isset($_POST['bulk_status'])) {
    $selected_ids = $_POST['selected_applications'];
    $new_status = in_array($_POST['bulk_status'], ['Pending', 'Shortlisted', 'Rejected']) ? $_POST['bulk_status'] : 'Pending';

    // Convert array to comma-separated string for SQL query
    $placeholders = implode(',', array_fill(0, count($selected_ids), '?'));

    $query = "UPDATE applications SET status = ? WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($query);
    
    // Bind parameters dynamically
    $types = str_repeat('i', count($selected_ids)); // 'i' for integers
    $stmt->bind_param("s$types", $new_status, ...$selected_ids);

    if ($stmt->execute()) {
        $_SESSION['message'] = "✅ Selected applications updated successfully!";
    } else {
        $_SESSION['message'] = "❌ Failed to update applications.";
    }
}

header("Location: manage-applications.php");
exit();
