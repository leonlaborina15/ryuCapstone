<?php
session_start();
require '../db_connect.php';
include 'components/header.php';

// Check if the user is logged in and is a customer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}

// Fetch customer name from users table
$user_id = $_SESSION['user_id'];
$query = "SELECT name FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($customer_name);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/customer-styles/global.css">
    <title>Customer Dashboard</title>
</head>

<body>
    <?php renderHeader($customer_name); ?>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($customer_name); ?>!</h1>

        <div class="dashboard-menu">
            <a href="customer_view_replies.php" class="dashboard-item">View Messages and Admin Replies</a>
            <a href="browse_cars.php" class="dashboard-item">Browse Cars</a>
            <a href="view_purchases.php" class="dashboard-item">View Purchases</a>
            <a href="buy_car.php" class="dashboard-item">Buy a Car</a>
            <a href="messages.php" class="dashboard-item">Send a Message</a>
        </div>

        <div class="logout-section">
            <a href="../logout.php" class="logout-button">Logout</a>
        </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>