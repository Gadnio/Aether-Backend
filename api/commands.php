<?php
include '/www/wwwroot/Aether/classes/db.php';

// Set header to return JSON data
header('Content-Type: application/json');

try {
    // Fetch all commands from the database
    $stmt = $pdo->prepare("SELECT id, name, rank, aliases, description FROM commands");
    $stmt->execute();
    $commands = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the data in JSON format
    echo json_encode($commands);
} catch (PDOException $e) {
    // Return an error if there's an issue with the query
    echo json_encode(['error' => $e->getMessage()]);
}
?>
