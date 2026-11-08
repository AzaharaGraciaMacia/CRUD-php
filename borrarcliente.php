<?php
require_once "conectar_db.php";
require_once "funcionesValidacion.php";

$DNI = $_GET["DNI"];
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
$nombre = obtenerNombreCliente($DNI);

function eliminarCliente($DNI): void
{
    try {
        $con = conectar();
        $sql = "DELETE FROM usuarios WHERE dni = :dni;";
        $stmt = $con->prepare($sql);
        $stmt->execute([":dni" => $DNI]);
    } catch (PDOException $e) {
        error_log("Error PDO: " . $e->getMessage());
        die("Ocurrió un error al eliminar el cliente en la base de datos.");
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $DNI = $_POST["DNI"];
           eliminarCliente($DNI);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Cliente</title>
</head>
<body>
<h3>CONFIRMACIÓN DE ELIMINACIÓN DE REGISTRO</h3>
<br>
<p>¿Está seguro de que desea eliminar permanentemente de la base de datos al cliente?</p>
<p><b><?= $nombre ?></b> (DNI: <?= $DNI ?>)</p>
<div>
    <form method="post" action="borrarcliente.php">
        <button type="submit" value="<?= $DNI ?>" name="DNI">Sí, Eliminar Registro</button>
        <button type="reset"><a style="text-decoration: none; color: black" href="index.php">Cancelar y volver</a></button>
    </form>
</div>
</body>
</html>