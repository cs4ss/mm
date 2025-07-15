<?php
session_start(); // Start session so it can be destroyed

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page
header("Location: login.html");
exit();
?>
