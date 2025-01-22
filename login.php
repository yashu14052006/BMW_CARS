<?php
/**
 * 
 * This script handles the login functionality for the BMW_CARS application.
 * It includes the database connection, processes form submissions, validates user inputs,
 * checks user credentials, and redirects to the dashboard upon successful login.
 */

// Include the database connection file
include('db_connect.php');

// Start the session
session_start();

// Initialize variables for storing user inputs and error messages
$email = $password = "";
$emailErr = $passwordErr = $generalErr = "";

// Process form submission when the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email input
    if (empty($_POST["email"])) {
        $emailErr = "Email is required"; // Set error message if email is empty
    } else {
        $email = $_POST["email"]; // Sanitize and store email input
    }

    // Validate password input
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required"; // Set error message if password is empty
    } else {
        $password = $_POST["password"]; // Sanitize and store password input
    }

    // If no validation errors, proceed to check user credentials
    if (empty($emailErr) && empty($passwordErr)) {
        // Prepare SQL statement to select user by email
        $sql = "SELECT id, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email); // Bind email parameter to the SQL statement
        $stmt->execute(); // Execute the SQL statement
        $stmt->store_result(); // Store the result of the query

        // Check if a user with the provided email exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashedPassword); // Bind result variables
            $stmt->fetch(); // Fetch the result
            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                // If credentials are valid, store user ID in session and redirect to the dashboard
                $_SESSION['user_id'] = $id;
                header("Location: index.php");
                exit();
            } else {
                $generalErr = "Invalid email or password"; // Set error message for invalid credentials
            }
        }
        if (isset($stmt)) {
            $stmt->close(); // Close the statement
        }
        } else {
            $generalErr = "Invalid email or password"; // Set error message if no user found
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
        /* Basic styling for the login page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .login-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .signup-link {
            margin-top: 20px;
            text-align: center;
        }
        .signup-link a {
            color: #007bff;
            text-decoration: none;
        }
        .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <div class="error"><?php echo $emailErr; ?></div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                <div class="error"><?php echo $passwordErr; ?></div>
            </div>
            <button type="submit">Login</button>
            <div class="error"><?php echo $generalErr; ?></div>
        </form>
        <div class="signup-link">
            Don't have an account? <a href="signup.php">Sign up here</a>.
        </div>
    </div>
</body>
</html>