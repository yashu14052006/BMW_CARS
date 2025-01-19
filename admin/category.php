<?php
// Include the database connection file
include('../db_connect.php');

// Initialize variables
$categoryName = "";
$categoryNameErr = $generalErr = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    if (empty($_POST["category_name"])) {
        $categoryNameErr = "Category name is required";
    } else {
        $categoryName = $_POST["category_name"];
    }

    // If no errors, insert into database
    if (empty($categoryNameErr)) {
        // Check if category already exists
        $sql = "SELECT id FROM categories WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $categoryName);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $categoryNameErr = "Category already exists";
        } else {
            $stmt->close();
            $sql = "INSERT INTO categories (name) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $categoryName);
            if ($stmt->execute()) {
                $generalErr = "Category added successfully";
            } else {
                $generalErr = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Category</title>
    <style>
       body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding-top: 50px; /* Add padding to avoid content being hidden behind navbar */
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .form-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 400px;
    }
    .form-container h2 {
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
    /* Dashboard Navigation Bar */
    .navbar {
        background-color: #333;
        overflow: hidden;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
    }

    .navbar a {
        float: left;
        display: block;
        color: #f2f2f2;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
    }

    .navbar a:hover {
        background-color: #ddd;
        color: black;
    }

    .navbar a.active {
        background-color: #4CAF50;
        color: white;
    }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="add_car.php">Add Car</a>
        <a href="category.php" class="active">Add Category</a>
        <a href="remove.php">Remove Car & Category</a>
        <a href="manage_cars.php">Manage Cars</a>
        <a href="manage_categories.php">Manage Categories</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="form-container">
        <h2>Add New Category</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" id="category_name" name="category_name" value="<?php echo htmlspecialchars($categoryName); ?>">
                <div class="error"><?php echo $categoryNameErr; ?></div>
            </div>
            <button type="submit">Add Category</button>
            <div class="error"><?php echo $generalErr; ?></div>
        </form>
    </div>
</body>
</html>