<?php
session_start();
require 'config.php';

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION["user_id"];
    $title = trim($_POST["title"]);
    $entry = trim($_POST["entry"]);

    if (empty($entry)) {
        die(" Journal entry cannot be empty.");
    }

    // Prepare SQL statement
    $query = "INSERT INTO journal_entries (user_id, title, entry) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $title, $entry);

    if ($stmt->execute()) {
        // Redirect back to dashboard or journal view
        header("Location: dashboard.php");
        exit();
    } else {
        echo " Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo " Invalid access.";
}
?>
