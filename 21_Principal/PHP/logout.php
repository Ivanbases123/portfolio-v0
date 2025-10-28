<?php
session_start();
session_unset(); // Limpia todas las variables de sesión
session_destroy(); // Destruye la sesión actual

// Redirige al usuario a la página principal
header("Location: ../HTML/S21sec.html");
exit;


