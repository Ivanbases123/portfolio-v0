<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/inicio_sesion1.css">
    <link rel="shortcut icon" href="..\IMG\favicon.png" type="image/x-icon">
    <title>Iniciar Sesión - S21Sec</title>
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>

        <?php if (!empty($mensaje_error)): ?>
            <div class="error-message" style="color: red; margin-bottom: 15px;">
                <?php echo htmlspecialchars($mensaje_error); ?>
            </div>
        <?php endif; ?>

        <form action="inicio_sesion.php" method="POST">
            <div class="input-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Iniciar Sesión</button>
        </form>

        <p class="register-link">
            ¿No tienes cuenta? <a href="../HTML/registro.html">Regístrate aquí</a>
        </p>
    </div>
</body>
</html>
