<?php
session_start(); // Start the session to access session variables

// Include database connection
include 'php/config.php';

// Get job ID from URL
$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;

// Fetch job details
$query = "SELECT * FROM jobs WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

// If job doesn't exist, redirect to job listings
if (!$job) {
    header("Location: job-listings.php");
    exit();
}

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $cover_letter = trim($_POST["cover_letter"]);

    // File upload handling
    $resume_dir = "uploads/";
    $resume_file = $resume_dir . basename($_FILES["resume"]["name"]);
    $resume_file_type = strtolower(pathinfo($resume_file, PATHINFO_EXTENSION));

    // Allowed file types
    $allowed_types = array("pdf", "doc", "docx");

    if (!in_array($resume_file_type, $allowed_types)) {
        $message = "❌ Only PDF, DOC, and DOCX files are allowed.";
    } elseif ($_FILES["resume"]["size"] > 2000000) { // 2MB limit
        $message = "❌ File size should be less than 2MB.";
    } else {
        // Move uploaded file to the uploads directory
        if (move_uploaded_file($_FILES["resume"]["tmp_name"], $resume_file)) {

            // Prevent duplicate applications
            $checkQuery = "SELECT * FROM applications WHERE job_id = ? AND email = ?";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bind_param("is", $job_id, $email);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                $message = "❌ You have already applied for this job!";
            } else {
                // Insert application into database
                $insertQuery = "INSERT INTO applications (job_id, job_seeker_name, email, phone, resume, cover_letter) 
                                VALUES (?, ?, ?, ?, ?, ?)";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bind_param("isssss", $job_id, $name, $email, $phone, $resume_file, $cover_letter);

                if ($insertStmt->execute()) {
                    header("Location: apply-success.php");
                    exit();
                } else {
                    $message = "❌ Failed to submit application. Please try again.";
                }
            }
        } else {
            $message = "❌ Error uploading file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for <?= htmlspecialchars($job['job_title']) ?> | Jobify</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <!-- FontAwesome Icons -->
</head>

<body>
    <header>
        <div class="logo">
            <h1>Jobify</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="job-listings.php" class="active">Jobs</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <!-- Profile Icon -->
                <div class="profile-icon" onclick="toggleSidebar()">
                    <img src="assets/images/profile.png" alt="Profile">
                </div>
            </ul>
        </nav>
    </header>

    <main class="apply-container">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <button class="close-btn" onclick="toggleSidebar()">×</button>
            </div>

            <div class="sidebar-content">
                <div class="user-info">
                    <img src="assets/images/profile.png" alt="Profile">
                    <p><strong><?= $_SESSION['name'] ?></strong></p>
                    <p><?= $_SESSION['email'] ?></p>
                </div>

                <hr>

                <ul class="sidebar-menu">
                    <li><a href="profile.php"><i class="fas fa-user"></i> Your Profile</a></li>
                    <li><a href="edit-profile.php"><i class="fas fa-user-gear"></i> Edit Profile</a></li>
                    <li><a href="saved-jobs.php"><i class="fas fa-bookmark"></i> Saved Jobs</a></li>
                    <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
        <h2>Apply for <?= htmlspecialchars($job['job_title']) ?> role</h2>
        <p><strong>Company:</strong> <?= htmlspecialchars($job['company_name']) ?></p>
        <p class="apply-location"><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
        <p><strong>Salary:</strong> $<?= htmlspecialchars($job['salary']) ?>/year</p>

        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="apply-form">
            <div class="apply-group">
                <i class="fas fa-user"></i>
                <input type="text" name="name" placeholder="Full Name" required>
            </div>

            <div class="apply-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email Address" required>
            </div>

            <div class="apply-group">
                <i class="fas fa-phone"></i>
                <input type="tel" name="phone" placeholder="Phone Number" required>
            </div>

            <div class="apply-group" style="text-align: center; cursor: pointer;" onclick="document.querySelector('input[type=file]').click()">
                <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                    <i class="fas fa-upload" style="font-size: 2em; margin-right: 0"></i>
                    <span class="file-placeholder" style="color: #888;">Upload Resume</span>
                </div>
                <input type="file" name="resume" required accept=".pdf, .doc, .docx" style="display: none" onchange="updateFileName(this)">
            </div>

            <div class="apply-group">
                <i class="fas fa-file-alt"></i>
                <textarea name="cover_letter" placeholder="Cover Letter" rows="5" required style="resize: none;"></textarea>
            </div>

            <button type="submit">Submit Application</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
    <script src='js/script.js'></script>
</body>

</html>