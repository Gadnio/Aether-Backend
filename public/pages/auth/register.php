<?php
include '/www/wwwroot/Aether/classes/db.php';

session_start();

$signupSuccess = "";
$signupError = "";

// If the user is already logged in, redirect to the dashboard
if (isset($_SESSION['user_id']) && isset($_SESSION['token'])) {
    header("Location: /dashboard");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;

    // Validate inputs
    if (empty($username) || empty($password)) {
        $signupError = "Please fill in all required fields.";
    } elseif (strlen($password) < 6) {
        $signupError = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm_password) {
        $signupError = "Passwords do not match.";
    } else {
        // Check if the username already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $signupError = "Username already exists. Please choose a different one.";
        } else {
            // Insert new user into the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            do {
                $token = bin2hex(random_bytes(16));
                $stmt = $pdo->prepare("SELECT * FROM users WHERE token = ?");
                $stmt->execute([$token]);
            } while ($stmt->rowCount() > 0);

            do {
                $loginkey = mt_rand(100000, 999999);
                $stmt = $pdo->prepare("SELECT * FROM users WHERE loginkey = ?");
                $stmt->execute([$loginkey]);
            } while ($stmt->rowCount() > 0);

            // Insert user data
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email, loginkey, token) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $hashedPassword, $email, $loginkey, $token]);

            $signupSuccess = "Signup successful! You can now log in.";
            header("Refresh: 2; URL=/login");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        <?php if (!empty($signupSuccess)): ?>
            showNotification('Signup Success', '<?= $signupSuccess ?>');
        <?php endif; ?>

        <?php if (!empty($signupError)): ?>
            showNotification('Signup Error', '<?= $signupError ?>');
        <?php endif; ?>
    </script>
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <form action="/register" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($username ?? '') ?>" required>
            </div>
            <div class="input-group">
                <label for="email">Email (optional)</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn">Register</button>
            <p class="switch">Already have an account? <a href="/login">Login here</a></p>
        </form>
    </div>
</body>
</html>
