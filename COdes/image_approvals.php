<?php
include('db.php');
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $photoId = $_POST['photo_id'];
    $status = $_POST['status'];

    $sql = "UPDATE photo_approval SET status='$status', reviewed_by='$_SESSION[user_id]', review_date=NOW() WHERE photo_id='$photoId'";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php?page=admin_panel");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
