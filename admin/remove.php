<?php
// Include the database connection file
include('../db_connect.php');

// Initialize error message variables
$carErr = $categoryErr = $generalErr = "";

// Process car deletion when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_car'])) {
    // Get the car ID from the POST request and convert it to an integer
    $carId = intval($_POST['car_id']);
    if ($carId > 0) {
        // Prepare the SQL statement to delete the car with the specified ID
        $sql = "DELETE FROM cars WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $carId);
        // Execute the statement and check for errors
        if ($stmt->execute()) {
            $generalErr = "Car deleted successfully";
        } else {
            $carErr = "Error deleting car: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $carErr = "Invalid car ID";
    }
}

// Process category deletion when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_category'])) {
    // Get the category ID from the POST request and convert it to an integer
    $categoryId = intval($_POST['category_id']);
    if ($categoryId > 0) {
        // Check if there are cars in the category
        $sql = "SELECT COUNT(*) FROM cars WHERE category_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $stmt->bind_result($carCount);
        $stmt->fetch();
        $stmt->close();

        // If there are cars in the category, do not allow deletion
        if ($carCount > 0) {
            $categoryErr = "Cannot delete category with cars in it";
        } else {
            // Prepare the SQL statement to delete the category with the specified ID
            $sql = "DELETE FROM categories WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $categoryId);
            // Execute the statement and check for errors
            if ($stmt->execute()) {
                $generalErr = "Category deleted successfully";
            } else {
                $categoryErr = "Error deleting category: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        $categoryErr = "Invalid category ID";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Cars and Categories</title>
    <style>
        /* Basic styling for the page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
            margin-bottom: 20px;
        }
        .container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
        .success {
            color: green;
            font-size: 14px;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        button {
            padding: 10px;
            background-color: #007bff;
            border: none;
            width: 100px;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .navbar {
            background-color: #333;
            overflow: hidden;
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
    <!-- Navigation bar -->
    <div class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="add_car.php">Add Car</a>
        <a href="category.php">Add Category</a>
        <a href="remove.php" class="active">Remove Car & Category</a>
        <a href="manage_cars.php">Manage Cars</a>
        <a href="manage_categories.php">Manage Categories</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Container for managing car categories -->
    <div class="container">
        <h2>Manage Car Categories</h2>
        <!-- Display error or success messages -->
        <?php if ($categoryErr) echo '<div class="error">' . $categoryErr . '</div>'; ?>
        <?php if ($generalErr) echo '<div class="success">' . $generalErr . '</div>'; ?>
        <?php if ($carErr) echo '<div class="error">' . $carErr . '</div>'; ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch and display categories from the database
                $sql = "SELECT id, name FROM categories";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                        echo '<td>
                            <form method="post" onsubmit="return confirm(\'Are you sure you want to delete this category?\');">
                                <input type="hidden" name="category_id" value="' . htmlspecialchars($row['id']) . '">
                                <button type="submit" name="delete_category">Delete</button>
                            </form>
                        </td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">No categories found</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Container for managing cars -->
    <div class="container">
        <h2>Manage Cars</h2>
        <!-- Display error or success messages -->
        <?php if ($carErr) echo '<div class="error">' . $carErr . '</div>'; ?>
        <?php if ($generalErr) echo '<div class="success">' . $generalErr . '</div>'; ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch and display cars along with their categories from the database
                $sql = "SELECT cars.id, cars.name, categories.name AS category_name FROM cars JOIN categories ON cars.category_id = categories.id";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['category_name']) . '</td>';
                        echo '<td style="display: flex; gap: 10px; align-items: center; justify-content: center;">
                            <form method="post" onsubmit="return confirm(\'Are you sure you want to delete this car?\');">
                                <input type="hidden" name="car_id" value="' . htmlspecialchars($row['id']) . '">
                                <button type="submit" name="delete_car">Delete</button>
                            </form>
                        
                            <form method="post" action="edit_info.php">
                                <input type="hidden" name="car_id" value="' . htmlspecialchars($row['id']) . '">
                                <button type="submit" name="edit_car">Edit</button>
                            </form>
                        </td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">No cars found</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Close the database connection after use
$conn->close();
?>
