<?php
require_once 'includes/auth.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap');

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

        .login-container {
            width: 100%;
            max-width: 420px;
            background: var(--dorado-claro);
            border-radius: 24px;
            padding: 40px 30px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: fadeIn 0.6s ease;
        }

        .logo {
            width: 90px;
            margin-bottom: 12px;
            filter: drop-shadow(0 4px 8px rgba(105, 28, 50, 0.3));
        }

        .titulo {
            color: var(--vino);
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .input-container {
            display: flex;
            align-items: center;
            background: #f9f6f0;
            border-radius: 12px;
            padding: 11px 14px;
            margin-bottom: 14px;
            border: 1.5px solid #e0d5c0;
            transition: border 0.25s, box-shadow 0.25s;
        }

        .input-container:focus-within {
            border-color: var(--dorado-oscuro);
            box-shadow: 0 0 0 3px rgba(188, 149, 92, 0.2);
        }

        .input-container input {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            padding-left: 10px;
            font-size: 0.92rem;
            font-family: 'Montserrat', sans-serif;
            color: #333;
        }

        .input-container input::placeholder {
            color: var(--gris-claro);
        }

        .input-icon {
            color: var(--vino);
            font-size: 1.2rem;
        }

        .input-error {
            border-color: #b00020 !important;
            box-shadow: 0 0 0 3px rgba(176, 0, 32, 0.15) !important;
        }

        .toggle-password {
            cursor: pointer;
            color: var(--gris-claro);
            font-size: 1.1rem;
            background: none;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: var(--vino);
        }

        .error-msg {
            background: #ffe5e5;
            color: #b00020;
            border: 1px solid #f5c0c0;
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 14px;
            font-size: 0.85rem;
            font-weight: 600;
            text-align: left;
            animation: shake 0.35s ease;
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(90deg, var(--rojo-principal), var(--vino));
            color: var(--blanco);
            font-weight: 700;
            font-size: 0.95rem;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: transform 0.25s, box-shadow 0.25s;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(105, 28, 50, 0.4);
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        .recuperar {
            display: block;
            margin: 12px 0 16px;
            font-size: 0.82rem;
            color: var(--gris-oscuro);
            text-decoration: none;
            transition: color 0.2s;
        }

        .recuperar:hover {
            color: var(--vino);
        }

        .footer-text {
            margin-top: 18px;
            font-size: 0.82rem;
            color: var(--gris-oscuro);
        }

        .footer-text a {
            color: var(--vino);
            font-weight: 700;
            text-decoration: none;
        }

        .footer-text a:hover {
            color: var(--rojo-principal);
        }

        .divisor {
            border: none;
            border-top: 1px solid rgba(105, 28, 50, 0.15);
            margin: 18px 0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-6px);
            }

            50% {
                transform: translateX(6px);
            }

            75% {
                transform: translateX(-4px);
            }
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 16px;
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <img src="imagenes/logo.png" class="logo" alt="Logo">
        <div class="titulo">DGETIA</div>

        <?php if (!empty($mensajeError)): ?>
            <div class="error-msg">
                <?php echo htmlspecialchars($mensajeError); ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="formLogin" novalidate>
            <div class="input-container" id="wrap-usuario">
                <input type="text" name="usuario" id="usuario"
                    placeholder="Usuario o email"
                    value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>"
                    maxlength="100" autocomplete="username">
            </div>

            <div class="input-container" id="wrap-password">
                <input type="password" name="password" id="password"
                    placeholder="Contraseña"
                    maxlength="100" autocomplete="current-password">
                <button type="button" class="toggle-password" id="togglePass">👁</button>
            </div>

            <a class="recuperar" href="recuperar.php">¿Olvidaste tu contraseña?</a>

            <button type="submit" class="btn-submit">Entrar</button>

            <hr class="divisor">

            <div class="footer-text">
                ¿No tienes cuenta? <a href="registro.php">Regístrate</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById("formLogin").addEventListener("submit", function(e) {
            const wu = document.getElementById("wrap-usuario");
            const wp = document.getElementById("wrap-password");
            const u = document.getElementById("usuario");
            const p = document.getElementById("password");

            wu.classList.remove("input-error");
            wp.classList.remove("input-error");

            let ok = true;

            if (u.value.trim() === "") {
                wu.classList.add("input-error");
                u.focus();
                ok = false;
            }

            if (p.value.trim() === "") {
                wp.classList.add("input-error");
                if (ok) p.focus();
                ok = false;
            }

            if (!ok) e.preventDefault();
        });

        document.getElementById("togglePass").addEventListener("click", function() {
            const input = document.getElementById("password");
            const type = input.type === "password" ? "text" : "password";
            input.type = type;
            this.textContent = type === "password" ? "👁" : "-";
        });
    </script>
</body>

</html>
