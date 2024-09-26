<?php
$servername = "localhost"; 
$username = "Roberto";
$password = "Manuel1234";
$dbname = "vuelos"; 

session_start();

// Establecer conexión con la base de datos
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

// Mensaje de eliminación
$mensaje = "";
if (isset($_GET['eliminar'])) {
    $id_reservacion = intval($_GET['eliminar']);
    
    // Elimina la reservación de la tabla
    $stmt = $conn->prepare("DELETE FROM reservaciones WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $id_reservacion, $id_usuario);
    
    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success' id='mensaje'>Reservación eliminada con éxito.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al eliminar la reservación: " . htmlspecialchars($stmt->error) . "</div>";
    }
    
    // Cerrar la declaración
    $stmt->close();
}

// Consulta para obtener las reservaciones del usuario
$stmt = $conn->prepare("SELECT r.id, v.origen, v.destino, v.fecha_salida, v.fecha_llegada, v.precio, r.fecha_reservacion
                        FROM reservaciones r
                        JOIN vuelos v ON r.id_vuelo = v.id
                        WHERE r.id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tus Reservaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos generales */
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: url('https://2.bp.blogspot.com/-4zTGeP6ktmw/UaAIttq6PiI/AAAAAAAByik/iC8r8TjY0PE/s1600/towards-the-sunset-1920x1200-wallpaper-amanecer-visto-desde-un-avi%C3%B3n-vistas-a%C3%A9reas.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #03001e;
            line-height: 1.6;
            flex-grow: 1;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(34, 145, 38, 0.2);
        }

        h2 {
            color: #7303c0;
            margin-bottom: 1.5em;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
        }

        li {
            padding: 15px;
            margin-bottom: 10px;
            background-color: rgba(240, 248, 255, 0.9);
            border-radius: 5px;
            border-left: 5px solid #007bff;
        }

        .btn-primary {
            background-color: #ec38bc;
            border-color: #ec38bc;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #7303c0;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            margin-left: 10px;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Diseño para la barra de navegación */
        nav {
            background-color: rgba(3, 0, 30, 0.9);
            padding: 1rem;
            border-bottom: 2px solid #ec38bc;
     	    text-align: center; /* Centrar el contenido del nav */

        }

        nav a {
            color: #ec38bc;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #7303c0;
        }

        nav span {
            color: #ffffff;
            margin-right: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <a href="index.php">Inicio</a>
        <a href="buscar_vuelos.php">Crear Vuelos</a>
        <a href="reservaciones.php">Ver vuelos</a>
        <span>Hola, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></span>
        <a href="logout.php">Cerrar Sesión</a>
    </nav>

    <div class="container">
        <h2>Tus Reservaciones</h2>

        <?php
        // Mostrar mensaje si existe
        if ($mensaje) {
            echo $mensaje;
        }

        // Verifica si hay reservaciones
        if ($result && $result->num_rows > 0) {
            echo "<ul>";
            while ($reservacion = $result->fetch_assoc()) {
                echo "<li>
                        <strong>Vuelo desde</strong> {$reservacion['origen']} <strong>a</strong> {$reservacion['destino']}<br>
                        <strong>Salida:</strong> {$reservacion['fecha_salida']}<br>
                        <strong>Llegada:</strong> {$reservacion['fecha_llegada']}<br>
                        <strong>Precio:</strong> $" . number_format($reservacion['precio'], 2) . "<br>
                        <strong>Reservado el:</strong> {$reservacion['fecha_reservacion']}
                        <a href='tusreservaciones.php?eliminar={$reservacion['id']}' class='btn btn-danger' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta reservación?\")'>Eliminar</a>
                      </li>";
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
    <script>
        // Función para ocultar el mensaje después de 5 segundos
        setTimeout(function() {
            var mensaje = document.getElementById('mensaje');
            if (mensaje) {
                mensaje.style.display = 'none';
            }
        }, 500);
    </script>
</body>
</html>
