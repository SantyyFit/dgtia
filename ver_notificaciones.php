<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['idusuario'])) {
    die("Debes iniciar sesión para ver las notificaciones.");
}

$id_usuario = $_SESSION['idusuario'];


try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=cruzzsan_dgtia;charset=utf8",
        "cruzzsan_usuario",
        "NuevaContraseñaSegura"
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error conectando a la base de datos: " . $e->getMessage());
}


try {
    $stmt = $pdo->prepare("SELECT id, tipo, mensaje, url FROM notificaciones WHERE idusuario = ? AND visto = 0 ORDER BY id DESC");
    $stmt->execute([$id_usuario]);
    $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error al obtener notificaciones: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>dgtIA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="imagenes/logo.png" type="image/x-icon" />
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
            --fondo: #0f172a;
            --card: rgba(255, 255, 255, 0.06);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--dorado-claro), var(--dorado-oscuro));
            color: var(--blanco);
            min-height: 100vh;
            padding: 20px;
        }

        .notificaciones-wrapper {
            max-width: 700px;
            margin: auto;
            background: var(--vino);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(221, 201, 163, 0.15);
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
        }

        h3 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: var(--dorado-claro);
            font-size: 22px;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
        }

        .globo {
            background: var(--dorado-claro);
            color: var(--vino);
            font-weight: bold;
            font-size: 13px;
            padding: 4px 10px;
            border-radius: 20px;
        }

        p {
            color: var(--gris-claro);
            text-align: center;
            padding: 15px 0;
        }

        .notificacion {
            margin-bottom: 12px;
            border-radius: 12px;
            overflow: hidden;
            transition: 0.3s;
            border: 1px solid rgba(221, 201, 163, 0.1);
            background: rgba(255, 255, 255, 0.04);
        }

        .notificacion:hover {
            transform: translateY(-2px);
            border-color: rgba(188, 149, 92, 0.4);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.25);
        }

        .notificacion a {
            display: block;
            padding: 14px 16px;
            text-decoration: none;
            color: var(--blanco);
            font-size: 15px;
            transition: 0.3s;
        }

        .notificacion a:hover {
            color: var(--dorado-claro);
        }

        .notificacion::before {
            content: "";
            display: block;
            height: 3px;
            width: 100%;
            background: linear-gradient(90deg, var(--dorado-oscuro), transparent);
            opacity: 0.6;
        }
    </style>
</head>

