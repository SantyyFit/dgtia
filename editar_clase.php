<?php
session_start();
if (!isset($_SESSION['idusuario'])) {
  header('Location: login.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Crear Clase - NewSkill</title>

  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

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

    html,
    body {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, var(--dorado-oscuro), var(--dorado-claro));
      color: var(--blanco);
      overflow-x: hidden;
    }

    .main-wrapper {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      gap: 20px;
      padding: 30px 20px;
      max-width: 1400px;
      margin: 0 auto;
      transition: all 0.5s ease;
    }

    .container {
      width: 100%;
      max-width: 900px;
      background: linear-gradient(13deg, var(--rojo-principal), var(--rojo-secundario));
      backdrop-filter: blur(12px);
      border: 1px solid rgba(221, 201, 163, 0.2);
      border-radius: 16px;
      padding: 25px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    h1 {
      color: var(--dorado-claro);
      margin-bottom: 20px;
    }

    label {
      margin: 10px 0 6px;
      font-weight: bold;
      color: var(--dorado-claro);
      display: block;
    }

    input[type="text"],
    select {
      padding: 12px;
      border-radius: 10px;
      border: 1px solid var(--gris-claro);
      width: 100%;
      background: rgba(255, 255, 255, 0.95);
      color: #000;
      font-size: 1rem;
      outline: none;
    }

    input[type="file"] {
      background: var(--blanco);
      padding: 10px;
      border-radius: 10px;
      width: 100%;
      border: 1px dashed var(--gris-claro);
      color: #000;
    }

    .btn-guardar {
      margin-top: 20px;
      padding: 12px 20px;
      background: linear-gradient(135deg, var(--dorado-claro), var(--dorado-oscuro));
      color: var(--blanco);
      font-weight: bold;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      width: 100%;
      transition: 0.3s;
    }

    .btn-guardar:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    }

    #editor {
      height: 300px;
      background: var(--blanco);
      border-radius: 10px;
      color: #000;
    }

    .ql-toolbar {
      background: var(--gris-oscuro);
      border: none !important;
      border-radius: 10px 10px 0 0;
    }

    .ql-toolbar button,
    .ql-toolbar .ql-picker {
      filter: invert(1);
    }

    .ql-container {
      border: none !important;
      border-radius: 0 0 10px 10px;
    }

    .ql-editor {
      color: #000;
      font-size: 15px;
    }

    .header-descripcion {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 15px;
      margin-bottom: 8px;
    }

    .btn-ia {
      background: linear-gradient(135deg, var(--rojo-secundario), var(--dorado-claro));
      color: white;
      border: none;
      padding: 7px 14px;
      border-radius: 10px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn-ia:hover {
      transform: scale(1.05);
    }

    .panel-ia {
      width: 0;
      padding: 0;
      opacity: 0;
      pointer-events: none;
      overflow: hidden;
      background: rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      border-left: 0px solid var(--dorado-oscuro);
      transition: all 0.5s ease;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
      display: flex;
      flex-direction: column;
    }

    .main-wrapper.modo-evaluacion .panel-ia {
      width: 450px;
      padding: 20px;
      opacity: 1;
      pointer-events: auto;
      border-left: 4px solid var(--dorado-oscuro);
    }

    .panel-ia h2 {
      color: var(--dorado-claro);
    }

    #respuesta-ia {
      flex: 1;
      overflow-y: auto;
      color: var(--blanco);
      padding-right: 10px;
    }

    #respuesta-ia h3 {
      color: var(--dorado-claro);
    }

    .btn-cerrar-ia {
      margin-top: 15px;
      padding: 10px;
      background: var(--rojo-principal);
      border: none;
      border-radius: 10px;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }

    .btn-cerrar-ia:hover {
      background: var(--rojo-secundario);
    }

    @media (max-width: 900px) {
      .main-wrapper {
        flex-direction: column;
      }

      .main-wrapper.modo-evaluacion .panel-ia {
        width: 100%;
      }
    }
  </style>
  </style>
</head>

