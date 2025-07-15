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

// Fetch mood entries for the calendar
$query = "SELECT mood, note, created_at FROM mood_entries WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Prepare mood entries as JavaScript-readable array
$moodEvents = [];

while ($row = $result->fetch_assoc()) {
    $moodEvents[] = [
        "title" => $row["mood"],
        "start" => date("Y-m-d", strtotime($row["created_at"])),
        "description" => $row["note"]
    ];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mood Calendar - MoodMate</title>
    <link rel="stylesheet" href="style.css">

    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
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
        <li><a href="view_journal_entries.php">View Journals</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<!-- Main Content -->
<main class="calendar-container">
    <h2><?php echo htmlspecialchars($username); ?>'s Mood Calendar</h2>
    <div id="calendar"></div>
</main>

<!-- Footer -->
<footer>
    <p>&copy; 2025 MoodMate. All rights reserved.</p>
</footer>


<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const moodEvents = <?php echo json_encode($moodEvents); ?>;

        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            events: moodEvents,
            eventClick: function (info) {
                const mood = info.event.title;
                const note = info.event.extendedProps.description || "No note.";

                document.getElementById("modalMood").innerText = mood;
                document.getElementById("modalNote").innerText = note;

                document.getElementById("moodModal").style.display = "block";
            }

        });

        calendar.render();

        // Close modal when X is clicked
        document.querySelector(".close-button").addEventListener("click", function () {
            document.getElementById("moodModal").style.display = "none";
        });

        // Close modal when clicking outside the box
        window.addEventListener("click", function (e) {
            const modal = document.getElementById("moodModal");
            if (e.target == modal) {
                modal.style.display = "none";
            }
        });

    });
</script>


<div id="moodModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h3 id="modalMood"></h3>
        <p id="modalNote"></p>
    </div>
</div>


</body>
</html>
