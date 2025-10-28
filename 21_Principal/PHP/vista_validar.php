<?php
session_start();

// Obtener mensaje de validación si existe
$mensaje = $_SESSION['mensaje_validacion'] ?? '';
$tipo_mensaje = $_SESSION['tipo_mensaje_validacion'] ?? '';

// Limpiar mensajes de sesión
unset($_SESSION['mensaje_validacion']);
unset($_SESSION['tipo_mensaje_validacion']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="..\IMG\favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="../CSS/validar1.css">
  <title>Validar Cuenta - s21sec</title>
</head>
<body>
  <div class="validation-container">
    <h2>Validar Cuenta</h2>
    <p>Introduce el código de verificación que hemos enviado a tu correo.</p>

    <?php if (!empty($mensaje)): ?>
      <div class="<?php echo $tipo_mensaje === 'exito' ? 'success-message' : 'error-message'; ?>" style="margin-bottom: 15px; color: <?php echo $tipo_mensaje === 'exito' ? 'green' : 'red'; ?>">
        <?php echo $mensaje; ?>
      </div>
    <?php endif; ?>

    <?php if ($tipo_mensaje !== 'exito'): ?>
      <form action="../PHP/validar.php" class="form" method="POST">
        <div class="input-group">
          <label for="codigo">Código de Validación</label>
          <input class="codigo" type="text" id="codigo" name="codigo" required>
        </div>
        <button type="submit" class="btn-validate">Validar Cuenta</button>
      </form>
    <?php else: ?>
      <a href="../PHP/vista_inicio_sesion.php" class="btn-validate" style="display:inline-block; margin-top: 20px;">Ir a iniciar sesión</a>
    <?php endif; ?>
  </div>
</body>
</html>
