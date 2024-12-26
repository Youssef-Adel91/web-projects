<?php
session_start();

// Database credentials
$servername = 'localhost';
$username = 'root';
$password = '';

// Get form data
$type = $_POST['type'];
$id = $_POST['id'];
$email = $_POST['email'];
$user_password = $_POST['password'];

// Validate input
if (empty($type) || empty($id) || empty($email) || empty($user_password)) {
    die(json_encode(["success" => false, "message" => "All fields are required."]));
}

// Create connection
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

if ($type === 'rented') { // Admin
    $dbname = 'admins';
    $conn->select_db($dbname);

    $sql = "SELECT email, password, name FROM admins WHERE id = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $id, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_email, $db_password, $name);
        $stmt->fetch();
        if (password_verify($user_password, $db_password)) {
            echo "<script>alert('Welcome, $name!'); window.location.href = 'index.html';</script>";
            exit;
        } else {
            echo json_encode(["success" => false, "message" => "Invalid password."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Admin not found."]);
    }
} else if ($type === 'available') { // Member
    $dbname = 'regular';
    $conn->select_db($dbname);

    // Ensure the 'registration' table exists
    $table_sql = "CREATE TABLE IF NOT EXISTS registration (
        id INT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        phone VARCHAR(15) NOT NULL,
        location VARCHAR(255) NOT NULL,
        gender VARCHAR(100) NOT NULL,
        rental_date DATE NOT NULL
    )";
    $conn->query($table_sql);

    // Check if member already exists
    $sql = "SELECT name FROM registration WHERE id = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $id, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($name);
        $stmt->fetch();
        echo "<script>alert('Welcome, $name!'); window.location.href = 'index.html';</script>";
        exit;
    } else {
        // Redirect to contact.html for registration
        header("Location: contact.html");
        exit;
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid user type."]);
}

$stmt->close();
$conn->close();
?>
