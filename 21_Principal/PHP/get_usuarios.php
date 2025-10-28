<?php
// Activar errores (solo en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../PHP/conexion_s21sec.php';

$query = "
  SELECT u.id_usuario, u.nombre_usuario, u.email, u.rol, d.nombre_departamento
  FROM Usuarios u
  LEFT JOIN Departamentos d ON u.id_departamento = d.id_departamento
";

$resultado = mysqli_query($conexion, $query);

if (!$resultado) {
  echo "Error en la consulta: " . mysqli_error($conexion);
}
?>
