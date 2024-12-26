<?php
// Display incoming POST data for debugging
echo '<pre>';
print_r($_POST);
echo '</pre>';

$vehicle_id = $_POST['vehicle_id'];
$model = $_POST['model'];
$year = $_POST['year'];
$license = $_POST['license'];
$status = $_POST['status'];

// Validate license plate format
if (!preg_match('/^[A-Z0-9\-]+$/', $license)) {
    die(json_encode([
        "success" => false,
        "message" => "Invalid license plate format."
    ]));
}

$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'vehicles_db';

// Connect to MySQL server
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "message" => "Connection failed: " . $conn->connect_error
    ]));
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $database";
if (!$conn->query($sql)) {
    die(json_encode([
        "success" => false,
        "message" => "Error creating database: " . $conn->error
    ]));
}

// Select the database
$conn->select_db($database);

// Create table if it doesn't exist
$table_sql = "CREATE TABLE IF NOT EXISTS vehicles (
    vehicle_id INT PRIMARY KEY,
    model VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    license VARCHAR(20) NOT NULL UNIQUE,
    status VARCHAR(20) NOT NULL
)";
if (!$conn->query($table_sql)) {
    die(json_encode([
        "success" => false,
        "message" => "Error creating table: " . $conn->error
    ]));
}

// Prepare and bind the SQL statement
$stmt = $conn->prepare("INSERT INTO vehicles (vehicle_id, model, year, license, status) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("isiss", $vehicle_id, $model, $year, $license, $status);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Vehicle added successfully!"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $stmt->error
    ]);
}

// Close connections
$stmt->close();
$conn->close();
?>
