<?php
session_start();

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
    <script>
        // Request Notification permission
        document.addEventListener('DOMContentLoaded', function() {
            if (Notification.permission !== 'granted') {
                Notification.requestPermission();
            }
        });

        function showNotification(title, message) {
            if (Notification.permission === 'granted') {
                const options = {
                    body: message,
                    icon: '/path-to-icon/icon.png' 
                };
                new Notification(title, options);
            }
        }
    </script>
</head>
<body>
    <!-- Top Bar -->
    <header class="topbar">
        <div class="topbar-left">
            ☁️ Aether Backdoor
        </div>
        <div class="topbar-right"></div>
    </header>

    <!-- Sidebar and Main Content -->
    <nav class="sidebar">
        <a href="/dashboard" class="menu-item"><i class="fas fa-home"></i> Home</a>
        <a href="/injector" class="menu-item"><i class="fa-solid fa-syringe"></i> Injector</a>
        <a href="/commands" class="menu-item active"><i class="fa-solid fa-file"></i> Commands</a>
        <a href="/settings" class="menu-item"><i class="fas fa-cogs"></i> Settings</a>
        <a href="/logout" class="menu-item-logout"><i class="fas fa-sign-out-alt"></i> Log Out</a>
    </nav>
    
    <!-- Main Content -->
    <main class="content">
        <div class="settings-container">
            <h2>Settings</h2>

            <div class="setting-item">
                <label>Username</label>
                <input type="text" name="username" value="" readonly>
            </div>

            <div class="setting-item">
                <label>ID</label>
                <input type="text" name="id" value="" readonly>
            </div>

            <div class="rank-upgrade-container">
                <div class="setting-item rank-input">
                    <label>Rank</label>
                    <input type="text" name="rank" value="" readonly>
                </div>
               <!-- <button class="upgrade-btn">Upgrade</button> -->
            </div>

            <div class="setting-item">
                <label>Login Key</label>
                <input type="text" name="loginkey" value="" readonly>
            </div>

            <div class="setting-item">
                <label>MC Username</label>
                <input type="text" name="mcUsername" value="">
            </div>

            <div class="setting-item">
                <label>Change Password</label>
                <input type="password" name="password" value="">
            </div>
            <button class="save-changes">Save Changes</button>
            
            <!-- Modal for Rank Upgrade -->
            <div id="rank-upgrade-modal" class="modal">
                <div class="modal-content">
                    <h2>Enter Rank Key</h2>
                    <input type="text" id="rankKey" placeholder="Enter Rank Key">
                    <button id="useRankKey">Use</button>
                </div>
            </div>
        </div>
    </main>

    <!-- Embedded JavaScript -->
    <script>
       document.addEventListener("DOMContentLoaded", () => {
    
        // Fetch user info from the server
        fetch('/api/user-info')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const user = data.user;
                    document.querySelector('input[name="username"]').value = user.username || '';
                    document.querySelector('input[name="id"]').value = user.id || '';
                    document.querySelector('input[name="rank"]').value = user.rank || '';
                    document.querySelector('input[name="loginkey"]').value = user.loginkey || '';
                    document.querySelector('input[name="mcUsername"]').value = user.mcUsername || '';
                    document.querySelector('input[name="password"]').value = ''; // Always start empty
                } else {
                    showNotification('Error', 'Error fetching user data: ' + data.error);
                }
            })
            .catch(error => showNotification('Error', 'Error fetching user data.'));
    
        // Save changes to the user data
        document.querySelector('.save-changes').addEventListener('click', () => {
            const mcUsername = document.querySelector('input[name="mcUsername"]').value;
            const password = document.querySelector('input[name="password"]').value;
    
            const updateData = {
                mcUsername: mcUsername || null, // Only send mcUsername if present
            };
    
            // Only add password to update data if it's not empty and meets criteria
            if (password) {
                if (password.length < 6) {
                    showNotification('Error', 'Password must be at least 6 characters long.');
                    return;
                }
                updateData.password = password;
            }
    
            // Send data to the API for saving changes
            fetch('/api/update-user', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(updateData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Success', 'Changes saved successfully!');
                } else {
                    showNotification('Error', 'Failed to save changes: ' + data.error);
                }
            })
            .catch(error => {
                showNotification('Error', 'An error occurred while saving changes.');
            });
        });
    });
    document.addEventListener("DOMContentLoaded", () => {
        const upgradeBtn = document.querySelector('.upgrade-btn');
        const modal = document.getElementById('rank-upgrade-modal');
        const content = document.querySelector('.settings-container');
        const useRankKeyBtn = document.getElementById('useRankKey');
    
        // Open the modal and blur the background
        upgradeBtn.addEventListener('click', () => {
            modal.style.display = 'flex'; // Show modal
            content.classList.add('blur-background'); // Add blur effect
        });
    
        // Optional: Close the modal when clicking outside of it
        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
                content.classList.remove('blur-background');
            }
        });
    });
    </script>
</body>
</html>
