<?php
session_start();
include('../db_connect.php'); // Include your database connection file

// Example credentials (In practice, these should come from your database)
$storedUsername = 'admin';
$storedPasswordHash = '$2y$10$kMkhr9UtGv3HzqxQ6KnRIi06Wr8szDd1cdeY96orYdx9lhIt9cVHS'; // This is '123456789' hashed

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Check if the user is an administrator
    if ($username === 'admin') {
        header("Location: dashboard.php");
        exit();
    }
    // Check if the username and password match
    if ($username === $storedUsername && password_verify($password, $storedPasswordHash)) {
        // Start session and store username
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $errorMessage = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 95%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .login-container input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php
        if (isset($errorMessage)) {
            echo "<p class='error'>$errorMessage</p>";
        }
        ?>
        <form method="post" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
