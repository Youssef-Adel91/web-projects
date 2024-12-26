<?php
$conn = new mysqli('localhost', 'root', '', 'regular');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$table_sql = "CREATE TABLE IF NOT EXISTS registration (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    location VARCHAR(255) NOT NULL,
    gender VARCHAR(100) NOT NULL,
    date DATE NOT NULL
)";
if (!$conn->query($table_sql)) {
    die('Error ensuring table existence: ' . $conn->error);
}

// Check if customer ID is provided in POST
if (isset($_POST['id']) && !empty($_POST['id'])) {
    $id = intval($_POST['id']);

    try {
        // Prepare the SQL query to delete the customer
        $stmt = $conn->prepare("DELETE FROM registration WHERE id = ?");
        $stmt->bind_param("i", $id);

        // Execute the query
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Customer deleted successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error deleting customer: " . $stmt->error]);
        }

        // Close the statement
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Error: Customer ID not provided."]);
}

// Close the database connection
$conn->close();
?>