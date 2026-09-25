<?php
require_once __DIR__ . '/../includes/verificar_sesion.php';
require_once __DIR__ . '/../config/conexion.php';

// Mensaje de confirmación dejado por guardar.php / actualizar.php / cambiar_estado.php
$mensaje = $_SESSION['mensaje'] ?? null;
unset($_SESSION['mensaje']);

// --- Búsqueda por nombre, código o categoría --------------------------
$busqueda = trim($_GET['q'] ?? '');

$sql = "SELECT p.id_producto, p.codigo, p.nombre, p.precio,
               p.stock_actual, p.stock_minimo, p.estado,
               c.nombre AS categoria
        FROM producto p
        INNER JOIN categoria c ON c.id_categoria = p.id_categoria";

$parametros = [];

if ($busqueda !== '') {
    $sql .= " WHERE p.nombre LIKE :busqueda1
                 OR p.codigo LIKE :busqueda2
                 OR c.nombre LIKE :busqueda3";
    $comodin = '%' . $busqueda . '%';
    $parametros = [
        'busqueda1' => $comodin,
        'busqueda2' => $comodin,
        'busqueda3' => $comodin,
    ];
}

$sql .= " ORDER BY p.nombre ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($parametros);
$productos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - TecnoStock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../includes/navbar.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Productos</h2>
        <a href="nuevo.php" class="btn btn-success">+ Nuevo producto</a>
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="GET" action="listar.php" class="row g-2 mb-4">
        <div class="col-auto flex-grow-1">
            <input
                type="text"
                name="q"
                class="form-control"
                placeholder="Buscar por nombre, código o categoría..."
                value="<?= htmlspecialchars($busqueda) ?>"
            >
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-secondary">Buscar</button>
            <?php if ($busqueda !== ''): ?>
                <a href="listar.php" class="btn btn-outline-danger">Limpiar</a>
            <?php endif; ?>
        </div>
    </form>

    <?php if (empty($productos)): ?>
        <p class="text-muted">No se encontraron productos.</p>
    <?php else: ?>
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock actual</th>
                    <th>Stock mínimo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $p): ?>
                    <?php $stockBajo = $p['stock_actual'] < $p['stock_minimo']; ?>
                    <tr class="<?= $stockBajo ? 'table-danger' : '' ?>">
                        <td><?= htmlspecialchars($p['codigo']) ?></td>
                        <td><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= htmlspecialchars($p['categoria']) ?></td>
                        <td>$<?= number_format((float)$p['precio'], 0, ',', '.') ?></td>
                        <td>
                            <?= (int)$p['stock_actual'] ?>
                            <?php if ($stockBajo): ?>
                                <span class="badge bg-danger ms-1">Stock bajo</span>
                            <?php endif; ?>
                        </td>
                        <td><?= (int)$p['stock_minimo'] ?></td>
                        <td>
                            <?php if ($p['estado'] === 'activo'): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="editar.php?id=<?= (int)$p['id_producto'] ?>"
                               class="btn btn-sm btn-primary">
                                Editar
                            </a>

                            <form
                                action="cambiar_estado.php"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('¿Confirmas <?= $p['estado'] === 'activo' ? 'desactivar' : 'reactivar' ?> este producto?');"
                            >
                                <input type="hidden" name="id" value="<?= (int)$p['id_producto'] ?>">
                                <button type="submit"
                                        class="btn btn-sm <?= $p['estado'] === 'activo' ? 'btn-warning' : 'btn-outline-success' ?>">
                                    <?= $p['estado'] === 'activo' ? 'Desactivar' : 'Reactivar' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
