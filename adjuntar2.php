<?php
include_once 'includes/dbconexion.php';
include_once 'includes/session.php';

// ─── Validar parámetro obligatorio ───────────────────────────────────────────
if (!isset($_GET["i"]) || (int)$_GET["i"] <= 0) {
    die("Acceso no válido.");
}

$i    = (int) $_GET["i"];
$user = isset($_GET["user"]) ? trim($_GET["user"]) : '';

// ─── Buscar usuario por ID entero con Prepared Statement ─────────────────────
$stmt = $conexion->prepare("SELECT idusuario, usuario FROM usuarios WHERE idusuario = ?");
if (!$stmt) die("Error en la consulta.");

$stmt->bind_param("i", $i);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) die("Usuario no encontrado.");

$f               = $result->fetch_assoc();
$stmt->close();

$idusuarioLimpio = (int) $f["idusuario"];
$Nombre          = htmlspecialchars($f["usuario"], ENT_QUOTES, 'UTF-8');
$userEsc         = htmlspecialchars($user,         ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Actualizar Foto — NewSkill</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');

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

        /* ─── RESET ──────────────────────────────────────────────────────────────────── */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ─── BODY ───────────────────────────────────────────────────────────────────── */
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, var(--vino) 0%, var(--rojo-principal) 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-bottom: 40px;
        }

        /* ─── HEADER ─────────────────────────────────────────────────────────────────── */
        .header {
            width: 100%;
            background: rgba(105, 28, 50, 0.88);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            padding: 18px 30px;
            text-align: center;
            border-bottom: 2px solid var(--dorado-oscuro);
            margin-bottom: 36px;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dorado-claro);
            letter-spacing: 2.5px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        /* ─── CONTENEDOR PRINCIPAL ───────────────────────────────────────────────────── */
        .container {
            width: 100%;
            max-width: 440px;
            padding: 0 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        /* ─── LOGO ───────────────────────────────────────────────────────────────────── */
        .logo {
            width: 110px;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.3));
        }

        /* ─── TÍTULO ─────────────────────────────────────────────────────────────────── */
        h1 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--blanco);
            text-align: center;
            line-height: 1.6;
            letter-spacing: 0.5px;
        }

        h1 span {
            font-size: 1rem;
            font-weight: 600;
            color: var(--dorado-claro);
        }

        /* ─── FORMULARIO ─────────────────────────────────────────────────────────────── */
        form {
            width: 100%;
            background: var(--dorado-claro);
            border-radius: 20px;
            padding: 30px 24px;
            box-shadow:
                0 12px 40px rgba(0, 0, 0, 0.3),
                0 2px 8px rgba(105, 28, 50, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        /* ─── INPUT FILE (oculto) ────────────────────────────────────────────────────── */
        input[type="file"] {
            display: none;
        }

        /* ─── LABEL ESTILIZADO COMO BOTÓN ────────────────────────────────────────────── */
        .file-label {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 13px 20px;
            border-radius: 10px;
            background: linear-gradient(90deg, var(--rojo-principal), var(--vino));
            color: var(--blanco);
            font-family: 'Montserrat', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s, filter 0.2s;
            box-shadow: 0 4px 16px rgba(105, 28, 50, 0.4);
        }

        .file-label:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 22px rgba(105, 28, 50, 0.55);
            filter: brightness(1.06);
        }

        .file-label:active {
            transform: scale(0.98);
        }

        /* ─── HINT DEBAJO DEL BOTÓN ──────────────────────────────────────────────────── */
        .file-hint {
            font-size: 0.78rem;
            color: var(--gris-oscuro);
            text-align: center;
            letter-spacing: 0.3px;
            margin-top: -6px;
        }

        /* ─── MENSAJES DE ESTADO ─────────────────────────────────────────────────────── */
        .upload-msg {
            width: 100%;
            min-height: 20px;
        }

        .alert {
            width: 100%;
            padding: 11px 16px;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: fadeIn 0.35s ease;
        }

        .alert-loading {
            background: #fff8e1;
            border: 1.5px solid var(--dorado-oscuro);
            color: #7a5c00;
        }

        .alert-error {
            background: #ffebee;
            border: 1.5px solid #ef9a9a;
            color: #b71c1c;
        }

        .alert-success,
        .alert-dismissible {
            background: #e8f5e9;
            border: 1.5px solid #a5d6a7;
            color: #2e7d32;
        }

        /* ─── ANIMACIONES ────────────────────────────────────────────────────────────── */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ─── RESPONSIVE ─────────────────────────────────────────────────────────────── */
        @media (max-width: 480px) {
            .header {
                font-size: 1rem;
                letter-spacing: 1.5px;
                padding: 16px 20px;
            }

            h1 {
                font-size: 1rem;
            }

            form {
                padding: 24px 18px;
            }

            .logo {
                width: 90px;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <i class="fas fa-camera"></i> Actualizar foto de perfil
    </div>

    <div class="container">
        <img src="imagenes/logo.png" alt="Logo dgtIA" class="logo">

        <h1>
            Foto de perfil<br>
            <span><?= $Nombre ?></span>
        </h1>

        <form id="uploadForm">
            <label for="fileToUpload" class="file-label">
                <i class="fas fa-upload"></i> Seleccionar imagen
            </label>
            <input type="file" id="fileToUpload" accept="image/*" onchange="upload_image();">
            <p class="file-hint">JPG, PNG o GIF · Máx. 2 MB</p>
            <div class="upload-msg"></div>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        function upload_image() {
            const msgBox = document.querySelector(".upload-msg");
            const fileInput = document.getElementById("fileToUpload");
            const file = fileInput.files[0];

            if (!file) return;

            // Validar tamaño en cliente (2 MB)
            if (file.size > 2 * 1024 * 1024) {
                msgBox.innerHTML = '<div class="alert alert-error"><i class="fas fa-times-circle"></i> El archivo supera los 2 MB.</div>';
                return;
            }

            msgBox.innerHTML = '<div class="alert alert-loading"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>';

            var data = new FormData();
            data.append('fileToUpload', file);

            $.ajax({
                url: "upload2.php?user=<?= $userEsc ?>&i=<?= $idusuarioLimpio ?>&idLimpio=<?= $idusuarioLimpio ?>",
                type: "POST",
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    msgBox.innerHTML = response;
                    setTimeout(function() {
                        const alertElem = document.querySelector(".alert-dismissible");
                        if (alertElem) {
                            alertElem.style.transition = "opacity 0.5s";
                            alertElem.style.opacity = 0;
                            setTimeout(() => alertElem.remove(), 500);
                        }
                    }, 5000);
                },
                error: function() {
                    msgBox.innerHTML = '<div class="alert alert-error"><i class="fas fa-times-circle"></i> Error al subir la imagen. Intenta de nuevo.</div>';
                }
            });
        }
    </script>
</body>

</html>
