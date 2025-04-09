<?php
include '/www/wwwroot/Aether/backend/session.php';
include '/www/wwwroot/Aether/classes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aether Backdoor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/public/css/dash.css">
    <meta property="og:title" content="Aether Injector">
    <meta property="og:description" content="Inject the Aether backdoor into a Minecraft plugin.">
    <meta property="og:type" content="website">
</head>
<body>
    <!-- Top Bar -->
    <header class="topbar">
        <div class="topbar-left">
            ☁️ Aether Backdoor
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar">
        <a href="/dashboard" class="menu-item"><i class="fas fa-home"></i> Home</a>
        <a href="/injector" class="menu-item active"><i class="fa-solid fa-syringe"></i> Injector</a>
        <a href="/commands" class="menu-item"><i class="fa-solid fa-file"></i> Commands</a>
        <a href="/settings" class="menu-item"><i class="fas fa-cogs"></i> Settings</a>
        <a href="/logout" class="menu-item-logout"><i class="fas fa-sign-out-alt"></i> Log Out</a>
    </nav>

    <!-- Main Content -->
    <main class="content">
        <div class="injector-container">
            <h2>Injector</h2>
            <p>Select a JAR file to inject.</p>

            <div class="file-selection">
                <input type="file" id="jarFileInput" accept=".jar" style="display: none;">
                <button id="selectFileButton" class="btn-select">Select File</button>
                <button id="injectButton" class="btn-inject" style="display: none;">Inject</button>
            </div>

            <!-- File Info -->
            <div class="file-info" id="fileInfo" style="display: none;">
                <p><strong>File:</strong> <span id="fileName"></span></p>
                <p><strong>Size:</strong> <span id="fileSize"></span></p>
            </div>
        </div>
    </main>
   <script>
        document.getElementById('selectFileButton').addEventListener('click', () => {
            document.getElementById('jarFileInput').click();
        });
        
        document.getElementById('jarFileInput').addEventListener('change', function() {
            if (this.files.length > 0) {
                const file = this.files[0];
                const fileExtension = file.name.split('.').pop().toLowerCase();
        
                // Validate file extension instead of MIME type
                if (fileExtension !== 'jar') {
                    alert('Please select a valid JAR file.');
                    return;
                }
        
                document.getElementById('fileName').innerText = file.name;
                document.getElementById('fileSize').innerText = (file.size / 1024).toFixed(2) + ' KB';
                document.getElementById('fileInfo').style.display = 'block';
                document.getElementById('injectButton').style.display = 'inline-block';
            }
        });
        
        document.getElementById('injectButton').addEventListener('click', function() {
            const fileInput = document.getElementById('jarFileInput').files[0];
            if (!fileInput) return alert('Please select a file first.');
        
            const formData = new FormData();
            formData.append('jarFile', fileInput);
        
            fetch('/injector/inject.php', {
                method: 'POST',
                body: formData
            }).then(response => {
                if (!response.ok) throw new Error('Injection failed');
                return response.blob();
            }).then(blob => {
                const downloadUrl = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = downloadUrl;
                // Corrected template string inside quotes
                a.download = `${fileInput.name.replace('.jar', '')}-injected.jar`;
                document.body.appendChild(a);
                a.click();
                a.remove();
            }).catch(error => alert('Error during injection: ' + error.message));
        });
    </script>
</body>
</html>