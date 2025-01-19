<?php
// Include the database connection file
include('../db_connect.php');

// Initialize variables
$carId = $carName = $year = $kilometers = $fuelType = $transmission = $price = $exteriorColor = $imagePath = $registrationYear = $groundClearance = $bootSpace = $torque = $power = $engineCapacity = $ownershipStatus = "";
$carNameErr = $yearErr = $kilometersErr = $fuelTypeErr = $transmissionErr = $priceErr = $exteriorColorErr = $imageErr = $registrationYearErr = $groundClearanceErr = $bootSpaceErr = $torqueErr = $powerErr = $engineCapacityErr = $ownershipStatusErr = $generalErr = "";

// Fetch car details if carId is provided
if (isset($_GET['carId']) && !empty($_GET['carId'])) {
    $carId = intval($_GET['carId']);
    $sql = "SELECT * FROM car_information WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $carId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $car = $result->fetch_assoc();
        $carName = $car['name'];
       
        $kilometers = $car['kilometers_done'];
        $fuelType = $car['fuel'];
        $transmission = $car['transmission'];
        $price = $car['price'];
        $exteriorColor = $car['exterior_color'];
        $imagePath = $car['image'];
        $registrationYear = $car['registration_year'];
        $groundClearance = $car['ground_clearance'];
        $bootSpace = $car['boot_space'];
        $torque = $car['torque'];
        $power = $car['power'];
        $engineCapacity = $car['engine_capacity'];
        $ownershipStatus = $car['ownership_status'];
    } else {
        $generalErr = "Car not found";
    }
    $stmt->close();
}

// Handle form submission for updating car details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $carId = intval($_POST["car_id"]);
$carName = isset($_POST["car_name"]) ? $_POST["car_name"] : "";

$kilometers = isset($_POST["kilometers"]) ? $_POST["kilometers"] : "";
$fuelType = isset($_POST["fuel_type"]) ? $_POST["fuel_type"] : "";
$transmission = isset($_POST["transmission"]) ? $_POST["transmission"] : "";
$price = isset($_POST["price"]) ? $_POST["price"] : "";
$exteriorColor = isset($_POST["exterior_color"]) ? $_POST["exterior_color"] : "";
$registrationYear = isset($_POST["registration_year"]) ? $_POST["registration_year"] : "";
$groundClearance = isset($_POST["ground_clearance"]) ? $_POST["ground_clearance"] : "";
$bootSpace = isset($_POST["boot_space"]) ? $_POST["boot_space"] : "";
$torque = isset($_POST["torque"]) ? $_POST["torque"] : "";
$power = isset($_POST["power"]) ? $_POST["power"] : "";
$engineCapacity = isset($_POST["engine_capacity"]) ? $_POST["engine_capacity"] : "";
$ownershipStatus = isset($_POST["ownership_status"]) ? $_POST["ownership_status"] : "";

    // Handle image upload
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $targetDir = "../assets/car-info/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
        $imagePath = "" . basename($_FILES["image"]["name"]);
    }

    // Update car details in the database
    $sql = "UPDATE car_information SET name=?, kilometers_done=?, fuel=?, transmission=?, price=?, exterior_color=?, image=?, registration_year=?, ground_clearance=?, boot_space=?, torque=?, power=?, engine_capacity=?, ownership_status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssi", $carName, $kilometers, $fuelType, $transmission, $price, $exteriorColor, $imagePath, $registrationYear, $groundClearance, $bootSpace, $torque, $power, $engineCapacity, $ownershipStatus, $carId);
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $generalErr = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
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
        .form-group input, .form-group select, .form-group textarea {
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Car Information</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($carId); ?>">
            <div class="form-group">
                <label for="car_name">Car Name</label>
                <input type="text" id="car_name" name="car_name" value="<?php echo htmlspecialchars($carName); ?>">
                <div class="error"><?php echo $carNameErr; ?></div>
            </div>
     
            <div class="form-group">
                <label for="kilometers">Kilometers Driven</label>
                <input type="text" id="kilometers" name="kilometers" value="<?php echo htmlspecialchars($kilometers); ?>">
                <div class="error"><?php echo $kilometersErr; ?></div>
            </div>
            <div class="form-group">
                <label for="fuel_type">Fuel Type</label>
                <input type="text" id="fuel_type" name="fuel_type" value="<?php echo htmlspecialchars($fuelType); ?>">
                <div class="error"><?php echo $fuelTypeErr; ?></div>
            </div>
            <div class="form-group">
                <label for="transmission">Transmission</label>
                <input type="text" id="transmission" name="transmission" value="<?php echo htmlspecialchars($transmission); ?>">
                <div class="error"><?php echo $transmissionErr; ?></div>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>">
                <div class="error"><?php echo $priceErr; ?></div>
            </div>
            <div class="form-group">
                <label for="exterior_color">Exterior Color</label>
                <input type="text" id="exterior_color" name="exterior_color" value="<?php echo htmlspecialchars($exteriorColor); ?>">
                <div class="error"><?php echo $exteriorColorErr; ?></div>
            </div>
            <div class="form-group">
                <label for="registration_year">Registration Year</label>
                <input type="text" id="registration_year" name="registration_year" value="<?php echo htmlspecialchars($registrationYear); ?>">
                <div class="error"><?php echo $registrationYearErr; ?></div>
            </div>
            <div class="form-group">
                <label for="ground_clearance">Ground Clearance</label>
                <input type="text" id="ground_clearance" name="ground_clearance" value="<?php echo htmlspecialchars($groundClearance); ?>">
                <div class="error"><?php echo $groundClearanceErr; ?></div>
            </div>
            <div class="form-group">
                <label for="boot_space">Boot Space</label>
                <input type="text" id="boot_space" name="boot_space" value="<?php echo htmlspecialchars($bootSpace); ?>">
                <div class="error"><?php echo $bootSpaceErr; ?></div>
            </div>
            <div class="form-group">
                <label for="torque">Torque</label>
                <input type="text" id="torque" name="torque" value="<?php echo htmlspecialchars($torque); ?>">
                <div class="error"><?php echo $torqueErr; ?></div>
            </div>
            <div class="form-group">
                <label for="power">Power</label>
                <input type="text" id="power" name="power" value="<?php echo htmlspecialchars($power); ?>">
                <div class="error"><?php echo $powerErr; ?></div>
            </div>
            <div class="form-group">
                <label for="engine_capacity">Engine Capacity</label>
                <input type="text" id="engine_capacity" name="engine_capacity" value="<?php echo htmlspecialchars($engineCapacity); ?>">
                <div class="error"><?php echo $engineCapacityErr; ?></div>
            </div>
            <div class="form-group">
                <label for="ownership_status">Ownership Status</label>
                <input type="text" id="ownership_status" name="ownership_status" value="<?php echo htmlspecialchars($ownershipStatus); ?>">
                <div class="error"><?php echo $ownershipStatusErr; ?></div>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image">
                <div class="error"><?php echo $imageErr; ?></div>
            </div>
            <button type="submit">Update Car</button>
            <div class="error"><?php echo $generalErr; ?></div>
        </form>
    </div>
</body>
</html>

<?php
// Close the connection after use
$conn->close();
?>