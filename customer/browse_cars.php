<?php
session_start();
require '../db_connect.php';

if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    $search_make = isset($_GET['make']) ? $_GET['make'] : '';
    $search_model = isset($_GET['model']) ? $_GET['model'] : '';
    $search_min_price = isset($_GET['min_price']) ? $_GET['min_price'] : 0;
    $search_max_price = isset($_GET['max_price']) ? $_GET['max_price'] : 1000000;

    $sql = "SELECT * FROM cars WHERE availability = 1";
    $params = [];
    $types = '';

    if (!empty($search_make)) {
        $sql .= " AND make LIKE ?";
        $params[] = '%' . $search_make . '%';
        $types .= 's';
    }
    if (!empty($search_model)) {
        $sql .= " AND model LIKE ?";
        $params[] = '%' . $search_model . '%';
        $types .= 's';
    }
    if (!empty($search_min_price) && !empty($search_max_price)) {
        $sql .= " AND price BETWEEN ? AND ?";
        $params[] = (int)$search_min_price;
        $params[] = (int)$search_max_price;
        $types .= 'ii';
    }

    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . htmlspecialchars($row['make']) . "</td>
                <td>" . htmlspecialchars($row['model']) . "</td>
                <td>" . htmlspecialchars($row['year']) . "</td>
                <td>" . htmlspecialchars($row['price']) . "</td>
                <td>" . ($row['availability'] ? 'Available' : 'Not Available') . "</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No cars found matching your criteria.</td></tr>";
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Cars</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        $(document).ready(function() {
            function searchCars() {
                $('#loading').show();
                $.ajax({
                    url: 'browse_cars.php',
                    method: 'GET',
                    data: $('#searchForm').serialize() + '&ajax=1',
                    success: function(response) {
                        $('#carResults tbody').html(response);
                        $('#loading').hide();
                    },
                    error: function() {
                        $('#carResults tbody').html('<tr><td colspan="5">An error occurred. Please try again later.</td></tr>');
                        $('#loading').hide();
                    }
                });
            }

            $('#make, #model, #min_price, #max_price').on('input', function() {
                searchCars();
            });

            $('#clearFilters').on('click', function() {
                $('#searchForm')[0].reset();
                searchCars();
            });

            searchCars();
        });
    </script>
</head>
<body>
    <h1>Available Cars</h1>

    <form id="searchForm" method="GET">
        <label for="make">Make:</label>
        <input type="text" id="make" name="make"><br>

        <label for="model">Model:</label>
        <input type="text" id="model" name="model"><br>

        <label for="min_price">Min Price:</label>
        <input type="number" id="min_price" name="min_price" value="0"><br>

        <label for="max_price">Max Price:</label>
        <input type="number" id="max_price" name="max_price" value="1000000"><br>

        <button type="button" id="clearFilters">Clear Filters</button>
    </form>

    <div id="loading">Loading...</div>

    <div id="carResults">
        <table>
            <thead>
                <tr>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Price</th>
                    <th>Availability</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <a href="buy_car.php">Buy a Car now</a>
</body>
</html>
