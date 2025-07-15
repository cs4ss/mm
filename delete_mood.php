<?php
session_start();
require 'config.php';

// Check admin session
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Check for mood entry ID
if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);

    $stmt = $conn->prepare("DELETE FROM mood_entries WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Failed to delete mood entry.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No mood ID provided.";
}
?>
