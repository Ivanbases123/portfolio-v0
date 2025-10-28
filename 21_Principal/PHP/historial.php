<?php 
include 'get_historial.php'; 
include 'get_perfil.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../CSS/stylesDashboard.css" />
  <link rel="shortcut icon" href="..\IMG\favicon.png" type="image/x-icon">
  <title>Historial de Cambios</title>
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
        <h1>Historial de Cambios de Estado</h1>
      </header>
      <main class="main-content">
        <form method="GET" class="filter-form">
          <label for="estado">Estado:</label>
          <select name="estado" id="estado">
            <option value="">-- Todos --</option>
            <option value="Pendiente" <?= ($_GET['estado'] ?? '') == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
            <option value="En proceso" <?= ($_GET['estado'] ?? '') == 'En proceso' ? 'selected' : '' ?>>En proceso</option>
            <option value="Finalizado" <?= ($_GET['estado'] ?? '') == 'Finalizado' ? 'selected' : '' ?>>Finalizado</option>
          </select>

          <label for="fecha_inicio">Desde:</label>
          <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?= $_GET['fecha_inicio'] ?? '' ?>">

          <label for="fecha_fin">Hasta:</label>
          <input type="date" name="fecha_fin" id="fecha_fin" value="<?= $_GET['fecha_fin'] ?? '' ?>">

          <button type="submit">Filtrar</button>
        </form>

        <table border="1" cellpadding="5">
          <thead>
            <tr>
              <th>Cliente</th>
              <th>Departamento</th>
              <th>Servicio</th>
              <th>Estado anterior</th>
              <th>Estado nuevo</th>
              <th>Fecha de cambio</th>
              <th>Cambiado por</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($resultado && mysqli_num_rows($resultado) > 0) {
              while ($fila = mysqli_fetch_assoc($resultado)) {
                echo "<tr>
                        <td>{$fila['nombre_cliente']} {$fila['apellidos']}</td>
                        <td>" . ($fila['nombre_departamento'] ?? 'No asignado') . "</td>
                        <td>" . ($fila['nombre_servicio'] ?? 'No aplica') . "</td>
                        <td>{$fila['estado_anterior']}</td>
                        <td>{$fila['estado_nuevo']}</td>
                        <td>{$fila['fecha_cambio']}</td>
                        <td>{$fila['cambiado_por']}</td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='7'>No hay registros de historial.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </main>
      <?php mysqli_close($conexion); ?>
    </div>
  </div>
</body>
</html>
