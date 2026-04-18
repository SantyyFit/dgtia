<?php
function Conectarse() {
    $host = "localhost";
    $usuario = "cruzzsan_usuario";
    $contrasena = "NuevaContraseñaSegura";
    $bd = "cruzzsan_dgtia";

    $conexion = new mysqli($host, $usuario, $contrasena, $bd);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Establecer codificación para caracteres especiales
    $conexion->set_charset("utf8");

    return $conexion;
}

// Esta línea crea la conexión al incluir este archivo
$conexion = Conectarse();
?>