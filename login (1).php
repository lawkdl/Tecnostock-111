<?php
session_start();

// Si ya hay una sesión activa, no tiene sentido mostrar el login de nuevo
if (isset($_SESSION['id_usuario'])) {
    header('Location: dashboard.php');
    exit;
}

// Mensaje de error dejado por procesar_login.php (patrón Post/Redirect/Get)
$error = $_SESSION['error_login'] ?? null;
unset($_SESSION['error_login']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - TecnoStock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">
            <h3 class="text-center mb-1">TecnoStock</h3>
            <p class="text-center text-muted mb-4">Sistema de Inventario</p>

            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="procesar_login.php" method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" required autofocus>
                    <div class="invalid-feedback">Ingresa un correo electrónico válido.</div>
                </div>

                <div class="mb-3">
                    <label for="clave" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="clave" name="clave" required minlength="6">
                    <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres.</div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/validaciones.js"></script>
</body>
</html>
