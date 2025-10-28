<?php
// Activar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a base de datos
require_once '../PHP/conexion_s21sec.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_solicitud = intval($_POST['id_solicitud'] ?? 0);
    $nuevo_estado = trim($_POST['nuevo_estado'] ?? '');
    $nuevo_departamento = intval($_POST['nuevo_departamento'] ?? 0);
    $mensaje = trim($_POST['respuesta'] ?? '');
    $usuario = "admin"; // Cambiar por $_SESSION['usuario'] si tienes sesión iniciada

    if (!$id_solicitud || !$nuevo_estado || !$nuevo_departamento) {
        die("Faltan datos para actualizar.");
    }

    $query_asignacion = "SELECT id_asignacion, estado FROM Asignaciones WHERE id_solicitud = $id_solicitud";
    $resultado_asignacion = mysqli_query($conexion, $query_asignacion);

    if (!$resultado_asignacion || mysqli_num_rows($resultado_asignacion) === 0) {
        die("No se encontró la asignación.");
    }

    $asignacion = mysqli_fetch_assoc($resultado_asignacion);
    $estado_anterior = mysqli_real_escape_string($conexion, $asignacion['estado']);
    $id_asignacion = intval($asignacion['id_asignacion']);
    $fecha_actual = date('Y-m-d H:i:s');

    $mensaje_escapado = mysqli_real_escape_string($conexion, $mensaje);
    $nuevo_estado_escapado = mysqli_real_escape_string($conexion, $nuevo_estado);
    $usuario_escapado = mysqli_real_escape_string($conexion, $usuario);

    $update_query = "
        UPDATE Asignaciones 
        SET 
            estado = '$nuevo_estado_escapado',
            id_departamento = $nuevo_departamento,
            mensaje_asignacion = '$mensaje_escapado',
            fecha_asignacion = '$fecha_actual'
        WHERE id_solicitud = $id_solicitud
    ";

    if (mysqli_query($conexion, $update_query)) {
        $insert_historial = "
            INSERT INTO HistorialEstados (
                id_asignacion, 
                estado_anterior, 
                estado_nuevo, 
                fecha_cambio, 
                cambiado_por
            ) VALUES (
                $id_asignacion,
                '$estado_anterior',
                '$nuevo_estado_escapado',
                '$fecha_actual',
                '$usuario_escapado'
            )
        ";
        mysqli_query($conexion, $insert_historial);

        header("Location: detalle_solicitud.php?id=$id_solicitud");
        exit();
    } else {
        echo "Error al actualizar la asignación: " . mysqli_error($conexion);
    }
} else {
    echo "Método de solicitud no permitido.";
}

mysqli_close($conexion);
?>
