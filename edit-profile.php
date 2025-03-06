<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'php/config.php';

$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);

    $updateQuery = "UPDATE users SET name = ?, phone = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssi", $name, $phone, $user_id);

    if ($stmt->execute()) {
        $_SESSION['name'] = $name;
        $message = "✅ Profile updated successfully!";
    } else {
        $message = "❌ Failed to update profile.";
    }
}

// Fetch user details
$query = "SELECT name, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Jobify</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <header>
        <div class="logo">
            <h1>Jobify</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="job-listings.php">Jobs</a></li>
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
                    <li><a href="edit-profile.php" class="active-sidebar"><i class="fas fa-user-gear"></i> Edit Profile</a></li>
                    <li><a href="saved-jobs.php"><i class="fas fa-bookmark"></i> Saved Jobs</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
        <h2 style="text-align: center;">Edit Profile</h2>
        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST" class="apply-form">
            <label for="name">Full Name:</label>
            <div class="apply-group">
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>

            <label for="phone">Phone:</label>
            <div class="apply-group">
                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
            </div>

            <button type="submit" style="width: 30%;" >Save Changes</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Jobify. All Rights Reserved.</p>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>