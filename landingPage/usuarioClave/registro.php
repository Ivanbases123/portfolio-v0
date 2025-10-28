<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="styles_registro.css">
</head>
<body>
    <!-- Aquí empiezo a construir la parte visible de la página. -->
    <div class="registro-container">
        <!-- Creo un contenedor para el logo de la empresa. -->
        <div class="logo-container">
            <!-- Muestro el logo de la empresa usando una imagen. -->
            <img src="../LOGO_movil.png" alt="Logo de la empresa codevauld">
        </div>

        <!-- Pongo un título para indicar que esta es la página de registro de usuario. -->
        <h2>Registro de Usuario</h2>

        <!-- Añado un mensaje para informar al usuario que recibirá un código de verificación por correo. -->
        <p>Por favor, completa el formulario. Recibirás un código de verificación en tu correo.</p>

        <!-- Verifico si hay un mensaje de error que mostrar. -->
        <?php if (isset($_GET['error']) && $_GET['error'] == 'correo_existente'): ?>
            <!-- Si el correo ya está registrado, muestro un mensaje de error. -->
            <p class="mensaje-error">
                El correo ya está registrado.  
                <!-- Añado un enlace para que el usuario pueda iniciar sesión en lugar de registrarse. -->
                <a href='login.html'>Inicia sesión aquí</a>  
            </p>
        <?php endif; ?>

        <!-- Creo un formulario para que el usuario ingrese sus datos. -->
        <form action="enviodecorreo.php" method="POST">
            <!-- Añado una etiqueta y un campo para que el usuario ingrese su nombre. -->
            <label for="nombre_usuario">Nombre:</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required>

            <!-- Añado una etiqueta y un campo para que el usuario ingrese su correo electrónico. -->
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>

            <!-- Añado una etiqueta y un campo para que el usuario ingrese su contraseña. -->
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <!-- Añado un botón para que el usuario envíe el formulario y se registre. -->
            <button class="login-button" type="submit">Registrarse</button>
        </form>
    </div>
</body>
</html>


