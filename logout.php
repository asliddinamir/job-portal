<?php
// Start the session
session_start();

// Destroy all data registered to a session
session_destroy();

// Redirect to the login page
header("Location: login.php");

// Ensure no further code is executed after the redirect
exit();
?>
