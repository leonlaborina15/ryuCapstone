<?php
session_start();
require '../db_connect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch all purchases along with customer names and car details
$query = "
    SELECT p.id, p.purchase_date, p.customer_name, p.car_id, c.make, c.model, c.year
    FROM purchases p
    JOIN cars c ON p.car_id = c.car_id
    ORDER BY p.purchase_date DESC
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
    <link rel="stylesheet" href="../assets/admin-styles/inventory.css">
    <title>All Transactions - Admin Dashboard</title>
</head>

<body>

    <div class="breadcrumbs">
        <a href="admin_dashboard.php">Dashboard</a> > <span>Purchases</span>
    </div>
    <h1>All Car Purchases</h1>
    <div class="page-action">
        <a href="admin_dashboard.php">&larr; Dashboard</a>
    </div>
    <main>
        <table border="1">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Car</th>
                    <th>Car Year</th>
                    <th>Purchase Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['make'] . ' ' . $row['model']); ?></td>
                            <td><?php echo htmlspecialchars($row['year']); ?></td>
                            <td><?php echo htmlspecialchars($row['purchase_date']); ?></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="4">No purchases found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>

    <?php
    $result->free();
    $conn->close();
    ?>
</body>

</html>