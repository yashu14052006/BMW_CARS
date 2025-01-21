<?php
/**
 * This script handles the user registration (sign-up) functionality for the BMW_CARS application.
 * It includes the database connection, processes form submissions, validates user inputs,
 * checks for existing email addresses, hashes passwords, and inserts new user records into the database.
 */

// Include the database connection file
include('db_connect.php');

// Initialize variables to store user inputs and error messages
$fullName = $email = $password = $confirmPassword = "";
$fullNameErr = $emailErr = $passwordErr = $confirmPasswordErr = $generalErr = "";

// Process form submission when the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate full name input
    if (empty($_POST["full_name"])) {
        $fullNameErr = "Full Name is required"; // Set error message if full name is empty
    } else {
        $fullName = $_POST["full_name"]; // Sanitize and store full name input
    }

    // Validate email input
    if (empty($_POST["email"])) {
        $emailErr = "Email is required"; // Set error message if email is empty
    } else {
        $email = $_POST["email"]; // Sanitize and store email input
        // Check if email already exists in the database
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email); // Bind email parameter to the SQL statement
        $stmt->execute(); // Execute the SQL statement
        $stmt->store_result(); // Store the result of the query
        if ($stmt->num_rows > 0) {
            $emailErr = "Email already exists"; // Set error message if email already exists
        }
        $stmt->close(); // Close the statement
    }

    // Validate password input
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required"; // Set error message if password is empty
    } else {
        $password = $_POST["password"]; // Sanitize and store password input
    }

    // Validate confirm password input
    if (empty($_POST["confirm_password"])) {
        $confirmPasswordErr = "Confirm Password is required"; // Set error message if confirm password is empty
    } else {
        $confirmPassword = $_POST["confirm_password"]; // Sanitize and store confirm password input
        if ($password !== $confirmPassword) {
            $confirmPasswordErr = "Passwords do not match"; // Set error message if passwords do not match
        }
    }

    // If no validation errors, proceed to insert the new user into the database
    if (empty($fullNameErr) && empty($emailErr) && empty($passwordErr) && empty($confirmPasswordErr)) {
        // Hash the password for secure storage
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // Prepare SQL statement to insert new user record
        $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $fullName, $email, $hashedPassword); // Bind parameters to the SQL statement
        if ($stmt->execute()) {
            // Redirect to login page with success message if insertion is successful
            header("Location: login.php?message=Account created successfully. Please log in.");
            exit();
        } else {
            $generalErr = "Error: " . $stmt->error; // Set general error message if insertion fails
        }
        $stmt->close(); // Close the statement
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        /* Basic styling for the sign-up page */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .signup-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .signup-container h2 {
            margin-bottom: 20px;
            font-size: 1.8rem;
            text-align: center;
        }
        .signup-container form {
            display: flex;
            flex-direction: column;
        }
        .signup-container form .form-group {
            margin-bottom: 15px;
        }
        .signup-container form .form-group label {
            margin-bottom: 5px;
            font-weight: 500;
        }
        .signup-container form .form-group input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        .signup-container form .form-group input:focus {
            border-color: #ffcc00;
            outline: none;
        }
        .signup-container form .form-group .error {
            color: red;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        .signup-container form button {
            padding: 10px;
            background-color: #ffcc00;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .signup-container form button:hover {
            background-color: #e6b800;
        }
        .signup-container .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .signup-container .login-link a {
            color: #ffcc00;
            text-decoration: none;
        }
        .signup-container .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($fullName); ?>">
                <div class="error"><?php echo $fullNameErr; ?></div>
            </div>
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
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password">
                <div class="error"><?php echo $confirmPasswordErr; ?></div>
            </div>
            <button type="submit">Sign Up</button>
            <div class="error"><?php echo $generalErr; ?></div>
        </form>
        <div class="login-link">
            Already have an account? <a href="login.php">Log in here</a>.
        </div>
    </div>
</body>
</html>