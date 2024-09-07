<?php
include('db.php');
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to upload photos.";
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $albumId = $_POST['album_id'];
    
    // Check if the upload directory exists
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);  // Create directory if it doesn't exist
    }

    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is an actual image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        // Move the file to the uploads directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // Insert the photo data into the database
            $sql = "INSERT INTO photos (user_id, album_id, title, description, image_path) 
                    VALUES ('$userId', '$albumId', '$title', '$description', '$targetFile')";
            if ($conn->query($sql) === TRUE) {
                echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded successfully.";
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
