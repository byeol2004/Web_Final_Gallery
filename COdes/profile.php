
<?php
include('db.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch user data from the database
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Handle profile picture upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $fileName = basename($_FILES['profile_pic']['name']);
        $targetDir = "uploads/";
        $targetFile = $targetDir . $fileName;

        // Check if the file is an image
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES['profile_pic']['tmp_name']);
        if ($check !== false) {
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile)) {
                // Update profile picture path in the database
                $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
                $stmt->bind_param("si", $targetFile, $userId);
                $stmt->execute();
            } else {
                echo "Error uploading profile picture.";
            }
        } else {
            echo "File is not a valid image.";
        }
    }

    // Update username and email in the database
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $email, $userId);
    $stmt->execute();

    // Display success message
    echo "Profile updated successfully!";
    // Optionally redirect after update
    // header("Location: profile.php");
    // exit();
}
?>

<h2>Profile</h2>

<p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
<p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

<!-- Profile Update Form -->
<form action="profile.php" method="post" enctype="multipart/form-data">
    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>
    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>
    
    <!-- Profile Picture Upload -->
    <input type="file" name="profile_pic"><br>
    
    <!-- Submit Button -->
    <input type="submit" value="Update Profile">
</form>

<!-- Display Profile Picture if available -->
<?php if (!empty($user['profile_pic'])): ?>
    <img src="<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Picture" width="150">
<?php endif; ?>
