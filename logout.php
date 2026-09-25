<?php
session_start();

// Limpia todas las variables de sesión y destruye la sesión por completo
$_SESSION = [];
session_destroy();

header('Location: login.php');
exit;
