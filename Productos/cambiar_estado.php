<?php
/**
 * cambiar_estado.php
 * Activa o desactiva un producto (baja lógica). NUNCA hace DELETE:
 * el caso exige que un producto con movimientos no se elimine
 * físicamente, y la base de datos ya lo refuerza con
 * ON DELETE RESTRICT en la tabla movimiento.
 */

require_once __DIR__ . '/../includes/verificar_sesion.php';
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: listar.php');
    exit;
}

$id = $_POST['id'] ?? null;

if (!ctype_digit((string)$id)) {
    header('Location: listar.php');
    exit;
}

$stmt = $pdo->prepare('SELECT estado FROM producto WHERE id_producto = :id');
$stmt->execute(['id' => $id]);
$producto = $stmt->fetch();

if (!$producto) {
    $_SESSION['mensaje'] = 'El producto no existe.';
    header('Location: listar.php');
    exit;
}

$nuevoEstado = ($producto['estado'] === 'activo') ? 'inactivo' : 'activo';

$stmt = $pdo->prepare('UPDATE producto SET estado = :estado WHERE id_producto = :id');
$stmt->execute(['estado' => $nuevoEstado, 'id' => $id]);

$_SESSION['mensaje'] = ($nuevoEstado === 'inactivo')
    ? 'Producto desactivado.'
    : 'Producto reactivado.';

header('Location: listar.php');
exit;
