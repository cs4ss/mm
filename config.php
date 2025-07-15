<?php
// Database configuration
$host = "localhost";         // Hostname
$dbname = "moodmate";        // my database name
$username = "root";          
$password = "";              

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
