<?php
// Inicio una sesión para poder guardar información que necesitaré más adelante.
session_start();

// Me conecto a la base de datos incluyendo un archivo que tiene los datos de conexión.
include '../conexion.php';

// Cargo una herramienta llamada PHPMailer que me ayudará a enviar correos electrónicos.
require './vendor/autoload.php'; // PHPMailer

// Uso las clases de PHPMailer para poder enviar correos.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Esta función genera un número aleatorio de seis dígitos que servirá como clave de validación.
function generarClave() {
    return rand(100000, 999999);
}

// Esta función crea el contenido del correo que voy a enviar al usuario.
function generarCorreo($nombre, $clave) {
    return "
    <html>
    <body>
        <p>Hola <strong>$nombre</strong>,</p>
        <p>Gracias por registrarte en Code Vault. Utiliza este código de validación para activar tu cuenta:</p>
        <h2>$clave</h2>
        <p>Si no realizaste este registro, puedes ignorar este mensaje.</p>
        <br>
        <p>Saludos,<br>Equipo de Code Vault.</p>
        <br>
        <div><img src='cid:logoimg' alt='Logo de la empresa Code Vault'></div>  
    </body>
    </html>";
}

// Esta función se encarga de enviar el correo de validación al usuario.
function enviarCorreoValidacion($correo, $nombre, $clave) {
    // Creo una nueva instancia de PHPMailer.
    $email = new PHPMailer(true);
    try {
        // Configuro PHPMailer para usar SMTP, que es un protocolo para enviar correos.
        $email->isSMTP();
        $email->Host = 'smtp.gmail.com'; // Uso el servidor de correo de Gmail.
        $email->SMTPAuth = true; // Habilito la autenticación.
        $email->Username = 'oivanaut125@gmail.com'; // Coloco mi dirección de correo.
        $email->Password = 'smjf wyla oyyd iyuw'; // Coloco mi contraseña de correo.
        $email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Uso un protocolo seguro.
        $email->Port = 587; // Configuro el puerto para enviar el correo.

        // Defino quién envía el correo y a quién va dirigido.
        $email->setFrom('oivanaut125@gmail.com', 'Soporte');
        $email->addAddress($correo);
        $email->Subject = "Clave de validación de usuario"; // Asunto del correo.
        
        // Agrego una imagen (el logo) al correo.
        $email->AddEmbeddedImage('../LOGO_movil.png', 'logoimg', 'LOGO_movil.png');
        $email->isHTML(true); // Indico que el correo es en formato HTML.
        $email->Body = generarCorreo($nombre, $clave); // Uso la función para generar el contenido.

        // Intento enviar el correo y devuelvo true si lo logro, o false si falla.
        return $email->send();
    } catch (Exception $e) {
        // Si algo sale mal, devuelvo false.
        return false;
    }
}

// Verifico si el formulario se envió usando el método POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtengo los datos que el usuario ingresó en el formulario.
    $nombre = $_POST['nombre_usuario'];
    $correo = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Protejo la contraseña.
    $clave = generarClave(); // Genero una clave de validación.

    // Guardo el correo del usuario en la sesión para usarlo más adelante.
    $_SESSION['correo_validacion'] = $correo;

    // Verifico si el correo ya está registrado en la base de datos.
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    // Si el correo ya existe, redirijo al usuario a la página de registro con un mensaje de error.
    if ($stmt->num_rows > 0) {
        header("Location: registro.php?error=correo_existente");
        exit();
    }

    // Cierro la consulta anterior.
    $stmt->close();

    // Preparo una consulta para insertar al nuevo usuario en la base de datos.
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, email, password, clave_asociada, estado) VALUES (?, ?, ?, ?, 0)");
    $stmt->bind_param("ssss", $nombre, $correo, $password, $clave);

    // Intento ejecutar la consulta para guardar al usuario.
    if ($stmt->execute()) {
        // Si se guarda correctamente, intento enviar el correo de validación.
        if (enviarCorreoValidacion($correo, $nombre, $clave)) {
            // Si el correo se envía, redirijo al usuario a la página de validación.
            header("Location: validacion.php");
        } else {
            // Si falla el envío del correo, muestro un mensaje de error.
            echo "Error al enviar el correo de validación.";
        }
    } else {
        // Si falla la inserción en la base de datos, muestro un mensaje de error.
        echo "Error al registrar el usuario.";
    }

    // Cierro la consulta.
    $stmt->close();
}
?>
