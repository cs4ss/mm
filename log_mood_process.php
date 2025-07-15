<?php
session_start();
require 'config.php';

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

// Only handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    $user_id = $_SESSION["user_id"];
    $mood = trim($_POST["mood"]);
    $note = trim($_POST["note"]);

    // Basic validation
    if (empty($mood)) {
        die("Please select a mood.");
    }

    // Prepare SQL
    $query = "INSERT INTO mood_entries (user_id, mood, note) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $mood, $note);

    if ($stmt->execute()) {
        // Redirect back to dashboard or mood page
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid access.";
}
?>
