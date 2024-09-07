
<?php
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    $sql = "INSERT INTO albums (user_id, name, description) VALUES ('$userId', '$name', '$description')";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php?page=albums");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
