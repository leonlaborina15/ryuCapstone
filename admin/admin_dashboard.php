<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/admin-styles/dashboard.css">
    <title>Admin Dashboard</title>
</head>

<body>
    <!-- TODO add bacground design -->
    <h1>Admin Dashboard</h1>
    <main>
        <div class="dashboard">
            <div class="cards-container">
                <a href="add_car.php">
                    <div class="card-header">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                            <path d='M3 9v10.4c0 .56 0 .84.109 1.054a1 1 0 0 0 .437.437C3.76 21 4.04 21 4.598 21H15m-1-8v-3m0 0V7m0 3h-3m3 0h3M7 13.8V6.2c0-1.12 0-1.68.218-2.108.192-.377.497-.682.874-.874C8.52 3 9.08 3 10.2 3h7.6c1.12 0 1.68 0 2.108.218a2 2 0 0 1 .874.874C21 4.52 21 5.08 21 6.2v7.6c0 1.12 0 1.68-.218 2.108a2.001 2.001 0 0 1-.874.874c-.428.218-.986.218-2.104.218h-7.607c-1.118 0-1.678 0-2.105-.218a2 2 0 0 1-.874-.874C7 15.48 7 14.92 7 13.8' />
                        </svg>
                        <h4 class="title">Add Car</h4>
                    </div>
                    <p class="description">
                        Add a new car to the inventory.
                    </p>
                </a>
                <!-- <a href="edit_car.php">
                    <div class="card-header">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                            <path d='M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19.5 3 21l1.5-4L16.5 3.5z' />
                        </svg>
                        <h4 class="title">Edit Car</h4>
                    </div>
                    <p class="description">
                        Edit details of an existing car.
                    </p>
                </a> -->
                <a href="inventory.php">
                    <div class="card-header">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                            <path d='M3 3h18v18H3V3zm3 3h12v12H6V6z' />
                        </svg>
                        <h4 class="title">Inventory</h4>
                    </div>
                    <p class="description">
                        Visit and manage the list of all cars in the inventory.
                    </p>
                </a>
                <a href="view_messages.php">
                    <div class="card-header">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                            <path d='M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8v.5z' />
                        </svg>
                        <h4 class="title">Messages</h4>
                    </div>
                    <p class="description">
                        View and manage messages from customers.
                    </p>
                </a>
            </div>
        </div>
    </main>
</body>

</html>