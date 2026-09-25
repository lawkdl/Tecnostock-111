<?php
require_once __DIR__ . '/../includes/verificar_sesion.php';
require_once __DIR__ . '/../config/conexion.php';

$categorias = $pdo->query(
    "SELECT id_categoria, nombre FROM categoria WHERE estado = 'activo' ORDER BY nombre"
)->fetchAll();

// Errores y valores dejados por guardar.php si la validación falló
// (así el usuario no tiene que volver a escribir todo de nuevo)
$error   = $_SESSION['error_producto'] ?? null;
$valores = $_SESSION['valores_producto'] ?? [];
unset($_SESSION['error_producto'], $_SESSION['valores_producto']);

function valor(array $valores, string $campo): string
{
    return htmlspecialchars($valores[$campo] ?? '');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo producto - TecnoStock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../includes/navbar.php'; ?>

<div class="container">
    <h2 class="mb-4">Nuevo producto</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="guardar.php" method="POST" class="needs-validation" novalidate style="max-width: 600px;">
        <div class="mb-3">
            <label for="codigo" class="form-label">Código</label>
            <input type="text" class="form-control" id="codigo" name="codigo"
                   value="<?= valor($valores, 'codigo') ?>" required maxlength="50">
            <div class="invalid-feedback">El código es obligatorio.</div>
        </div>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre"
                   value="<?= valor($valores, 'nombre') ?>" required maxlength="150">
            <div class="invalid-feedback">El nombre es obligatorio.</div>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= valor($valores, 'descripcion') ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio"
                       value="<?= valor($valores, 'precio') ?>" required min="0" step="1">
                <div class="invalid-feedback">Debe ser un número mayor o igual a 0.</div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="stock_actual" class="form-label">Stock actual</label>
                <input type="number" class="form-control" id="stock_actual" name="stock_actual"
                       value="<?= valor($valores, 'stock_actual') ?>" required min="0" step="1">
                <div class="invalid-feedback">Debe ser un número entero mayor o igual a 0.</div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="stock_minimo" class="form-label">Stock mínimo</label>
                <input type="number" class="form-control" id="stock_minimo" name="stock_minimo"
                       value="<?= valor($valores, 'stock_minimo') ?>" required min="0" step="1">
                <div class="invalid-feedback">Debe ser un número entero mayor o igual a 0.</div>
            </div>
        </div>

        <div class="mb-3">
            <label for="id_categoria" class="form-label">Categoría</label>
            <select class="form-select" id="id_categoria" name="id_categoria" required>
                <option value="" disabled <?= empty($valores['id_categoria']) ? 'selected' : '' ?>>
                    Selecciona una categoría
                </option>
                <?php foreach ($categorias as $c): ?>
                    <option
                        value="<?= (int)$c['id_categoria'] ?>"
                        <?= (isset($valores['id_categoria']) && $valores['id_categoria'] == $c['id_categoria']) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($c['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">Debes seleccionar una categoría.</div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar producto</button>
        <a href="listar.php" class="btn btn-outline-secondary">Cancelar</a>
    </form>
</div>

<script src="../assets/js/validaciones.js"></script>
</body>
</html>
