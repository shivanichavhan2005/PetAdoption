<?php
// =========================================
// Database Connection
// =========================================
$host = "localhost";
$db_user = "root";
$db_pass = "";        // default XAMPP MySQL has no password
$db_name = "pet_adoption";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
