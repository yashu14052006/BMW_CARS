<?php
// Include the database connection file
include('../db_connect.php');

// Initialize variables for form inputs and error messages
$carId = $carName = $year = $kilometers = $fuelType = $transmission = $price = $exteriorColor = $imagePath = $registrationYear = $groundClearance = $bootSpace = $torque = $power = $engineCapacity = $ownershipStatus = "";
$carNameErr = $yearErr = $kilometersErr = $fuelTypeErr = $transmissionErr = $priceErr = $exteriorColorErr = $imageErr = $registrationYearErr = $groundClearanceErr = $bootSpaceErr = $torqueErr = $powerErr = $engineCapacityErr = $ownershipStatusErr = $generalErr = "";

// Handle form submission for adding/updating car
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs

// Purpose: Ye code ensure karta hai ki "Car Name" field khaali na ho.
//Agar user ne kuch bhi nahi likha, to error message show karega: "Car name is required".
//Agar user ne field bhara hai, to uski input ko process ke liye accept kar lega.
    if (empty($_POST["car_name"])) {
        $carNameErr = "Car name is required";
    } else {
        $carName = $_POST["car_name"];
    }

    if (empty($_POST["year"])) {
        $yearErr = "Year is required";
    } else {
        $year = $_POST["year"];
    }

    if (empty($_POST["kilometers"])) {
        $kilometersErr = "Kilometers driven is required";
    } else {
        $kilometers = $_POST["kilometers"];
    }

    if (empty($_POST["fuel_type"])) {
        $fuelTypeErr = "Fuel type is required";
    } else {
        $fuelType = $_POST["fuel_type"];
    }

    if (empty($_POST["transmission"])) {
        $transmissionErr = "Transmission is required";
    } else {
        $transmission = $_POST["transmission"];
    }

    if (empty($_POST["price"])) {
        $priceErr = "Price is required";
    } else {
        $price = $_POST["price"];
    }

    if (empty($_POST["exterior_color"])) {
        $exteriorColorErr = "Exterior color is required";
    } else {
        $exteriorColor = $_POST["exterior_color"];
    }

    if (empty($_POST["registration_year"])) {
        $registrationYearErr = "Registration year is required";
    } else {
        $registrationYear = $_POST["registration_year"];
    }

    if (empty($_POST["ground_clearance"])) {
        $groundClearanceErr = "Ground clearance is required";
    } else {
        $groundClearance = $_POST["ground_clearance"];
    }

    if (empty($_POST["boot_space"])) {
        $bootSpaceErr = "Boot space is required";
    } else {
        $bootSpace = $_POST["boot_space"];
    }

    if (empty($_POST["torque"])) {
        $torqueErr = "Torque is required";
    } else {
        $torque = $_POST["torque"];
    }

    if (empty($_POST["power"])) {
        $powerErr = "Power is required";
    } else {
        $power = $_POST["power"];
    }

    if (empty($_POST["engine_capacity"])) {
        $engineCapacityErr = "Engine capacity is required";
    } else {
        $engineCapacity = $_POST["engine_capacity"];
    }

    if (empty($_POST["ownership_status"])) {
        $ownershipStatusErr = "Ownership status is required";
    } else {
        $ownershipStatus = $_POST["ownership_status"];
    }

    // Handle image upload

    //$targetFile ka use file ko specified folder me save karne ke liye hota hai.
    //Jab move_uploaded_file() function ko call karte hain, tab yeh batata hai ki file ko kaha move karna hai. 

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $targetDir = "../assets/car-info/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
        $imagePath = "" . basename($_FILES["image"]["name"]);
    }

    // Agar sabhi error variables khaali hain, matlab validation successful hai
    if (empty($carNameErr) && empty($yearErr) && empty($kilometersErr) && empty($fuelTypeErr) && empty($transmissionErr) && empty($priceErr) && empty($exteriorColorErr) && empty($imageErr) && empty($registrationYearErr) && empty($groundClearanceErr) && empty($bootSpaceErr) && empty($torqueErr) && empty($powerErr) && empty($engineCapacityErr) && empty($ownershipStatusErr)) {
        // Agar car_id set hai aur khaali nahi hai, to existing car ko update karenge
        if (isset($_POST["car_id"]) && !empty($_POST["car_id"])) {
          // Car ID ko integer me convert karte hain
            $carId = intval($_POST["car_id"]);
              // Existing car ki details ko update karne ka SQL query
            $sql = "UPDATE cars SET name=?, kilometers_done=?, fuel=?, transmission=?, price=?, exterior_color=?, image=?, registration_year=?, ground_clearance=?, boot_space=?, torque=?, power=?, engine_capacity=?, ownership_status=? WHERE id=?";
            $stmt = $conn->prepare($sql);
           // Parameters ko bind karte hain (car ke details ko database me update karne ke liye)
            $stmt->bind_param("ssssssssssssssi", $carName, $kilometers, $fuelType, $transmission, $price, $exteriorColor, $imagePath, $registrationYear, $groundClearance, $bootSpace, $torque, $power, $engineCapacity, $ownershipStatus, $carId);
        } else {
             // Agar car_id nahi hai, to new car ko add karenge
            $sql = "INSERT INTO car_information (name, kilometers_done, fuel, transmission, price, exterior_color, image, registration_year, ground_clearance, boot_space, torque, power, engine_capacity, ownership_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
           // New car ke details ko database me insert karne ke liye parameters ko bind karte hain
            $stmt->bind_param("ssssssssssssss", $carName, $kilometers, $fuelType, $transmission, $price, $exteriorColor, $imagePath, $registrationYear, $groundClearance, $bootSpace, $torque, $power, $engineCapacity, $ownershipStatus);
        }

      // Agar SQL query execute ho jaye, to success message set karte hain
        if ($stmt->execute()) {
        $generalErr = "Car details saved successfully";  // Success message
} else {
    // Agar query execute nahi hoti, to error message set karte hain
        $generalErr = "Error: " . $stmt->error;  // Error message with specific error details
}

// Statement ko close karte hain, taaki resources release ho sakein
        $stmt->close();

    }
}

