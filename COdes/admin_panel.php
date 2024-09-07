<?php
include('db.php');
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Fetch all users for moderation, approval, etc.
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<h2>Admin Panel</h2>

<h3>Manage Users</h3>
<ul>
    <?php while($user = $result->fetch_assoc()): ?>
        <li><?php echo $user['username']; ?> (<?php echo $user['role']; ?>)</li>
    <?php endwhile; ?>
</ul>

<h3>Photo Approval</h3>
<!-- Fetch and display photos pending approval -->
