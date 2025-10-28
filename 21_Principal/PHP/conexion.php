<?php
// Configuración para establecer la conexión con la base de datos
$servername = "localhost";  // Dirección del servidor, en este caso localhost
$username = "root";         // Nombre de usuario de MySQL
$password = "1234";             // Contraseña de MySQL
$dbname = "registro";  // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Función para generar claves aleatorias de 6 dígitos
function generarClave() {
    return rand(100000, 999999);  // Genera un número aleatorio entre 100000 y 999999
}

// Función para crear un correo con la plantilla que enviaremos al usuario
function generarCorreo($nombre, $clave){
    return "
    <p>Hola $nombre,</p>
    <p>Gracias por registrarte.¡Para validar utilize el codigo que te llego al correo!.</p>
    <p><strong>Código de validación:</strong> $clave</p>
    <p>Saludos,<br>El equipo de soporte.</p>
    ";
    
}
?>
