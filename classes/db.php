<?php
// db.php

$host = getenv('DB_HOST') ?: 'monorail.proxy.rlwy.net';
$port = getenv('DB_PORT') ?: 11565;
$db   = getenv('DB_NAME') ?: 'railway';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'hWkEMFJXMZevZqSjbLzOAqufElpcQFOq';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection error: " . $e->getMessage());
}
?>
