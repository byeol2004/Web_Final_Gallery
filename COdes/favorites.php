
<?php
include('db.php');
session_start();

header('Content-Type: application/json');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in'
    ]);
    exit();
}

$userId = $_SESSION['user_id'];

// Handle adding a photo to favorites
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_favorites'])) {
    $photoId = $_POST['photo_id'];
    
    $sql = "INSERT INTO favorites (user_id, photo_id) VALUES ('$userId', '$photoId')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Photo added to favorites']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    }
    exit();
}

// Handle removing a photo from favorites
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_favorites'])) {
    $photoId = $_POST['photo_id'];
    
    $sql = "DELETE FROM favorites WHERE user_id='$userId' AND photo_id='$photoId'";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Photo removed from favorites']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    }
    exit();
}

// Fetch the user's favorite photos
$sql = "SELECT photos.id, photos.title, photos.image_path 
        FROM photos 
        JOIN favorites ON photos.id = favorites.photo_id 
        WHERE favorites.user_id='$userId'";

$result = $conn->query($sql);

$favorites = [];
while ($row = $result->fetch_assoc()) {
    $favorites[] = $row;
}

// Return the favorite photos as JSON
echo json_encode([
    'success' => true,
    'favorites' => $favorites
]);
?>
