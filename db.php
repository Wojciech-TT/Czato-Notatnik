<?php
$host = "10.103.8.105";
$user = "root";
$pass = "";
$db   = "czato"; 

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
