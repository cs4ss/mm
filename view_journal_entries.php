<?php
session_start();
require 'config.php';

// Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];

// Fetch journal entries
$query = "SELECT title, entry, created_at FROM journal_entries WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Journals - MoodMate</title>
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
        <li><a href="view_entries.php">View Moods</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<!-- Main -->
<main class="entries-container">
    <h2><?php echo htmlspecialchars($username); ?>'s Journal Entries</h2>

    <?php if ($result->num_rows > 0): ?>
        <div class="entry-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="entry">
                    <div class="entry-header">
                        <span class="entry-date"><?php echo date("F j, Y - g:i A", strtotime($row['created_at'])); ?></span>
                        <?php if (!empty($row['title'])): ?>
                            <strong class="entry-title"><?php echo htmlspecialchars($row['title']); ?></strong>
                        <?php endif; ?>
                    </div>
                    <p class="entry-text"><?php echo nl2br(htmlspecialchars($row['entry'])); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No journal entries yet. <a href="journal_entry.html">Write one now</a>.</p>
    <?php endif; ?>
</main>

<footer>
    <p>&copy; 2025 MoodMate. All rights reserved.</p>
</footer>

</body>
</html>
