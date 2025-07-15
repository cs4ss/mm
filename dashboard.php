<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

// Get the username for welcome message
$username = $_SESSION["username"];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MoodMate</title>
    <!-- Link to external CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Header -->
    <header>
        <div class="logo">
            <h1>Mood<span>Mate</span></h1>
        </div>
    </header>

    <!-- Navigation Bar -->
    <nav class="dashboard-nav">
        <ul>
            <li><a href="log_mood.html">Log Mood</a></li>
            <li><a href="journal_entry.html">Journal Entry</a></li>
            <li><a href="view_entries.php">View Entries</a></li>
            <li><a href="view_entries_calendar.php">View Mood Calendar</a></li>
             <li><a href="view_journal_entries.php">View Journals</a></li> 
            <li><a href="resources.html">Resources</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="dashboard-main">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <p>Start your day by logging your mood or writing a quick journal entry.</p>

        <div class="dashboard-buttons">
            <a href="log_mood.html" class="btn">Log Your Mood</a>
            <a href="journal_entry.html" class="btn">Write Journal</a>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 MoodMate. All rights reserved.</p>
    </footer>

</body>
</html>
