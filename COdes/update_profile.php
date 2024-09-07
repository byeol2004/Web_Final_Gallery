
<?php
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    
    if (!empty($_FILES['profile_pic']['name'])) {
        $targetDir = "uploads/profile_pics/";
        $targetFile = $targetDir . basename($_FILES["profile_pic"]["name"]);
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile);

        $sql = "UPDATE users SET username='$username', email='$email', profile_pic='$targetFile' WHERE id='$userId'";
    } else {
        $sql = "UPDATE users SET username='$username', email='$email' WHERE id='$userId'";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php?page=profile");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
