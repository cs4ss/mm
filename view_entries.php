<?php
session_start();
require 'config.php';

// Check if logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];

// Get mood entries for this user
$query = "SELECT mood, note, created_at FROM mood_entries WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Mood Entries - MoodMate</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Header -->
    <header>
        <div class="logo">
            <h1>Mood<span>Mate</span></h1>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="dashboard-nav">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="log_mood.html">Log Mood</a></li>
            <li><a href="journal_entry.html">Journal Entry</a></li>
            <li><a href="resources.html">Resources</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Main -->
    <main class="entries-container">
        <h2><?php echo htmlspecialchars($username); ?>'s Mood History</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="entry-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="entry">
                        <div class="entry-header">
                            <span class="entry-date"><?php echo date("F j, Y - g:i A", strtotime($row['created_at'])); ?></span>
                            <span class="entry-mood"><?php echo htmlspecialchars($row['mood']); ?></span>
                        </div>
                        <?php if (!empty($row['note'])): ?>
                            <p class="entry-text"><?php echo htmlspecialchars($row['note']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No mood entries yet. <a href="log_mood.html">Log one now</a>!</p>
        <?php endif; ?>

    </main>

    <footer>
        <p>&copy; 2025 MoodMate. All rights reserved.</p>
    </footer>

</body>
</html>
