<?php
// Activar errores (solo en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../PHP/conexion_s21sec.php';

// Obtener departamentos para el <select>
$query_deptos = "SELECT id_departamento, nombre_departamento FROM Departamentos";
$resultado_deptos = mysqli_query($conexion, $query_deptos);

// Captura de mensaje de error (si vuelve desde el backend con error)
$error = isset($_GET['error']) ? urldecode($_GET['error']) : '';
?>