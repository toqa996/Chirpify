<?php
// Include the configuration file to connect to the database
include 'config.php';

// Start a new session or resume the existing session
session_start();

// Check if the request method is POST (form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize the email input
    $email = trim($_POST["email"]);
    // Retrieve the password input (no sanitization needed here as it's hashed)
    $password = $_POST["password"];

    // Prepare a SQL statement to select the user by email
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // Bind the email parameter to the query
    $stmt->execute(); // Execute the query
    $stmt->store_result(); // Store the result for further processing

    // Check if a user with the given email exists
    if ($stmt->num_rows > 0) {
        // Bind the result columns to variables
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch(); // Fetch the result

        // Verify the provided password against the hashed password in the database
        if (password_verify($password, $hashed_password)) {
            // If the password is correct, store user details in the session
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $username;
            // Redirect the user to the dashboard
            header("Location: dashboard.php");
            exit; // Stop further script execution
        } else {
            // If the password is incorrect, display an error message
            echo "Invalid password!";
        }
    } else {
        // If no user is found with the given email, display an error message
        echo "User not found!";
    }

    // Close the prepared statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Chirpify</title>
</head>
<body>
    <h2>Login</h2>
    <!-- Login form for the user -->
    <form method="post">
        <!-- Input field for email -->
        <input type="email" name="email" placeholder="Email" required><br>
        <!-- Input field for password -->
        <input type="password" name="password" placeholder="Password" required><br>
        <!-- Submit button for the form -->
        <button type="submit">Login</button>
    </form>
</body>
</html>