<?php
require_once "conectar_db.php";
require_once "funcionesValidacion.php";
session_start();
// Declaración de variables que se usarán
$DNI = "";
$nombre = "";
$email = "";
$direccion = "";
$localidad = "";
$provincia = "";
$telefono = "";
$errores = [];

// Función para comprobar que el cliente no existe en la BD antes de insertar
function comprobarCliente($DNI): string
{
    try {
        $con = conectar();
        $sql = "SELECT * FROM usuarios WHERE dni = :dni";
        $stmt = $con->prepare($sql);
        $stmt->execute([":dni" => $DNI]);
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($datos) == 0) {
            return false;
        } else {
            return true;
        }
    } catch (PDOException $e) {
        error_log("Error PDO: " . $e->getMessage());
        die("Ocurrió un error al recuperar datos de la base de datos.");
    }
}

// Función con la query para insertar los datos en la BD
function insertarCliente($DNI, $nombre, $email, $direccion, $localidad, $provincia, $telefono): void
{
    try {
        $con = conectar();
        $sql = "INSERT INTO usuarios (dni, nombre, direccion, localidad, provincia, telefono, email) 
        VALUES (:dni, :nombre, :direccion, :localidad, :provincia, :telefono, :email)";
        $stmt = $con->prepare($sql);
        $stmt->execute([":dni" => $DNI, ":nombre" => $nombre, ":direccion" => $direccion,
                ":localidad" => $localidad, ":provincia" => $provincia, ":telefono" => $telefono, ":email" => $email]);
        $_SESSION["mensaje"] = "Cliente creado correctamente.";
    } catch (PDOException $e) {
        error_log("Error PDO: " . $e->getMessage());
        die("Ocurrió un error al insertar en la base de datos.");
    }
}

// Recibe los datos del formulario, comprueba que no hay errores e inserta los datos. Después devuelve a la página principal.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $DNI = obtenerDato("DNI");
    $nombre = obtenerDato("nombre");
    $email = obtenerDato("email");
    $direccion = obtenerDato("direccion");
    $localidad = obtenerDato("localidad");
    $provincia = obtenerDato("provincia");
    $telefono = obtenerDato("telefono");
    if ($DNI === "" || $email === "" || $nombre === ""){
        $errores[] = "Debe rellenar todos los campos obligatorios (DNI, nombre, email)";
    } else {
        if (!validarDNI($DNI)) {
            $errores[] = "DNI no válido";
        }

        if (comprobarCliente($DNI)) {
            $errores[] = "El DNI ya existe en la base de datos";
        }
        if (!validarEmail($email)) {
            $errores[] = "Email no válido";
        }
    }
    if (empty($errores)) {
        insertarCliente($DNI, $nombre, $email, $direccion, $localidad, $provincia, $telefono);
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Alta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid text-center">
<h1>Formulario de alta de cliente</h1>
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
<h4 style="color: dodgerblue">NUEVO CLIENTE - FORMULARIO DE REGISTRO</h4>
</div>
<div class="d-flex justify-content-around">
    <form method="post" action="clientenuevo.php" class="form">
        <table class="table">
        <tbody>
        <tr>
            <td>
        <label for="DNI">DNI de cliente (*):</label>
            </td>
            <td>
        <input type="text" name="DNI" id= "DNI" value="<?= $DNI ?>" placeholder="Ej: 12345678Z">
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
            <button type="submit" style="color: green; border: none; background-color: transparent"><b>[■ Guardar Cliente]</b></button>
            <button type="reset" style="border: none; background-color: transparent"><a style="color: gray" href="index.php">[■■ Cancelar y Volver]</a></button>
        </div>
    </form>
</div>
</body>
</html>
