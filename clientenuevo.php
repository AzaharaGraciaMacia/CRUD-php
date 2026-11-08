<?php
require_once "conectar_db.php";
require_once "funcionesValidacion.php";

$DNI = "";
$nombre = "";
$email = "";
$direccion = "";
$localidad = "";
$provincia = "";
$telefono = "";
$errores = [];

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
function insertarCliente($DNI, $nombre, $email, $direccion, $localidad, $provincia, $telefono): void
{
    try {
        $con = conectar();
        $sql = "INSERT INTO usuarios (dni, nombre, direccion, localidad, provincia, telefono, email) 
        VALUES (:dni, :nombre, :direccion, :localidad, :provincia, :telefono, :email)";
        $stmt = $con->prepare($sql);
        $stmt->execute([":dni" => $DNI, ":nombre" => $nombre, ":direccion" => $direccion,
                ":localidad" => $localidad, ":provincia" => $provincia, ":telefono" => $telefono, ":email" => $email
        ]);
    } catch (PDOException $e) {
        error_log("Error PDO: " . $e->getMessage());
        die("Ocurrió un error al insertar en la base de datos.");
    }
}

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
        $_SESSION["nuevoCliente"] = [
                "DNI" => $DNI,
                "nombre" => $nombre,
                "email" => $email,
                "direccion" => $direccion,
                "localidad" => $localidad,
                "provincia" => $provincia,
                "telefono" => $telefono
        ];
        insertarCliente($DNI, $nombre, $email, $direccion, $localidad, $provincia, $telefono);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Alta</title>
</head>
<body>
<h1>Formulario de alta de cliente</h1>
<br>

<?php if (!empty($errores)) :?>
    <h4>Se han encontrado los siguientes errores:</h4>
<?php foreach ($errores as $error) : ?>
        <ul>
            <li><?= $error ?></li>
        </ul>
<?php endforeach ?>
<?php endif ?>

<h3>NUEVO CLIENTE - FORMULARIO DE REGISTRO</h3>
<div>
    <form method="post" action="clientenuevo.php">
        <label for="DNI">DNI de cliente (*):</label>
        <input type="text" name="DNI" id= "DNI" value="<?= $DNI ?>" placeholder="Ej: 12345678Z">
        <br>
        <label for="nombre">Nombre Completo (*):</label>
        <input type="text" name="nombre" id="nombre" value="<?= $nombre ?>" placeholder="Ej: Azahara Gracia Maciá">
        <br>
        <label for="email">Correo electrónico (*):</label>
        <input type="email" name="email" id="email" value="<?= $email ?>" placeholder="Ej: azagramac@alu.edu.gva.es">
        <br>
        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" id="direccion" value="<?= $direccion ?>" placeholder="Ej: C/Concepción Arenal">
        <br>
        <label for="localidad">Localidad:</label>
        <input type="text" name="localidad" id="localidad" value="<?= $localidad ?>" placeholder="Ej: Elche">
        <br>
        <label for="provincia">Provincia:</label>
        <input type="text" name="provincia" id="provincia" value="<?= $provincia ?>" placeholder="Ej: Alicante">
        <br>
        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" id="telefono" value="<?= $telefono ?>" placeholder="Ej: 695439149">
        <br>
        <button type="submit">Guardar Cliente</button>
        <button type="reset"><a style="text-decoration: none; color: black" href="index.php">Cancelar y Volver</a></button>
    </form>
</div>
</body>
</html>
