<?php
session_start();
require 'config.php';

// Total user count
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()["total"];

// Total mood entries
$totalMoods = $conn->query("SELECT COUNT(*) AS total FROM mood_entries")->fetch_assoc()["total"];

// Total journal entries
$totalJournals = $conn->query("SELECT COUNT(*) AS total FROM journal_entries")->fetch_assoc()["total"];

// Most common mood
$commonMoodQuery = $conn->query("SELECT mood, COUNT(*) AS count FROM mood_entries GROUP BY mood ORDER BY count DESC LIMIT 1");
$commonMood = ($commonMoodQuery->num_rows > 0) ? $commonMoodQuery->fetch_assoc()["mood"] : "N/A";


// Check if the admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all users
$users = $conn->query("SELECT id, fullname, email, username, created_at FROM users ORDER BY created_at DESC");

// Fetch all mood entries
$moods = $conn->query("SELECT m.id, u.username, m.mood, m.note, m.created_at
                       FROM mood_entries m
                       JOIN users u ON m.user_id = u.id
                       ORDER BY m.created_at DESC");

// Fetch all journal entries
$journals = $conn->query("SELECT j.id, u.username, j.title, j.entry, j.created_at
                          FROM journal_entries j
                          JOIN users u ON j.user_id = u.id
                          ORDER BY j.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - MoodMate</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-section {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .admin-section h3 {
            margin-top: 30px;
            color: #4c1d95;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        th {
            background-color: #f3e8ff;
            color: #333;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <h1>Mood<span>Mate</span> Admin</h1>
    </div>
</header>

<main class="admin-section">

    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; gap: 20px; margin-bottom: 30px;">
        <div style="flex: 1; min-width: 200px; background: #f3e8ff; padding: 20px; border-radius: 10px;">
            <h4>Total Users</h4>
            <p style="font-size: 1.5em;"><?php echo $totalUsers; ?></p>
        </div>
        <div style="flex: 1; min-width: 200px; background: #f3e8ff; padding: 20px; border-radius: 10px;">
            <h4>Total Mood Entries</h4>
            <p style="font-size: 1.5em;"><?php echo $totalMoods; ?></p>
        </div>
        <div style="flex: 1; min-width: 200px; background: #f3e8ff; padding: 20px; border-radius: 10px;">
            <h4>Total Journal Entries</h4>
            <p style="font-size: 1.5em;"><?php echo $totalJournals; ?></p>
        </div>
        <div style="flex: 1; min-width: 200px; background: #f3e8ff; padding: 20px; border-radius: 10px;">
            <h4>Most Common Mood</h4>
            <p style="font-size: 1.5em;"><?php echo htmlspecialchars($commonMood); ?></p>
        </div>
    </div>


    <h2>Welcome, Admin</h2>

    <p><a href="admin_logout.php" style="color:#c00; font-weight:bold;">Logout</a></p>


    <!-- Users Table -->
    <h3>All Users</h3>
    <table>
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Username</th><th>Registered On</th></tr>
        <?php while ($row = $users->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo htmlspecialchars($row["fullname"]); ?></td>
                <td><?php echo htmlspecialchars($row["email"]); ?></td>
                <td><?php echo htmlspecialchars($row["username"]); ?></td>
                <td><?php echo $row["created_at"]; ?>
                    <?php if ($row["username"] !== "admin"): ?>
                    | <a href="delete_user.php?id=<?php echo $row["id"]; ?>" onclick="return confirm('Are you sure you want to delete this user?');" style="color:red;">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Mood Logs -->
    <h3>Mood Logs</h3>
    <table>
        <tr><th>ID</th><th>User</th><th>Mood</th><th>Note</th><th>Date</th></tr>
        <?php while ($row = $moods->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo htmlspecialchars($row["username"]); ?></td>
                <td><?php echo htmlspecialchars($row["mood"]); ?></td>
                <td><?php echo htmlspecialchars($row["note"]); ?></td>
                <td><?php echo $row["created_at"]; ?>
                    | <a href="delete_mood.php?id=<?php echo $row["id"]; ?>" onclick="return confirm('Delete this mood entry?');" style="color:red;">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Journal Entries -->
    <h3>Journal Entries</h3>
    <table>
        <tr><th>ID</th><th>User</th><th>Title</th><th>Entry</th><th>Date</th></tr>
        <?php while ($row = $journals->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo htmlspecialchars($row["username"]); ?></td>
                <td><?php echo htmlspecialchars($row["title"]); ?></td>
                <td><?php echo htmlspecialchars($row["entry"]); ?></td>
                <td><?php echo $row["created_at"]; ?>
                    | <a href="delete_journal.php?id=<?php echo $row["id"]; ?>" onclick="return confirm('Delete this journal entry?');" style="color:red;">Delete</a>
                </td>

            </tr>
        <?php endwhile; ?>
    </table>
</main>

<footer>
    <p style="text-align:center;">&copy; 2025 MoodMate Admin Panel</p>
</footer>

</body>
</html>
