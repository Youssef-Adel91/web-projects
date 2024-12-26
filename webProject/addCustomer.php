<?php
echo '<pre>';
print_r($_POST);
echo '</pre>';

$access_key = $_POST['access_key']; 
$id = intval(value: $_POST['id']);
$name = htmlspecialchars($_POST['name']);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password
$phone = htmlspecialchars($_POST['phone']);
$location = htmlspecialchars($_POST['location']);
$gender = htmlspecialchars($_POST['gender']);
$date = htmlspecialchars($_POST['date']);

if (!preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $access_key)) {
    die(json_encode([
        "success" => false,
        "message" => "Invalid access key format. Must be a valid UUID."
    ]));
}

$servername = 'localhost';
$username = 'root';
$db_password = ''; 
$database = 'regular';

$conn = new mysqli($servername, $username, $db_password);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $database";
if (!$conn->query($sql)) {
    die("Error creating database: " . $conn->error);
}

$conn->select_db($database);

$table_sql = "CREATE TABLE IF NOT EXISTS registration (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    location VARCHAR(255) NOT NULL,
    gender VARCHAR(100) NOT NULL,
    rental_date DATE NOT NULL
)";
if (!$conn->query($table_sql)) {
    die("Error creating table: " . $conn->error);
}

$stmt = $conn->prepare("INSERT INTO registration (id, name, email, password, phone, location, gender, rental_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssssss", $id, $name, $email, $password, $phone, $location, $gender, $date);

if ($stmt->execute()) {
    echo "<script>alert('Welcome, $name!'); window.location.href = 'index.html';</script>";
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
