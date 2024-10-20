<?php
session_start();
require '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = $_POST['customer_name'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO customer_messages (customer_name, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $customer_name, $message);

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
    <title>Contact Us</title>
</head>
<body>
    <h1>Send Us a Message</h1>
    <form method="POST" action="messages.php">
        <input type="text" name="customer_name" placeholder="Your Name" required><br>
        <textarea name="message" placeholder="Your Message" required></textarea><br>

        <button type="submit">Send Message</button>
    </form>

    <a href="browse_cars.php">Back to Car Listings</a>

    <?php $conn->close(); ?>
</body>
</html>
