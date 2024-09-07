<?php
// Start the PHP script for handling dynamic data
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - MyGallery</title>
    <link rel="stylesheet" href="profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-logo">MyGallery</div>
            <ul class="sidebar-links">
                <li><a href="#"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
                <li><a href="#"><i class="fas fa-heart"></i> Favourites</a></li>
                <li><a href="#"><i class="fas fa-search"></i> Explore</a></li>
                <li><a href="login.html"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <div class="main-content">
            <header>
                <nav>
                    <ul class="top-nav">
                        <li data-text="Home"><a href="index.html"><i class="fa-solid fa-house-chimney"></i></a></li>
                        <li data-text="Upload"><a href="upload.html"><i class="fa-solid fa-cloud-arrow-up"></i></a></li>
                        <li data-text="Gallery"><a href="gallery.html"><i class="fa-solid fa-photo-film"></i></a></li>
                        <li data-text="Albums"><a href="albums.html"><i class="fa-solid fa-folder-plus"></i></a></li>
                    </ul>
                </nav>
            </header>
            
            <main>
                <section class="profile">
                    <div class="profile-header">
                        <div class="profile-picture">
                            <!-- Display the profile picture from PHP -->
                            <img src="<?php echo htmlspecialchars($user['profile_pic'] ?? 'default-profile.png'); ?>" alt="Profile Picture">
                        </div>
                        <div class="profile-info">
                            <!-- Display the username and email from PHP -->
                            <h1><?php echo htmlspecialchars($user['username']); ?></h1>
                            <p>@<?php echo htmlspecialchars($user['username']); ?></p>
                            <p><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                    </div>

                    <div class="profile-details">
                        <div class="profile-statistics">
                            <div class="stat">
                                <h3>Posts</h3>
                                <p>120</p>
                            </div>
                            <div class="stat">
                                <h3>Followers</h3>
                                <p>340</p>
                            </div>
                            <div class="stat">
                                <h3>Following</h3>
                                <p>180</p>
                            </div>
                        </div>
                        <div class="profile-bio">
                            <h2>About Me</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam euismod, nisi vel consectetur euismod, nunc nunc lacinia ligula, non egestas eros eros ac justo.</p>
                        </div>
                        <div class="profile-feed">
                            <h2>My Feed</h2>
                            <div class="feed-grid">
                                <div class="feed-item">
                                    <img src="images/94953cab9adccb0173ba92333c76510b.jpg" alt="Feed Image 1">
                                </div>
                                <div class="feed-item">
                                    <img src="images/b9fe7e8fb3590fafbad90c57a12f31cc.jpg" alt="Feed Image 2">
                                </div>
                                <div class="feed-item">
                                    <img src="images/5189952.png" alt="Feed Image 3">
                                </div>
                                <div class="feed-item">
                                    <img src="images/2024-08-30 11.14.32 PM.jpg" alt="Feed Image 4">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Update Form -->
                    <form action="profile.php" method="post" enctype="multipart/form-data">
                        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>
                        <input type="file" name="profile_pic"><br>
                        <input type="submit" value="Update Profile">
                    </form>
                </section>
            </main>
            
            <footer>
                <p>&copy; 2024 MyGallery. All rights reserved.</p>
            </footer>
        </div>
    </div>
</body>
</html>
