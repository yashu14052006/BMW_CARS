<?php
// Include the database connection file
// Yeh file database se connection banane ke liye include ki gayi hai
include('../db_connect.php');

// Initialize variables
// Car ID ko GET request se fetch kar rahe hain, agar nahi hai to 0 set karte hain
$carId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Car details ko initialize kar rahe hain, sabhi fields ko empty set kiya gaya hai
$carData = [
    'name' => '',
    'price' => '',
    'image' => '',
    'manufacturing_year' => '',
    'kilometers_done' => '',
    'transmission' => '',
    'fuel' => '',
    'registration_year' => '',
    'exterior_color' => '',
    'ground_clearance' => '',
    'boot_space' => '',
    'torque' => '',
    'power' => '',
    'engine_capacity' => '',
    'ownership_status' => '',
    'registered_state' => '',
];

// Error handling ke liye empty array
$errors = [];
// Success message initialize
$successMessage = "";

// Agar car ID provided hai, toh car ki details fetch karte hain
if ($carId > 0) {
    // SQL query to fetch car details from the database
    $sql = "SELECT * FROM car_information WHERE id = ?";
    
    // Prepared statement banate hain
    $stmt = $conn->prepare($sql);
    
    // Car ID ko query me bind karte hain
    $stmt->bind_param("i", $carId);
    
    // Query execute karte hain
    $stmt->execute();
    
    // Result ko fetch karte hain
    $result = $stmt->get_result();
    
    // Agar car milti hai, toh data fetch kar ke carData array me store karte hain
    if ($result->num_rows > 0) {
        $carData = $result->fetch_assoc();
    } else {
        // Agar car ID ke corresponding koi record nahi milta, toh error message
        die("Car with ID $carId not found.");
    }
    // Statement close karte hain
    $stmt->close();
}

// Form submission ko handle karte hain
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form data ko sanitize aur validate karte hain
    foreach ($carData as $field => &$value) {
        // Agar form me koi value di gayi hai, toh trim karte hain
        if (isset($_POST[$field])) {
            $value = trim($_POST[$field]);
            
            // Agar koi field empty hai, toh error message set karte hain
            if (empty($value)) {
                // Field ka naam ko human-readable form me convert karte hain (example: car_name -> Car name)
                $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
            }
        }
    }

    // Agar koi validation errors nahi hain, toh database update karte hain
    if (empty($errors)) {
        // SQL query to update car details in the database
        $sql = "UPDATE car_information SET 
                name=?, price=?, image=?, kilometers_done=?, 
                transmission=?, fuel=?, registration_year=?, exterior_color=?, 
                ground_clearance=?, boot_space=?, torque=?, power=?, 
                engine_capacity=?, ownership_status=? 
                WHERE id=?";
        
        // Prepared statement banate hain
        $stmt = $conn->prepare($sql);
        
        // Values ko query me bind karte hain (all fields)
        $stmt->bind_param(
            "ssssssssssssssi",  // Data types ke format
            $carData['name'],
            $carData['price'],
            $carData['image'],
            $carData['kilometers_done'],
            $carData['transmission'],
            $carData['fuel'],
            $carData['registration_year'],
            $carData['exterior_color'],
            $carData['ground_clearance'],
            $carData['boot_space'],
            $carData['torque'],
            $carData['power'],
            $carData['engine_capacity'],
            $carData['ownership_status'],
            $carId  // Car ID ko last me bind karte hain (update karne ke liye)
        );
        
        // Agar query successfully execute hoti hai
        if ($stmt->execute()) {
            // Success message set karte hain
            $successMessage = "Car details updated successfully!";
        } else {
            // Agar database update me koi error hoti hai, toh error message show karte hain
            die("Database update failed: " . $stmt->error);
        }
        
        // Statement close karte hain
        $stmt->close();
    }
}

// Database connection ko close karte hain
$conn->close();
?>


$conn->close();
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
            margin: 0;
            padding: 0;
        }

        .form-container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .success {
            color: green;
            text-align: center;
            font-size: 16px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Edit Car Information</h2>

    <!-- Display success message if it's set -->
    <?php if ($successMessage): ?>
        <div class="success"><?php echo $successMessage; ?></div>
    <?php endif; ?>

    <!-- Form for editing car information -->
    <form method="post">
        
        <!-- Loop through each field in carData to create form inputs -->
        <?php foreach ($carData as $field => $value): ?>
            <div class="form-group">
                <!-- Label for each field (e.g., Car Name, Price) -->
                <label for="<?php echo $field; ?>"><?php echo ucfirst(str_replace('_', ' ', $field)); ?></label>

                <!-- Input field for each car data (e.g., Car Name, Price) -->
                <input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" value="<?php echo htmlspecialchars($value); ?>">

                <!-- Display error message if there are errors for this field -->
                <?php if (isset($errors[$field])): ?>
                    <div class="error"><?php echo $errors[$field]; ?></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        
        <!-- Submit button to update car details -->
        <button type="submit">Update Car</button>
    </form>
</div>

</body>
</html>