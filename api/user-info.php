<?php
include '/www/wwwroot/Aether/classes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['token'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit();
}

$userId = $_SESSION['user_id'];

// Debug: Check if the user ID is correct
error_log("User ID: " . $userId);

$query = "SELECT id, username, mc_username AS mcUsername, CONCAT(UPPER(SUBSTRING(rank, 1, 1)), LOWER(SUBSTRING(rank, 2))) AS rank, loginkey, password FROM users WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Debug: Check if the query fetched a valid user
if (!$user) {
    error_log("No user found with ID: " . $userId);
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'User not found.']);
    exit();
}

// Mask the password for security reasons
$user['password'] = '********';

// Debug: Log the user object being returned
error_log(print_r($user, true));

echo json_encode(['success' => true, 'user' => $user]);

?>
