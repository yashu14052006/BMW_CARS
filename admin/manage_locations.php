<?php
// Include the database connection file
include('../db_connect.php');

// Initialize variables for form inputs and error messages
$stateName = $cityName = $stateId = "";
$stateNameErr = $cityNameErr = $stateIdErr = $generalErr = "";

// Handle form submission for adding a state
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_state'])) {
    if (empty($_POST["state_name"])) {
        $stateNameErr = "State name is required";
    } else {
        $stateName = $_POST["state_name"];
    }

    if (empty($stateNameErr)) {
        $sql = "INSERT INTO states (state_name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $stateName);
        if ($stmt->execute()) {
            $generalErr = "State added successfully";
        } else {
            $generalErr = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Handle form submission for adding a city
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_city'])) {
    if (empty($_POST["city_name"])) {
        $cityNameErr = "City name is required";
    } else {
        $cityName = $_POST["city_name"];
    }

    if (empty($_POST["state_id"])) {
        $stateIdErr = "State is required";
    } else {
        $stateId = intval($_POST["state_id"]);
    }

    if (empty($cityNameErr) && empty($stateIdErr)) {
        $sql = "INSERT INTO cities (state_id, city_name) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $stateId, $cityName);
        if ($stmt->execute()) {
            $generalErr = "City added successfully";
        } else {
            $generalErr = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Handle state deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_state'])) {
    $stateId = intval($_POST['state_id']);
    if ($stateId > 0) {
        $sql = "DELETE FROM states WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $stateId);
        if ($stmt->execute()) {
            $generalErr = "State and its cities deleted successfully";
        } else {
            $generalErr = "Error deleting state: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $generalErr = "Invalid state ID";
    }
}

// Handle city deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_city'])) {
    $cityId = intval($_POST['city_id']);
    if ($cityId > 0) {
        $sql = "DELETE FROM cities WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $cityId);
        if ($stmt->execute()) {
            $generalErr = "City deleted successfully";
        } else {
            $generalErr = "Error deleting city: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $generalErr = "Invalid city ID";
    }
}

// Fetch all states
$states = [];
$sql = "SELECT * FROM states";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $states[] = $row;
    }
}

// Fetch all cities with their corresponding states
$cities = [];
$sql = "SELECT cities.id, cities.city_name, states.state_name FROM cities JOIN states ON cities.state_id = states.id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage States and Cities</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .form-container, .list-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .form-container h2, .list-container h2 {
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
        .form-group input, .form-group select {
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add State</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="state_name">State Name</label>
                <input type="text" id="state_name" name="state_name" value="<?php echo htmlspecialchars($stateName); ?>">
                <div class="error"><?php echo $stateNameErr; ?></div>
            </div>
            <button type="submit" name="add_state">Add State</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Add City</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="city_name">City Name</label>
                <input type="text" id="city_name" name="city_name" value="<?php echo htmlspecialchars($cityName); ?>">
                <div class="error"><?php echo $cityNameErr; ?></div>
            </div>
            <div class="form-group">
                <label for="state_id">State</label>
                <select id="state_id" name="state_id">
                    <option value="">Select State</option>
                    <?php foreach ($states as $state): ?>
                        <option value="<?php echo htmlspecialchars($state['id']); ?>"><?php echo htmlspecialchars($state['state_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="error"><?php echo $stateIdErr; ?></div>
            </div>
            <button type="submit" name="add_city">Add City</button>
        </form>
    </div>

    <div class="list-container">
        <h2>States</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>State Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($states as $state): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($state['id']); ?></td>
                        <td><?php echo htmlspecialchars($state['state_name']); ?></td>
                        <td>
                            <form method="post" onsubmit="return confirm('Are you sure you want to delete this state?');">
                                <input type="hidden" name="state_id" value="<?php echo htmlspecialchars($state['id']); ?>">
                                <button type="submit" name="delete_state">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="list-container">
        <h2>Cities</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>City Name</th>
                    <th>State Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cities as $city): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($city['id']); ?></td>
                        <td><?php echo htmlspecialchars($city['city_name']); ?></td>
                        <td><?php echo htmlspecialchars($city['state_name']); ?></td>
                        <td>
                            <form method="post" onsubmit="return confirm('Are you sure you want to delete this city?');">
                                <input type="hidden" name="city_id" value="<?php echo htmlspecialchars($city['id']); ?>">
                                <button type="submit" name="delete_city">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="error"><?php echo $generalErr; ?></div>
</body>
</html>

<?php
// Close the connection after use
$conn->close();
?>