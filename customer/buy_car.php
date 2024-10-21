<?php
session_start();
require '../db_connect.php';
include 'components/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID
    $car_id = $_POST['car_id'];

    // Insert purchase using user_id
    $stmt = $conn->prepare("INSERT INTO purchases (user_id, car_id, purchase_date) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $car_id);

    if ($stmt->execute()) {
        // Update car availability after successful purchase
        $update_car_stmt = $conn->prepare("UPDATE cars SET availability = 0 WHERE car_id = ?");
        $update_car_stmt->bind_param("i", $car_id);

        if ($update_car_stmt->execute()) {
            echo "Car purchased successfully, and availability updated!";
        } else {
            echo "Error updating car availability: " . $conn->error;
        }

        $update_car_stmt->close();
    } else {
        echo "Error purchasing car: " . $conn->error;
    }

    $stmt->close();
}

// Get available cars for purchase
$cars = $conn->query("SELECT car_id, make, model FROM cars WHERE availability = 1");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/customer-styles/global.css">

    <title>Buy a Car</title>
</head>

<body>
    <?php renderHeader(); ?>
    <h1>Buy a Car</h1>

    <form method="POST" action="buy_car.php">
        <label for="car_id">Choose a Car:</label>
        <select name="car_id" id="car_id" required>
            <?php while ($car = $cars->fetch_assoc()) { ?>
                <option value="<?php echo $car['car_id']; ?>">
                    <?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?>
                </option>
            <?php } ?>
        </select><br>

        <button type="submit">Buy Car</button>
    </form>

    <a href="view_purchases.php">
        <button type="button">View Purchases</button>
    </a>

    <a href="browse_cars.php">Back to Car Listings</a>

    <?php $conn->close(); ?>
</body>

</html>