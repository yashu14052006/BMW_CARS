<?php
// Include the database connection file
include('../db_connect.php');

// Initialize variables
$carName = $description = $category = $imagePath = "";
$carNameErr = $descriptionErr = $categoryErr = $imageErr = $generalErr = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $carName = $_POST["car_name"];
    $description = $_POST["description"];
    $category = intval($_POST["category"]);

    // Handle image upload
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $targetDir = "../assets/images/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
        $imagePath = "" . basename($_FILES["image"]["name"]);
    }

    // Insert into database
    $sql = "INSERT INTO cars (category_id, name, images, description) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $category, $carName, $imagePath, $description);
    if ($stmt->execute()) {
        header("Location: add_car.php?success=1");
        exit();
    } else {
        $generalErr = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch categories from the database
$categories = [];
$sql = "SELECT id, name FROM categories";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Close the connection after use
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Car</title>
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
        <h2>Add New Car</h2>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="success">Car added successfully</div>
        <?php endif; ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="car_name">Car Name</label>
                <input type="text" id="car_name" name="car_name" value="<?php echo htmlspecialchars($carName); ?>">
                <div class="error"><?php echo $carNameErr; ?></div>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea>
                <div class="error"><?php echo $descriptionErr; ?></div>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="error"><?php echo $categoryErr; ?></div>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image">
                <div class="error"><?php echo $imageErr; ?></div>
            </div>
            <button type="submit">Add Car</button>
            <div class="error"><?php echo $generalErr; ?></div>
        </form>
    </div>
</body>
</html>