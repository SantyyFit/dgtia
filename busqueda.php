<?php
include_once 'includes/dbconexion.php';
include_once 'includes/head.php';
?>

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

    body.body-busqueda {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--dorado-claro);
        color: var(--vino);
        min-height: 100vh;
        padding-top: 80px;
        padding-bottom: 60px;
    }

    /* ── Header ── */
    .header-busqueda {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 65px;
        background-color: var(--vino);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        border-bottom: 3px solid var(--dorado-oscuro);
        box-shadow: 0 2px 12px rgba(105, 28, 50, 0.4);
        padding: 0 20px;
    }

    .buscar-container {
        width: 100%;
        max-width: 600px;
    }

    .buscar-container form {
        display: flex;
        align-items: center;
    }

    .input-buscar {
        width: 100%;
        padding: 10px 18px;
        border-radius: 30px;
        border: 2px solid var(--dorado-oscuro);
        background-color: rgba(221, 201, 163, 0.15);
        color: var(--blanco);
        font-size: 1rem;
        outline: none;
        transition: background 0.3s, border 0.3s;
    }

    .input-buscar::placeholder {
        color: var(--dorado-claro);
        opacity: 0.8;
    }

    .input-buscar:focus {
        background-color: rgba(221, 201, 163, 0.25);
        border-color: var(--blanco);
    }

    /* ── Main ── */
    .main-busqueda {
        max-width: 650px;
        margin: 0 auto;
        padding: 20px 16px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* ── Tarjeta de usuario ── */
    .usuario-card {
        background-color: rgba(221, 201, 163, 0.5);
        border-radius: 18px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 12px rgba(105, 28, 50, 0.2);
        border-left: 4px solid var(--dorado-oscuro);
        transition: transform 0.25s ease, background 0.25s ease;
        cursor: pointer;
    }

    .usuario-card:hover {
        background-color: var(--rojo-principal);
        transform: translateY(-3px);
        border-left-color: var(--vino);
    }

    .usuario-card:hover .usuario-info a {
        color: var(--blanco);
    }

    /* ── Foto de perfil ── */
    .perfil-img {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--dorado-oscuro);
        box-shadow: 0 0 8px rgba(188, 149, 92, 0.5);
        cursor: pointer;
        transition: transform 0.2s;
    }

    .perfil-img:hover {
        transform: scale(1.08);
    }

    /* ── Info del usuario ── */
    .usuario-info a {
        text-decoration: none;
        color: var(--vino);
        font-size: 1.05rem;
        font-weight: 600;
        transition: color 0.2s;
    }

    .usuario-info a:hover {
        color: var(--blanco);
    }

    /* ── Modal imagen ── */
    .modal {
        display: none;
        position: fixed;
        inset: 0;
        background-color: rgba(105, 28, 50, 0.85);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    .modal-contenido {
        max-width: 85vw;
        max-height: 85vh;
        border-radius: 16px;
        border: 3px solid var(--dorado-oscuro);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        object-fit: contain;
    }

    .cerrar {
        position: absolute;
        top: 18px;
        right: 28px;
        font-size: 2.5rem;
        color: var(--dorado-claro);
        cursor: pointer;
        font-weight: bold;
        transition: color 0.2s;
        line-height: 1;
    }

    .cerrar:hover {
        color: var(--blanco);
    }

    /* ── Responsive ── */
    @media (max-width: 600px) {
        .input-buscar {
            font-size: 0.9rem;
            padding: 9px 14px;
        }

        .perfil-img {
            width: 44px;
            height: 44px;
        }

        .usuario-info a {
            font-size: 0.95rem;
        }
    }
</style>

<body class="body-busqueda">

    <header class="header-busqueda">
        <div class="buscar-container">
            <form action="busqueda.php?user=<?= $_GET["user"] ?>&i=<?= $_GET["i"] ?>" method="post">
                <input
                    class="input-buscar"
                    type="search"
                    name="busuarios"
                    list="listadoUsuarios"
                    placeholder="Buscar usuarios..."
                    onchange="this.form.submit()" />
                <datalist id="listadoUsuarios">
                    <?php
                    $queryConsultaUsuarios = "SELECT usuario FROM usuarios ORDER BY usuario ASC";
                    $resqcu = mysqli_query($conexion, $queryConsultaUsuarios);
                    while ($row = mysqli_fetch_assoc($resqcu)) {
                        echo '<option value="' . htmlspecialchars($row['usuario']) . '"/>';
                    }
                    ?>
                </datalist>
            </form>
        </div>
    </header>

    <main class="main-busqueda">
        <?php
        if (isset($_POST["busuarios"])) {
            $usuariobuscado = mysqli_real_escape_string($conexion, $_POST["busuarios"]);
            $querybuscausuario = "SELECT * FROM usuarios WHERE usuario = '$usuariobuscado'";
            $consulta = mysqli_query($conexion, $querybuscausuario);

            while ($row = mysqli_fetch_array($consulta)) {
                $idusuarioencontrado = $row["idusuario"];
                $usuarioencontrado   = $row["usuario"];
                $fotoPerfilUsuario   = $row["img_perfil"];
        ?>
                <div class="usuario-card">
                    <img
                        src="<?= htmlspecialchars($fotoPerfilUsuario) ?>"
                        alt="Foto de perfil"
                        class="perfil-img"
                        onclick="expandirImagen(this.src)">
                    <div class="usuario-info">
                        <a href="perfilUsuario.php?user=<?= urlencode($_GET['user']) ?>&UsuarioB=<?= urlencode($usuarioencontrado) ?>&idUsuarioB=<?= $idusuarioencontrado ?>&i=<?= $_GET['i'] ?>">
                            <?= htmlspecialchars($usuarioencontrado) ?>
                        </a>
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </main>

    <!-- Modal imagen expandida -->
    <div id="modalImagen" class="modal" onclick="cerrarModal(event)">
        <span class="cerrar" onclick="cerrarModal(event)">&times;</span>
        <img class="modal-contenido" id="imgExpandida">
    </div>

    <?php include_once 'includes/header.php'; ?>

    <script>
        function expandirImagen(src) {
            const modal = document.getElementById("modalImagen");
            const img = document.getElementById("imgExpandida");
            modal.style.display = "flex";
            img.src = src;
        }

        function cerrarModal(e) {
            if (e.target.id === "modalImagen" || e.target.classList.contains("cerrar")) {
                document.getElementById("modalImagen").style.display = "none";
            }
        }
    </script>

</body>


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
  <!-- ------------------------------------------------------------- Termina ------------------------------------------------------------------- -->
