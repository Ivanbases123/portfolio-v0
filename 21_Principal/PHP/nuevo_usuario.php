  <?php 
  include 'get_nuevo_usuario.php'; 
  include 'get_perfil.php';
  ?>
  <!DOCTYPE html>
  <html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../CSS/stylesDashboard.css" />
    <link rel="stylesheet" href="../CSS/styles_nuevo_usuario.css" />
    <link rel="shortcut icon" href="..\IMG\favicon.png" type="image/x-icon">
    <title>Nuevo Usuario</title>
  </head>
  <body class="light">
    <div class="dashboard">
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

      <div class="main-wrapper">
        <header class="topbar">
          <h1>Registrar Nuevo Usuario</h1>
        </header>
        <main class="main-content">
          <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
          <?php endif; ?>

          <form method="POST" action="procesar_nuevo_usuario.php" class="formulario-usuario">
            <label for="nombre_usuario">Nombre:</label>
            <input type="text" name="nombre_usuario" id="nombre_usuario" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>

            <label for="rol">Rol:</label>
            <select name="rol" id="rol" required>
              <option value="Administrador">Administrador</option>
              <option value="Departamento">Departamento</option>
            </select>

            <label for="id_departamento">Departamento:</label>
            <select name="id_departamento" id="id_departamento">
              <option value="">-- Seleccionar --</option>
              <?php while ($row = mysqli_fetch_assoc($resultado_deptos)): ?>
                <option value="<?php echo $row['id_departamento']; ?>">
                  <?php echo $row['nombre_departamento']; ?>
                </option>
              <?php endwhile; ?>
            </select>

            <button type="submit" class="btn btn-add">Guardar Usuario</button>
          </form>
        </main>
        <?php mysqli_close($conexion); ?>
      </div>
    </div>
  </body>
  </html>
