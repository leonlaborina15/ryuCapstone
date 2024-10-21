<?php
session_start();
require '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = $_POST['customer_name'];
    $car_id = $_POST['car_id'];

    $stmt = $conn->prepare("INSERT INTO purchases (customer_name, car_id, purchase_date) VALUES (?, ?, NOW())");
    $stmt->bind_param("si", $customer_name, $car_id);

    if ($stmt->execute()) {
        echo "Car purchased successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}

$cars = $conn->query("SELECT car_id, make, model FROM cars WHERE availability = 1");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy a Car</title>
</head>
<body>
    <h1>Buy a Car</h1>

    <form method="POST" action="buy_car.php">
        <input type="text" name="customer_name" placeholder="Your Name" required><br>

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
