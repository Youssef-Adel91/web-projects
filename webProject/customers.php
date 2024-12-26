<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'userinfo2');

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer List</title>
</head>
<body>
    <h1>Customer List</h1>
    <table border="1">
        <tr>
            <th>Customer ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>

        <?php
        // Query to fetch all customers from the database
        $query = "SELECT * FROM customer";
        $result = $conn->query($query);

        // Loop through each row and display the customer data
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['customer_id']}</td>
                        <td>{$row['Cus-name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phonenumber']}</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No customers found</td></tr>";
        }

        // Free result set
        $result->free();
        ?>

    </table>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
