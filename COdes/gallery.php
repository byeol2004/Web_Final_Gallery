
<?php
include('db.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo '<p style="color: red;">User not logged in. <a href="login.php">Login here</a></p>';
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch gallery images data from the database
$stmt = $conn->prepare("SELECT image_url, upload_date FROM gallery WHERE user_id = ? ORDER BY upload_date DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Prepare gallery items
$galleryItems = [];
while ($row = $result->fetch_assoc()) {
    $galleryItems[] = [
        'image_url' => $row['image_url'],
        'upload_date' => $row['upload_date']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <link rel="stylesheet" href="gallery.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="toggle-sidebar">
        <button id="sidebar-toggle" class="sidebar-toggle"><i class="fas fa-bars"></i></button>
    </div>

    <div class="container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-logo">MyGallery</div>
            <ul class="sidebar-links">
                <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="favorites.php"><i class="fas fa-heart"></i> Favourites</a></li>
                <li><a href="home.php"><i class="fas fa-search"></i> Explore</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <div class="main-content">
            <header>
                <nav>
                    <ul class="top-nav">
                        <li data-text="Home"><a href="home.php"><i class="fa-solid fa-house-chimney"></i></a></li>
                        <li data-text="Upload"><a href="upload.php"><i class="fa-solid fa-cloud-arrow-up"></i></a></li>
                        <li data-text="Gallery"><a href="gallery.php"><i class="fa-solid fa-photo-film"></i></a></li>
                        <li data-text="Albums"><a href="albums.php"><i class="fa-solid fa-folder-plus"></i></a></li>
                    </ul>
                </nav>
            </header>

            <main>
                <h1>Gallery</h1>

                <!-- Dynamically Load Gallery Items from Database -->
                <?php if (count($galleryItems) > 0): ?>
                    <?php
                    // Group images by upload date
                    $currentDate = '';
                    foreach ($galleryItems as $item):
                        $uploadDate = date('F j, Y', strtotime($item['upload_date']));
                        if ($uploadDate !== $currentDate):
                            if ($currentDate !== ''):
                                echo '</div></section>';
                            endif;
                            echo '<section class="gallery-date"><h2>Uploaded on: ' . $uploadDate . '</h2><div class="gallery-grid">';
                            $currentDate = $uploadDate;
                        endif;
                    ?>
                        <div class="gallery-item">
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="Gallery Image">
                            <div class="overlay">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="image-info">
                                <p>Uploaded on: <?php echo $uploadDate; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div></section>
                <?php else: ?>
                    <p>No images found in your gallery.</p>
                <?php endif; ?>
            </main>

            <footer>
                <div class="footer-content">
                    <p>&copy; 2024 MyGallery. All rights reserved.</p>
                    <div class="footer-links">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a></div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        document.querySelectorAll('.gallery-item .overlay i').forEach(icon => {
            icon.addEventListener('click', function() {
                alert('You liked this image!');
            });
        });
        const sidebarToggleBtn = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');

        sidebarToggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');  // Show/hide the sidebar
            mainContent.classList.toggle('shifted');  // Shift the main content accordingly
        });
    </script>
</body>
</html>
