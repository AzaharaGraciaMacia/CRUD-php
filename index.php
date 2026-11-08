<?php
require_once "conectar_db.php";
require_once "funcionesValidacion.php";
function obtenerClientes(): array
{
    try {
        $con = conectar();
        $sql = "SELECT * FROM usuarios";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error PDO: " . $e->getMessage());
        die("Ocurrió un error al recuperar datos de la base de datos.");
    }
}
$clientes = obtenerClientes();

?>
<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Mantenimiento de Clientes</title>
</head>
<body>
<h1>MANTENIMIENTO DE CLIENTES</h1>
<div>
    <form method="post" action="index.php">
        <label for="buscar">Buscar:</label>
        <input type="text" name="buscar" id="buscar" placeholder="Buscar por nombre o DNI">
        <button type="submit">Buscar</button>
    </form>
    <span>Orden: <a>[A-Z]</a> <a>[Z-A]</a></span>
    <a href="clientenuevo.php">+ Nuevo Cliente</a>
</div>
<div>
    <table>
        <thead>
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Localidad</th>
                <th>Provincia</th>
                <th>Teléfono</th>
                <th>e-mail</th>
                <th>Editar</th>
                <th>Borrar</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($clientes)): ?>
            <tr>
                <td colspan="9">No hay clientes registrados.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <?php foreach ([
                                       "dni",
                                       "nombre",
                                       "direccion",
                                       "localidad",
                                       "provincia",
                                       "telefono",
                                       "email"
                                   ] as $campo): ?>
                        <td>
                            <?= textoProtegido($cliente[$campo]) ?? "" ?>
                        </td>
                    <?php endforeach; ?>

                    <td>
                        <form method="get" action="editarcliente.php">
                            <button type="submit" style="color: green; border: none; background-color: white" value="<?= $cliente["dni"] ?>" name="DNI">Editar</button>
                        </form>
                    </td>

                    <td>
                        <form method="get" action="borrarcliente.php">
                            <button type="submit" style="color: red; border: none; background-color: white" value="<?= $cliente["dni"] ?>" name="DNI">Borrar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
