<?php
session_start();
include 'config.php';

if (!isset($_SESSION["user_id"])) {
    die("You must be logged in to like posts.");
}

$user_id = $_SESSION["user_id"];
$post_id = $_POST["post_id"];

// Check if the user already liked the post
$stmt = $conn->prepare("SELECT id FROM likes WHERE user_id = ? AND post_id = ?");
$stmt->bind_param("ii", $user_id, $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Insert like if not already liked
    $stmt = $conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $post_id);
    $stmt->execute();
}

$stmt->close();
header("Location: dashboard.php");
exit;
?>
