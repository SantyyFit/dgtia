<?php
session_start();

$mensajeError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($usuario === '' || $password === '') {
        $mensajeError = "Completa todos los campos.";
    } else {

        include_once 'includes/dbconexion.php';

        // Evitar inyección SQL
        $usuario = mysqli_real_escape_string($conexion, $usuario);
        $password = mysqli_real_escape_string($conexion, $password);

        // Encriptar contraseña (MD5 como tu BD)
        $password = md5($password);

        $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' AND password='$password' LIMIT 1";
        $resultado = mysqli_query($conexion, $sql);

        if ($resultado && mysqli_num_rows($resultado) > 0) {

            $fila = mysqli_fetch_assoc($resultado);

            // Guardar sesión
            $_SESSION['idusuario'] = $fila['idusuario'];
            $_SESSION['usuario'] = $fila['usuario'];

            // Redirigir a inicio.html con parámetros
            header("Location: inicio.php?user=" . $fila['usuario'] . "&i=" . $fila['idusuario']);
            exit();
        } else {
            $mensajeError = "Usuario o contraseña incorrectos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <div class="login-container">
        <h2>Iniciar Sesión</h2>

        <?php if ($mensajeError != ""): ?>
            <p style="color:red;"><?php echo $mensajeError; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="usuario" placeholder="Usuario">
            <input type="password" name="password" placeholder="Contraseña">
            <button type="submit">Ingresar</button>
        </form>
    </div>

</body>

</html>
