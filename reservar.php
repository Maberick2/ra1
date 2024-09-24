<?php
$servername = "localhost";
$username = "root";
$password = "Aylin2024!";
$dbname = "vuelos";

session_start();

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (!isset($_SESSION['id_usuario'])) {
    echo "Por favor, <a href='login.php'>inicia sesión</a para reservar un vuelo.";
    exit;
}

if (isset($_GET['id_vuelo'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $id_vuelo = $_GET['id_vuelo'];

    $stmt = $conn->prepare("INSERT INTO reservaciones (id_usuario, id_vuelo) VALUES (?, ?)");
    $stmt->bind_param("ii", $id_usuario, $id_vuelo);

    if ($stmt->execute()) {
        echo "Reservación realizada exitosamente. <a href='reservaciones.php'>Ver mis reservaciones</a>";
    } else {
        echo "Error al realizar la reservación.";
    }
} else {
    echo "No se especificó ningún vuelo.";
}
?>
