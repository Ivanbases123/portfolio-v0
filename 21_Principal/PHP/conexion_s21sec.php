<?php
// Configuración de conexión
$servidor = "localhost";
$usuario = "root";
$contrasena = "1234";
$basedatos = "s21sec";

// Crear conexión
$conexion = mysqli_connect($servidor, $usuario, $contrasena, $basedatos);

// Verificar conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

?>