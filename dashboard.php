<?php


require_once __DIR__ . '/includes/verificar_sesion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel - TecnoStock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">TecnoStock</span>
        <div class="d-flex align-items-center">
            <span class="text-white me-3">
                Hola, <?= htmlspecialchars($_SESSION['nombre_usuario']) ?>
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Bienvenido al panel de TecnoStock</h2>
    <p class="text-muted">
        .
    </p>
</div>

</body>
</html>
