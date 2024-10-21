<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

if (isset($_GET['car_id'])) {
    $car_id = $_GET['car_id'];

    $stmt = $conn->prepare("DELETE FROM cars WHERE car_id = ?");
    $stmt->bind_param("i", $car_id);

    if ($stmt->execute()) {
        header("Location: inventory.php?status=success");
    } else {
        header("Location: inventory.php?status=error&message=" . urlencode($conn->error));
    }

    $stmt->close();
    exit();
} else {
    header("Location: inventory.php?status=error&message=NoCarIdProvided");
    exit();
}
?>
