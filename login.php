    <?php
    include('db_connect.php');
    session_start();

    $login_success = false;
    $error_message = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error_message = "Both fields are required.";
        } else {
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

                    $login_success = true;
                    $redirect_url = ($user['role'] == 'admin') ? 'admin/admin_dashboard.php' : 'customer/customer_dashboard.php';
                } else {
                    $error_message = "Invalid email or password.";
                }
            } else {
                $error_message = "Invalid email or password.";
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required autocomplete="off" />
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required autocomplete="off" />
                    </div>


                    <?php if ($login_success): ?>
                        <div class="alert alert-success mt-3" role="alert">
                            Login successful. Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!
                        </div>
                        <script>
                            setTimeout(function() {
                                window.location.href = "<?php echo $redirect_url; ?>";
                            }, 2000);
                        </script>
                    <?php elseif (!empty($error_message)): ?>
                        <div class="alert alert-danger mt-3" role="alert">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <p class="link">Don't have an account? <a href="register.php">Register here</a></p>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Login</button>
                </form>
            </div>
        </main>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    </body>

    </html>
