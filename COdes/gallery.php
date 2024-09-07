
<?php
include('db.php');
session_start();

header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch gallery images data from the database
$stmt = $conn->prepare("SELECT image_url, upload_date FROM gallery WHERE user_id = ? ORDER BY upload_date DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$galleryItems = [];
while ($row = $result->fetch_assoc()) {
    $galleryItems[] = [
        'image_url' => $row['image_url'],
        'upload_date' => $row['upload_date']
    ];
}

// Return the gallery data as JSON
echo json_encode($galleryItems);
?>
