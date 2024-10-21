<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price = $_POST['price'];
    $availability = isset($_POST['availability']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO cars (make, model, year, price, availability) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiid", $make, $model, $year, $price, $availability);

    if ($stmt->execute()) {
        $message = "Car added successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/admin-styles/inventory.css">
    <title>Add Car</title>
</head>

<body>
    <div class="breadcrumbs">
        <a href="admin_dashboard.php">Dashboard</a> > <a href="inventory.php">Inventory</a> > <span>Add Car</span>
    </div>
    <h1>Add Car</h1>
    <div class="page-action">
        <a href="admin_dashboard.php">&larr; Dashboard</a>
        <a href="inventory.php">Inventory</a>
    </div>
    <main>
        <form action="add_car.php" method="post">
            <?php if ($message): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>
            <div class="form-group" title="Brand of the car">
                <label for="make">Make</label>
                <input type="text" id="make" name="make" required>
            </div>
            <div class="form-group">
                <label for="model">Model</label>
                <input type="text" id="model" name="model" required>
            </div>
            <div class="form-group">
                <label for="year">Year</label>
                <input type="number" id="year" name="year" required>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" required>
            </div>
            <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                <label for="availability">
                    <input type="checkbox" id="availability" name="availability">
                    Availability
                </label>
            </div>
            <button type="submit">Add Car</button>
        </form>
    </main>

    <?php
    $conn->close();
    ?>
</body>

</html>