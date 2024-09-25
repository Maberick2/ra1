<?php
$servername = "localhost"; 
$username = "Roberto";
$password = "Manuel1234";
$dbname = "vuelos"; 


session_start();

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Redirigir a login.php si no está iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Verificar el ID del usuario
echo "ID de usuario: " . htmlspecialchars($id_usuario); // Para verificar el ID del usuario de manera segura

// Consulta para obtener las reservaciones y datos del vuelo
$stmt = $conn->prepare("SELECT r.id, v.origen, v.destino, v.fecha_salida, v.fecha_llegada, v.precio, r.fecha_reservacion
                        FROM reservaciones r
                        JOIN vuelos v ON r.id_vuelo = v.id
                        WHERE r.id_usuario = ?");
$stmt->bind_param("i", $id_usuario);

// Ejecutar la consulta
if ($stmt->execute()) {
    $result = $stmt->get_result();
} else {
    echo "Error en la consulta: " . htmlspecialchars($stmt->error);
    exit(); // Salir si hay un error en la consulta
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tus Reservaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }
        nav {
            background-color: #005f73;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-bottom: 3px solid #003840;
        }
        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        nav a:hover {
            color: #a8dadc;
        }
        nav span {
            color: #ffffff;
            margin-right: 20px;
            font-weight: bold;
        }
        .container {
            margin-top: 50px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #007bff;
            margin-bottom: 20px;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        li {
            padding: 15px;
            margin-bottom: 10px;
            background-color: #f0f8ff;
            border-radius: 5px;
            border-left: 5px solid #007bff;
        }
        li:nth-child(even) {
            background-color: #e9f5ff;
        }
        .btn-primary {
            background-color: #005f73;
            border-color: #005f73;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #004954;
            border-color: #004040;
        }
        .btn-primary:active {
            background-color: #003840;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <a href="index.php">Inicio</a>
        <a href="buscar_vuelos.php">Buscar Vuelos</a>
        <?php if (isset($_SESSION['id_usuario'])): ?>
            <span>Hola, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></span>
            <a href="logout.php">Cerrar Sesión</a>
        <?php endif; ?>
    </nav>

    <div class="container">
        <h2>Tus Reservaciones</h2>

        <?php
        // Verifica si hay reservaciones
        if ($result && $result->num_rows > 0) {
            echo "<ul>";
            while ($reservacion = $result->fetch_assoc()) {
                echo "<li><strong>Vuelo desde</strong> {$reservacion['origen']} <strong>a</strong> {$reservacion['destino']}<br>
                      <strong>Salida:</strong> {$reservacion['fecha_salida']}<br>
                      <strong>Llegada:</strong> {$reservacion['fecha_llegada']}<br>
                      <strong>Precio:</strong> $" . number_format($reservacion['precio'], 2) . "<br>
                      <strong>Reservado el:</strong> {$reservacion['fecha_reservacion']}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p class='alert alert-warning'>No tienes reservaciones.</p>";
        }

        // Cerrar la declaración y la conexión
        $stmt->close();
        $conn->close();
        ?>

        <a href="index.php" class="btn btn-primary mt-3">Volver a la página principal</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
