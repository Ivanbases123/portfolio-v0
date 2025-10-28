<?php 
include 'get_perfil.php';
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="../CSS/stylesDashboard.css" />
  <link rel="stylesheet" href="../CSS/styles_perfil.css" />
  <link rel="shortcut icon" href="..\IMG\favicon.png" type="image/x-icon">
  <title>Perfil del Administrador</title>
</head>
<body class="light">
<div class="dashboard">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-logo">
      <a href="pendientes.php"><img src="../IMG/logoazul.jpg" alt="Logo Empresa" class="logo"></a>
    </div>
    <nav>
      <a href="perfil.php"><img src="../IMG/avatar.svg" alt=""> <?php echo htmlspecialchars($nombre); ?></a>
      <a href="pendientes.php"><img src="../IMG/pendientes.svg"> Solicitudes pendientes</a>
      <a href="proceso.php"><img src="../IMG/proceso.svg"> Solicitudes en proceso</a>
      <a href="finalizado.php"><img src="../IMG/finalizada.svg">Solicitudes finalizadas</a>
      <a href="usuarios.php"><img src="../IMG/usuarios.svg"> Usuarios</a>
      <a href="historial.php"><img src="../IMG/historial.svg"> Historial / Auditoría</a>
      <a href="../PHP/logout.php" class="logout-link"><img src="../IMG/cerrar.svg"> Cerrar sesión</a>
    </nav>
  </aside>

  <!-- Main content -->
  <div class="main-wrapper">
    <header class="topbar">
      <h1>Perfil del Administrador</h1>
    </header>

    <main class="main-content">
      <div class="perfil-container">
        <div class="perfil-header">
          <img src="../IMG/avatar.svg" alt="Avatar">
          <h2><?php echo htmlspecialchars($nombre); ?></h2>
        </div>

        <div class="perfil-info">
          <!-- Info usuario -->
          <div class="info-box">
            <h3>Información básica</h3>
            <p><strong>Nombre completo:</strong> <?php echo htmlspecialchars($nombre); ?></p>
            <p><strong>Correo:</strong> <?php echo htmlspecialchars($correo); ?></p>
            <p><strong>Contraseña:</strong> *********</p>
          </div>

          <!-- Cambiar contraseña -->
            <div class="info-box">
              <h3>Cambiar contraseña</h3>

              <!-- Mostrar mensaje si existe -->
            <?php if (isset($_GET['mensaje'])): ?>
              <div class="alert">
                <?php
                  if ($_GET['mensaje'] === 'ok') {
                    echo "✅ Contraseña actualizada correctamente.";
                  } elseif ($_GET['mensaje'] === 'incorrecta') {
                    echo "❌ La contraseña actual es incorrecta.";
                  } elseif ($_GET['mensaje'] === 'no_coincide') {
                    echo "❌ Las nuevas contraseñas no coinciden.";
                  } else {
                    echo "Ocurrió un error inesperado.";
                  }
                ?>
              </div>
            <?php endif; ?>

            <form method="POST" action="cambiar_contrasena.php">
              <label for="contrasena_actual">Contraseña actual:</label>
              <input type="password" name="contrasena_actual" id="contrasena_actual" required />

              <label for="nueva_contrasena">Nueva contraseña:</label>
              <input type="password" name="nueva_contrasena" id="nueva_contrasena" required />

              <label for="confirmar_contrasena">Confirmar nueva contraseña:</label>
              <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" required />

              <button type="submit">Actualizar</button>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>
</body>
</html>
