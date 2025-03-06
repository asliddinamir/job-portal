<?php
// Include the configuration file which contains the database connection details
include 'config.php';

// Check if the database connection is successful
if ($conn) {
    // If the connection is successful, print a success message
    echo "✅ Database connected successfully!";
} else {
    // If the connection fails, print an error message
    echo "❌ Database connection failed!";
}
?>
