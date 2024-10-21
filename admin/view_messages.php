<?php
session_start();
require '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$query = "SELECT id, customer_name, message, created_at FROM customer_messages ORDER BY created_at DESC";
$result = $conn->query($query);

if (!$result) {
    die("Database query failed: " . $conn->error);
}

if (isset($_POST['reply']) && isset($_POST['message_id']) && isset($_POST['reply_message'])) {
    $message_id = $_POST['message_id'];
    $reply_message = $_POST['reply_message'];

    $stmt = $conn->prepare("INSERT INTO customer_replies (message_id, reply_message, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $message_id, $reply_message);

    if ($stmt->execute()) {
        $stmt_notify = $conn->prepare("UPDATE customer_messages SET admin_replied = 1 WHERE id = ?");
        $stmt_notify->bind_param("i", $message_id);
        $stmt_notify->execute();

        echo "<script>alert('Reply sent successfully. The customer will be notified.'); window.location.href = window.location.href;</script>";
    } else {
        echo "<script>alert('Failed to send reply.');</script>";
    }

    $stmt->close();
}

if (isset($_POST['delete']) && isset($_POST['message_id'])) {
    $message_id = $_POST['message_id'];

    $stmt = $conn->prepare("DELETE FROM customer_messages WHERE id = ?");
    $stmt->bind_param("i", $message_id);

    if ($stmt->execute()) {
        echo "<script>alert('Message deleted successfully.'); window.location.href = window.location.href;</script>";
    } else {
        echo "<script>alert('Failed to delete the message.');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/admin-styles/view-messages.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
                <th>Reply</th>
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
                    <td>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#responseModal<?php echo $row['id']; ?>">
                            Response
                        </button>

                        <form method="post" action="" style="display:inline;">
                            <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                        </form>

                        <div class="modal fade" id="responseModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="responseModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="responseModalLabel<?php echo $row['id']; ?>">Reply to <?php echo htmlspecialchars($row['customer_name']); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="">
                                            <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                                            <textarea name="reply_message" class="form-control" placeholder="Enter your reply" required></textarea>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="reply" class="btn btn-primary">Send Reply</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php $conn->close(); ?>
</body>

</html>
