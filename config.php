<?php
$host = "localhost";
$user = "root";  // Change if using another username
$password = "";  // Change if you have a password
$database = "travel_itinerary";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
