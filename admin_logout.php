<?php
session_start();

// Remove all admin session data
unset($_SESSION["admin_logged_in"]);
unset($_SESSION["admin_id"]);

// destroy all session data
// session_destroy();

// Redirect to admin login
header("Location: admin_login.php");
exit();
?>
