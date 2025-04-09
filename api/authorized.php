<?php
include '/www/wwwroot/Aether/classes/db.php';

$mcUsername = $_GET['mcusername'];

$query = "SELECT COUNT(*) AS count FROM users WHERE mc_username = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$mcUsername]);
$count = $stmt->fetchColumn();

echo $count > 0 ? 'true' : 'false';
?>
