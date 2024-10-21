<?php
include('db_connect.php'); // Include your DB connection

$password_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        echo "All fields are required.";
        exit;
    }

    if (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
    } elseif ($password[0] !== strtoupper($password[0])) {
        $password_error = "Password must start with an uppercase letter.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Email is already registered.";
            exit;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO Users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            echo "Registration successful. You can now login.";
        } else {
            echo "Error during registration.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/login-register.css">
    <title>Register</title>
</head>

<body>
    <main role="main">
        <div class="image-container">
            <img src="./assets/images/car3.jpg" alt="">
        </div>
        <div class="form-container">

            <form method="POST" action="register.php">
                <h1>Register</h1>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <?php if (!empty($password_error)): ?>
                        <p class="error"><?php echo $password_error; ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select name="role" id="role">
                        <option value="" disabled selected>Select role</option>
                        <option value="customer">Customer</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <p class="link">Already have an account? <a href="login.php">Login here</a></p>
                </div>
                <button type="submit">Register</button>
            </form>
        </div>
    </main>
</body>

</html>