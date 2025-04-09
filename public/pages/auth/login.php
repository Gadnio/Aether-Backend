<?php
include '/www/wwwroot/Aether/classes/db.php';

ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

$loginSuccess = "";
$loginError = "";

if (isset($_SESSION['user_id']) && isset($_SESSION['token'])) {
    header("Location: /dashboard");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate form inputs
    if (empty($username) || empty($password)) {
        $loginError = "Please fill in both fields.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['token'] = $user['token'];

            $loginSuccess = "Login successful. Redirecting to dashboard...";
            header("Refresh: 1; URL=/dashboard"); // Redirect after 1 second
        } else {
            $loginError = "Invalid credentials. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/public/css/auth.css">
    <script>
        // Request Notification permission
        document.addEventListener('DOMContentLoaded', function() {
            if (Notification.permission !== 'granted') {
                Notification.requestPermission();
            }
        });

        // Function to show browser notification
        function showNotification(title, message) {
            if (Notification.permission === 'granted') {
                const options = {
                    body: message,
                    icon: '/path-to-icon/icon.png' // Optional icon path
                };
                new Notification(title, options);
            }
        }

        // Trigger notifications for success and error
        <?php if (!empty($loginSuccess)): ?>
            showNotification('Login Success', '<?= $loginSuccess ?>');
        <?php endif; ?>

        <?php if (!empty($loginError)): ?>
            showNotification('Login Error', '<?= $loginError ?>');
        <?php endif; ?>
    </script>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form action="/login" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">Login</button>
            <p class="switch">Don't have an account? <a href="register">Register here</a></p>
        </form>
    </div>
</body>
</html>
