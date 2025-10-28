<?php
// Activar errores (solo en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conectar a la base de datos
require_once '../PHP/conexion_s21sec.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "ID de solicitud no proporcionado o inválido.";
  exit;
}

$id_solicitud = intval($_GET['id']);

// Obtener información de la solicitud y el cliente
$query = "
  SELECT 
    c.empresa,
    c.nombre,
    c.apellidos,
    c.pais,
    c.ciudad,
    c.email,
    c.cargo,
    c.telefono,
    te.descripcion AS tamano_empresa,
    se.descripcion AS sector_empresa,
    d.nombre_deseo,
    s.nombre_servicio,
    sol.mensaje,
    sol.fecha_solicitud,
    a.estado,
    a.id_departamento,
    dep.nombre_departamento,
    a.fecha_asignacion,
    a.mensaje_asignacion
  FROM Solicitudes sol
  INNER JOIN Clientes c ON sol.id_cliente = c.id_cliente
  INNER JOIN TamanoEmpresa te ON c.id_tamano = te.id_tamano
  INNER JOIN SectorEmpresa se ON c.id_sector = se.id_sector
  INNER JOIN Deseo d ON sol.id_deseo = d.id_deseo
  LEFT JOIN Servicios s ON sol.id_servicio = s.id_servicio
  LEFT JOIN Asignaciones a ON sol.id_solicitud = a.id_solicitud
  LEFT JOIN Departamentos dep ON a.id_departamento = dep.id_departamento
  WHERE sol.id_solicitud = $id_solicitud
";

$resultado = mysqli_query($conexion, $query);
if (!$resultado || mysqli_num_rows($resultado) === 0) {
  echo "No se encontró la solicitud.";
  exit;
}

$data = mysqli_fetch_assoc($resultado);

// Asignación de datos a variables
$cliente = $data;
$solicitud = $data;
$asignacion = $data;
$deseo = $data['nombre_deseo'];
$servicio = $data['nombre_servicio'] ?? 'No especificado';
$tamanoempresa = ['descripcion' => $data['tamano_empresa']];
$sectorempresa = ['descripcion' => $data['sector_empresa']];
$departamento = $data['nombre_departamento'] ?? 'No asignado';

// Obtener historial de estados
$historialEstados = [];
$historial_query = "
  SELECT 
    he.estado_anterior, 
    he.estado_nuevo, 
    he.fecha_cambio AS fecha, 
    he.cambiado_por AS usuario
  FROM HistorialEstados he
  INNER JOIN Asignaciones a ON he.id_asignacion = a.id_asignacion
  WHERE a.id_solicitud = $id_solicitud
  ORDER BY he.fecha_cambio ASC
";

$historial_resultado = mysqli_query($conexion, $historial_query);
if ($historial_resultado && mysqli_num_rows($historial_resultado) > 0) {
  while ($row = mysqli_fetch_assoc($historial_resultado)) {
    $historialEstados[] = $row;
  }
}

// Obtener departamentos para el formulario de reasignación
$departamentos = [];
$dep_query = "SELECT id_departamento, nombre_departamento FROM Departamentos";
$dep_resultado = mysqli_query($conexion, $dep_query);
if ($dep_resultado && mysqli_num_rows($dep_resultado) > 0) {
  while ($row = mysqli_fetch_assoc($dep_resultado)) {
    $departamentos[] = $row;
  }
}
?>
