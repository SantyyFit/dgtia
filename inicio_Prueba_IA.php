<?php
include_once 'includes/session.php'

?>

<?php
$usuario = $_GET['user'];
?>

<?
include_once 'includes/dbconexion.php'
?>

<?php

include_once './includes/head.php';

?>


<link rel="stylesheet" href="css/inicioo.css">


<body>
    <header class="header-foro" id="Home">
        <h1>Foro de Aprendizaje</h1>
    </header>


    <section class="portfolio" id="Portfolio">
        <div class="portfolio_project-container">
            <div class="portfolio_project troncoComun" style=" background-image: linear-gradient(#0009, #0009), url(imagenes/troncocomun.jpg)">
                <h2><a href="troncoComun.php?user=<?= $_GET["user"]; ?>&i=<?= $_GET["i"]; ?>" style="text-decoration: none; color:inherit">Tronco Común</a></h2>
            </div>
            <div class="portfolio_project Modulos" style=" background-image: linear-gradient(#0009, #0009), url(imagenes/modulos.jpg)">
                <h2><a href="modulos.php?user=<?= $_GET["user"]; ?>&i=<?= $_GET["i"]; ?>" style="text-decoration: none; color:inherit">Módulos</a></h2>
            </div>
        </div>
    </section>




    <?
    include_once 'includes/header.php';
    ?>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<script>
  window.MathJax = { tex: { inlineMath: [['$', '$'], ['\\(', '\\)']], displayMath: [['$$', '$$'], ['\\[', '\\]']] } };
</script>

<style>
  /* 1. LA BURBUJA (Abajo a la derecha) */
  #dgt-burbuja {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 65px;
    height: 65px;
    background: #a7201f;
    border-radius: 50%;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    cursor: pointer;
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: transform 0.3s ease;
    overflow: hidden;
  }
  #dgt-burbuja:hover {
    transform: scale(1.08);
  }

  /* 2. EL CONTENEDOR DEL CHAT (Con animación de despliegue) */
  #dgt-chat-container {
    position: fixed;
    bottom: 110px; /* Un poco más arriba de la burbuja */
    right: 30px;   /* Alineado a la derecha */
    width: 380px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    z-index: 9998;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    font-family: system-ui, sans-serif;
    
    /* MAGIA VISUAL: Empieza invisible y chiquito desde la esquina inferior derecha */
    opacity: 0;
    visibility: hidden;
    transform: scale(0.1) translateY(100px);
    transform-origin: bottom right; 
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  }

  /* 3. CLASE ACTIVA (Cuando presionas la burbuja) */
  #dgt-chat-container.abierto {
    opacity: 1;
    visibility: visible;
    transform: scale(1) translateY(0); /* Crece a su tamaño normal */
  }

  /* 4. EL ENCABEZADO */
  .dgt-header {
    background: #a7201f;
    color: white;
    padding: 12px 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-weight: bold;
  }
  .dgt-header-info { display: flex; align-items: center; gap: 10px; }
  .dgt-btn-cerrar { background: none; border: none; color: white; font-size: 20px; cursor: pointer; }

  /* 5. LA CAJA DE MENSAJES */
  .dgt-chat-box {
    height: 400px;
    overflow-y: auto;
    padding: 15px;
    background: linear-gradient(135deg, #fdfbf7, #f7edc4);
    display: flex;
    flex-direction: column;
  }
  .dgt-msg {
    margin: 8px 0;
    padding: 10px 14px;
    border-radius: 15px;
    max-width: 85%;
    line-height: 1.4;
    word-wrap: break-word;
    font-size: 0.95rem;
  }
  .dgt-user { background: #ddc9a3; color: #333; align-self: flex-end; border-bottom-right-radius: 2px; }
  .dgt-bot { background: white; color: #333; align-self: flex-start; border-bottom-left-radius: 2px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }

  /* 6. ÁREA DE TEXTO */
  .dgt-input-area { display: flex; border-top: 1px solid #ddd; background: white; }
  .dgt-input-area input { flex: 1; padding: 15px; border: none; outline: none; }
  .dgt-input-area button { background: #a7201f; color: white; border: none; padding: 0 20px; cursor: pointer; font-weight: bold; }
</style>

<div id="dgt-burbuja" onclick="toggleDgtChat()">
  <img src="Conexion_API_IA/ruta_logo_robot.png" alt="IA" style="width: 100%; height: 100%; object-fit: cover;">
</div>

<div id="dgt-chat-container">
  <div class="dgt-header">
    <div class="dgt-header-info">
      <img src="Conexion_API_IA/ruta_logo_robot.png" alt="Logo" style="width: 30px; height: 30px; border-radius: 50%;">
      <span>Asistente degetIA</span>
    </div>
    <button class="dgt-btn-cerrar" onclick="toggleDgtChat()">✖</button>
  </div>
  
  <div id="dgt-chat-historial" class="dgt-chat-box">
    <div class="dgt-msg dgt-bot">¡Hola! Soy degetIA. ¿En qué te puedo ayudar hoy con Matemáticas, Inglés o Programación?</div>
  </div>

  <div class="dgt-input-area">
    <input type="text" id="dgt-input" placeholder="Escribe aquí..." onkeypress="dgtManejarEnter(event)">
    <button onclick="llamarDgtIA()">Enviar</button>
  </div>
</div>

<script>
  // NUEVO: Función más inteligente para abrir y cerrar con animación
  function toggleDgtChat() {
    const chat = document.getElementById('dgt-chat-container');
    chat.classList.toggle('abierto');
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
        const response = await fetch('Conexion_API_IA/API.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ mensaje: texto })
        });

        const data = await response.json();
        const burbujaIA = document.getElementById(idTemporal);
        
        burbujaIA.innerHTML = marked.parse(data.respuesta);

        if (window.MathJax && window.MathJax.typesetPromise) {
            MathJax.typesetPromise([burbujaIA]).catch((err) => console.log(err));
        }

        chatBox.scrollTop = chatBox.scrollHeight; 
        
    } catch (e) {
        document.getElementById(idTemporal).innerHTML = "⚠️ Error de conexión con el servidor.";
    }
  }
</script>
</body>
