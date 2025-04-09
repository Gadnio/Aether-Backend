<?php
include '/www/wwwroot/Aether/classes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['token'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit();
}

$mcUsername = '';
$password = '';

if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
    $data = json_decode(file_get_contents('php://input'), true);
    $mcUsername = isset($data['mcUsername']) ? trim($data['mcUsername']) : '';
    $password = isset($data['password']) ? trim($data['password']) : '';
} else {
    $mcUsername = isset($_POST['mcUsername']) ? trim($_POST['mcUsername']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
}

$userId = $_SESSION['user_id'];

if (!empty($password) && strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Password must be at least 6 characters.']);
    exit();
}

$query = "UPDATE users SET mc_username = ?";
$params = [$mcUsername];


if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query .= ", password = ?";
    $params[] = $hashedPassword;
}

$query .= " WHERE id = ?";
$params[] = $userId;

$stmt = $pdo->prepare($query);
if ($stmt->execute($params)) {
    echo json_encode(['success' => true, 'message' => 'User information updated successfully.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to update user information.']);
}
?>
