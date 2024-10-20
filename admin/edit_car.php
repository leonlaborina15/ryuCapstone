<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

if (isset($_GET['car_id'])) {
    $car_id = $_GET['car_id'];

    $stmt = $conn->prepare("SELECT make, model, year, price, availability FROM cars WHERE car_id = ?");
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $stmt->bind_result($make, $model, $year, $price, $availability);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price = $_POST['price'];
    $availability = isset($_POST['availability']) ? 1 : 0;  // Check if checkbox is selected

    $stmt = $conn->prepare("UPDATE cars SET make = ?, model = ?, year = ?, price = ?, availability = ? WHERE car_id = ?");
    $stmt->bind_param("ssiiii", $make, $model, $year, $price, $availability, $car_id);

    if ($stmt->execute()) {
        echo "Car details updated!";
        header("Location: inventory.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Car</title>
</head>
<body>
    <h1>Edit Car Details</h1>
    <form method="POST" action="edit_car.php?car_id=<?php echo $car_id; ?>">
        <input type="text" name="make" value="<?php echo $make; ?>" placeholder="Make" required><br>
        <input type="text" name="model" value="<?php echo $model; ?>" placeholder="Model" required><br>
        <input type="number" name="year" value="<?php echo $year; ?>" placeholder="Year" required><br>
        <input type="number" name="price" value="<?php echo $price; ?>" placeholder="Price" required><br>

        <!-- Checkbox for availability -->
        <label>
            <input type="checkbox" name="availability" <?php echo $availability ? 'checked' : ''; ?>> Available
        </label><br>

        <button type="submit">Update Car</button>
    </form>
    <a href="inventory.php">Back to Inventory</a>
</body>
</html>
