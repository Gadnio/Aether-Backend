<?php
// Get the requested file from the URL query parameter
if (isset($_GET['file'])) {
    $file = basename($_GET['file']);
    $filePath = __DIR__ . '/files/' . $file;

    // Check if the file exists in the local /filehosting/ directory
    if (file_exists($filePath)) {
        // Set the appropriate headers for file download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Length: ' . filesize($filePath));

        // Read the file and output it to the user
        readfile($filePath);
        exit;
    } else {
        // File not found, send a 404 response
        http_response_code(404);
        echo 'File not found.';
        exit;
    }
} else {
    // No file specified, send a 400 Bad Request response
    http_response_code(400);
    echo 'No file specified.';
    exit;
}
