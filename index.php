<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agencia de Viajes</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos generales */
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Open Sans', sans-serif;
            background: url('https://2.bp.blogspot.com/-4zTGeP6ktmw/UaAIttq6PiI/AAAAAAAByik/iC8r8TjY0PE/s1600/towards-the-sunset-1920x1200-wallpaper-amanecer-visto-desde-un-avi%C3%B3n-vistas-a%C3%A9rea.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #03001e;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        /* Container principal */
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(34, 145, 38, 0.2);
        }

        /* Estilos de la cabecera */
        header {
            background-color: rgba(3, 0, 30, 0.9);
            color: #ec38bc;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }

        nav {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        nav a {
            color: #ec38bc;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }

        section {
            padding: 20px;
            margin: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #7303c0;
            margin-bottom: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin: 0 auto;
            max-width: 1200px;
        }

        .destino {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            background-color: #ffffff;
            text-align: center;
        }

        .destino:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .destino img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .destino h3 {
            margin: 10px;
            color: #005f73;
        }

        .destino p {
            margin: 0 10px 10px;
            color: #333;
        }

        /* Estilo del footer */
        .footer {
            background-color: rgba(3, 0, 30, 0.9);
            padding: 20px;
            text-align: center;
            color: #ec38bc;
            border-top: 2px solid #ec38bc;
            margin-top: auto;
        }

        .footer p {
            margin: 0;
            font-size: 16px;
            color: #ec38bc;
        }

        .footer a {
            color: #ec38bc;
            text-decoration: underline;
        }

        .footer a:hover {
            color: #7303c0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenido a la Agencia de Viajes</h1>
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

    <section>
        <h2>Explora nuestros destinos populares</h2>
        <div class="grid">
            <!-- Contenido de destinos -->
            <div class="destino">
                <img src="https://caribemexicano.travel/simpleview/image/upload/c_fill,h_900,q_75,w_1700/v1/clients/quintanaroo/_75c90302-4043-49a2-a37f-e1442fb47819.1399911722-f77ae8489dab838370d4fce9b0ade69d54b65beb51fa52ce26e1b7c02e90c4d7-d_640.jpg" alt="Playa del Carmen">
                <h3>Playa del Carmen</h3>
                <p>Disfruta de hermosas playas, arrecifes de coral y una vibrante vida nocturna.</p>
            </div>
            <div class="destino">
                <img src="https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0" alt="Montañas Rocosas">
                <h3>Montañas Rocosas</h3>
                <p>Un destino ideal para los amantes del senderismo, el esquí y la naturaleza.</p>
            </div>
            <div class="destino">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b3/Catedral_Metropolitana_de_la_Ciudad_de_M%C3%A9xico_1.jpg/1920px-Catedral_Metropolitana_de_la_Ciudad_de_M%C3%A9xico_1.jpg" alt="Ciudad de México">
                <h3>Ciudad de México</h3>
                <p>Descubre la rica historia, cultura y gastronomía de la capital mexicana.</p>
            </div>
            <div class="destino">
                <img src="https://www.grecotour.com/modules/advancedcms/uploads/AdvancedCmsBlockImageSlider/santorini-grecia-1200x600-00-oia-ia.jpg" alt="Santorini, Grecia">
                <h3>Santorini, Grecia</h3>
                <p>Famoso por sus impresionantes puestas de sol y casas blancas con cúpulas azules.</p>
            </div>
            <div class="destino">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/71/Shibuya_10pm_%2849785239871%29.jpg/1920px-Shibuya_10pm_%2849785239871%29.jpg" alt="Tokio, Japón">
                <h3>Tokyo, Japón</h3>
                <p>Una mezcla vibrante de tradición y modernidad, cultura y tecnología.</p>
            </div>
            <div class="destino">
                <img src="https://images.unsplash.com/photo-1516802273409-68526ee1bdd6" alt="Maui, Hawaii">
                <h3>Maui, Hawaii</h3>
                <p>Playas de arena dorada, volcanes y un ambiente relajado en la isla.</p>
            </div>
            <div class="destino">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/La_Tour_Eiffel_vue_de_la_Tour_Saint-Jacques%2C_Paris_ao%C3%BBt_2014_%282%29.jpg/1920px-La_Tour_Eiffel_vue_de_la_Tour_Saint-Jacques%2C_Paris_ao%C3%BBt_2014_%282%29.jpg" alt="París, Francia">
                <h3>París, Francia</h3>
                <p>La ciudad del amor, famosa por la Torre Eiffel y su deliciosa gastronomía.</p>
            </div>
            <div class="destino">
                <img src="https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0" alt="Barcelona, España">
                <h3>Barcelona, España</h3>
                <p>Arquitectura única, playas y una vibrante cultura mediterránea.</p>
            </div>
        </div>
    </section>

    <footer class="footer">
        <p>© 2024 Agencia de Viajes. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
