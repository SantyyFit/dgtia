<?php
session_start();
include_once 'includes/PDOdb.php';

if (!isset($_GET['id'])) {
    echo "Clase no especificada.";
    exit();
}

$id_clase = $_GET['id'];

// Obtener clase y creador
$stmt = $pdo->prepare("
    SELECT c.*, u.usuario AS creador_nombre
    FROM clases c
    JOIN usuarios u ON c.id_creador = u.idusuario
    WHERE c.id_clase = ?
");
$stmt->execute([$id_clase]);
$clase = $stmt->fetch();

if (!$clase) {
    echo "Clase no encontrada.";
    exit();
}


// Obtener materiales
$stmt = $pdo->prepare("
    SELECT tipo, contenido
    FROM materiales_clase
    WHERE id_clase = ?
");
$stmt->execute([$id_clase]);
$materiales = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($clase['titulo']) ?> - NewSkill</title>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<style>
    body {
        background: #0f172a;
        color: #e2e8f0;
        font-family: sans-serif;
        padding: 20px;
    }
    a { color: #60a5fa; text-decoration: none; }
    .contenedor {
        max-width: 700px;
        margin: auto;
        background: #1e293b;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(100, 149, 237, 0.1);
    }
    h1 { color: #93c5fd; }
    .material {
        margin-bottom: 25px;
    }
    img {
        max-width: 100%;
        border-radius: 8px;
    }
    video {
        width: 100%;
        border-radius: 8px;
    }
    iframe {
        width: 100%;
        height: 500px;
        border: none;
    }
</style>
</head>
<body>
<div class="contenedor">
    <h1><?= htmlspecialchars($clase['titulo']) ?></h1>
    <p><strong>Por:</strong> <?= htmlspecialchars($clase['creador_nombre']) ?></p>
    <p><strong>Descripción:</strong></p>
<div class="ql-editor" style="background:white; color:black; padding:10px; border-radius:8px;">
  <?= $clase['descripcion']; ?>
</div>


    <hr>

    <h2>Materiales</h2>

    <?php if ($materiales): ?>
        <?php foreach ($materiales as $m): ?>
            <div class="material">
                <?php
                $tipo = $m['tipo'];
                $archivo = htmlspecialchars($m['contenido']);

                if ($tipo === 'imagen') {
                    echo "<img src='$archivo' alt='Imagen'>";
                } elseif ($tipo === 'pdf') {
                    echo "<iframe src='$archivo'></iframe>";
                } elseif ($tipo === 'video') {
                    echo "<video controls><source src='$archivo' type='video/mp4'>Tu navegador no soporta video.</video>";
                } else {
                    echo "<a href='$archivo' download>Descargar archivo</a>";
                }
                ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay materiales en esta clase.</p>
    <?php endif; ?>
</div>
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
    #dgt-burbuja {
      position: fixed;
      bottom: 110px;
      right: 30px;
      width: 65px;
      height: 65px;
      background: #691c32;
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
      background: white;
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
      background: #691c32;
      color: white !important;
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
      color: white;
    }

    .dgt-chat-box {
      flex: 1;
      overflow-y: auto;
      padding: 15px;
      background-color: #ddc9a3;
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
      background: #ddc9a3;
      align-self: flex-end;
      border-bottom-right-radius: 2px;
    }

    .dgt-bot {
      background: white;
      align-self: flex-start;
      border-bottom-left-radius: 2px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
    }

    .dgt-input-area {
      flex: none;
      display: flex;
      border-top: 1px solid #ddd;
      background: white;
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
      color: #666666;
      font-weight: normal;
    }

    .dgt-input-area button {
      background: #691c32;
      color: white;
      border: none;
      padding: 0 20px;
      cursor: pointer;
      font-weight: bold;
      font-size: 0.95rem;
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
</body>
</html>
