CREATE DATABASE IF NOT EXISTS vuelos;
USE vuelos;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS vuelos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    origen VARCHAR(100) NOT NULL,
    destino VARCHAR(100) NOT NULL,
    fecha_salida DATE NOT NULL,
    fecha_llegada DATE NOT NULL,
    precio DECIMAL(10, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS reservaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_vuelo INT NOT NULL,
    fecha_reservacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_vuelo) REFERENCES vuelos(id) ON DELETE CASCADE
);

INSERT INTO vuelos (origen, destino, fecha_salida, fecha_llegada, precio) VALUES
('Ciudad de México', 'Nueva York', '2024-10-01', '2024-10-02', 350.00),
('Bogotá', 'Miami', '2024-11-15', '2024-11-16', 220.00),
('Lima', 'Buenos Aires', '2024-12-10', '2024-12-11', 300.00),
('Madrid', 'París', '2024-09-25', '2024-09-25', 120.00),
('Tokio', 'Seúl', '2024-10-05', '2024-10-06', 450.00),
('Londres', 'Ámsterdam', '2024-11-20', '2024-11-21', 180.00);

