<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'includes/dbconexion.php';

$usuario = $_POST["usuario"];
$password = $_POST["password"];

$query = "SELECT * FROM usuarios WHERE usuario='$usuario' AND password='$password'";
$resultado = mysqli_query($conexion, $query);

if (mysqli_num_rows($resultado) > 0) {
    $datos = mysqli_fetch_array($resultado);
    $idUsuario = $datos["idusuario"];

    session_start();
    $_SESSION["access"] = md5($usuario);
    $_SESSION["idusuario"] = $idUsuario;

    $key = "clave_secreta";
    $hash = md5($idUsuario);

    header("Location: inicio.php?user=" . urlencode($usuario) . "&i=" . $hash);
} else {
    header("Location: index.php?r=error");
}
?>
