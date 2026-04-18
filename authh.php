<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit();
}

include_once 'includes/conexion.php';

$usuario  = trim($_POST['usuario'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($usuario === '' || $password === '') {
    header("Location: login.php?error=" . urlencode("Completa todos los campos"));
    exit();
}

$usuarioEscapado = mysqli_real_escape_string($conexion, $usuario);

$sql = "SELECT * FROM usuarios
        WHERE (usuario = '$usuarioEscapado' OR email = '$usuarioEscapado')
        LIMIT 1";

$res = mysqli_query($conexion, $sql);

if (!$res || mysqli_num_rows($res) === 0) {
    header("Location: login.php?error=" . urlencode("Usuario o contraseña incorrectos"));
    exit();
}

$user = mysqli_fetch_assoc($res);

$loginCorrecto = false;

// 🔐 1. Verificar con password_hash (nuevo)
if (password_verify($password, $user['password'])) {
    $loginCorrecto = true;
}

// 🔧 2. Verificar con MD5 (viejo)
elseif (md5($password) === $user['password']) {
    $loginCorrecto = true;

    // 🔥 MIGRAR AUTOMÁTICAMENTE A HASH SEGURO
    $nuevoHash = password_hash($password, PASSWORD_DEFAULT);

    mysqli_query($conexion, "UPDATE usuarios
                            SET password='$nuevoHash'
                            WHERE idusuario=" . $user['idusuario']);
}

if (!$loginCorrecto) {
    header("Location: login.php?error=" . urlencode("Usuario o contraseña incorrectos"));
    exit();
}

// ✅ LOGIN OK
$_SESSION['idusuario'] = $user['idusuario'];
$_SESSION['usuario']   = $user['usuario'];

session_regenerate_id(true);

header("Location: inicio.php");
exit();
