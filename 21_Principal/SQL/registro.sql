CREATE DATABASE IF NOT EXISTS s21sec;
USE s21sec;
DROP database s21sec;
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(255) NOT NULL,
    correo VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    clave_validacion VARCHAR(6) NOT NULL,
    estado BOOLEAN DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS envios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    correo VARCHAR(255) NOT NULL,
    clave_enviada VARCHAR(6) NOT NULL,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
select * from usuarios;