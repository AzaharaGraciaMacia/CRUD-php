<?php
require_once "conectar_db.php";
require_once "funcionesValidacion.php";
session_start();
$errores = [];

// Función para obtener los datos del cliente
function obtenerDatosCliente($DNI): array
{
    try {
        $con = conectar();
        $sql = "SELECT * FROM usuarios WHERE dni = :dni";
        $stmt = $con->prepare($sql);
        $stmt->execute([":dni" => $DNI]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error PDO: " . $e->getMessage());
        die("Ocurrió un error al recuperar datos de la base de datos.");
    }
}

// Recibe por GET el DNI desde la página principal y obtiene el resto de datos del cliente
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $DNI = $_GET["DNI"];
    $cliente = obtenerDatosCliente($DNI);
    $nombre = $cliente["nombre"];
    $email = $cliente["email"];
    $direccion = $cliente["direccion"];
    $localidad = $cliente["localidad"];
    $provincia = $cliente["provincia"];
    $telefono = $cliente["telefono"];
}

// Función para modificar el cliente en la BD
function editarCliente($DNI, $nombre, $email, $direccion, $localidad, $provincia, $telefono): void
{
    try {
        $con = conectar();
        $sql = "UPDATE usuarios 
                SET nombre = :nombre, 
                    direccion = :direccion, 
                    localidad = :localidad, 
                    provincia = :provincia, 
                    telefono = :telefono, 
                    email = :email
                WHERE dni = :dni;";
        $stmt = $con->prepare($sql);
        $stmt->execute([":dni" => $DNI, ":nombre" => $nombre, ":direccion" => $direccion,
                ":localidad" => $localidad, ":provincia" => $provincia, ":telefono" => $telefono, ":email" => $email]);
        $_SESSION["mensaje"] = "Cliente actualizado correctamente.";
    } catch (PDOException $e) {
        error_log("Error PDO: " . $e->getMessage());
        die("Ocurrió un error al modificar el cliente en la base de datos.");
    }
}

// Recoge los datos del formulario y llama a la función para actualizar la BD
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $DNI = $_POST["DNI"];
    $nombre = obtenerDato("nombre");
    $email = obtenerDato("email");
    $direccion = obtenerDato("direccion");
    $localidad = obtenerDato("localidad");
    $provincia = obtenerDato("provincia");
    $telefono = obtenerDato("telefono");

    if (!validarEmail($email)) {
        $errores[] = "Email no válido";
    }
    if (empty($errores)) {
        editarCliente($DNI, $nombre, $email, $direccion, $localidad, $provincia, $telefono);
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid text-center">
<h1>EDICIÓN DE REGISTRO DE CLIENTE</h1>
<br>
</div>
<div class="container-fluid text-center">
<?php if (!empty($errores)) :?>
    <h4 class="text-danger">Se han encontrado los siguientes errores:</h4>
<?php foreach ($errores as $error) : ?>
        <ul>
            <li class="text-danger" style="list-style: none">■■ <?= $error ?></li>
        </ul>
<?php endforeach ?>
<?php endif ?>
</div>
<div class="container-fluid text-center">
<h4  style="color: dodgerblue">MODIFICAR CLIENTE (DNI: <?= $DNI ?>)</h4>
</div>
<div class="d-flex justify-content-around">
    <form method="post" action="editarcliente.php">
        <table class="table">
            <tbody>
                <tr>
                    <td>
                        <label for="DNI">DNI de cliente (*):</label>
                    </td>
                    <td>
                        <span id= "DNI"><?= $DNI ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="nombre">Nombre Completo (*):</label>
                    </td>
                    <td>
                        <input type="text" name="nombre" id="nombre" value="<?= $nombre ?>" placeholder="Ej: Azahara Gracia Maciá">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="email">Correo electrónico (*):</label>
                    </td>
                    <td>
                        <input type="email" name="email" id="email" value="<?= $email ?>" placeholder="Ej: azagramac@alu.edu.gva.es">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="direccion">Dirección:</label>
                    </td>
                    <td>
                        <input type="text" name="direccion" id="direccion" value="<?= $direccion ?>" placeholder="Ej: C/Concepción Arenal">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="localidad">Localidad:</label>
                    </td>
                    <td>
                        <input type="text" name="localidad" id="localidad" value="<?= $localidad ?>" placeholder="Ej: Elche">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="provincia">Provincia:</label>
                    </td>
                    <td>
                        <input type="text" name="provincia" id="provincia" value="<?= $provincia ?>" placeholder="Ej: Alicante">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="telefono">Teléfono:</label>
                    </td>
                    <td>
                        <input type="text" name="telefono" id="telefono" value="<?= $telefono ?>" placeholder="Ej: 695439149">
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="container-fluid text-center">
        <button type="submit" style="color: dodgerblue; border: none; background-color: transparent" value="<?= $DNI ?>" name="DNI"><b>[■ Actualizar Datos]</b></button>
        <button type="reset" style="border: none; background-color: transparent"><a style="color: gray" href="index.php">[■■ Cancelar]</a></button>
        </div>
    </form>
    </div>
</body>
</html>