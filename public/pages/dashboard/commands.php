<?php
include '/www/wwwroot/Aether/backend/session.php';
include '/www/wwwroot/Aether/classes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aether Backdoor - Commands</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/public/css/dash.css">
    <script>
        function searchCommands() {
            const input = document.getElementById("searchInput").value.toLowerCase();
            const table = document.getElementById("commandsTable");
            const rows = table.getElementsByTagName("tr");
        
            for (let i = 1; i < rows.length; i++) { 
                const nameCell = rows[i].getElementsByTagName("td")[1]; 
                if (nameCell) {
                    const txtValue = nameCell.textContent || nameCell.innerText;
                    rows[i].style.display = txtValue.toLowerCase().includes(input) ? "" : "none";
                }
            }
        }        
    </script>

    <meta property="og:title" content="Aether Commands">
    <meta property="og:description" content="List of commands that are in the Aether Backdoor.">
    <meta property="og:type" content="website">
</head>
<body>
    <!-- Top Bar -->
    <header class="topbar">
        <div class="topbar-left">☁️ Aether Backdoor</div>
        <div class="topbar-right"></div>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar">
        <a href="/dashboard" class="menu-item"><i class="fas fa-home"></i> Home</a>
        <a href="/injector" class="menu-item"><i class="fa-solid fa-syringe"></i> Injector</a>
        <a href="/commands" class="menu-item active"><i class="fa-solid fa-file"></i> Commands</a>
        <a href="/settings" class="menu-item"><i class="fas fa-cogs"></i> Settings</a>
        <a href="/logout" class="menu-item-logout"><i class="fas fa-sign-out-alt"></i> Log Out</a>
    </nav>
    <main class="content">
        <section class="commands-section">
            <h1>Commands List</h1>

            <div class="search-bar">
                <input type="text" id="searchInput" onkeyup="searchCommands()" placeholder="Search for commands..." />
            </div>

        <div class="table-container">
            <table id="commandsTable" class="commands-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Rank</th>
                        <th>Aliases</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        </section>
    </main>

    <script>
        async function loadCommands() {
            try {
                const response = await fetch('/api/commands'); 
                const commands = await response.json(); 

                const tableBody = document.querySelector('#commandsTable tbody');
                tableBody.innerHTML = '';

                commands.forEach(command => {
                    const row = document.createElement('tr');

                    const idCell = document.createElement('td');
                    idCell.textContent = command.id;
                    row.appendChild(idCell);

                    const nameCell = document.createElement('td');
                    nameCell.textContent = command.name;
                    row.appendChild(nameCell);

                    const rankCell = document.createElement('td');
                    rankCell.textContent = command.rank;
                    row.appendChild(rankCell);

                    const aliasesCell = document.createElement('td');
                    aliasesCell.textContent = command.aliases || '';
                    row.appendChild(aliasesCell);

                    const descriptionCell = document.createElement('td');
                    descriptionCell.textContent = command.description;
                    row.appendChild(descriptionCell);

                    tableBody.appendChild(row);
                });
            } catch (error) {
                console.error('Error loading commands:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', loadCommands);
    </script>
</body>
</html>

