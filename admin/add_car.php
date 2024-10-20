<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price = $_POST['price'];
    $availability = isset($_POST['availability']) ? 1 : 0; 

    $stmt = $conn->prepare("INSERT INTO cars (make, model, year, price, availability) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiid", $make, $model, $year, $price, $availability);

    if ($stmt->execute()) {
        echo "Car added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Car</title>
</head>
<body>
    <h1>Add New Car</h1>
    <form method="POST" action="add_car.php">
        <input type="text" name="make" placeholder="Make" required><br>
        <input type="text" name="model" placeholder="Model" required><br>
        <input type="number" name="year" placeholder="Year" required><br>
        <input type="number" name="price" placeholder="Price" required><br>

        <label>
            <input type="checkbox" name="availability" checked> Available
        </label><br>

        <button type="submit">Add Car</button>
    </form>
    <a href="inventory.php">Back to Inventory</a>
</body>
</html>
