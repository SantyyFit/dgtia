<?php
include_once './includes/head.php';

session_start();

if (isset($_SESSION['idusuario'])) {
    header("Location: inicio.php");
    exit();
}
?>

<link rel="stylesheet" href="css/logueo.css">

<body>
    <div>
        <div>Login NewSkill</div>
        <div>
            <img src="imagenes/LogoNS250.png" alt="Logo NewSkill">
        </div>
    </div>

    <div>
        <form name="Login" action="authh.php" method="post">

            <div class="input-container">
                <ion-icon name="person" class="input-icon"></ion-icon>
                <input name="usuario" id="id_ipt_usuario" type="text" maxlength="50" placeholder="Usuario o correo" />
            </div>

            <div class="input-container">
                <ion-icon name="lock-closed" class="input-icon"></ion-icon>
                <input name="password" id="id_ipt_password" type="password" maxlength="50" placeholder="Contraseña" />
            </div>

            <!-- Error -->
            <?php if (isset($_GET['error'])) { ?>
                <div class="error server-error">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php } ?>

            <div class="input-container">
                <input type="submit" value="Entrar">
            </div>

            <div>
                Si no tiene cuenta <a href="registro.php">Regístrese aquí</a>
            </div>
            <br>
            <a href="recuperar_contrasena.php" style="font-size: 10px;">¿Olvidaste tu contraseña?</a>

        </form>
    </div>

    <script>
        document.querySelector('form[name="Login"]').addEventListener('submit', function(e) {
            const usuario = document.getElementById('id_ipt_usuario');
            const password = document.getElementById('id_ipt_password');

            usuario.classList.remove('input-error');
            password.classList.remove('input-error');

            if (usuario.value.trim() === '' || password.value.trim() === '') {
                e.preventDefault();
                alert("Completa todos los campos");
            }
        });
    </script>
</body>
