<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/21_Principal/PHP/conexion.php';
require $_SERVER['DOCUMENT_ROOT'] . '/21_Principal/php/vendor/autoload.php';

function redirigirConMensaje($mensaje, $tipo = 'error') {
    $_SESSION['mensaje_validacion'] = $mensaje;
    $_SESSION['tipo_mensaje_validacion'] = $tipo;
    header("Location: ../PHP/vista_validar.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['codigo'])) {
        redirigirConMensaje("Por favor, ingresa el código de validación.");
    }

    if (!isset($_SESSION['correo_validacion'])) {
        redirigirConMensaje("No se encontró un correo de validación en la sesión.");
    }

    $codigoIngresado = $_POST['codigo'];
    $correo = $_SESSION['correo_validacion'];

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ? AND clave_validacion = ?");
    $stmt->bind_param("ss", $correo, $codigoIngresado);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Activar cuenta
        $update = $conn->prepare("UPDATE usuarios SET estado = TRUE WHERE correo = ?");
        $update->bind_param("s", $correo);
        $update->execute();

        redirigirConMensaje(" Cuenta validada correctamente. Ya puedes iniciar sesión.", "exito");
    } else {
        // Código incorrecto: generar nuevo y reenviar
        $mensaje = " Código incorrecto. Se ha enviado un nuevo código.";
        $nueva_clave = generarClave();

        $update = $conn->prepare("UPDATE usuarios SET clave_validacion = ? WHERE correo = ?");
        $update->bind_param("ss", $nueva_clave, $correo);
        $update->execute();

        // Reenviar por correo
        $email = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $email->isSMTP();
            $email->Host = 'smtp.gmail.com';
            $email->SMTPAuth = true;
            $email->Username = 'tu_correo@gmail.com';
            $email->Password = 'contraseña_generada';
            $email->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $email->Port = 587;

            $email->setFrom('tu_correo@gmail.com', 'Soporte');
            $email->addAddress($correo);
            $email->Subject = 'Nuevo código de validación';
            $email->Body = generarCorreo($correo, $nueva_clave);
            $email->isHTML(true);
            $email->send();
        } catch (Exception $e) {
            $mensaje .= " Error al enviar nuevo código: " . $email->ErrorInfo;
        }

        redirigirConMensaje($mensaje);
    }
}
?>
