<?php
session_start();
require 'config.php'; // Connect to the database

// If already logged in, redirect to dashboard
if (isset($_SESSION["admin_logged_in"]) && $_SESSION["admin_logged_in"] === true) {
    header("Location: admin_dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Only allow login for admin username
        $query = "SELECT id, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            if ($username === "admin" && password_verify($password, $hashed_password)) {
                // Success: Start admin session
                $_SESSION["admin_logged_in"] = true;
                $_SESSION["admin_id"] = $id;
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Admin account not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - MoodMate</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="login-container">
    <h2>Admin Login</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="admin" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="••••••" required>

        <button type="submit" class="btn">Login</button>
    </form>
</main>

</body>
</html>
