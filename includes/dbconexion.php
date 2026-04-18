<?php
function Conectarse(){
    // Usar mysqli en lugar de mysql
    $conexion = mysqli_connect("localhost", "cruzzsan_usuario", "NuevaContraseñaSegura", "cruzzsan_dgtia");
    
    // Verificar conexión
    if (!$conexion) {
        die("Error conectando a la base de datos: " . mysqli_connect_error());
    }
    
    // Establecer charset a UTF-8
    mysqli_set_charset($conexion, "utf8");
    
    return $conexion;
}

$conexion = Conectarse();
?>