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

// Inicializar la variable de mensaje
$mensaje = "";

// Verificar si el usuario está autenticado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: login.php");
        exit();
    }

    // Procesar el formulario para agregar el vuelo
    if (isset($_POST['origen']) && isset($_POST['destino']) && isset($_POST['fecha_salida']) && isset($_POST['fecha_llegada']) && isset($_POST['precio'])) {
        
        $origen = $_POST['origen'];
        $destino = $_POST['destino'];
        $fecha_salida = $_POST['fecha_salida'];
        $fecha_llegada = $_POST['fecha_llegada'];
        $precio = $_POST['precio'];

        $stmt = $conn->prepare("INSERT INTO vuelos (origen, destino, fecha_salida, fecha_llegada, precio) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssd", $origen, $destino, $fecha_salida, $fecha_llegada, $precio);

        if ($stmt->execute()) {
             $mensaje = "<div id='mensaje' class='alert alert-success'>Vuelo agregado exitosamente.</div>";
        } else {
            $mensaje = "<div id='mensaje' class='alert alert-danger'>Error al agregar el vuelo: " . $conn->error . "</div>";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Vuelo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400&display=swap" rel="stylesheet">
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
            margin-bottom: 1.5em;
            color: #7303c0;
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

        /* Diseño de los formularios */
        .form-label {
            color: #ec38bc;
            font-weight: bold;
        }

        input[type="text"], input[type="date"], input[type="number"] {
            padding: 15px;
            margin-bottom: 20px;
            border: 2px solid #7303c0;
            border-radius: 8px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
            background: #fdeff9;
        }

        .btn {
            padding: 10px 20px;
            background-color: #ec38bc;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #7303c0;
        }

        /* Diseño del footer */
        .footer {
            background-color: rgba(3, 0, 30, 0.9);
            padding: 20px;
            text-align: center;
            color: #ec38bc;
            border-top: 2px solid #ec38bc;
            margin-top: auto;  /* Esto empuja el footer al final de la página */
        }

        .footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <a href="index.php">Inicio</a>
        <a href="reservaciones.php">Ver vuelos</a>
        <a href="tusreservaciones.php">Ver tus Reservaciones</a>
        <?php if (isset($_SESSION['id_usuario'])): ?>
            <span>Hola, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></span>
            <a href="logout.php">Cerrar Sesión</a>
        <?php endif; ?>
    </nav>

    <div class="container">
        <h2 class="my-4">Agregar un nuevo vuelo</h2>
        
        <!-- Mostrar mensaje aquí -->
        <?php if ($mensaje): ?>
            <?php echo $mensaje; ?>
        <?php endif; ?>

        <form method="POST" action="" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="origen" class="form-label">Origen:</label>
                <input type="text" class="form-control" id="origen" name="origen" required>
                <button type="button" class="btn btn-secondary mt-2" onclick="getLocation()">Usar mi ubicación actual</button>
                <div class="invalid-feedback">Por favor, ingrese el origen.</div>
            </div>

            <div class="mb-3">
                <label for="destino" class="form-label">Destino:</label>
                <input type="text" class="form-control" id="destino" name="destino" required>
                <div class="invalid-feedback">Por favor, ingrese el destino.</div>
            </div>

            <div class="mb-3">
                <label for="fecha_salida" class="form-label">Fecha de salida:</label>
                <input type="date" class="form-control" id="fecha_salida" name="fecha_salida" required>
                <div class="invalid-feedback">Por favor, ingrese la fecha de salida.</div>
            </div>

            <div class="mb-3">
                <label for="fecha_llegada" class="form-label">Fecha de llegada:</label>
                <input type="date" class="form-control" id="fecha_llegada" name="fecha_llegada" required>
                <div class="invalid-feedback">Por favor, ingrese la fecha de llegada.</div>
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio (MEX):</label>
                <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
                <div class="invalid-feedback">Por favor, ingrese el precio.</div>
            </div>

            <button type="submit" class="btn btn-primary">Agregar vuelo</button>
        </form>
        
        <!-- Botón de regreso mejorado con nuevo color -->
        <a href="index.php" class="btn btn-link mt-3">Volver a la página principal</a>
    </div>

    <script>
        // Validación de Bootstrap
        (function () {
            "use strict";
            var forms = document.querySelectorAll(".needs-validation");
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener("submit", function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add("was-validated");
                }, false);
            });
        })();

        // Función para ocultar el mensaje después de 5 segundos
        setTimeout(function() {
            var mensaje = document.getElementById('mensaje');
            if (mensaje) {
                mensaje.style.display = 'none';
            }
        }, 500); // Cambiado a 5000 ms para 5 segundos

        // Función para obtener ubicación
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("Geolocalización no soportada por este navegador.");
            }
        }

        function showPosition(position) {
            // Enviar las coordenadas a los inputs correspondientes
            document.getElementById("origen").value = position.coords.latitude + ", " + position.coords.longitude;
        }
    </script>
    
    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 Tu Agencia de Viajes</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

