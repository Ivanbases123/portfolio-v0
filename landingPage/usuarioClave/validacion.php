<?php
// Inicio una sesión para poder acceder a la información guardada en ella.
session_start();

// Me conecto a la base de datos incluyendo un archivo que tiene los datos de conexión.
include '../conexion.php';

// Inicializo una variable para guardar mensajes que se mostrarán al usuario.
$mensaje = "";

// Verifico si el correo de validación está guardado en la sesión.
if (!isset($_SESSION['correo_validacion'])) {
    // Si no está, muestro un mensaje de error y detengo la ejecución.
    die("Error: No se especificó un correo.");
}

// Obtengo el correo electrónico guardado en la sesión.
$correo = $_SESSION['correo_validacion'];

// Verifico si el formulario se envió usando el método POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtengo el código de validación que el usuario ingresó en el formulario.
    $clave = $_POST['clave'];

    // Preparo una consulta para verificar si el código ingresado es correcto.
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? AND clave_asociada = ?");
    // Asocio el correo y el código ingresado con la consulta.
    $stmt->bind_param("si", $correo, $clave);
    // Ejecuto la consulta.
    $stmt->execute();
    // Guardo el resultado de la consulta para poder trabajar con él.
    $stmt->store_result();

    // Verifico si se encontró algún registro con el correo y el código proporcionados.
    if ($stmt->num_rows > 0) {
        // Si el código es correcto, preparo una consulta para activar la cuenta del usuario.
        $update = $conn->prepare("UPDATE usuarios SET estado = 1 WHERE email = ?");
        // Asocio el correo con la consulta.
        $update->bind_param("s", $correo);
        // Ejecuto la consulta para activar la cuenta.
        $update->execute();
        
        // Limpio la sesión eliminando el correo de validación, ya que ya no lo necesito.
        unset($_SESSION['correo_validacion']);

        // Redirijo al usuario a la página de inicio de sesión.
        header("Location: login.html");
        exit(); // Detengo la ejecución del script.
    } else {
        // Si el código es incorrecto, guardo un mensaje de error para mostrar al usuario.
        $mensaje = "Código incorrecto. Por favor, inténtalo de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Configuro el documento HTML con el idioma español y la codificación UTF-8. -->
    <meta charset="UTF-8">
    <!-- Ajusto la vista para que sea responsive en dispositivos móviles. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Defino el título de la página. -->
    <title>Validación de Cuenta</title>
    <!-- Enlazo el archivo de estilos CSS para darle diseño a la página. -->
    <link rel="stylesheet" href="styles_validacion.css">
</head>
<body>
<!-- Creo un contenedor para el contenido de la página. -->
<div class="validation-container">
    <!-- Contenedor para el logo de la empresa. -->
    <div class="logo-container">
        <!-- Muestro el logo de la empresa. -->
        <img src="../LOGO_movil.png" alt="Logo de la empresa Code Vault">
    </div>
    <!-- Título de la página. -->
    <h2>Validación de Cuenta</h2>

    <!-- Mensaje informativo para el usuario. -->
    <p style="color: #555; font-size: 14px; text-align: center;">
        Revisa tu bandeja de entrada o carpeta de spam. Este código es necesario para activar tu cuenta.
        Hemos enviado un código de validación a tu correo electrónico.
    </p>

    <!-- Verifico si hay un mensaje de error para mostrar. -->
    <?php if (!empty($mensaje)) { ?>
        <!-- Muestro el mensaje de error en color rojo. -->
        <p style="color: red;"><?php echo $mensaje; ?></p>
    <?php } ?>

    <!-- Formulario para que el usuario ingrese el código de validación. -->
    <form action="validacion.php" method="POST">
        <!-- Etiqueta y campo para ingresar el código. -->
        <label for="clave">Código de Validación:</label>
        <input type="text" name="clave" required>

        <!-- Botón para enviar el formulario. -->
        <button type="submit">Validar Cuenta</button>
    </form>
</div>
</body>
</html>