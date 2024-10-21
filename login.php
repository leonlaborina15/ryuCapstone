<?php
include('db_connect.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "Both fields are required.";
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            echo "Login successful. Welcome, " . htmlspecialchars($user['name']);

            if ($user['role'] == 'admin') {
                header("Location: admin/admin_dashboard.php");
            } else {
                header("Location: customer/customer_dashboard.php");
            }
            exit;
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Invalid email or password.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/login-register.css">
    <title>Login</title>
</head>

<body>
    <main role="main">
        <div class="image-container">
            <img src="./assets/images/car3.jpg" alt="">
        </div>
        <div class="form-container">
            <form method="POST" action="login.php">
                <h1>Login</h1>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required autocomplete="off" />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="off" />
                </div>
                <div class="form-group">
                    <p class="link">Don't have an account? <a href="register.php">Register here</a></p>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </main>
</body>

</html>