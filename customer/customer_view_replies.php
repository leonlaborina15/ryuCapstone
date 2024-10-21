<?php
session_start();
require '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT cm.message, cm.created_at AS message_date, cr.reply_message, cr.created_at AS reply_date
          FROM customer_messages cm
          LEFT JOIN customer_replies cr ON cm.id = cr.message_id
          WHERE cm.customer_id = ? AND cr.reply_message IS NOT NULL
          ORDER BY cm.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/customer-styles/view-replies.css">
    <title>View Replies</title>
</head>

<body>
    <h1>Your Messages and Admin Replies</h1>

    <div class="page-action">
        <a href="customer_dashboard.php">&larr; Dashboard</a>
    </div>

    <table border="1">
        <thead>
            <tr>
                <th>Your Message</th>
                <th>Submitted On</th>
                <th>Admin Reply</th>
                <th>Reply Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $messageDate = new DateTime($row['message_date']);
                    $formattedMessageDate = $messageDate->format('F j, Y, g:i a');

                    $replyDate = new DateTime($row['reply_date']);
                    $formattedReplyDate = $replyDate->format('F j, Y, g:i a');
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                    <td><?php echo htmlspecialchars($formattedMessageDate); ?></td>
                    <td><?php echo htmlspecialchars($row['reply_message']); ?></td>
                    <td><?php echo htmlspecialchars($formattedReplyDate); ?></td>
                </tr>
            <?php }
            } else { ?>
                <tr>
                    <td colspan="4">No messages with replies found.</td>
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
