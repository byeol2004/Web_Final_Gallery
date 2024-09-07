<?php
include('db.php');

// Start the session
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id']; // Assuming you have a logged-in user
    $title = $_POST['title'];
    $description = $_POST['description'];
    $albumId = $_POST['album_id'];
    
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $sql = "INSERT INTO photos (user_id, album_id, title, description, image_path) VALUES ('$userId', '$albumId', '$title', '$description', '$targetFile')";
            if ($conn->query($sql) === TRUE) {
                echo "The file ". htmlspecialchars(basename($_FILES["image"]["name"])). " has been uploaded.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
}
?>

<!-- HTML Form for image upload -->
<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Title" required><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <select name="album_id">
        <!-- Populate with user's albums -->
    </select><br>
    <input type="file" name="image" required><br>
    <input type="submit" value="Upload Image">
</form>
