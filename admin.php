<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Check if user is an admin
$stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || !$user["is_admin"]) {
    echo "Access denied. You are not an admin.";
    exit;
}

// Handle user deletion
if (isset($_GET["delete"])) {
    $user_id = $_GET["delete"];

    // Prevent admin from deleting themselves
    if ($user_id == $_SESSION["user_id"]) {
        echo "You cannot delete yourself!";
    } else {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            echo "User deleted successfully!";
        }
        $stmt->close();
    }
}

// Get all users
$stmt = $conn->prepare("SELECT id, username, is_admin FROM users");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Chirpify</title>
</head>
<body>
    <h2>Admin Panel</h2>
    
    <table border="1">
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php while ($user = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $user["id"]; ?></td>
            <td><?php echo htmlspecialchars($user["username"]); ?></td>
            <td><?php echo $user["is_admin"] ? "Admin" : "User"; ?></td>
            <td>
                <?php if (!$user["is_admin"]): ?>
                    <a href="admin.php?delete=<?php echo $user["id"]; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
