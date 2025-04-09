<?php
include '/www/wwwroot/Aether/classes/db.php';

$userId = $_GET['id'];

$query = "SELECT id, username, mc_username AS mcUsername, CONCAT(UPPER(SUBSTRING(rank, 1, 1)), LOWER(SUBSTRING(rank, 2))) AS rank FROM users WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'User not found.']);
} else {
    echo json_encode(['success' => true, 'user' => $user]);
}
?>
