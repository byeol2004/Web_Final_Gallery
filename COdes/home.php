
<?php
session_start();
include('db.php'); // Assuming this contains the database connection

// Fetch featured photos from the database
$sql = "SELECT * FROM photos ORDER BY created_at DESC LIMIT 4";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Gallery</title>
    <link rel="stylesheet" href="home.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <div class="hero-container">
        <img src="images/Untitled design.png" alt="Hero Image">
        <div class="hero-text">
            <h1>Welcome to MyGallery</h1>
            <p>Explore, upload, and share your favorite photos.</p>
            <button class="cta">Get Started</button>
        </div>
    </div>

    <div class="toggle-sidebar">
        <button id="sidebar-toggle" class="sidebar-toggle"><i class="fas fa-bars"></i></button>
    </div>

    <div class="container">
        <aside class="sidebar" id="sidebar">
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
                        <li data-text="Albums"><a href="albums.html"><i class="fa-solid fa-folder-plus"></i></a></li>
                    </ul>
                </nav>
            </header>

            <main>
                <section class="browse">
                    <h2>Browse Photos</h2>
                    <div class="dropdowns">
                        <div class="dropdown">
                            <label for="categories">Categories:</label>
                            <select id="categories" name="categories">
                                <option value="nature">Nature</option>
                                <option value="city">City</option>
                                <option value="animals">Animals</option>
                                <option value="people">People</option>
                            </select>
                        </div>
                        <div class="dropdown">
                            <label for="tags">Tags:</label>
                            <select id="tags" name="tags">
                                <option value="sunset">Sunset</option>
                                <option value="mountains">Mountains</option>
                                <option value="urban">Urban</option>
                                <option value="wildlife">Wildlife</option>
                            </select>
                        </div>
                    </div>
                </section>

                <section class="gallery">
                    <h2>Featured Photos</h2>
                    <div class="photo-grid">
                        <!-- Dynamically load featured photos from the database -->
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <div class="photo-card">
                                    <img src="<?php echo $row['photo_path']; ?>" alt="<?php echo $row['photo_name']; ?>">
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No featured photos available.</p>
                        <?php endif; ?>
                    </div>
                </section>
            </main>

            <footer>
                <p>&copy; 2024 MyGallery. All rights reserved.</p>
            </footer>
        </div>
    </div>

    <script src="home.js"></script> <!-- Link to your JavaScript file -->
</body>
</html>

<?php
$conn->close(); // Close database connection
?>
