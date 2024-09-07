
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Website</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <header>
        <h1>Gallery Website</h1>
        <nav>
            <ul>
                <li><a href="index.php?page=home">Home</a></li>
                <li><a href="index.php?page=upload">Upload</a></li>
                <li><a href="index.php?page=gallery">Gallery</a></li>
                <li><a href="index.php?page=albums">Albums</a></li>
                <li><a href="index.php?page=categories">Categories</a></li>
                <li><a href="index.php?page=favorites">Favorites</a></li>
                <li><a href="index.php?page=profile">Profile</a></li>
                <li><a href="index.php?page=settings">Settings</a></li>
                <li><a href="index.php?page=help">Help/Support</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="index.php?page=logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="index.php?page=login">Login/Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <?php
        include "db.php";

        // Load the content based on the 'page' parameter
        switch ($page) {
            case 'home':
                include('home.php');
                break;
            case 'upload':
                include('upload.php');
                break;
            case 'gallery':
                include('gallery.php');
                break;
            case 'albums':
                include('albums.php');
                break;
            case 'categories':
                include('categories.php');
                break;
            case 'favorites':
                include('favorites.php');
                break;
            case 'profile':
                include('profile.php');
                break;
            case 'settings':
                include('settings.php');
                break;
            case 'help':
                include('help.php');
                break;
            case 'login':
                include('login.php');
                break;
            case 'logout':
                // Handle logout
                session_destroy();
                header("Location: index.php");
                break;
            default:
                include('home.php');
                break;
        }
        ?>
    </main>

    <footer>
        <p>&copy; 2024 Gallery Website. All rights reserved.</p>
    </footer>
</body>
</html>
