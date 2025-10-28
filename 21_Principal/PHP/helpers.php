<?php
// Este archivo ayuda a evitar duplicaciones y encapsula lógica
function getOrCreateId($conexion, $tabla, $campo, $valor, $campo_id) {
    // Buscar si existe
    $stmt = $conexion->prepare("SELECT {$campo_id} FROM {$tabla} WHERE {$campo} = ?");
    $stmt->bind_param("s", $valor);
    $stmt->execute();

    $id = null;
    $stmt->bind_result($id);
    if ($stmt->fetch()) {
        $stmt->close();
        return $id;
    }
    $stmt->close();

    // Insertar si no existe
    $stmt = $conexion->prepare("INSERT INTO {$tabla} ({$campo}) VALUES (?)");
    $stmt->bind_param("s", $valor);
    $stmt->execute();
    $new_id = $stmt->insert_id;
    $stmt->close();

    return $new_id;
}

?>