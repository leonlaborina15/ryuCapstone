<?php
session_start();
require '../db_connect.php';
include 'components/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form inputs
    if (!empty($_POST['customer_name']) && !empty($_POST['message'])) {
        $customer_name = $_POST['customer_name'];
        $message = $_POST['message'];

        $customer_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("INSERT INTO customer_messages (customer_name, message, customer_id) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssi", $customer_name, $message, $customer_id);

            // Execute the query and check for errors
            if ($stmt->execute()) {
                echo "Message sent successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "Please fill in all fields.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/customer-styles/global.css">
    <link rel="stylesheet" href="../assets/global.css">
    <title>Contact Us</title>
</head>

<body>
    <?php renderHeader(); ?>
    <div class="page-title">
        <h1>Send Us a Message</h1>
    </div>
    <div class="page-actions">
        <a href="browse_cars.php">Car Listings</a>
    </div>
    <main>
        <form method="POST" action="messages.php">
            <input type="text" name="customer_name" placeholder="Your Name" required><br>
            <textarea name="message" placeholder="Your Message" required></textarea><br>
            <button type="submit">Send Message</button>
        </form>
    </main>
</body>

</html>