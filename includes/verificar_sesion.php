<?php
/**
 * verificar_sesion.php
 * "Guardia" de acceso: cualquier página que requiera login la incluye
 * al comienzo, antes de imprimir cualquier HTML.
 *
 *   require_once __DIR__ . '/../includes/verificar_sesion.php';
 *
 * Si no hay sesión activa, corta la ejecución y manda a login.php.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/constantes.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}
