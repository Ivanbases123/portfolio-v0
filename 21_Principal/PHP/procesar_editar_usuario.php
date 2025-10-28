<?php
// Activar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../PHP/conexion_s21sec.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_usuario = intval($_POST['id_usuario']);
  $nombre = mysqli_real_escape_string($conexion, $_POST['nombre_usuario']);
  $email = mysqli_real_escape_string($conexion, $_POST['email']);
  $rol = mysqli_real_escape_string($conexion, $_POST['rol']);
  $id_departamento = !empty($_POST['id_departamento']) ? intval($_POST['id_departamento']) : 'NULL';

  $password_actualizada = '';
  if (!empty($_POST['password'])) {
    $nueva_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $password_actualizada = ", password = '$nueva_password'";
  }

  $query = "
    UPDATE Usuarios
    SET nombre_usuario = '$nombre',
        email = '$email',
        rol = '$rol',
        id_departamento = " . ($id_departamento ?: 'NULL') . "
        $password_actualizada
    WHERE id_usuario = $id_usuario
  ";

  if (mysqli_query($conexion, $query)) {
    header("Location: usuarios.php");
    exit;
  } else {
    echo "Error al actualizar: " . mysqli_error($conexion);
  }
}
?>
