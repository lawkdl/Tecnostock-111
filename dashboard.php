<?php
// Esta línea es la que realmente "protege" la página: si no hay sesión,
// verificar_sesion.php redirige a login.php y detiene la ejecución aquí.
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

<?php require __DIR__ . '/includes/navbar.php'; ?>

<div class="container">
    <h2>Bienvenido al panel de TecnoStock</h2>
    <p class="text-muted mb-4">
        Desde aquí administras el catálogo de productos y el control de
        existencias.
    </p>

    <a href="productos/listar.php" class="btn btn-primary">
        Ir al listado de productos
    </a>
</div>

</body>
</html>
