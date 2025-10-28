<?php
session_start();
require_once '../PHP/conexion.php'; // Asegúrate de que la ruta es correcta

// Verifica que el usuario haya iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

// Usar correctamente la conexión $conn
$stmt = $conn->prepare("SELECT nombre_completo, correo FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($nombre, $correo);

if ($stmt->fetch()) {
    // Usuario encontrado, $nombre y $correo están disponibles
} else {
    // Usuario no encontrado, puedes manejar el error
    $nombre = "Desconocido";
    $correo = "Desconocido";
}

$stmt->close();
?>
