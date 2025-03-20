<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "inventory";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
} 
?>
