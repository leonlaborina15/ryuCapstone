<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

$query = "
    SELECT td.id, td.customer_name, td.test_drive_date, c.make, c.model, c.year
    FROM test_drives td
    JOIN cars c ON td.car_id = c.car_id
";
$result = $conn->query($query);

if (!$result) {
    die("Database query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Booked Test Drives</title>
</head>
<body>
    <h1>Booked Test Drives</h1>

    <table border="1">
        <tr>
            <th>Customer Name</th>
            <th>Car</th>
            <th>Test Drive Date</th>
            <th>Car Year</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                <td><?php echo htmlspecialchars($row['make'] . ' ' . $row['model']); ?></td>
                <td><?php echo htmlspecialchars($row['test_drive_date']); ?></td>
                <td><?php echo htmlspecialchars($row['year']); ?></td>
            </tr>
        <?php } ?>
    </table>

    <a href="inventory.php">Back to Inventory</a>

    <?php $conn->close(); ?>
</body>
</html>
