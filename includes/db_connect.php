<?php
// Database connection parameters
$host = "localhost";
$user = "root";
$password = "";
$database = "social_network";

// Create connection
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>