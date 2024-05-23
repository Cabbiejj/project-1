<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$database = "project1"; 

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connected successfully!";
}

$conn->close();
?>
