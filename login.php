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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    $stmt = $conn->prepare("SELECT id, contrasena, nombre FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_contrasena, $nombre_usuario);
        $stmt->fetch();

        if (password_verify($contrasena, $hashed_contrasena)) {
            $_SESSION['id_usuario'] = $id;
            $_SESSION['nombre_usuario'] = $nombre_usuario; 
            header("Location: buscar_vuelos.php"); // Redireccionar a index.php al iniciar sesión
            exit;
        } else {
            echo "<div class='error'>Contraseña incorrecta.</div>";
        }
    } else {
        echo "<div class='error'>Correo no registrado.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
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

        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: auto;
            text-align: center;
            margin-top: 50px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #7303c0;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #ec38bc;
            font-weight: bold;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 2px solid #7303c0;
            border-radius: 8px;
            background: #fdeff9;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            background-color: #ec38bc;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #7303c0;
        }

        .error {
            color: #ff4d4d; /* Color rojo para errores */
            margin-bottom: 15px;
        }

        header {
            text-align: center; /* Centrar el texto del encabezado */
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

        

        footer {
            background-color: rgba(3, 0, 30, 0.9);
            padding: 20px;
            text-align: center;
            color: #ec38bc;
            border-top: 2px solid #ec38bc;
            margin-top: auto;  /* Esto empuja el footer al final de la página */
        }

        footer p {
            margin: 0;
            font-size: 16px;
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

    <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <form action="login.php" method="post">
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
    <footer>
        <p>&copy; 2024 Agencia de Viajes. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