<body>

    <div class="notificaciones-wrapper">
        <h3>Notificaciones
            <?php if (count($notificaciones) > 0): ?>
                <span class="globo"><?= count($notificaciones) ?></span>
            <?php endif; ?>
        </h3>

        <?php if (empty($notificaciones)): ?>
            <p>No tienes notificaciones nuevas.</p>
        <?php else: ?>
            <?php foreach ($notificaciones as $n): ?>
                <div class="notificacion">
                    <a href="marcar_visto.php?id=<?= $n['id'] ?>&url=<?= urlencode($n['url']) ?>">
                        <?= htmlspecialchars($n['mensaje']) ?>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        function marcarComoVisto(event, id, url) {
            event.preventDefault();
            fetch('marcar_visto.php?id=' + id, {
                    method: 'GET'
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error al marcar como visto');
                    window.location.href = url;
                })
                .catch(error => {
                    console.error('Falló:', error);
                    window.location.href = url;
                });
        }
    </script>

    <?php include 'includes/header.php'; ?>

    <!-- ----------------------------------- IA Flotante --------------------------------------------------------------------------------------------- -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <script>
        window.MathJax = {
            tex: {
                inlineMath: [
                    ['$', '$'],
                    ['\\(', '\\)']
                ],
                displayMath: [
                    ['$$', '$$'],
                    ['\\[', '\\]']
                ]
            }
        };
    </script>

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

        #dgt-burbuja {
            position: fixed;
            bottom: 110px;
            right: 30px;
            width: 65px;
            height: 65px;
            background: var(--vino);
            border-radius: 50%;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
            cursor: pointer;
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        #dgt-burbuja:hover {
            transform: scale(1.1);
        }

        #dgt-chat-container {
            position: fixed;
            bottom: 190px;
            right: 30px;
            width: 380px;
            height: 500px;
            background: var(--blanco);
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            z-index: 9998;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            font-family: system-ui, -apple-system, sans-serif;

            opacity: 0;
            visibility: hidden;
            transform: scale(0.1) translateY(100px);
            transform-origin: bottom right;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        #dgt-chat-container.abierto {
            opacity: 1;
            visibility: visible;
            transform: scale(1) translateY(0);
        }

        .dgt-header {
            flex: none;
            background: var(--vino);
            color: var(--blanco) !important;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: bold;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }

        .dgt-header-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dgt-btn-cerrar {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            font-size: 20px;
            cursor: pointer;
            transition: color 0.3s;
        }

        .dgt-btn-cerrar:hover {
            color: var(--blanco);
        }

        .dgt-chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            background-color: var(--dorado-claro);
            display: flex;
            flex-direction: column;
        }

        .dgt-msg {
            margin: 8px 0;
            padding: 11px 15px;
            border-radius: 15px;
            max-width: 85%;
            line-height: 1.5;
            word-break: break-word;
            overflow-wrap: anywhere;

            color: #000000 !important;
            font-weight: 500 !important;
            font-size: 0.95rem;

            text-align: left !important;
        }

        .dgt-msg ul,
        .dgt-msg ol {
            margin: 10px 0;
            padding-left: 25px !important;
            text-align: left !important;
        }

        .dgt-msg li {
            margin-bottom: 5px;
            text-align: left !important;
        }

        .dgt-msg pre {
            background: rgba(0, 0, 0, 0.05);
            padding: 10px;
            border-radius: 8px;
            overflow-x: auto;
        }

        .dgt-msg * {
            color: #000000 !important;
            font-weight: 500 !important;
        }

        .dgt-user {
            background: var(--dorado-claro);
            align-self: flex-end;
            border-bottom-right-radius: 2px;
        }

        .dgt-bot {
            background: var(--blanco);
            align-self: flex-start;
            border-bottom-left-radius: 2px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
        }

        .dgt-input-area {
            flex: none;
            display: flex;
            border-top: 1px solid var(--gris-claro);
            background: var(--blanco);
        }

        .dgt-input-area input {
            flex: 1;
            padding: 15px;
            border: none;
            outline: none;
            font-size: 0.95rem;
            color: #000000;
            font-weight: 500;
        }

        .dgt-input-area input::placeholder {
            color: var(--gris-oscuro);
            font-weight: normal;
        }

        .dgt-input-area button {
            background: var(--vino);
            color: var(--blanco);
            border: none;
            padding: 0 20px;
            cursor: pointer;
            font-weight: bold;
            font-size: 0.95rem;
            transition: background 0.3s ease;
        }

        .dgt-input-area button:hover {
            background: var(--rojo-principal);
        }
    </style>

    <div id="dgt-burbuja" onclick="toggleDgtChat()">
        <img src="dgetIA_burbuja (1).png" alt="IA" style="width: 100%; height: 100%; object-fit: cover;">
    </div>

    <div id="dgt-chat-container">
        <div class="dgt-header">
            <div class="dgt-header-info">
                <img src="dgetIA_burbuja (1).png" alt="Logo" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                <span>Asistente degetIA</span>
            </div>
            <button class="dgt-btn-cerrar" onclick="toggleDgtChat()">✖</button>
        </div>

        <div id="dgt-chat-historial" class="dgt-chat-box">
            <div class="dgt-msg dgt-bot">¡Hola! Soy degetIA. ¿En qué te puedo ayudar hoy?</div>
        </div>

        <div class="dgt-input-area">
            <input type="text" id="dgt-input" placeholder="Escribe aquí..." onkeypress="dgtManejarEnter(event)">
            <button onclick="llamarDgtIA()">Enviar</button>
        </div>
    </div>

    <script>
        function toggleDgtChat() {
            document.getElementById('dgt-chat-container').classList.toggle('abierto');
        }

        function dgtManejarEnter(event) {
            if (event.key === "Enter") llamarDgtIA();
        }

        async function llamarDgtIA() {
            const input = document.getElementById("dgt-input");
            const chatBox = document.getElementById("dgt-chat-historial");
            const texto = input.value;

            if (!texto.trim()) return;

            chatBox.innerHTML += `<div class="dgt-msg dgt-user">${texto}</div>`;
            input.value = "";
            chatBox.scrollTop = chatBox.scrollHeight;

            const idTemporal = "dgt_" + Date.now();
            chatBox.innerHTML += `<div class="dgt-msg dgt-bot" id="${idTemporal}"><i>Pensando...</i></div>`;
            chatBox.scrollTop = chatBox.scrollHeight;

            try {
                const response = await fetch('Conexión_IA_API/API.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        mensaje: texto
                    })
                });

                const data = await response.json();
                const burbujaIA = document.getElementById(idTemporal);

                burbujaIA.innerHTML = marked.parse(data.respuesta);

                if (window.MathJax && window.MathJax.typesetPromise) {
                    MathJax.typesetPromise([burbujaIA]).catch((err) => console.log(err));
                }

                chatBox.scrollTop = chatBox.scrollHeight;

            } catch (e) {
                document.getElementById(idTemporal).innerHTML = "⚠️ Error de conexión con el servidor degetIA.";
            }
        }
    </script>
    <!-- ------------------------------------------------------------- Termina ------------------------------------------------------------------- -->
</body>

</html>
