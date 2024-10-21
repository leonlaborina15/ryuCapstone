<?php
include('db_connect.php');

$password_error = '';
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? ''; 

    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $error_message = "All fields are required.";
    } elseif (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
    } elseif ($password[0] !== strtoupper($password[0])) {
        $password_error = "Password must start with an uppercase letter.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Email is already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO Users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                $success_message = "Registration successful. You can now login.";
            } else {
                $error_message = "Error during registration.";
            }

            $stmt->close();
            $conn->close();
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

                <div class="form-group mb-3">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    <?php if (!empty($password_error)): ?>
                        <div class="alert alert-danger mt-2">
                            <?php echo $password_error; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group mb-3">
                    <label for="role">Role</label>
                    <select name="role" id="role" class="form-select">
                        <option value="" disabled selected>Select role</option>
                        <option value="customer">Customer</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <p class="link">Already have an account? <a href="login.php">Login here</a></p>
                </div>

                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success mt-3">
                        <?php echo $success_message; ?>
                    </div>
                <?php elseif (!empty($error_message)): ?>
                    <div class="alert alert-danger mt-3">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary mt-3">Register</button>
            </form>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
