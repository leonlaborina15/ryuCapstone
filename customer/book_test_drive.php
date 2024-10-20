<?php
session_start();
require '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = $_POST['customer_name'];
    $car_id = $_POST['car_id'];
    $test_drive_date = $_POST['test_drive_date'];

    $stmt = $conn->prepare("INSERT INTO test_drives (customer_name, car_id, test_drive_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $customer_name, $car_id, $test_drive_date);

    if ($stmt->execute()) {
        echo "Test drive booked successfully!";
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
    <title>Book Test Drive</title>
</head>
<body>
    <h1>Book a Test Drive</h1>

    <form method="POST" action="book_test_drive.php">
        <input type="text" name="customer_name" placeholder="Your Name" required><br>

        <label for="car_id">Choose a Car:</label>
        <select name="car_id" id="car_id" required>
            <?php while ($car = $cars->fetch_assoc()) { ?>
                <option value="<?php echo $car['car_id']; ?>">
                    <?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?>
                </option>
            <?php } ?>
        </select><br>

        <label for="test_drive_date">Choose a Date:</label>
        <input type="date" name="test_drive_date" id="test_drive_date" required><br>

        <button type="submit">Book Test Drive</button>
    </form>

    <a href="view_booked_test_drives.php">
        <button type="button">View Bookings</button>
    </a>

    <a href="browse_cars.php">Back to Car Listings</a>

    <?php $conn->close(); ?>
</body>
</html>
