<?php
session_start();
include 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["content"])) { 
        // Handle new post submission
        $content = trim($_POST["content"]);
        $user_id = $_SESSION["user_id"];

        if (!empty($content)) {
            $stmt = $conn->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
            $stmt->bind_param("is", $user_id, $content);
            $stmt->execute();
            $stmt->close();
            header("Location: dashboard.php");
            exit;
        }
    } elseif (isset($_POST["like"])) {
        // Handle post like
        $post_id = $_POST["post_id"];
        $user_id = $_SESSION["user_id"];

        $check_like = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND post_id = ?");
        $check_like->bind_param("ii", $user_id, $post_id);
        $check_like->execute();
        $result = $check_like->get_result();

        if ($result->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $post_id);
            $stmt->execute();
            $stmt->close();
        }
    } elseif (isset($_POST["delete"])) {
        // Handle post delete
        $post_id = $_POST["post_id"];
        $user_id = $_SESSION["user_id"];



            // Check if the post belongs to the logged-in user
        $check_post = $conn->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
        $check_post->bind_param("ii", $post_id, $user_id);
        $check_post->execute();
        $result = $check_post->get_result();

        if ($result->num_rows > 0) {
            // Delete the post and its likes
            $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->bind_param("i", $post_id);
            $stmt->execute();
            $stmt->close();
        }

        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Chirpify</title>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
    <form method="post">
        <textarea name="content" placeholder="What's on your mind?" required></textarea><br>
        <button type="submit">Post</button>
    </form>

    <h2>Recent Posts</h2>
    <?php
    $stmt = $conn->prepare("SELECT posts.id, posts.content, posts.created_at, users.username, posts.user_id,
                           (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) AS like_count
                            FROM posts 
                            JOIN users ON posts.user_id = users.id 
                            ORDER BY posts.created_at DESC");
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>{$row['username']}</strong>: {$row['content']} <br><small>{$row['created_at']}</small></p>";
        echo "<form method='post'>
                <input type='hidden' name='post_id' value='{$row['id']}'>
                <button type='submit' name='like'>❤️ Like ({$row['like_count']})</button>
              </form>";

        // Show delete button only for the post owner
        if ($_SESSION["user_id"] == $row["user_id"]) {
            echo "<form method='post'>
                    <input type='hidden' name='post_id' value='{$row['id']}'>
                    <button type='submit' name='delete'>🗑️ Delete</button>
                  </form>";
        }
    }

    $stmt->close();
    ?>


    <br>
    <a href="logout.php">Logout</a>
</body>
</html>
