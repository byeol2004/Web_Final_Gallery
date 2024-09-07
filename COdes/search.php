
<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $searchTerm = $_POST['search'];

    $sql = "SELECT * FROM photos WHERE title LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%'";
    $result = $conn->query($sql);
}
?>

<h2>Search Results</h2>

<div class="photo-grid">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="photo-item">
            <a href="index.php?page=view_photo&id=<?php echo $row['id']; ?>">
                <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['title']; ?>">
            </a>
            <p><?php echo $row['title']; ?></p>
        </div>
    <?php endwhile; ?>
</div>

<form action="index.php?page=search" method="post">
    <input type="text" name="search" placeholder="Search..." required><br>
    <input type="submit" value="Search">
</form>
