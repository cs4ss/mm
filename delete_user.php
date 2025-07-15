<?php
session_start();
require 'config.php';

// Make sure admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Get URL of user ID
if (isset($_GET["id"])) {
    $user_id = intval($_GET["id"]);

    //prevent deleting the admin user
    if ($user_id === $_SESSION["admin_id"]) {
        die("You cannot delete the admin account.");
    }

    // Delete user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Failed to delete user.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No user ID provided.";
}
?>
