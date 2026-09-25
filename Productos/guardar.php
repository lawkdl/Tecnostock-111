<?php
require_once __DIR__ . '/../includes/verificar_sesion.php';
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: nuevo.php');
    exit;
}

// --- Recolección y limpieza básica -----------------------------------
$codigo       = trim($_POST['codigo'] ?? '');
$nombre       = trim($_POST['nombre'] ?? '');
$descripcion  = trim($_POST['descripcion'] ?? '');
$precio       = $_POST['precio'] ?? '';
$stockActual  = $_POST['stock_actual'] ?? '';
$stockMinimo  = $_POST['stock_minimo'] ?? '';
$idCategoria  = $_POST['id_categoria'] ?? '';

// --- Validación del lado del servidor ----------------------------------
// Repite lo que ya valida el HTML5/JS, porque esa validación se puede
// saltar fácilmente. Estas reglas reflejan las del caso:
// "El precio, el stock actual, el stock mínimo... no pueden ser negativos."
$errores = [];

if ($codigo === '') {
    $errores[] = 'El código es obligatorio.';
}
if ($nombre === '') {
    $errores[] = 'El nombre es obligatorio.';
}
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

// Código único: la base de datos también lo exige (UNIQUE), pero
// comprobarlo aquí primero permite dar un mensaje claro en español,
// en vez de dejar que el usuario vea un error crudo de MySQL.
if (empty($errores)) {
    $stmt = $pdo->prepare('SELECT id_producto FROM producto WHERE codigo = :codigo');
    $stmt->execute(['codigo' => $codigo]);
    if ($stmt->fetch()) {
        $errores[] = 'Ya existe un producto registrado con ese código.';
    }
}

if (!empty($errores)) {
    $_SESSION['error_producto']   = implode(' ', $errores);
    $_SESSION['valores_producto'] = $_POST;
    header('Location: nuevo.php');
    exit;
}

// --- Inserción (consulta preparada) ------------------------------------
try {
    $stmt = $pdo->prepare(
        'INSERT INTO producto (codigo, nombre, descripcion, precio, stock_actual, stock_minimo, id_categoria)
         VALUES (:codigo, :nombre, :descripcion, :precio, :stock_actual, :stock_minimo, :id_categoria)'
    );
    $stmt->execute([
        'codigo'       => $codigo,
        'nombre'       => $nombre,
        'descripcion'  => $descripcion,
        'precio'       => $precio,
        'stock_actual' => $stockActual,
        'stock_minimo' => $stockMinimo,
        'id_categoria' => $idCategoria,
    ]);
} catch (PDOException $e) {
    // Red de seguridad final por si dos personas registran el mismo
    // código al mismo tiempo (condición de carrera) y la restricción
    // UNIQUE de la base es la que realmente frena la duplicación.
    $_SESSION['error_producto']   = 'No se pudo guardar el producto. Verifica los datos e intenta de nuevo.';
    $_SESSION['valores_producto'] = $_POST;
    header('Location: nuevo.php');
    exit;
}

$_SESSION['mensaje'] = 'Producto registrado correctamente.';
header('Location: listar.php');
exit;
