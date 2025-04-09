<?php
include '/www/wwwroot/Aether/backend/session.php';
include '/www/wwwroot/Aether/classes/db.php';

header('Content-Type: image/jpeg');

$imageUrl = 'https://bigrat.monster/media/bigrat.jpg';

try {
    $imageContent = file_get_contents($imageUrl);

    if ($imageContent === false) {
        http_response_code(500);
        echo 'Error: Unable to retrieve image.';
    } else {
        echo $imageContent;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo 'Error: ' . $e->getMessage();
}
?>
