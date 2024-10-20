<?php
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        echo "All fields are required.";
        exit;
    }

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
?>

<form method="POST" action="register.php">
    <label>Name: </label>
    <input type="text" name="name" required><br>

    <label>Email: </label>
    <input type="email" name="email" required><br>

    <label>Password: </label>
    <input type="password" name="password" required><br>

    <label>Role: </label>
    <select name="role">
        <option value="customer">Customer</option>
        <option value="admin">Admin</option>
    </select><br>

    <button type="submit">Register</button>
</form>
