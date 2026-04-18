<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // <-- IMPORTANTE: Debe ir ANTES de cualquier salida HTML

include_once 'includes/PDOdb.php';
$conexion = Conectarse();

$nombre = $_POST["nombre"];
$usuario = $_POST["usuario"];
$password = $_POST["password"];
$password2 = $_POST["password2"];

if ($password2 == $password) {
    // CORREGIDO: Escapar caracteres para evitar errores y SQL injection
    $usuario = htmlspecialchars(trim($_POST["usuario"]));
    $password = md5(trim($_POST["password"])); // Hash the password
    $password2 = htmlspecialchars(trim($_POST["password2"]));
    $nombre = htmlspecialchars(trim($_POST["nombre"]));
    
    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios(usuario, password, nombre) VALUES (?, ?, ?)");
        $stmt->execute([$usuario, $password, $nombre]);
        
        $userId = $pdo->lastInsertId();
        $_SESSION["access"] = md5($usuario);
        
        // CORREGIDO: La variable se llamaba $usario (sin 'u'), ahora es $usuario
        header("Location: inicio.php?user=" . urlencode($usuario) . "&i=" . $userId);
        exit(); // Importante: detener la ejecución después de redirigir
    } catch (PDOException $e) {
        // Mostrar error si falla la inserción
        echo "Error al guardar: " . $e->getMessage();
    }
} else {
    // CORREGIDO: Usar header() en lugar de JavaScript
    header("Location: registrarse.php?user=" . urlencode($usuario) . "&coderror=ec");
    exit();
}
?>