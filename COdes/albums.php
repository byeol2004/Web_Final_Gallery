
<?php
// albums.php
session_start(); // Make sure session is started
include('db.php'); // Assuming 'db.php' contains the database connection logic

// Fetch the user id from the session
$userId = $_SESSION['user_id'];

// Fetch albums from the database for the logged-in user
$sql = "SELECT * FROM albums WHERE user_id='$userId'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albums - MyGallery</title>
    <link rel="stylesheet" href="albums.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-logo">MyGallery</div>
            <ul class="sidebar-links">
                <li><a href="profile.html"><i class="fas fa-user"></i> Profile</a></li>
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
                        <li data-text="Home"><a href="#"><i class="fa-solid fa-house-chimney"></i></a></li>
                        <li data-text="Upload"><a href="upload.html"><i class="fa-solid fa-cloud-arrow-up"></i></a></li>
                        <li data-text="Gallery"><a href="gallery.html"><i class="fa-solid fa-photo-film"></i></a></li>
                        <li data-text="Albums"><a href="albums.php"><i class="fa-solid fa-folder-plus"></i></a></li>
                    </ul>
                </nav>
            </header>
            
            <main>
                <section class="albums">
                    <h2>Your Albums</h2>
                    <div class="album-grid">
                        <!-- Display albums dynamically from the database -->
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <div class="album-card">
                                    <a href="view_album.php?id=<?php echo $row['id']; ?>">
                                        <img src="path_to_album_image_placeholder.jpg" alt="<?php echo $row['name']; ?>">
                                    </a>
                                    <div class="album-info">
                                        <h3><?php echo $row['name']; ?></h3>
                                        <p><?php echo $row['description']; ?></p>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No albums found. Create a new one!</p>
                        <?php endif; ?>
                    </div>
                </section>

                <section class="create-album">
                    <h2>Create New Album</h2>
                    <form action="create_album.php" method="post">
                        <input type="text" name="name" placeholder="New Album Name" required><br>
                        <textarea name="description" placeholder="Album Description"></textarea><br>
                        <input type="submit" value="Create Album">
                    </form>
                </section>
            </main>
            
            <footer>
                <p>&copy; 2024 MyGallery. All rights reserved.</p>
            </footer>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>

<?php $conn->close(); ?>


