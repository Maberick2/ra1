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
            echo "<div class='alert alert-success'>Vuelo agregado exitosamente.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error al agregar el vuelo: " . $conn->error . "</div>";
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
    <style>
        body {
            background-color: #f0f8ff;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        h2 {
            color: #007bff;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-link {
            color: #ffffff;
            background-color: #005f73;
            border: 2px solid transparent;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-link:hover {
            background-color: #004954;
            border-color: #004040;
        }
        .btn-link:active {
            background-color: #003840;
        }
        nav {
            background-color: #005f73;
            padding: 20px; /* Aumentar el padding para hacerlo más grueso */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-bottom: 3px solid #003840; /* Agregar un borde inferior */
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
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <a href="index.php">Inicio</a>
        <a href="reservaciones.php">Ver Reservaciones</a>
        <?php if (isset($_SESSION['id_usuario'])): ?>
            <span>Hola, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></span>
            <a href="logout.php">Cerrar Sesión</a>
        <?php endif; ?>
    </nav>

    <div class="container">
        <h2 class="my-4">Agregar un nuevo vuelo</h2>
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

        // Función de geolocalización
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                alert("La geolocalización no es compatible con este navegador.");
            }
        }

        function showPosition(position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
            .then(response => response.json())
            .then(data => {
                const location = data.address.city ? data.address.city : data.address.town;
                document.getElementById("origen").value = location;
            })
            .catch(error => console.error("Error al obtener la ubicación:", error));
        }

        function showError(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("El usuario negó la solicitud de geolocalización.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("La información de ubicación no está disponible.");
                    break;
                case error.TIMEOUT:
                    alert("La solicitud de geolocalización ha caducado.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("Se ha producido un error desconocido.");
                    break;
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
