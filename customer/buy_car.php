<?php
session_start();
require '../db_connect.php';
include 'components/header.php';

$modalMessage = '';
$modalType = '';

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
            $modalMessage = "Car purchased successfully, and availability updated!";
            $modalType = 'success';
        } else {
            $modalMessage = "Error updating car availability: " . $conn->error;
            $modalType = 'error';
        }

        $update_car_stmt->close();
    } else {
        $modalMessage = "Error purchasing car: " . $conn->error;
        $modalType = 'error';
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Buy a Car</title>
</head>

<body>
    <?php renderHeader(); ?>
    <main class="d-flex flex-column align-items-center justify-content-center mt-5">
        <form class="card shadow-sm w-50" method="POST" action="buy_car.php">
            <div class="card-header">
                <h4 class="card-title">Buy a Car</h4>
            </div>
            <div class="card-body mb-3">
                <label for="car_id" class="form-label">Choose a Car:</label>
                <select class="form-select" name="car_id" id="car_id" required>
                    <option value="" selected disabled>Select a car to purchase</option>
                    <?php while ($car = $cars->fetch_assoc()) { ?>
                        <option value="<?php echo $car['car_id']; ?>">
                            <?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?>
                        </option>
                    <?php } ?>
                </select>
                <button type="submit" class="btn btn-primary w-50 mt-3">Submit</button>
            </div>
        </form>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="responseModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="responseModalLabel"><?php echo ucfirst($modalType); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo $modalMessage; ?>
                </div>
                <div class="modal-footer">
                    <a href="view_purchases.php" class="btn btn-primary">View Purchases</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        <?php if ($modalMessage) { ?>
            $(document).ready(function() {
                $('#responseModal').modal('show');
            });
        <?php } ?>
    </script>
    <?php $conn->close(); ?>
</body>

</html>