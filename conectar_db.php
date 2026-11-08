<?php
// Archivo de conexión reutilizable
const HOSTNAME = "localhost";
const DATABASE = "clientes";
const USER_DB = "root";
const PASSWORD = "1968";
const PORT_DB = 3308;
function conectar(): PDO {
    $dsn = "mysql:host=" . HOSTNAME . ";port=" . PORT_DB . ";dbname=" . DATABASE . ";charset=utf8mb4";
    try {
        $pdo = new PDO($dsn, USER_DB, PASSWORD);
// Configuración de atributos de seguridad y rendimiento
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    } catch (PDOException $e) {
// Registro en log y muestra de mensaje genérico
        error_log("Error PDO: " . $e->getMessage());
        die("Ocurrió un error al conectar con la base de datos.");
    }
}

?>