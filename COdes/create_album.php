
<?php
include('db.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    // Insert album data into the database
    $stmt = $conn->prepare("INSERT INTO albums (user_id, name, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $name, $description);
    if ($stmt->execute()) {
        // Redirect back to the albums page
        header("Location: albums.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
