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

    <meta property="og:title" content="Aether Dashboard">
    <meta property="og:description" content="The dashboard for the Aether Backdoor. Who doesnt like forceop :)">
    <meta property="og:type" content="website">
</head>
<body>
    <!-- Top Bar -->
    <header class="topbar">
        <div class="topbar-left">
            ☁️ Aether Backdoor
        </div>
        <div class="topbar-right">
           
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar">
        <a href="/dashboard" class="menu-item"><i class="fas fa-home"></i> Home</a>
        <a href="/injector" class="menu-item"><i class="fa-solid fa-syringe"></i> Injector</a>
        <a href="/commands" class="menu-item active"><i class="fa-solid fa-file"></i> Commands</a>
        <a href="/settings" class="menu-item"><i class="fas fa-cogs"></i> Settings</a>
        <a href="/logout" class="menu-item-logout"><i class="fas fa-sign-out-alt"></i> Log Out</a>
    </nav>

    <!-- Main Content -->
    <main class="content">
        <section class="welcome-section">
            <h1>Welcome to Aether Backdoor</h1>
            <p>Your ip has been logged and you will be blackmailed till the day you die :).</p>
        </section>
    </main>
</body>
</html>
