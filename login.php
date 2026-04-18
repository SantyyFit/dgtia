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

        $usuario = mysqli_real_escape_string($conexion, $usuario);
        $password = mysqli_real_escape_string($conexion, $password);

        $password = md5($password);

        $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' AND password='$password' LIMIT 1";
        $resultado = mysqli_query($conexion, $sql);

        if ($resultado && mysqli_num_rows($resultado) > 0) {

            $fila = mysqli_fetch_assoc($resultado);

            $_SESSION['idusuario'] = $fila['idusuario'];
            $_SESSION['usuario'] = $fila['usuario'];

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

    <style>
        :root {
            --rojo-principal: #a7201f;
            --rojo-secundario: #9f2241;
            --vino: #691c32;
            --dorado-claro: #ddc9a3;
            --dorado-oscuro: #bc955c;
            --gris-claro: #98989a;
            --gris-oscuro: #6f7271;
            --blanco: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, var(--vino), var(--rojo-principal));
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* CONTENEDOR */
        .login-container {
            width: 100%;
            max-width: 420px;
            background: var(--dorado-claro);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: center;
        }

        /* LOGO */
        .logo {
            width: 90px;
        }

        /* TITULO */
        .login-container h2 {
            font-size: 1.6rem;
            font-weight: bold;
            color: var(--vino);
            text-align: center;
        }

        /* FORM */
        form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* INPUTS */
        form input {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #ddd;
            outline: none;
            font-size: 0.95rem;
            transition: 0.3s;
        }

        form input:focus {
            border-color: var(--rojo-principal);
            box-shadow: 0 0 8px rgba(167, 32, 31, 0.3);
        }

        /* BOTON */
        form button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(90deg, var(--rojo-principal), var(--vino));
            color: var(--blanco);
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        form button:hover {
            transform: scale(1.03);
        }

        /* ERROR */
        .error {
            background: #ffe5e5;
            color: #b00020;
            border: 1px solid #b00020;
            padding: 10px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            width: 100%;
            text-align: center;
        }

        /* REGISTRO */
        .registro {
            margin-top: 10px;
            font-size: 14px;
            color: var(--gris-oscuro);
            text-align: center;
        }

        .registro a {
            color: var(--vino);
            font-weight: bold;
            text-decoration: none;
        }

        .registro a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="login-container">

        <!-- LOGO -->
        <img src="imagenes/logo.png" class="logo" alt="Logo">

        <h2>Iniciar Sesión</h2>

        <?php if ($mensajeError != ""): ?>
            <p class="error"><?php echo $mensajeError; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="usuario" placeholder="Usuario">
            <input type="password" name="password" placeholder="Contraseña">
            <button type="submit">Ingresar</button>
        </form>

        <!-- REGISTRO -->
        <div class="registro">
            ¿No tienes cuenta? <a href="registro.php">Regístrate</a>
        </div>

    </div>

</body>

</html>
