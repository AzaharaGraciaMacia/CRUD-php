<?php
require_once "conectar_db.php";
require_once "funcionesValidacion.php";
session_start();
// Función para obtener el nombre del cliente, para poder mostrarlo en la confirmación
function obtenerNombreCliente($DNI): string
{
    try {
        $con = conectar();
        $sql = "SELECT nombre FROM usuarios WHERE dni = :dni";
        $stmt = $con->prepare($sql);
        $stmt->execute([":dni" => $DNI]);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error PDO: " . $e->getMessage());
        die("Ocurrió un error al recuperar datos de la base de datos.");
    }
}

// Recibe por GET el DNI desde la página principal
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $DNI = $_GET["DNI"];
    $nombre = obtenerNombreCliente($DNI);
}

// Función para eliminar al cliente en la BD, filtrando por DNI
function eliminarCliente($DNI): void
{
    try {
        $con = conectar();
        $sql = "DELETE FROM usuarios WHERE dni = :dni;";
        $stmt = $con->prepare($sql);
        $stmt->execute([":dni" => $DNI]);
        $_SESSION["mensaje"] = "Cliente eliminado correctamente.";
    } catch (PDOException $e) {
        error_log("Error PDO: " . $e->getMessage());
        die("Ocurrió un error al eliminar el cliente en la base de datos.");
    }
}

// Recibe la confirmación y devuelve a la página principal.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
           $DNI = $_POST["DNI"];
           eliminarCliente($DNI);
           header("Location: index.php");
           exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="m-5 text-center bg-danger bg-opacity-10 border border-2 border-danger">
<h5 class="text-danger">CONFIRMACIÓN DE ELIMINACIÓN DE REGISTRO</h5>
<br>
<p>¿Está seguro de que desea eliminar permanentemente de la base de datos al cliente?</p>
<p><b><?= $nombre ?></b> (DNI: <?= $DNI ?>)</p>
<div>
    <form method="post" action="borrarcliente.php">
        <button type="submit" value="<?= $DNI ?>" name="DNI" class="text-danger" style="border: none; background-color: transparent"><b>[■ Sí, Eliminar Registro]</b></button>
        <button type="reset" style="border: none; background-color: transparent"><a style="color: dodgerblue; text-decoration: none" href="index.php"><b>[■■ Cancelar y Volver]</b></a></button>
    </form>
</div>
</div>
</body>
</html>