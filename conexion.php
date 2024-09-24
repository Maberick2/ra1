<?php
$servername = "localhost"; 
$username = "Roberto";
$password = "Ramirez2024";
$dbname = "vuelos"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
