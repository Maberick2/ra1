<?php
require 'conexion.php';
session_start();

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
            header("Location: index.php"); // Redireccionar a index.php al iniciar sesión
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #005f73;
            color: white;
            padding: 20px;
            text-align: center;
        }
        nav {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }
        .login-container {
            background-color: white;
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
            color: #005f73;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: #005f73; /* Color verde */
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #004f59; /* Color verde más oscuro */
        }
        .error {
            color: #ff4d4d; /* Color rojo para errores */
            margin-bottom: 15px;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            color: #555;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenido a la Agencia de Viajes</h1>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="buscar_vuelos.php">Buscar Vuelos</a>
            <a href="reservaciones.php">Ver Reservaciones</a>
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
