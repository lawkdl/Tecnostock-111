<?php
/**
 * procesar_login.php
 * Recibe el POST del formulario de login.php, valida y autentica.
 * No genera ninguna salida propia: siempre redirige (patrón PRG),
 * ya sea de vuelta a login.php (con error) o a dashboard.php (éxito).
 */

session_start();
require_once __DIR__ . '/config/conexion.php';

// Solo se acepta por POST; si alguien intenta acceder por GET, se rechaza.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// --- Validación del lado del servidor --------------------------------
// El HTML5/JS ya validan en el navegador, pero esa validación se puede
// saltar fácilmente (formularios deshabilitados, Postman, etc.), así que
// TODO se vuelve a validar aquí, que es lo que de verdad importa.
$correo = trim($_POST['correo'] ?? '');
$clave  = $_POST['clave'] ?? '';

if ($correo === '' || $clave === '') {
    $_SESSION['error_login'] = 'Debes completar correo y contraseña.';
    header('Location: login.php');
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_login'] = 'El correo ingresado no tiene un formato válido.';
    header('Location: login.php');
    exit;
}

// --- Búsqueda del usuario con consulta preparada ------------------------
$stmt = $pdo->prepare(
    'SELECT id_usuario, nombre, clave, estado
     FROM usuario
     WHERE correo = :correo'
);
$stmt->execute(['correo' => $correo]);
$usuario = $stmt->fetch();

// --- Verificación de credenciales ----------------------------------------
// Se usa el mismo mensaje genérico tanto si el correo no existe como si
// la clave es incorrecta: no conviene revelar cuál de los dos falló.
$credencialesValidas = $usuario
    && $usuario['estado'] === 'activo'
    && password_verify($clave, $usuario['clave']);

if (!$credencialesValidas) {
    $_SESSION['error_login'] = 'Correo o contraseña incorrectos.';
    header('Location: login.php');
    exit;
}

// --- Login exitoso ---------------------------------------------------------
session_regenerate_id(true); // evita fijación de sesión
$_SESSION['id_usuario']     = $usuario['id_usuario'];
$_SESSION['nombre_usuario'] = $usuario['nombre'];

header('Location: dashboard.php');
exit;
