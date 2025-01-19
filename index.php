<?php
// Include the database connection file
include('db_connect.php');

// Select the database
mysqli_select_db($conn, 'bmw_car');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Showcase</title>

    <link rel="stylesheet" href="styles.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<style>
        /* Full-screen video background */
        #home {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        #home video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        /* Text styling */
        #home .container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            z-index: 1;
        }

        #home h1 {
            font-size: 3rem;
            font-weight: 600;
            margin: 0;
        }

        #home p {
            font-size: 1.5rem;
            font-weight: 400;
            margin-top: 20px;
        }
        a {
            text-decoration-line: none;
            color: black;
        }
    </style>
<body>

<!-- Navigation Bar -->
<nav>
    <div class="logo">Fusion Cars</div>
    <ul class="nav-links">
        <li><a href="#home">Home</a></li>
        <li><a href="#categories">Categories</a></li>
        <li><a href="#cars">Contact Us</a></li>
        <li><a href="#contact">About Us</a></li>
    </ul>
</nav>


<!-- Video Section -->
<section id="home">
    <div class="container">
        <h1>Welcome To Fusion Cars</h1>
        <p>Explore the ultimate collection of BMW cars</p>
    </div>
    <video autoplay muted loop>
        <source src="Car 4k wallpaper(4K_60FPS).mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</section>

<!-- Categories Section -->
<section id="categories" class="categories-section">
    <h2>Choose Your Category</h2>
    <div class="categories">
        <?php
        // SQL Query to fetch categories
        $sql = "SELECT id, name FROM categories"; // Fetch id and name
        $result = $conn->query($sql);

        if ($result === false) {
            echo "Error: " . $conn->error; // Show error if query fails
        } else {
            if ($result->num_rows > 0) {
                // Loop through all categories
                while ($row = $result->fetch_assoc()) {
                    // Ensure 'name' and 'id' exist in $row
                    if (isset($row['name']) && isset($row['id'])) {
                        echo '<a href="#category-' . htmlspecialchars($row['id']) . '" class="category-btn">' . htmlspecialchars($row['name']) . '</a>';
                    } else {
                        echo "Category not found.";
                    }
                }
            } else {
                echo "No categories available.";
            }
        }
        ?>
    </div>
</section>

<?php
$sql = "SELECT * FROM categories";
$categories_result = $conn->query($sql);

if ($categories_result === false) {
    die("Error fetching categories: " . $conn->error);
} else {
    // Loop through categories to fetch cars
    mysqli_data_seek($categories_result, 0); // Reset the result pointer
    while ($category = $categories_result->fetch_assoc()) {
        echo '<section id="category-' . htmlspecialchars($category['id']) . '" class="car-section">';
        echo '<h3 class="category-title">' . htmlspecialchars($category['name']) . '</h3>';
        echo '<div class="car-container">';

        // Fetch cars for the current category
        $car_sql = "SELECT * FROM cars WHERE category_id = " . intval($category['id']);
        $cars_result = $conn->query($car_sql);

        if ($cars_result && $cars_result->num_rows > 0) {
            while ($car = $cars_result->fetch_assoc()) {
            echo '<a href="car_info.php?id=' . htmlspecialchars($car['id']) . '" class="car-card">';
            // Check if the image path is not empty
            if (!empty($car['images'])) {
                echo '<img src="./assets/images/' . htmlspecialchars($car['images']) . '" alt="' . htmlspecialchars($car['name']) . '">';
            } else {
                echo '<img src="default-image.jpg" alt="Default Image">';
            }
            echo '<h3>' . htmlspecialchars($car['name']) . '</h3>';
            if (!empty($car['description'])) {
                echo '<p>' . htmlspecialchars($car['description']) . '</p>';
            }
          
            echo '</a>';
            }
        } else {
            echo "<p>No cars available in this category.</p>";
        }
            echo '</div>';
        }
    }

    echo '</section>';

?>

<!-- Additional car categories go here similarly (BMW X Series, M Series, etc.) -->

</body>
</html>

<?php
// Close the connection after use
$conn->close();
?>
