<?php
$host = "localhost";
$username = "root";
$password = ""; // Leave empty if no password is set for MySQL's root user
$database = "eventbuddy";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
