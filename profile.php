<?php
session_start();
include 'config.php';

// Get user ID from URL or session
$user_id = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];

// Fetch user info
$stmt = $conn->prepare("SELECT id, username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch user's posts
$stmt = $conn->prepare("SELECT id, content, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$posts = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['username']); ?>'s Profile - Chirpify</title>
</head>
<body>

<h2><?php echo htmlspecialchars($user['username']); ?>'s Profile</h2>

<h3>Recent Posts</h3>
<ul>
    <?php while ($post = $posts->fetch_assoc()): ?>
        <li>
            <p><?php echo htmlspecialchars($post['content']); ?></p>
            <small>Posted on <?php echo $post['created_at']; ?></small>
        </li>
    <?php endwhile; ?>
</ul>

<br>
<a href="dashboard.php">Back to Dashboard</a>

</body>
</html>
