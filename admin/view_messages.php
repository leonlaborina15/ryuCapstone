<?php
session_start();
require '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$query = "SELECT customer_name, message, created_at FROM customer_messages ORDER BY created_at DESC";
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
    <link rel="stylesheet" href="../assets/admin-styles/view-messages.css">
    <title>View Messages</title>
</head>

<body>
    <h1>Customer Messages</h1>

    <div class="page-action">
        <a href="admin_dashboard.php">&larr; Dashboard</a>
    </div>

    <table border="1">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Message</th>
                <th>Submitted On</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) {
                $date = new DateTime($row['created_at']);
                $formattedDate = $date->format('F j, Y, g:i a');
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                    <td><?php echo htmlspecialchars($formattedDate); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php $conn->close(); ?>
</body>

</html>