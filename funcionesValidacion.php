<?php

// Constante para validación de DNI
const LETRAS_CONTROL = "TRWAGMYFPDXBNJZSQVHLCKE";

// Función para validar el DNI, pone la letra en mayúscula y comprueba que tiene patrón de 8 dígitos + 1 letra
// Después separa en dos cadenas el número y la letra, divide el número por 23 y busca con el resto la posición dentro de las letras de control
// Si la letra del DNI coincide con la letra en la posición del resto, el DNI es válido.
function validarDNI($DNI): bool
{
    if ($DNI === "") return false;

        $DNI = strtoupper($DNI);

        if (!preg_match("/\A[0-9]{8}[A-Z]\z/",$DNI)) return false;

        $letra = substr($DNI, -1);
        $numeroDNI = (int) substr($DNI,0, -1);
        $letraEsperada = substr(LETRAS_CONTROL,$numeroDNI % 23,1);
        return $letra === $letraEsperada;
}

// Valida que el email sea correcto (algo@algo.algo)
function validarEmail($email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Función para proteger el texto enviado desde formularios
function textoProtegido($texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, "UTF-8");
}

// Función para recibir datos de formularios
function obtenerDato($dato): string
{
    $valor = textoProtegido($_POST[$dato]) ?? "";
    return trim($valor);
}
?>