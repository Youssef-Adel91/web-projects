<?php
session_start();

$access_key = $_POST['access_key'] ?? null;
$id = $_POST['id'] ?? null;
$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;
$phone = $_POST['phone'] ?? null;
$location = $_POST['location'] ?? null;
$gender = $_POST['gender'] ?? null;
$date = $_POST['date'] ?? null;

if (!preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $access_key)) {
    die(json_encode([
        "success" => false,
        "message" => "Invalid access key format. Must be a valid UUID."
    ]));
}

// Validate required fields
if (empty($id) || empty($name) || empty($email) || empty($phone) || empty($location) || empty($gender) || empty($date)) {
    die(json_encode([
        "success" => false,
        "message" => "All fields are required."
    ]));
}

// Database credentials
$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'regular';

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "message" => "Connection failed: " . $conn->connect_error
    ]));
}

$table_sql = "CREATE TABLE IF NOT EXISTS registration (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    location VARCHAR(255) NOT NULL,
    gender VARCHAR(100) NOT NULL,
    date DATE NOT NULL
)";
if (!$conn->query($table_sql)) {
    die(json_encode([
        "success" => false,
        "message" => "Error ensuring table existence: " . $conn->error
    ]));
}

// Ensure all columns exist
//Iterates through required columns and ensures they exist.
//Adds any missing columns dynamically.
//Stops execution if adding a column fails.
$columns = ['id', 'name', 'email', 'password', 'phone', 'location', 'gender', 'date'];
foreach ($columns as $column) {
    $column_check = $conn->query("SHOW COLUMNS FROM registration LIKE '$column'");
    if ($column_check->num_rows === 0) {
        $alter_sql = "ALTER TABLE registration ADD COLUMN $column VARCHAR(255)";
        if (!$conn->query($alter_sql)) {
            die(json_encode([
                "success" => false,
                "message" => "Error adding missing column '$column': " . $conn->error
            ]));
        }
    }
}

// Step 1: Check if user exists by ID
$check_stmt = $conn->prepare("SELECT * FROM registration WHERE id = ?");
$check_stmt->bind_param("i", $id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    // User ID does not exist
    die(json_encode([
        "success" => false,
        "message" => "No user found with ID: $id"
    ]));
}
$check_stmt->close();

// Step 2: Update user data
$stmt = $conn->prepare("UPDATE registration SET name = ?, email = ?, phone = ?, location = ?, gender = ?, date = ? WHERE id = ?");
$stmt->bind_param("ssssssi", $name, $email, $phone, $location, $gender, $date, $id);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Customer details successfully updated!"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $stmt->error
    ]);
}

// Close statement and connection
$stmt->close();
$conn->close();
?>