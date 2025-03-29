<?php
session_start();
include 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$query = "SELECT posts.id, posts.content, posts.created_at, users.username 
          FROM posts JOIN users ON posts.user_id = users.id 
          ORDER BY posts.created_at DESC";
$result = $conn->query($query);
?>

<h1>Welcome, <?php echo $_SESSION["username"]; ?>!</h1>
<a href="logout.php">Logout</a>

<form method="post" action="post.php">
    <textarea name="content" required></textarea>
    <button type="submit">Post</button>
</form>

<?php while ($post = $result->fetch_assoc()) { ?>
    <div>
        <strong><?php echo htmlspecialchars($post["username"]); ?></strong>
        <p><?php echo htmlspecialchars($post["content"]); ?></p>
        <a href="like.php?post_id=<?php echo $post['id']; ?>">Like</a>
    </div>
<?php } ?>
