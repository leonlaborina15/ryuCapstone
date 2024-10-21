<?php
session_start();
require '../db_connect.php';
include 'components/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = $_POST['customer_name'];
    $message = $_POST['message'];

    // Assuming customer_id is stored in the session after login
    $customer_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO customer_messages (customer_name, message, customer_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $customer_name, $message, $customer_id);

    if ($stmt->execute()) {
        echo "Message sent successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/customer-styles/global.css">
    <title>Contact Us</title>
</head>
<body>
    <?php renderHeader(); ?>
    <h1>Send Us a Message</h1>
    <form method="POST" action="messages.php">
        <input type="text" name="customer_name" placeholder="Your Name" required><br>
        <textarea name="message" placeholder="Your Message" required></textarea><br>
        <button type="submit">Send Message</button>
    </form>
    <a href="browse_cars.php">Back to Car Listings</a>
</body>
</html>
