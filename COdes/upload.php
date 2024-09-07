
<?php
include('db.php');
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to upload photos.";
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate form inputs
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $albumId = (int)$_POST['album_id']; // Cast to integer for security

    // Check if a file was uploaded without errors
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Set up the upload directory
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // Create directory if it doesn't exist
        }

        // Generate a unique file name to prevent overwriting
        $fileName = basename($_FILES["image"]["name"]);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = uniqid('img_') . '.' . $fileExt;
        $targetFile = $targetDir . $newFileName;

        // Check if the file is an actual image
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES["image"]["tmp_name"]);

        if (in_array($fileType, $allowedTypes)) {
            // Move the file to the uploads directory
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                // Use prepared statements to prevent SQL injection
                $stmt = $conn->prepare("INSERT INTO photos (user_id, album_id, title, description, image_path) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("iisss", $userId, $albumId, $title, $description, $targetFile);

                if ($stmt->execute()) {
                    echo "The file " . htmlspecialchars($fileName) . " has been uploaded successfully.";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Invalid file type. Only JPG, PNG, and GIF files are allowed.";
        }
    } else {
        echo "No file was uploaded or there was an upload error.";
    }
}
?>
