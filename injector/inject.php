<?php
// Define directories
$uploadDir = '/www/wwwroot/Aether/injector/uploads/';
$injectedDir = '/www/wwwroot/Aether/injector/injected/';
$injectorJarPath = '/www/wwwroot/Aether/injector/Injector.jar';
$logPath = '/www/wwwroot/Aether/injector/injector_log.txt';
$normPath = '/www/wwwroot/Aether/injector/';
$commonPluginsPath = $normPath . 'commonplugins.txt';

// Ensure directories and commonplugins.txt exist
if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
if (!file_exists($injectedDir)) mkdir($injectedDir, 0777, true);
if (!file_exists($commonPluginsPath)) file_put_contents($commonPluginsPath, '');
if (!file_exists($logPath)) file_put_contents($logPath, '');

// Handle the uploaded JAR file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['jarFile'])) {
    $uploadedFile = $_FILES['jarFile'];

    // Ensure the file is a JAR file by checking the extension
    $fileExtension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
    if (strtolower($fileExtension) !== 'jar') {
        http_response_code(400);
        echo "Only JAR files are allowed.";
        exit;
    }

    // Move uploaded file to the uploads directory
    $filePath = $uploadDir . basename($uploadedFile['name']);
    if (!move_uploaded_file($uploadedFile['tmp_name'], $filePath)) {
        http_response_code(500);
        echo "Failed to upload file.";
        exit;
    }

    // Define the output path for the injected file
    $outputFilePath = $injectedDir . pathinfo($uploadedFile['name'], PATHINFO_FILENAME) . '-injected.jar';

    // Run the injection command
    $command = "java -jar \"$injectorJarPath\" -i \"$filePath\" -o \"$outputFilePath\"";
    exec($command, $output, $resultCode);

    // Log command output for debugging
    file_put_contents($logPath, "Command: $command\nResult Code: $resultCode\nOutput: " . implode("\n", $output) . "\n", FILE_APPEND);

    // Check if injection was successful
    if ($resultCode !== 0 || !file_exists($outputFilePath)) {
        http_response_code(500);
        echo "Injection failed.";
        exit;
    }

    // Serve the injected file for download
    header('Content-Type: application/java-archive');
    header('Content-Disposition: attachment; filename="' . basename($outputFilePath) . '"');
    header('Content-Length: ' . filesize($outputFilePath));
    readfile($outputFilePath);

    // Cleanup only common plugins
    $commonPlugins = file($commonPluginsPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Check if the uploaded file contains any word from commonplugins.txt
    foreach ($commonPlugins as $commonPlugin) {
        if (stripos(pathinfo($uploadedFile['name'], PATHINFO_FILENAME), trim($commonPlugin)) !== false) {
            unlink($filePath); // Delete the uploaded file if it contains the common plugin name
            break; // If one match is found, no need to check further
        }
    }

    // Optional: Clean up all files in the uploads directory that match any word in commonplugins.txt
    foreach (glob($uploadDir . '*') as $file) {
        $filenameWithoutExtension = pathinfo($file, PATHINFO_FILENAME);
        foreach ($commonPlugins as $commonPlugin) {
            if (stripos($filenameWithoutExtension, trim($commonPlugin)) !== false) {
                unlink($file); // Delete the file if it contains the common plugin name
                break; // If one match is found, no need to check further
            }
        }
    }

    exit;
} else {
    http_response_code(400);
    echo "No file uploaded.";
}
?>
