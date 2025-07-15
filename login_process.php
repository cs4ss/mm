<?php
// Start a session to track the logged-in user
session_start();

// Include database connection
require 'config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        die("Please fill in both username and password.");
    }

    // Check if user exists
    $query = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        // User found
        $stmt->bind_result($id, $fetched_username, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            // ✅ Password correct - set session and redirect
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $fetched_username;

            // Redirect to dashboard (use PHP version later)
            header("Location: dashboard.html");
            exit();
        } else {
            die("Incorrect password.");
        }
    } else {
        die("User not found with that username.");
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid access.";
}
?>
