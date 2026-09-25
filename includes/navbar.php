<?php
/**
 * navbar.php
 * Menú superior reutilizable. Se incluye en cada página protegida,
 * después de verificar_sesion.php (necesita que $_SESSION y
 * BASE_URL ya existan).
 */
?>
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= BASE_URL ?>/dashboard.php">TecnoStock</a>
        <div class="d-flex align-items-center">
            <a href="<?= BASE_URL ?>/productos/listar.php" class="btn btn-sm btn-outline-light me-2">
                Productos
            </a>
            <span class="text-white me-3">
                Hola, <?= htmlspecialchars($_SESSION['nombre_usuario'] ?? '') ?>
            </span>
            <a href="<?= BASE_URL ?>/logout.php" class="btn btn-outline-light btn-sm">
                Cerrar sesión
            </a>
        </div>
    </div>
</nav>
