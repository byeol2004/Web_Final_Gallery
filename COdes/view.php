<?php
$photoId = $_GET['id'];
$sql = "SELECT * FROM photos WHERE id='$photoId'";
$result = $conn->query($sql);
$photo = $result->fetch_assoc();
?>

<h2><?php echo $photo['title']; ?></h2>
<img src="<?php echo $photo['image_path']; ?>" alt="<?php echo $photo['title']; ?>">
<p><?php echo $photo['description']; ?></p>

<!-- Comments Section -->
<h3>Comments</h3>
<?php
$sql = "SELECT * FROM comments WHERE photo_id='$photoId' ORDER BY created_at DESC";
$comments = $conn->query($sql);
while($comment = $comments->fetch_assoc()) {
    echo "<p><strong>{$comment['user_id']}:</strong> {$comment['comment']}</p>";
}
?>

<form action="index.php?page=add_comment" method="post">
    <textarea name="comment" placeholder="Add a comment..." required></textarea><br>
    <input type="hidden" name="photo_id" value="<?php echo $photoId; ?>">
    <input type="submit" value="Submit Comment">
</form>
