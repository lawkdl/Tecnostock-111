<?php
require_once __DIR__ . '/../includes/verificar_sesion.php';
require_once __DIR__ . '/../config/conexion.php';

$id = $_GET['id'] ?? null;

if (!ctype_digit((string)$id)) {
    header('Location: listar.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM producto WHERE id_producto = :id');
$stmt->execute(['id' => $id]);
$producto = $stmt->fetch();

if (!$producto) {
    $_SESSION['mensaje'] = 'El producto que intentas editar no existe.';
    header('Location: listar.php');
    exit;
}

$categorias = $pdo->query(
    "SELECT id_categoria, nombre FROM categoria WHERE estado = 'activo' ORDER BY nombre"
)->fetchAll();

$error = $_SESSION['error_producto'] ?? null;
unset($_SESSION['error_producto']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar producto - TecnoStock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../includes/navbar.php'; ?>

<div class="container">
    <h2 class="mb-4">Editar producto</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="actualizar.php" method="POST" class="needs-validation" novalidate style="max-width: 600px;">
        <input type="hidden" name="id_producto" value="<?= (int)$producto['id_producto'] ?>">

        <div class="mb-3">
            <label class="form-label">Código</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($producto['codigo']) ?>" disabled>
            <div class="form-text">El código no se puede modificar una vez creado el producto.</div>
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($producto['nombre']) ?>" disabled>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio"
                       value="<?= htmlspecialchars($producto['precio']) ?>" required min="0" step="1">
                <div class="invalid-feedback">Debe ser un número mayor o igual a 0.</div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="stock_actual" class="form-label">Stock actual</label>
                <input type="number" class="form-control" id="stock_actual" name="stock_actual"
                       value="<?= htmlspecialchars($producto['stock_actual']) ?>" required min="0" step="1">
                <div class="invalid-feedback">Debe ser un número entero mayor o igual a 0.</div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="stock_minimo" class="form-label">Stock mínimo</label>
                <input type="number" class="form-control" id="stock_minimo" name="stock_minimo"
                       value="<?= htmlspecialchars($producto['stock_minimo']) ?>" required min="0" step="1">
                <div class="invalid-feedback">Debe ser un número entero mayor o igual a 0.</div>
            </div>
        </div>

        <div class="mb-3">
            <label for="id_categoria" class="form-label">Categoría</label>
            <select class="form-select" id="id_categoria" name="id_categoria" required>
                <?php foreach ($categorias as $c): ?>
                    <option
                        value="<?= (int)$c['id_categoria'] ?>"
                        <?= ((int)$c['id_categoria'] === (int)$producto['id_categoria']) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($c['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">Debes seleccionar una categoría.</div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <a href="listar.php" class="btn btn-outline-secondary">Cancelar</a>
    </form>
</div>

<script src="../assets/js/validaciones.js"></script>
</body>
</html>
