<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

$query = "
    SELECT p.id, p.purchase_date, p.customer_name, p.car_id, c.make, c.model, c.year
    FROM purchases p
    JOIN cars c ON p.car_id = c.car_id
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
    <title>View Bought Cars</title>
</head>
<body>
    <h1>Bought Cars</h1>

    <table border="1">
        <tr>
            <th>Customer Name</th>
            <th>Car</th>
            <th>Purchase Date</th>
            <th>Car Year</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                <td><?php echo htmlspecialchars($row['make'] . ' ' . $row['model']); ?></td>
                <td><?php echo htmlspecialchars($row['purchase_date']); ?></td>
                <td><?php echo htmlspecialchars($row['year']); ?></td>
            </tr>
        <?php } ?>
    </table>

    <a href="browse_cars.php">Back to Browsing</a>

    <?php $conn->close(); ?>
</body>
</html>
