<?php
include('db.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit();
}

// Add a photo to favorites
if (isset($_POST['add_to_favorites'])) {
    $userId = $_SESSION['user_id'];
    $photoId = $_POST['photo_id'];

    // Insert into the favorites table
    $sql = "INSERT INTO favorites (user_id, photo_id) VALUES ('$userId', '$photoId')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Photo added to favorites.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Remove a photo from favorites
if (isset($_POST['remove_from_favorites'])) {
    $userId = $_SESSION['user_id'];
    $photoId = $_POST['photo_id'];

    // Delete from the favorites table
    $sql = "DELETE FROM favorites WHERE user_id='$userId' AND photo_id='$photoId'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Photo removed from favorites.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Display user's favorite photos
$userId = $_SESSION['user_id'];
$sql = "SELECT photos.id, photos.title, photos.image_path FROM photos 
        JOIN favorites ON photos.id = favorites.photo_id 
        WHERE favorites.user_id='$userId'";
$result = $conn->query($sql);

?>

<h2>My Favorites</h2>

<div class="photo-grid">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="photo-item">
            <a href="index.php?page=view_photo&id=<?php echo $row['id']; ?>">
                <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['title']; ?>">
            </a>
            <p><?php echo $row['title']; ?></p>
            <form method="post" action="index.php?page=favorites">
                <input type="hidden" name="photo_id" value="<?php echo $row['id']; ?>">
                <input type="submit" name="remove_from_favorites" value="Remove from Favorites">
            </form>
        </div>
    <?php endwhile; ?>
</div>

