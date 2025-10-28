<?php
// Me conecto a la base de datos incluyendo un archivo que tiene los datos de conexión.
include '../conexion.php';

// Verifico si el formulario se envió usando el método POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtengo el correo electrónico que el usuario ingresó en el formulario.
    $correo = $_POST['email'];
    // Obtengo la contraseña que el usuario ingresó en el formulario.
    $password = $_POST['password'];

    // Preparo una consulta para buscar el estado de la cuenta del usuario en la base de datos.
    $stmt = $conn->prepare("SELECT estado FROM usuarios WHERE email = ?");
    // Asocio el correo electrónico ingresado con la consulta.
    $stmt->bind_param("s", $correo);
    // Ejecuto la consulta.
    $stmt->execute();
    // Guardo el resultado de la consulta para poder trabajar con él.
    $stmt->store_result();
    // Asocio el resultado de la consulta (el estado del usuario) a una variable.
    $stmt->bind_result($estado);
    // Obtengo el resultado de la consulta.
    $stmt->fetch();

    // Verifico si se encontró algún usuario con ese correo electrónico.
    if ($stmt->num_rows > 0) {
        // Si el estado del usuario es 1, significa que su cuenta está activada.
        if ($estado == 1) {
            // Muestro un mensaje de éxito.
            echo "Inicio de sesión exitoso.";
            // Aquí podrías redirigir al usuario a la página principal de su cuenta.
        } else {
            // Si el estado no es 1, significa que la cuenta no está activada.
            echo "Tu cuenta no está activada. Verifica tu correo.";
        }
    } else {
        // Si no se encontró ningún usuario con ese correo, muestro un mensaje de error.
        echo "Usuario no encontrado.";
    }
}
?>