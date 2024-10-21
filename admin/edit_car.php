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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/admin-styles/inventory.css">

    <title>Edit Car</title>
</head>

<body>
    <div class="breadcrumbs">
        <a href="admin_dashboard.php">Dashboard</a> > <a href="inventory.php">Inventory</a> > <span>Edit Car</span>
    </div>
    <h1>Edit Car Details</h1>
    <div class="page-action">
        <a href="admin_dashboard.php">&larr; Dashboard</a>
        <a href="inventory.php">Inventory</a>
    </div>
    <main>

        <form method="POST" action="edit_car.php?car_id=<?php echo $car_id; ?>">
            <div class="form-group">
                <label for="make">Make</label>
                <input type="text" name="make" id="make" value="<?php echo $make; ?>" placeholder="Make" required />
            </div>
            <div class="form-group">
                <input type="text" name="model" value="<?php echo $model; ?>" placeholder="Model" required /><br>
            </div>
            <div class="form-group">
                <input type="number" name="year" value="<?php echo $year; ?>" placeholder="Year" required /><br>
            </div>
            <div class="form-group">
                <input type="number" name="price" value="<?php echo $price; ?>" placeholder="Price" required /><br>
            </div>

            <!-- Checkbox for availability -->
            <label>
                <input type="checkbox" name="availability" <?php echo $availability ? 'checked' : ''; ?> />
                Available
            </label>
            <br>

            <button type="submit">Update Car</button>
        </form>
    </main>
</body>

</html>