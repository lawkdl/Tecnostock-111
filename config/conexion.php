<?php
/**
 * conexion.php
 * TecnoStock - Sistema de Inventario
 *
 * Punto único de conexión a la base de datos, usando PDO.
 * Todas las demás páginas PHP del proyecto deben incluir este archivo
 * en vez de abrir su propia conexión, para mantener consistencia
 * y usar siempre consultas preparadas.
 */

// --- Datos de conexión ---------------------------------------------
// XAMPP por defecto: usuario 'root' sin clave. Si tú le pusiste clave
// a MySQL/phpMyAdmin en tu instalación, cámbiala aquí.
$host    = 'localhost';
$puerto  = '3306';
$bd      = 'tecnostock';
$usuario = 'root';
$clave   = '';
$charset = 'utf8mb4';

// --- Construcción del DSN (Data Source Name) ------------------------
$dsn = "mysql:host={$host};port={$puerto};dbname={$bd};charset={$charset}";

// --- Opciones de PDO --------------------------------------------------
// ERRMODE_EXCEPTION: cualquier error SQL lanza una excepción en vez de
//                    fallar en silencio (fundamental para depurar).
// FETCH_ASSOC:       los resultados vienen como arrays asociativos
//                    (['nombre' => 'Mouse', ...]) en vez de índices numéricos.
// EMULATE_PREPARES = false: usa consultas preparadas REALES del driver
//                    de MySQL, no una simulación de PHP. Es más seguro
//                    y respeta los tipos de dato definidos en la base.
$opciones = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// --- Conexión ----------------------------------------------------------
try {
    $pdo = new PDO($dsn, $usuario, $clave, $opciones);
} catch (PDOException $e) {
    // En producción nunca se muestra el detalle del error al usuario final,
    // pero en este proyecto académico ayuda a depurar rápido.
    die('Error de conexión a la base de datos: ' . $e->getMessage());
}