<body>

  <div class="main-wrapper" id="wrapper-principal">

    <div class="container">
      <h1>Crear nueva clase</h1>
      <form action="crear_clase.php" method="POST" enctype="multipart/form-data" id="formCrearClase">

        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo" required>

        <div class="header-descripcion">
          <label for="descripcion">Descripción:</label>
          <button type="button" class="btn-ia" id="btn-evaluar-ia">✨ Evaluar con IA</button>
        </div>

        <div id="editor"></div>
        <input type="hidden" name="descripcion" id="descripcionInput" required>

        <label for="visibilidad">Visibilidad:</label>
        <select name="visibilidad" id="visibilidad" required>
          <option value="privada">Privada</option>
          <option value="publica">Pública</option>
        </select>

        <label for="materiales">Materiales (PDF, imágenes, etc.):</label>
        <input type="file" name="materiales[]" id="materiales" multiple>

        <button type="submit" name="crear_clase" class="btn-guardar">Guardar clase</button>
      </form>
    </div>

    <div class="panel-ia" id="panel-evaluador">
      <h2>Recomendaciones IA</h2>
      <div id="respuesta-ia">
        <p>Haz clic en "Evaluar con IA" para analizar tu clase...</p>
      </div>
      <button type="button" class="btn-cerrar-ia" id="btn-cerrar-ia">Cerrar Panel</button>
    </div>

  </div>

  <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
  <script>
    // Configuración de Quill
    const toolbarOptions = [
      [{
        'header': [1, 2, 3, false]
      }],
      ['bold', 'italic', 'underline', 'strike'],
      [{
        'color': []
      }, {
        'background': []
      }],
      [{
        'list': 'ordered'
      }, {
        'list': 'bullet'
      }],
      ['code-block'],
      ['clean']
    ];

    const quill = new Quill('#editor', {
      theme: 'snow',
      modules: {
        toolbar: toolbarOptions
      }
    });

    document.querySelector('.ql-editor').style.color = '#000';
    document.querySelector('.ql-editor').style.backgroundColor = '#fff';

    document.getElementById('formCrearClase').onsubmit = function() {
      document.getElementById('descripcionInput').value = quill.root.innerHTML.trim();
    };

    // ==========================================
    // LÓGICA DE LA IA EVALUADORA (RUTA EXACTA APLICADA)
    // ==========================================
    const wrapper = document.getElementById('wrapper-principal');
    const btnEvaluar = document.getElementById('btn-evaluar-ia');
    const btnCerrar = document.getElementById('btn-cerrar-ia');
    const cajaRespuesta = document.getElementById('respuesta-ia');

    btnCerrar.addEventListener('click', () => {
      wrapper.classList.remove('modo-evaluacion');
    });

    btnEvaluar.addEventListener('click', async () => {
      const titulo = document.getElementById('titulo').value;
      const descripcion = quill.getText().trim();

      if (!titulo || !descripcion) {
        alert("¡Escribe un título y una descripción primero para que la IA tenga algo que analizar!");
        return;
      }

      wrapper.classList.add('modo-evaluacion');
      cajaRespuesta.innerHTML = "<p><i>Analizando la pedagogía de tu clase... ⏳</i></p>";

      // 1. Preparamos el mensaje normal
      const consultaPrompt = `Por favor analiza la siguiente clase que estoy preparando para mis alumnos.\n\nTítulo de la clase: ${titulo}\n\nDescripción de la clase: ${descripcion}`;

      // 2. MAGIA ANTI-FIREWALL: Encriptamos a Base64 (Soportando acentos de español)
      const textoSeguro = btoa(unescape(encodeURIComponent(consultaPrompt)));

      try {
        // RUTA CON TILDE (Como a ti te funciona)
        const response = await fetch('Conexión_IA_API/Evaluador_Clases.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            mensaje: textoSeguro
          }) // <--- Enviamos el encriptado
        });

        const data = await response.json();

        if (data.respuesta) {
          cajaRespuesta.innerHTML = marked.parse(data.respuesta);
        } else {
          cajaRespuesta.innerHTML = `<p>⚠️ Error técnico: ${JSON.stringify(data)}</p>`;
        }

      } catch (e) {
        console.error("Error de Fetch:", e);
        cajaRespuesta.innerHTML = `<p>⚠️ <b>Error de conexión:</b> No se pudo conectar con Conexión_IA_API/Evaluador_Clases.php. Revisa la consola (F12).</p>`;
      }
    });
  </script>

</body>

</html>
