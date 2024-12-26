<?php
$conn = new mysqli('localhost', 'root', '', 'userinfo');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$table_sql = "CREATE TABLE IF NOT EXISTS Vehicle (
    Vehicle_id INT PRIMARY KEY AUTO_INCREMENT,
    model VARCHAR(255) NOT NULL,
    make VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL
)";
if (!$conn->query($table_sql)) {
    die('Error ensuring table existence: ' . $conn->error);
}

// Check if vehicle_id is provided in POST
if (isset($_POST['vehicle_id']) && !empty($_POST['vehicle_id'])) {
    $vehicle_id = intval($_POST['vehicle_id']);

    try {
        // Prepare the SQL query to delete the vehicle
        $stmt = $conn->prepare("DELETE FROM Vehicle WHERE Vehicle_id = ?");
        $stmt->bind_param("i", $vehicle_id);

        // Execute the query
        if ($stmt->execute()) {
            echo "Vehicle deleted successfully!";
        } else {
            echo "Error deleting vehicle: " . $stmt->error;
        }

        $stmt->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Error: Vehicle ID not provided.";
}

$conn->close();
?>