<?php
// Include the database connection file
include('db_connect.php');

// Fetch car details based on the provided car ID from the URL query parameter
$carId = isset($_GET['id']) ? intval($_GET['id']) : 0; // Get the car ID from the URL, default to 0 if not set
$sql = "SELECT * FROM car_information WHERE id = ?"; // SQL query to fetch car details
$stmt = $conn->prepare($sql); // Prepare the SQL statement
$stmt->bind_param("i", $carId); // Bind the car ID parameter to the SQL query
$stmt->execute(); // Execute the SQL query
$result = $stmt->get_result(); // Get the result set from the executed query
$car = $result->fetch_assoc(); // Fetch the car details as an associative array
$stmt->close(); // Close the prepared statement
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Details - Fusion Cars</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Basic styling for the page */
        body {
            font-family: Arial, sans-serif;
            background-color: transparent;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: transparent;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .logo {
            font-size: 1.5rem;
            font-weight: 600;
        }
        .main-content {
            display: flex;
            flex-wrap: wrap;
            padding: 20px;
            background-color: transparent;
            margin: 20px;
            margin-top: 10vh;
            border-radius: 8px;
        }
        .main-content img {
            width: 100%;
            max-width: 500px;
            border-radius: 8px;
        }
        .car-details {
            flex: 1;
            padding: 20px;
        }
        .car-details h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .car-details p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        .booking-form {
            flex: 1;
            padding: 20px;
            background-color: transparent;
            border-radius: 8px;
        }
        .booking-form h3 {
            margin-bottom: 20px;
        }
        .booking-form input, .booking-form select, .booking-form textarea, .booking-form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .booking-form button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .booking-form button:hover {
            background-color: #0056b3;
        }
        .features {
            display: flex;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .feature {
            flex: 1 1 30%;
            padding: 10px;
            background-color: transparent;
            margin: 10px;
            border-radius: 8px;
            text-align: center;
        }
        .feature i {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            background-color: transparent;
            color: #fff;
        }
    </style>
</head>
<body>
    <?php 
    // Include the header file
    include('header.php');
    ?>

    <div class="main-content">
        <?php if ($car): // Check if car details are available ?>
        <img src="./assets/car-info/<?php echo htmlspecialchars($car['image']); ?>" alt="<?php echo htmlspecialchars($car['name']); ?>">
        <div class="car-details">
            <h2><?php echo htmlspecialchars($car['name']); ?></h2>
            <p>Price: ₹<?php echo number_format($car['price'], 2); ?> (Included All Taxes)</p>
        </div>
        <?php else: // If car details are not available ?>
        <div class="car-details">
            <h2>Car not found</h2>
            <p>Price: ₹0.00 (Included All Taxes)</p>
        </div>
        <?php endif; ?>
        <div class="booking-form">
            <h3>Request for Booking</h3>
            <form action="booking.php" method="post">
                <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($car['id']); ?>"> <!-- Hidden field to pass car ID -->
                <input type="text" name="name" placeholder="Name" required> <!-- Input field for name -->
                <input type="text" name="mobile" placeholder="Mobile" required> <!-- Input field for mobile number -->
                <select name="state" required> <!-- Dropdown for selecting state -->
                    <option value="">Select State</option>
                    <!-- Add state options here -->
                </select>
                <select name="city" required> <!-- Dropdown for selecting city -->
                    <option value="">Select City</option>
                    <!-- Add city options here -->
                </select>
                <textarea name="query" placeholder="Query" required></textarea> <!-- Textarea for additional queries -->
                <button type="submit">Request for Booking</button> <!-- Submit button for the form -->
            </form>
        </div>
    </div>

    <div class="features">
        <!-- Display various car features -->
        <div class="feature">
            <i class="fas fa-calendar-alt"></i>
            <p>Manufacturing Year: Not Available</p>
        </div>
        <div class="feature">
            <i class="fas fa-road"></i>
            <p>Kilometers Done: <?php echo isset($car['kilometers_done']) ? htmlspecialchars($car['kilometers_done']) : 'Not Available'; ?></p>
        </div>
        <div class="feature">
            <i class="fas fa-cogs"></i>
            <p>Transmission: <?php echo isset($car['transmission']) ? htmlspecialchars($car['transmission']) : 'Not Available'; ?></p>
        </div>
        <div class="feature">
            <i class="fas fa-gas-pump"></i>
            <p>Fuel Type: <?php echo isset($car['fuel']) ? htmlspecialchars($car['fuel']) : 'Not Available'; ?></p>
        </div>
        <div class="feature">
            <i class="fas fa-calendar-check"></i>
            <p>Registration Year: <?php echo isset($car['registration_year']) ? htmlspecialchars($car['registration_year']) : 'Not Available'; ?></p>
        </div>
        <div class="feature">
            <i class="fas fa-palette"></i>
            <p>Exterior Color: <?php echo isset($car['exterior_color']) ? htmlspecialchars($car['exterior_color']) : 'Not Available'; ?></p>
        </div>
        <div class="feature">
            <i class="fas fa-arrows-alt-v"></i>
            <p>Ground Clearance: <?php echo isset($car['ground_clearance']) ? htmlspecialchars($car['ground_clearance']) : 'Not Available'; ?></p>
        </div>
        <div class="feature">
            <i class="fas fa-box"></i>
            <p>Boot Space: <?php echo isset($car['boot_space']) ? htmlspecialchars($car['boot_space']) : 'Not Available'; ?></p>
        </div>
        <div class="feature">
            <i class="fas fa-tachometer-alt"></i>
            <p>Torque: <?php echo isset($car['torque']) ? htmlspecialchars($car['torque']) : 'Not Available'; ?></p>
        </div>
        <div class="feature">
            <i class="fas fa-bolt"></i>
            <p>Power: <?php echo isset($car['power']) ? htmlspecialchars($car['power']) : 'Not Available'; ?></p>
        </div>
        <div class="feature">
            <i class="fas fa-car"></i>
            <p>Engine Capacity: <?php echo isset($car['engine_capacity']) ? htmlspecialchars($car['engine_capacity']) : 'Not Available'; ?></p>
        </div>
        <div class="feature">
            <i class="fas fa-user"></i>
            <p>Ownership Status: <?php echo isset($car['ownership_status']) ? htmlspecialchars($car['ownership_status']) : 'Not Available'; ?></p>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection after use
$conn->close();
?>
