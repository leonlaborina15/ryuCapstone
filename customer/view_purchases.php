<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    exit();
}

require '../db_connect.php';

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Query to select purchases for the logged-in customer using their user_id
$query = "
    SELECT p.id, p.purchase_date, c.make, c.model, c.year
    FROM purchases p
    JOIN cars c ON p.car_id = c.car_id
    WHERE p.user_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

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
    <h1>Your Bought Cars</h1>

    <table border="1">
        <tr>
            <th>Car</th>
            <th>Purchase Date</th>
            <th>Car Year</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['make'] . ' ' . $row['model']); ?></td>
                <td><?php echo htmlspecialchars($row['purchase_date']); ?></td>
                <td><?php echo htmlspecialchars($row['year']); ?></td>
            </tr>
        <?php } ?>
    </table>

    <a href="browse_cars.php">Back to Browsing</a>

    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>
