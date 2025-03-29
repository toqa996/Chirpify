<?php
session_start();
include 'config.php';

if (!isset($_SESSION["user_id"])) {
    die("You must be logged in to delete posts.");
}

$user_id = $_SESSION["user_id"];
$post_id = $_POST["post_id"];

// Fetch the post to check if it's owned by the current user
$stmt = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if ($post['user_id'] == $user_id) {
    // Delete the post if it belongs to the logged-in user
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->close();
    echo "Post deleted successfully!";
} else {
    echo "You cannot delete someone else's post.";
}

header("Location: dashboard.php");
exit;
?>
