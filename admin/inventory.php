<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

$result = $conn->query("SELECT * FROM cars");

if (!$result) {
    die("Database query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Inventory</title>
</head>
<body>
    <h1>Car Inventory</h1>
    <a href="add_car.php">Add New Car</a>
    <table border="1">
        <tr>
            <th>Make</th>
            <th>Model</th>
            <th>Year</th>
            <th>Price</th>
            <th>Availability</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['make']); ?></td>
                <td><?php echo htmlspecialchars($row['model']); ?></td>
                <td><?php echo htmlspecialchars($row['year']); ?></td>
                <td><?php echo htmlspecialchars($row['price']); ?></td>
                <td><?php echo $row['availability'] ? 'Available' : 'Not Available'; ?></td>
                <td>
                    <a href="edit_car.php?car_id=<?php echo $row['car_id']; ?>">Edit</a> |
                    <a href="delete_car.php?car_id=<?php echo $row['car_id']; ?>" onclick="return confirm('Are you sure you want to delete this car?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <?php
    $conn->close();
    ?>
</body>
</html>
