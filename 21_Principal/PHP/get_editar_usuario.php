<?php
// Activar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../PHP/conexion_s21sec.php';

$id_usuario = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_usuario <= 0) {
  die("ID de usuario inválido.");
}

// Obtener datos del usuario
$query_usuario = "SELECT * FROM Usuarios WHERE id_usuario = $id_usuario";
$resultado_usuario = mysqli_query($conexion, $query_usuario);

if (!$resultado_usuario || mysqli_num_rows($resultado_usuario) === 0) {
  die("Usuario no encontrado.");
}

$usuario = mysqli_fetch_assoc($resultado_usuario);

// Obtener departamentos
$query_deptos = "SELECT id_departamento, nombre_departamento FROM Departamentos";
$resultado_deptos = mysqli_query($conexion, $query_deptos);
?>
