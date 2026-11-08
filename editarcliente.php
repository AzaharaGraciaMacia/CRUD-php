<?php
require_once "conectar_db.php";
require_once "funcionesValidacion.php";

$DNI = $_GET["DNI"];
$errores = [];

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
$cliente = obtenerDatosCliente($DNI);
$nombre = $cliente["nombre"];
$email = $cliente["email"];
$direccion = $cliente["direccion"];
$localidad = $cliente["localidad"];
$provincia = $cliente["provincia"];
$telefono = $cliente["telefono"];
function editarCliente($DNI,$nombre, $email, $direccion, $localidad, $provincia, $telefono): void
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
                ":localidad" => $localidad, ":provincia" => $provincia, ":telefono" => $telefono, ":email" => $email
        ]);
    } catch (PDOException $e) {
        error_log("Error PDO: " . $e->getMessage());
        die("Ocurrió un error al modificar el cliente en la base de datos.");
    }
}

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
        $_SESSION["clienteEditado"] = [
                "nombre" => $nombre,
                "email" => $email,
                "direccion" => $direccion,
                "localidad" => $localidad,
                "provincia" => $provincia,
                "telefono" => $telefono
        ];
        editarCliente($DNI, $nombre, $email, $direccion, $localidad, $provincia, $telefono);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Cliente</title>
</head>
<body>
<h1>EDICIÓN DE REGISTRO DE CLIENTE</h1>
<br>

<?php if (!empty($errores)) :?>
    <h4>Se han encontrado los siguientes errores:</h4>
<?php foreach ($errores as $error) : ?>
        <ul>
            <li><?= $error ?></li>
        </ul>
<?php endforeach ?>
<?php endif ?>

<h3>MODIFICAR CLIENTE</h3>
<div>
    <form method="post" action="editarcliente.php">
        <label for="DNI">DNI de cliente (*):</label>
        <span id= "DNI"><?= $DNI ?></span>
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
        <button type="submit" value="<?= $DNI ?>" name="DNI">Actualizar Datos</button>
        <button type="reset"><a style="text-decoration: none; color: black" href="index.php">Cancelar</a></button>
    </form>
</div>
</body>
</html>