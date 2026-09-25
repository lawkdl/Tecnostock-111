<?php
require_once __DIR__ . '/../includes/verificar_sesion.php';
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: listar.php');
    exit;
}

$id = $_POST['id_producto'] ?? null;

if (!ctype_digit((string)$id)) {
    header('Location: listar.php');
    exit;
}

// Nota: código y nombre NO se reciben aquí a propósito. El caso solo
// permite modificar precio, descripción, categoría y cantidades — así
// que aunque alguien manipule el formulario a mano, este script
// simplemente no toca esas dos columnas.
$descripcion  = trim($_POST['descripcion'] ?? '');
$precio       = $_POST['precio'] ?? '';
$stockActual  = $_POST['stock_actual'] ?? '';
$stockMinimo  = $_POST['stock_minimo'] ?? '';
$idCategoria  = $_POST['id_categoria'] ?? '';

$errores = [];

if (!is_numeric($precio) || (float)$precio < 0) {
    $errores[] = 'El precio debe ser un número mayor o igual a 0.';
}
if (!ctype_digit((string)$stockActual)) {
    $errores[] = 'El stock actual debe ser un número entero mayor o igual a 0.';
}
if (!ctype_digit((string)$stockMinimo)) {
    $errores[] = 'El stock mínimo debe ser un número entero mayor o igual a 0.';
}
if ($idCategoria === '' || !ctype_digit((string)$idCategoria)) {
    $errores[] = 'Debes seleccionar una categoría.';
}

if (!empty($errores)) {
    $_SESSION['error_producto'] = implode(' ', $errores);
    header('Location: editar.php?id=' . $id);
    exit;
}

$stmt = $pdo->prepare(
    'UPDATE producto
     SET descripcion  = :descripcion,
         precio       = :precio,
         stock_actual = :stock_actual,
         stock_minimo = :stock_minimo,
         id_categoria = :id_categoria
     WHERE id_producto = :id'
);
$stmt->execute([
    'descripcion'  => $descripcion,
    'precio'       => $precio,
    'stock_actual' => $stockActual,
    'stock_minimo' => $stockMinimo,
    'id_categoria' => $idCategoria,
    'id'           => $id,
]);

$_SESSION['mensaje'] = 'Producto actualizado correctamente.';
header('Location: listar.php');
exit;
