<?php
require_once "conectar_db.php";
require_once "funcionesValidacion.php";
session_start();

// Mensajes que recibirá desde las otras páginas para mostrar tras redirigir
$mensaje = $_SESSION["mensaje"] ?? "";
// Limpia el mensaje cuando refresca
unset($_SESSION["mensaje"]);

// Función para conectar con la BD y seleccionar todos los clientes
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

function obtenerClientesFiltrados($filtro, $orden): array
{
    try {
        $con = conectar();
        $sql = "SELECT * FROM usuarios";
        $parametros = [];
        if ($filtro !== "") {
            $sql .= " WHERE dni LIKE :dni OR nombre LIKE :nombre";
            $patron = "%" . $filtro . "%";
            $parametros[":dni"] = $patron;
            $parametros[":nombre"] = $patron;
            if ($orden !== "") {
                $sql .= " ORDER BY nombre $orden";
                $stmt = $con->prepare($sql);
                $stmt->execute($parametros);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt = $con->prepare($sql);
            $stmt->execute($parametros);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            if ($orden !== "") {
                $sql .= " ORDER BY nombre $orden";
                $stmt = $con->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        $stmt = $con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error PDO: " . $e->getMessage());
        die("Ocurrió un error al recuperar datos de la base de datos.");
    }
}
$filtro = "";
if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $filtro = obtenerDato("filtro");
    $orden = $_POST["orden"] ?? "";
    $clientes = obtenerClientesFiltrados($filtro, $orden);
}

?>
<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Mantenimiento de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php if ($mensaje !== ""): ?>
    <div class="alert alert-success mx-4 text-center" role="alert">
        <?= $mensaje ?>
    </div>
<?php endif; ?>
<div>
    <div class="container-fluid text-center">
<h1>MANTENIMIENTO DE CLIENTES</h1>
    </div>

<div style="display: grid; grid-template-columns: (6, 1fr); grid-template-rows: (1, 1fr); margin: 0 5% 0 5% ">
    <form method="post" action="index.php" style="display: grid; grid-column: 1/6; grid-template-columns: (4, 1fr); grid-template-rows: (1, 1fr)">
        <div class="input-group" style="grid-column: 1/3; display: flex">
            <label for="buscar">Buscar: </label>
            <input type="text" name="filtro" value="<?= $filtro?>" id="buscar" placeholder="Buscar por nombre o DNI">
            <button type="submit" style="color: dodgerblue; border: none; background-color: transparent">[Buscar]</button>
        </div>
        <div class="input-group" style="grid-column: 3/5; display: flex">
            <span>Orden:
                <button style="color: dodgerblue; border: none; background-color: transparent" name="orden" value="ASC">[A-Z]</button>
                <button style="color: dodgerblue; border: none; background-color: transparent" name="orden" value="DESC">[Z-A]</button>
            </span>
        </div>
    </form>
    <div  style="grid-column: 6">
        <a href="clientenuevo.php" style="color: green;"><b>[+ Nuevo Cliente]</b></a>
    </div>
</div>

<div class="container-fluid text-center">
    <table class="table table-striped table-bordered border-gray">
        <thead>
            <tr>
                <th style="background-color: lightsalmon">DNI</th>
                <th style="background-color: lightsalmon">Nombre</th>
                <th style="background-color: lightsalmon">Dirección</th>
                <th style="background-color: lightsalmon">Localidad</th>
                <th style="background-color: lightsalmon">Provincia</th>
                <th style="background-color: lightsalmon">Teléfono</th>
                <th style="background-color: lightsalmon">e-mail</th>
                <th style="background-color: lightsalmon">Editar</th>
                <th style="background-color: lightsalmon">Borrar</th>
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
                            <button type="submit" style="color: green; border: none; background-color: transparent" value="<?= $cliente["dni"] ?>" name="DNI"><b>■ Editar</b></button>
                        </form>
                    </td>

                    <td>
                        <form method="get" action="borrarcliente.php">
                            <button type="submit" class="text-danger" style="border: none; background-color: transparent" value="<?= $cliente["dni"] ?>" name="DNI"><b>■ Borrar</b></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</div>
</body>
</html>
