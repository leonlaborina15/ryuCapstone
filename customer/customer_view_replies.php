<?php
session_start();
require '../db_connect.php';
include 'components/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to fetch customer messages, customer replies, and admin replies
$query = "SELECT cm.message, cm.created_at AS message_date,
                 cr.reply_message AS customer_reply, cr.created_at AS customer_reply_date,
                 ar.reply_message AS admin_reply, ar.created_at AS admin_reply_date
          FROM customer_messages cm
          LEFT JOIN customer_replies cr ON cm.id = cr.message_id
          LEFT JOIN admin_replies ar ON cm.id = ar.message_id
          WHERE cm.customer_id = ?
          ORDER BY cm.created_at DESC";

$stmt = $conn->prepare($query);

if ($stmt === false) {
    die('Error preparing the SQL query: ' . $conn->error);
}

$stmt->bind_param("i", $user_id);

if (!$stmt->execute()) {
    die('Error executing the SQL query: ' . $stmt->error);
}

$result = $stmt->get_result();

if (!$result) {
    die('Error fetching the results: ' . $stmt->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/customer-styles/global.css">
    <title>View Replies</title>
</head>

<body>
    <?php renderHeader(); ?>
    <h1>Your Messages and Admin Replies</h1>

    <div class="page-action">
        <a href="customer_dashboard.php">&larr; Dashboard</a>
    </div>

    <table border="1">
        <thead>
            <tr>
                <th>Your Message</th>
                <th>Submitted On</th>
                <th>Your Reply</th>
                <th>Reply Date</th>
                <th>Admin Reply</th>
                <th>Admin Reply Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $messageDate = new DateTime($row['message_date']);
                    $formattedMessageDate = $messageDate->format('F j, Y, g:i a');

                    $customerReplyDate = isset($row['customer_reply_date']) ? (new DateTime($row['customer_reply_date']))->format('F j, Y, g:i a') : "N/A";

                    $adminReplyDate = isset($row['admin_reply_date']) ? (new DateTime($row['admin_reply_date']))->format('F j, Y, g:i a') : "N/A";
            ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                        <td><?php echo htmlspecialchars($formattedMessageDate); ?></td>
                        <td><?php echo htmlspecialchars($row['customer_reply'] ?? 'No reply yet'); ?></td>
                        <td><?php echo htmlspecialchars($customerReplyDate); ?></td>
                        <td><?php echo htmlspecialchars($row['admin_reply'] ?? 'No reply yet'); ?></td>
                        <td><?php echo htmlspecialchars($adminReplyDate); ?></td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="6">No messages with replies found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>

</html>