<?php
session_start();
include 'components/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    exit();
}

require '../db_connect.php';

<<<<<<< HEAD
$user_id = $_SESSION['user_id'];  
=======
$user_id = $_SESSION['user_id'];
>>>>>>> origin/ced

$query = "
    SELECT p.id, p.purchase_date, c.make, c.model, c.year
    FROM purchases p
    JOIN cars c ON p.car_id = c.car_id
    WHERE p.user_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Database query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/customer-styles/global.css">
    <link rel="stylesheet" href="../assets/table.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <title>View Bought Cars</title>
</head>

<body>
    <?php renderHeader(); ?>
    <div class="page-title">
        <h1>Your Bought Cars</h1>
    </div>

    <main>
        <div class="container-xl p-2 pb-4 h-100 w-75 shadow-sm rounded-4 border">
            <table>
                <thead>
                    <tr>
                        <th>Car</th>
                        <th>Purchase Date</th>
                        <th class="text-center">Car Year</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr class="border-bottom">
                            <td><?php echo htmlspecialchars($row['make'] . ' ' . $row['model']); ?></td>
                            <td><?php echo htmlspecialchars($row['purchase_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['year']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>

</html>