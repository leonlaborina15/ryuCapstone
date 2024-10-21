<?php
session_start();
require '../db_connect.php';
include 'components/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Initialize messages
$message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form inputs
    if (!empty($_POST['customer_name']) && !empty($_POST['message'])) {
        $customer_name = $_POST['customer_name'];
        $message_content = $_POST['message']; // Changed variable name to avoid conflict

        $customer_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("INSERT INTO customer_messages (customer_name, message, customer_id) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssi", $customer_name, $message_content, $customer_id);

            // Execute the query and check for errors
            if ($stmt->execute()) {
                $message = "Message sent successfully!";
            } else {
                $error_message = "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error_message = "Error preparing statement: " . $conn->error;
        }
    } else {
        $error_message = "Please fill in all fields.";
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
                        <!-- Success and Error Alerts -->
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $message; ?>
                            </div>
                        <?php elseif (!empty($error_message)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>

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
