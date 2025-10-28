<?php
// Activar errores (solo en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conectar a la base de datos
require_once '../PHP/conexion_s21sec.php';
require_once '../PHP/conexion.php';

$query = "
  SELECT 
    c.nombre AS nombre_cliente,
    c.empresa,
    d.nombre_deseo,
    s.nombre_servicio,
    sol.fecha_solicitud,
    a.estado,
    sol.id_solicitud
  FROM Solicitudes sol
  INNER JOIN Clientes c ON sol.id_cliente = c.id_cliente
  INNER JOIN Deseo d ON sol.id_deseo = d.id_deseo
  LEFT JOIN Servicios s ON sol.id_servicio = s.id_servicio
  LEFT JOIN Asignaciones a ON sol.id_solicitud = a.id_solicitud
  WHERE a.estado = 'Pendiente'
  ORDER BY sol.fecha_solicitud DESC;
";

$resultado = mysqli_query($conexion, $query);
?>
