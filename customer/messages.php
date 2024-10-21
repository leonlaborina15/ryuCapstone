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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <link rel="stylesheet" href="../assets/customer-styles/global.css">
    <link rel="stylesheet" href="../assets/global.css"> -->
    <title>Contact Us</title>
</head>

<body>
    <?php renderHeader(); ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="card-title">Send Us a Message</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="messages.php">
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Your Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Your Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Your Message</label>
                                <textarea class="form-control" id="message" name="message" rows="4" placeholder="Your Message" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>