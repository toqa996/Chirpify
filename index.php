<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Chirpify</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            margin-top: 100px;
        }
        h1 {
            color: #333;
        }
        p {
            font-size: 18px;
            color: #555;
        }
        .buttons {
            margin-top: 20px;
        }
        a {
            text-decoration: none;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            display: inline-block;
        }
        .signup {
            background-color: #007bff;
            color: white;
        }
        .login {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Welcome to Chirpify 🐦</h1>
    <p>Chirpify is the best place to share your thoughts, like posts, and connect with others.</p>
    
    <div class="buttons">
        <a href="register.php" class="signup">Sign Up</a>
        <a href="login.php" class="login">Log In</a>
    </div>
</div>

</body>
</html>
