<?php 
include 'get_usuarios.php'; 
include 'get_perfil.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../CSS/stylesDashboard.css" />
  <link rel="shortcut icon" href="..\IMG\favicon.png" type="image/x-icon">
  <title>Usuarios Registrados</title>
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
        <h1>Usuarios Registrados</h1>
      </header>
      <main class="main-content">
        <a href="nuevo_usuario.php" class="btn btn-new responsive-icon">➕ <span class="btn-text">Nuevo Usuario</span></a>

        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Departamento</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($resultado && mysqli_num_rows($resultado) > 0) {
                while ($fila = mysqli_fetch_assoc($resultado)) {
                  echo "<tr border='none'>
                          <td>{$fila['nombre_usuario']}</td>
                          <td>{$fila['email']}</td>
                          <td>{$fila['rol']}</td>
                          <td>" . ($fila['nombre_departamento'] ?? 'Sin asignar') . "</td>
                          <td class='action-btns'>
                            <a href='editar_usuario.php?id={$fila['id_usuario']}' class='btn btn-edit responsive-icon'>✏️ <span class='btn-text'>Editar</span></a>
                            <a href='eliminar_usuario.php?id={$fila['id_usuario']}' class='btn btn-delete responsive-icon' onclick=\"return confirm('¿Seguro que quieres eliminar este usuario?');\">🗑️ <span class='btn-text'>Eliminar</span></a>
                          </td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='5'>No hay usuarios registrados.</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </main>
      <?php mysqli_close($conexion); ?>
    </div>
  </div>
</body>
</html>
