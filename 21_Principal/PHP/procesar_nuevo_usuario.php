<?php
// Activar errores (solo en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../PHP/conexion_s21sec.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = mysqli_real_escape_string($conexion, $_POST['nombre_usuario']);
  $email = mysqli_real_escape_string($conexion, $_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $rol = mysqli_real_escape_string($conexion, $_POST['rol']);
  $id_departamento = !empty($_POST['id_departamento']) ? intval($_POST['id_departamento']) : 'NULL';

  $insert_query = "
    INSERT INTO Usuarios (nombre_usuario, email, password, rol, id_departamento)
    VALUES ('$nombre', '$email', '$password', '$rol', " . ($id_departamento ?: 'NULL') . ")
  ";

  if (mysqli_query($conexion, $insert_query)) {
    header("Location: usuarios.php");
    exit;
  } else {
    $error = "Error al registrar el usuario: " . mysqli_error($conexion);
    header("Location: nuevo_usuario.php?error=" . urlencode($error));
    exit;
  }
}
?>
