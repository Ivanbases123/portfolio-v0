<?php
include $_SERVER['DOCUMENT_ROOT'] . '/21_Principal/PHP/conexion.php';
session_start();

$mensaje_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, nombre_completo, password FROM Usuarios WHERE correo = ? AND estado = TRUE");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        if (password_verify($password, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];
            header("Location: welcome.php");
            exit();
        } else {
            $mensaje_error = "Contraseña incorrecta.";
        }
    } else {
        $mensaje_error = "Usuario no encontrado o cuenta no validada.";
    }

    $stmt->close();
}
// Cargar la vista del formulario
include 'vista_inicio_sesion.php';

?>