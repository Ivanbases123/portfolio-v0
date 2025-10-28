<?php
session_start();
include '../conexion.php';
require './vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function generarClave() {
    return rand(100000, 999999);
}

function enviarCorreoValidacion($correo, $nombre, $clave) {
    $email = new PHPMailer(true);
    try {
        $email->isSMTP();
        $email->Host = 'smtp.gmail.com';
        $email->SMTPAuth = true;
        $email->Username = 'oivanaut125@gmail.com';
        $email->Password = 'smjf wyla oyyd iyuw';
        $email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $email->Port = 587;

        $email->setFrom('oivanaut125@gmail.com', 'Soporte');
        $email->addAddress($correo);
        $email->Subject = "Reenvío de código de validación";

        // Adjuntar imagen del logo
        $email->AddEmbeddedImage('../LOGO_movil.png', 'logoimg', 'LOGO_movil.png');

        // Cuerpo del correo en HTML
        $email->isHTML(true);
        $email->Body = "
        <html>
        <body>
            <p>Hola <strong>$nombre</strong>,</p>
            <p>Solicitaste un nuevo código de validación. Aquí tienes tu código:</p>
            <h2>$clave</h2>
            <p>Si no realizaste esta solicitud, ignora este mensaje.</p>
            <br>
            <p>Saludos,<br>Equipo de Code Vault.</p>
            <br>
            <div><img src='cid:logoimg' alt='Logo de Code Vault'></div>
        </body>
        </html>";

        return $email->send();
    } catch (Exception $e) {
        return false;
    }
}

// Verificar si el correo está en sesión
if (!isset($_SESSION['correo_validacion'])) {
    die("Error: No se especificó un correo.");
}

$correo = $_SESSION['correo_validacion'];

// Verificar si el correo está registrado
$stmt = $conn->prepare("SELECT nombre_usuario, clave_asociada, TIMESTAMPDIFF(MINUTE, fecha_clave, NOW()) AS minutos 
                        FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($nombre, $clave_actual, $minutos);
$stmt->fetch();
$stmt->close();

if ($nombre) {
    // Si el código es reciente (menos de 10 minutos), reutilizarlo
    if ($minutos !== null && $minutos <= 10) {
        $clave_nueva = $clave_actual;
    } else {
        // Si es antiguo, generar uno nuevo y actualizar la base de datos
        $clave_nueva = generarClave();
        $updateClave = $conn->prepare("UPDATE usuarios SET clave_asociada = ?, fecha_clave = NOW() WHERE email = ?");
        $updateClave->bind_param("ss", $clave_nueva, $correo);
        $updateClave->execute();
        $updateClave->close();
    }

    // Enviar el correo con el código
    if (enviarCorreoValidacion($correo, $nombre, $clave_nueva)) {
        echo "Se ha enviado un nuevo código a tu correo.";
    } else {
        echo "Error al enviar el correo.";
    }
} else {
    echo "El correo no está registrado.";
}
?>
