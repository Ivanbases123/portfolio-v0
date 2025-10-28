<?php
// Mostrar errores (desactivar en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conexión y helpers
require_once '../PHP/conexion_s21sec.php';
require_once '../PHP/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Escapar y limpiar datos del formulario
    $empresa = trim($_POST['empresa']);
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $pais = trim($_POST['pais']);
    $ciudad = trim($_POST['ciudad']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $cargo = trim($_POST['cargo']);
    $tamano_empresa = $_POST['tamano_empresa'];
    $sector_empresa = $_POST['sector_empresa'];
    $deseo = $_POST['deseo'];
    $informacion = $_POST['informacion'] ?? null;
    $mensaje = $_POST['mensaje'] ?? null;

    // Paso 1: Insertar o buscar id_tamano
    $id_tamano = getOrCreateId($conexion, 'TamanoEmpresa', 'descripcion', $tamano_empresa, 'id_tamano');

    // Paso 2: Insertar o buscar id_sector
    $id_sector = getOrCreateId($conexion, 'SectorEmpresa', 'descripcion', $sector_empresa, 'id_sector');

    // Paso 3: Insertar cliente
    $stmt_cliente = $conexion->prepare("
        INSERT INTO Clientes (empresa, nombre, apellidos, pais, ciudad, email, telefono, cargo, id_tamano, id_sector)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt_cliente->bind_param("ssssssssii", $empresa, $nombre, $apellidos, $pais, $ciudad, $email, $telefono, $cargo, $id_tamano, $id_sector);
    $stmt_cliente->execute();
    $id_cliente = $stmt_cliente->insert_id;
    $stmt_cliente->close();

    // Paso 4: Insertar o buscar deseo
    $id_deseo = getOrCreateId($conexion, 'Deseo', 'nombre_deseo', $deseo, 'id_deseo');

    // Paso 5: Si hay información adicional, obtener ID del servicio
    $id_servicio = null;
    if (!empty($informacion)) {
        $id_servicio = getOrCreateId($conexion, 'Servicios', 'nombre_servicio', $informacion, 'id_servicio');
    }

    // Paso 6: Insertar solicitud
    $stmt_sol = $conexion->prepare("
        INSERT INTO Solicitudes (id_cliente, id_deseo, id_servicio, mensaje)
        VALUES (?, ?, ?, ?)
    ");
    $stmt_sol->bind_param("iiis", $id_cliente, $id_deseo, $id_servicio, $mensaje);
    $stmt_sol->execute();
    $id_solicitud = $stmt_sol->insert_id;
    $stmt_sol->close();

    // Paso 7: Insertar asignación automática
    $id_departamento = 1;
    $stmt_asig = $conexion->prepare("
        INSERT INTO Asignaciones (id_solicitud, id_departamento)
        VALUES (?, ?)
    ");
    $stmt_asig->bind_param("ii", $id_solicitud, $id_departamento);
    $stmt_asig->execute();
    $stmt_asig->close();

    // Redirigir de vuelta al formulario (puedes agregar un query param como ?success=1 si quieres)
    header('Location: ../HTML/S21sec_contacto.html?success=1');
    exit;

} else {
    // Si el método no es POST, redirigir también (por seguridad)
    header('Location: ../HTML/S21sec_contacto.html?success=1');
    exit;
}
?>
