<?php
$servername = "localhost"; 
$username = "Roberto";
$password = "Manuel1234";
$dbname = "vuelos"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    // Verificar si el correo ya está registrado
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('El correo electrónico ya está registrado.');</script>";
    } else {
        // Si el correo no está registrado, proceder a insertar
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, contrasena) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $correo, $contrasena);

        if ($stmt->execute()) {
          header("Location: login.php"); // Redireccionar a login.php tras el registro
            echo "<div class='success'>Registro exitoso. <a href='login.php'>Iniciar sesión</a></div>";
        } else {
            echo "<div class='error'>Error al registrar usuario.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
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

        h1 {
            margin-bottom: 1.5em;
            color: #7303c0;
        }

        /* Diseño para la barra de navegación */
        nav {
            background-color: rgba(3, 0, 30, 0.9);
            padding: 1rem;
            border-bottom: 2px solid #ec38bc;
        }

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
        label {
            color: #ec38bc;
            font-weight: bold;
        }

        input[type="text"], input[type="password"], input[type="email"] {
            padding: 15px;
            margin-bottom: 20px;
            border: 2px solid #7303c0;
            border-radius: 8px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
            background: #fdeff9;
            width: 100%;
        }

        button {
            padding: 10px 20px;
            background-color: #ec38bc;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #7303c0;
        }

        .error {
            color: #ff4d4d;
            margin-bottom: 15px;
        }

        .success {
            color: #4caf50;
            margin-bottom: 15px;
        }

        /* Diseño del footer */
        footer {
            background-color: rgba(3, 0, 30, 0.9);
            padding: 20px;
            text-align: center;
            color: #ec38bc;
            border-top: 2px solid #ec38bc;
            margin-top: auto;
        }

        footer p {
            margin: 0;
            font-size: 16px;
            color: #ec38bc;
        }

        footer a {
            color: #ec38bc;
            text-decoration: underline;
        }

        footer a:hover {
            color: #7303c0;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="buscar_vuelos.php">Crear Vuelos</a>
            <a href="reservaciones.php">Ver vuelos</a>
            <a href="tusreservaciones.php">Ver tus Reservaciones</a>
            <?php if (isset($_SESSION['id_usuario'])): ?>
                <span>Hola, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></span>
                <a href="logout.php">Cerrar Sesión</a>
            <?php else: ?>
                <a href="login.php">Iniciar Sesión</a>
                <a href="registro.php">Registrarse</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="container">
        <h1>Registro de Usuario</h1>
        <form action="registro.php" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
            <button type="submit">Registrarse</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Agencia de Viajes. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
