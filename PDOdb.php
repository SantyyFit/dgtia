<?php
function Conectarse() {
    $host = "localhost";
    $dbname = "cruzzsan_dgtia";
    $username = "cruzzsan_usuario";
    $password = "NuevaContraseñaSegura";

    try {
        // Crear la conexión utilizando PDO
        $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

        // Configurar atributos de PDO
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $conexion;
    } catch (PDOException $e) {
        // Mostrar error y detener script si falla la conexión
        die("Error conectando a la base de datos: " . $e->getMessage());
    }
}

// Llamar a la función para obtener la conexión
$pdo = Conectarse();
?>