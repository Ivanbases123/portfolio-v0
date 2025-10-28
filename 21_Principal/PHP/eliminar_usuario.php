<?php
// Activar errores (solo en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../PHP/conexion_s21sec.php';
session_start();

// Validar ID de usuario
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die("ID de usuario inválido.");
}

$id_usuario = intval($_GET['id']);

// Prevención básica: evitar que el usuario se elimine a sí mismo (opcional)
// session_start();
// if ($_SESSION['id_usuario'] == $id_usuario) {
//   die("No puedes eliminar tu propia cuenta.");
// }

// Ejecutar la eliminación
$query = "DELETE FROM Usuarios WHERE id_usuario = $id_usuario";

if (mysqli_query($conexion, $query)) {
  header("Location: usuarios.php");
  exit;
} else {
  echo "Error al eliminar usuario: " . mysqli_error($conexion);
}
?>