// Agar 'delete' parameter GET request se pass hota hai aur khaali nahi hota
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    // Car ID ko integer me convert karte hain
    $carId = intval($_GET['delete']);
    
    // SQL query jo car ko delete karegi
    $sql = "DELETE FROM car_information WHERE id=?";
    
    // Query ko prepare karte hain
    $stmt = $conn->prepare($sql);
    // Car ID ko SQL query me bind karte hain
    $stmt->bind_param("i", $carId);

    // Agar query successful hoti hai to success message, nahi to error message
    if ($stmt->execute()) {
        $generalErr = "Car deleted successfully";  // Success message
    } else {
        $generalErr = "Error deleting car: " . $stmt->error;  // Error message with specific error details
    }

    // Statement ko close karte hain
    $stmt->close();
}


// Car information ko fetch karte hain taaki dropdown me display kiya ja sake
$car_information = [];

// SQL query jo cars table se saari car information fetch karegi
$sql = "SELECT * FROM cars";
$result = $conn->query($sql);

// Agar query se koi result milta hai to usse car_information array me store karte hain
if ($result->num_rows > 0) {
    // Har row ko fetch karte hain aur array me add karte hain
    while ($row = $result->fetch_assoc()) {
        $car_information[] = $row;  // Car details ko $car_information array me add karte hain
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage car_information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
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
    <script>
        // JavaScript to populate form fields based on selected car
        var carInformation = <?php echo json_encode($car_information); ?>;
       //    * Function to populate form fields with car details based on selected car ID
        function populateForm(carId) {
            //  // Find the car object in the array that matches the selected car ID
            var car = carInformation.find(car => car.id == carId);
            // // If a matching car is found, populate the form fields with its details
            if (car) {
                document.getElementById('car_name').value = car.name;
                document.getElementById('year').value = car.year;
                document.getElementById('kilometers').value = car.kilometers_done;
                document.getElementById('fuel_type').value = car.fuel;
                document.getElementById('transmission').value = car.transmission;
                document.getElementById('price').value = car.price;
                document.getElementById('exterior_color').value = car.exterior_color;
                document.getElementById('registration_year').value = car.registration_year;
                document.getElementById('ground_clearance').value = car.ground_clearance;
                document.getElementById('boot_space').value = car.boot_space;
                document.getElementById('torque').value = car.torque;
                document.getElementById('power').value = car.power;
                document.getElementById('engine_capacity').value = car.engine_capacity;
                document.getElementById('ownership_status').value = car.ownership_status;
            } else {
                document.getElementById('car_name').value = '';
                document.getElementById('year').value = '';
                document.getElementById('kilometers').value = '';
                document.getElementById('fuel_type').value = '';
                document.getElementById('transmission').value = '';
                document.getElementById('price').value = '';
                document.getElementById('exterior_color').value = '';
                document.getElementById('registration_year').value = '';
                document.getElementById('ground_clearance').value = '';
                document.getElementById('boot_space').value = '';
                document.getElementById('torque').value = '';
                document.getElementById('power').value = '';
                document.getElementById('engine_capacity').value = '';
                document.getElementById('ownership_status').value = '';
            }
        }
    </script>
</head>
<body>
      <!-- Yeh code ek dropdown menu banata hai jisme user ek car select kar sakta hai, aur select karne par us car ki details form ke fields me automatically populate hoti hain. -->

<div class="form-container">
        <h2>Manage car_information</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="car_id">Select Car to Update</label>
                <select id="car_id" name="car_id" onchange="populateForm(this.value)">
                    <option value="">Select Car</option>
                    <?php foreach ($car_information as $car) { ?>
                        <option value="<?php echo htmlspecialchars($car['id']); ?>"><?php echo htmlspecialchars($car['name']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
    <!-- Label for the Car Name input field -->
    <label for="car_name">Car Name</label>

    <!-- Input field for the car's name -->
    <!-- Pre-fills the value if $carName is set and escapes special characters -->
    <input type="text" id="car_name" name="car_name" value="<?php echo htmlspecialchars($carName); ?>">

    <!-- Display an error message for the Car Name field if validation fails -->
    <div class="error"><?php echo $carNameErr; ?></div>
</div>

            <div class="form-group">
                <label for="year">Year</label>
                <input type="text" id="year" name="year" value="<?php echo htmlspecialchars($year); ?>">
                <div class="error"><?php echo $yearErr; ?></div>
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
            <button type="submit">Save Car</button>
            <div class="error"><?php echo $generalErr; ?></div>
        </form>
    </div>

    
</body>
</html>

<?php
// Close the connection after use
$conn->close();
?>
