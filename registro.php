<?php
include_once './includes/head.php';

$mensajeError = "";
$usuarioPrellenado = "";

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'usuarioexistente':
            $mensajeError = "Ese usuario ya existe.";
            break;
        case 'passdiff':
            $mensajeError = "Las contraseñas no coinciden.";
            break;
    }
}

if (isset($_GET['user'])) {
    $usuarioPrellenado = htmlspecialchars($_GET['user']);
}
?>

<link rel="shortcut icon" href="imagenes/logo.png" type="image/x-icon">

<body>

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

        .incio-sesion {
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

        .login-texto {
            font-size: 1.6rem;
            font-weight: bold;
            color: var(--vino);
            text-align: center;
        }

        .login-imagen {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-imagen img {
            width: 90px;
        }

        form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .input-container {
            display: flex;
            align-items: center;
            background: #f3f3f3;
            border-radius: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            transition: 0.3s;
        }

        .input-container:focus-within {
            border-color: var(--rojo-principal);
            box-shadow: 0 0 8px rgba(167, 32, 31, 0.3);
        }

        .input-container input {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            padding-left: 10px;
            font-size: 0.95rem;
        }

        .input-icon {
            color: var(--vino);
            font-size: 1.2rem;
        }

        .submit-container {
            margin-top: 10px;
        }

        .boton-entrar {
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

        .boton-entrar:hover {
            transform: scale(1.03);
        }

        #error-mensajes {
            width: 100%;
        }

        .error-msg {
            background: #ffe5e5;
            color: #b00020;
            border: 1px solid #b00020;
            padding: 10px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .input-error {
            border: 2px solid #b00020 !important;
        }
    </style>

    <div class="incio-sesion">
        <div class="login-texto">Registro dgtIA</div>
        <div class="login-imagen">
            <img src="imagenes/logo.png">
        </div>




        <form name="Registro" action="guardarNuevoUsuario.php" method="post">

            <div class="input-container">
                <ion-icon name="person-circle-outline" class="input-icon"></ion-icon>
                <input name="nombre" id="id_ipt_nombre" type="text" placeholder="Nombre">
            </div>

            <div class="input-container">
                <ion-icon name="person-outline" class="input-icon"></ion-icon>
                <input name="usuario" id="id_ipt_usuario" type="text" placeholder="Usuario"
                    value="<?= $usuarioPrellenado ?>">
            </div>

            <div class="input-container">
                <ion-icon name="lock-closed-outline" class="input-icon"></ion-icon>
                <input name="password" id="id_ipt_password" type="password" placeholder="Contraseña">
            </div>

            <div class="input-container">
                <ion-icon name="lock-closed-outline" class="input-icon"></ion-icon>
                <input name="password2" id="id_ipt_password2" type="password" placeholder="Confirmar contraseña">
            </div>

            <div id="error-mensajes">
                <?php if (!empty($mensajeError)) : ?>
                    <div class="error-msg"><?= $mensajeError ?></div>
                <?php endif; ?>
            </div>

            <div class="submit-container">
                <input type="submit" class="boton-entrar" value="Registrar">
            </div>

        </form>

    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>

    <script>
        document.querySelector('form[name="Registro"]').addEventListener('submit', function(e) {

            const nombre = document.getElementById('id_ipt_nombre');
            const usuario = document.getElementById('id_ipt_usuario');
            const password = document.getElementById('id_ipt_password');
            const password2 = document.getElementById('id_ipt_password2');
            const errorBox = document.getElementById('error-mensajes');

            [nombre, usuario, password, password2].forEach(el => el.classList.remove('input-error'));
            errorBox.innerHTML = '';

            function error(msg) {
                const div = document.createElement('div');
                div.className = 'error-msg';
                div.textContent = msg;
                errorBox.appendChild(div);
            }

            if (nombre.value.trim() === '') {
                nombre.classList.add('input-error');
                error('El nombre es obligatorio');
                e.preventDefault();
                return;
            }

            if (usuario.value.trim() === '') {
                usuario.classList.add('input-error');
                error('El usuario es obligatorio');
                e.preventDefault();
                return;
            }

            if (password.value.trim() === '') {
                password.classList.add('input-error');
                error('La contraseña es obligatoria');
                e.preventDefault();
                return;
            }

            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
            if (!regex.test(password.value)) {
                password.classList.add('input-error');
                error('Mínimo 8 caracteres, una mayúscula, una minúscula y un número');
                e.preventDefault();
                return;
            }

            if (password2.value.trim() === '') {
                password2.classList.add('input-error');
                error('Confirma la contraseña');
                e.preventDefault();
                return;
            }

            if (password.value !== password2.value) {
                password2.classList.add('input-error');
                error('Las contraseñas no coinciden');
                e.preventDefault();
                return;
            }
        });
    </script>

</body>
