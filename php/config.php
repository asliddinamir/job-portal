<?php
$host = "localhost"; // Change if needed
$username = "root"; // Default username for local XAMPP/WAMP
$password = ""; // Default password is empty
$database = "jobify"; // The database we created

// Establish a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
