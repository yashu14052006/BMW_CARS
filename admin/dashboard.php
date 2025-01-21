<?php
// Include the database connection file
include('../db_connect.php');

// Fetch summary data for the dashboard
// Get the total number of cars from the 'cars' table
$totalCars = $conn->query("SELECT COUNT(*) AS total FROM cars")->fetch_assoc()['total'];
// Get the total number of categories from the 'categories' table
$totalCategories = $conn->query("SELECT COUNT(*) AS total FROM categories")->fetch_assoc()['total'];
// Fetch the latest 5 cars with their name and image path from the 'cars' table
$recentCars = $conn->query("SELECT name, images FROM cars ORDER BY id DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        /* Navbar styling */
        .navbar {
            background-color: #333;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        
        /* Main container styling */
        .container {
            padding: 20px;
        }

        /* Welcome section */
        .welcome {
            margin-bottom: 20px;
        }

        /* Summary section with total cars and categories */
        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .summary div {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 30%;
            text-align: center;
        }

        /* Actions section (Add Car, Add Category, Manage Cars) */
        .actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .actions a {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            width: 30%;
        }
        .actions a:hover {
            background-color: #0056b3;
        }

        /* Recent Cars section */
        .recent-cars {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .recent-cars img {
            width: 100px;
            height: auto;
            margin-right: 10px;
        }
        .recent-cars div {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        /* Welcome admin message */
        .bottom-middle {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar section with admin options -->
    <div class="navbar">
        <div class="logo">Admin Dashboard</div>
        <div>
            <a href="category.php">Add Category</a>
            <a href="remove.php">Remove Car & Category</a>
            <a href="manage_cars.php">Manage Cars</a>
            <a href="manage_categories.php">Manage Categories</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- Main content container -->
    <div class="container">
        <!-- Welcome message (can be customized) -->
        <div class="welcome">
            <h1><br></h1>
            <p><br></p>
        </div>

        <!-- Summary of total cars and total categories -->
        <div class="summary">
            <div>
                <h2>Total Cars</h2>
                <p><?php echo $totalCars; ?></p>
            </div>
            <div>
                <h2>Total Categories</h2>
                <p><?php echo $totalCategories; ?></p>
            </div>
        </div>

        <!-- Actions section with links for adding cars, adding categories, and managing cars -->
        <div class="actions">
            <a href="add_car.php">Add Car</a>
            <a href="category.php">Add Category</a>
            <a href="manage_cars.php">Manage Cars</a>
        </div>

        <!-- Admin welcome message -->
        <div class="bottom-middle">
            <h2>Welcome, Admin!</h2>
            <p>Manage your car showroom efficiently from this dashboard.</p>
        </div>
    </div>
</body>
</html>

<?php
// Close the connection after use
$conn->close();
?>
