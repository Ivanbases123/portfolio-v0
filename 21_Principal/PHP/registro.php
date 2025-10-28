<?php
include $_SERVER['DOCUMENT_ROOT'] . '/21_Principal/PHP/conexion.php'; // Incluir la conexión con la base de datos y funciones
require $_SERVER['DOCUMENT_ROOT'] . '/21_Principal/php/vendor/autoload.php';


// Verificar si la solicitud es un POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Obtener los datos del formulario
    $nombre = $_POST['name'];
    $correo = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Verificar que las contraseñas coincidan antes de encriptarlas
    if ($password !== $confirm_password) {
        die("Las contraseñas no coinciden.");
    }

    // Encriptar la contraseña
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Generar la clave de validación
    $clave = generarClave();

    // Verificar si el correo ya está registrado en la base de datos antes de insertar
    $stmt_check = $conn->prepare("SELECT id FROM Usuarios WHERE correo = ?");
    $stmt_check->bind_param("s", $correo);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        echo "El correo electrónico ya está registrado. Por favor, usa otro.";
        exit();  // Detener la ejecución si el correo ya existe
    }

    // Insertar el usuario en la base de datos
    $stmt = $conn->prepare("INSERT INTO Usuarios (nombre_completo, correo, clave_validacion, password, estado) VALUES (?, ?, ?, ?, FALSE)");
    $stmt->bind_param("ssss", $nombre, $correo, $clave, $password_hashed);

    if ($stmt->execute()) {
        // Iniciar sesión y guardar el correo para la validación
        session_start();
        $_SESSION['correo_validacion'] = $correo;

        // Enviar el correo con la clave de validación
        $email = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $email->isSMTP();
            $email->Host = 'smtp.gmail.com';
            $email->SMTPAuth = true;
            $email->Username = 'alineitor165@gmail.com';  
            $email->Password = 'jbyj mpxc ijeq nuny';  
            $email->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $email->Port = 587;

            // Configuración del correo
            $email->setFrom('alineitor165@gmail.com', 'Soporte'); 
            $email->addAddress($correo);
            $email->Subject = 'Clave de validación';
            $email->Body = generarCorreo($nombre, $clave);
            $email->isHTML(true);

            // Enviar el correo
            if ($email->send()) {
                echo 'Correo de validación enviado. Revisa tu bandeja de entrada.';
            } else {
                echo 'Hubo un error al enviar el correo de validación.';
            }
        } catch (Exception $e) {
            echo 'Error al enviar el correo: ' . $email->ErrorInfo;
        }

        // Redirigir al usuario a la página de validación
        header("Location: ../PHP/vista_validar.php");
        exit();

    } else {
        echo 'Error al registrar el usuario. Por favor, intenta de nuevo.';
    }

    // Cerrar la conexión
    $stmt->close();
    $stmt_check->close();
}
?>
