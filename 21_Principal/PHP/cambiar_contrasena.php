<?php

// Activar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../PHP/conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: iniciar_sesion.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];
$contrasena_actual = $_POST['contrasena_actual'] ?? '';
$nueva_contrasena = $_POST['nueva_contrasena'] ?? '';
$confirmar_contrasena = $_POST['confirmar_contrasena'] ?? '';

// Validar campos vacíos
if (empty($contrasena_actual) || empty($nueva_contrasena) || empty($confirmar_contrasena)) {
    header("Location: perfil.php?mensaje=error");
    exit();
}

// Verificar que las nuevas contraseñas coincidan
if ($nueva_contrasena !== $confirmar_contrasena) {
    header("Location: perfil.php?mensaje=no_coincide");
    exit();
}

// Obtener el hash actual de la base de datos
$stmt = $conn->prepare("SELECT password FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($hash_actual);
$stmt->fetch();
$stmt->close();

// Verificar la contraseña actual
if (!password_verify($contrasena_actual, $hash_actual)) {
    header("Location: perfil.php?mensaje=incorrecta");
    exit();
}

// Hashear nueva contraseña
$nuevo_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

// Actualizar en la base de datos
$update = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
$update->bind_param("si", $nuevo_hash, $id_usuario);

if ($update->execute()) {
    header("Location: perfil.php?mensaje=ok");
} else {
    header("Location: perfil.php?mensaje=error");
}

$update->close();
$conn->close();
?>
