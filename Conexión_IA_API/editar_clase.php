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
    /* ESTILOS GENERALES */
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #0d1b2a;
      color: #cbd5e1;
      overflow-x: hidden; 
    }

    /* EL CONTENEDOR MAESTRO (Animación Flexbox) */
    .main-wrapper {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      gap: 20px;
      padding: 30px 20px;
      max-width: 1400px;
      margin: 0 auto;
      transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    /* EL FORMULARIO PRINCIPAL */
    .container {
      width: 100%;
      max-width: 900px;
      background: #0d1b2a; 
      transition: all 0.5s ease;
    }

    h1 { color: #60a5fa; margin-bottom: 20px; margin-top: 0; }
    label { margin: 10px 0 6px; font-weight: bold; color: #93c5fd; display: block; }

    input[type="text"], select {
      padding: 10px; border-radius: 6px; border: none; width: 100%;
      background: #1e293b; color: #cbd5e1; font-size: 1rem; box-sizing: border-box;
    }

    input[type="file"] { 
      background: #1e293b; padding: 10px; border: none; color: #cbd5e1; 
      width: 100%; box-sizing: border-box; 
    }

    .btn-guardar {
      margin-top: 20px; padding: 12px 20px; background: #2563eb; color: white;
      font-weight: bold; border: none; border-radius: 6px; cursor: pointer; width: 100%;
    }
    .btn-guardar:hover { background: #3b82f6; }

    /* EL EDITOR QUILL */
    #editor { height: 300px; background: white; border-radius: 6px; }

    /* EL BOTÓN DE LA IA (Alineado con la etiqueta Descripción) */
    .header-descripcion {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      margin-top: 15px;
      margin-bottom: 8px;
    }
    .header-descripcion label { margin: 0; }
    .btn-ia {
      background: #3b82f6; color: white; border: none; padding: 6px 12px;
      border-radius: 6px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 5px;
      transition: background 0.3s; font-size: 0.9rem;
    }
    .btn-ia:hover { background: #60a5fa; }

    /* EL PANEL ROJO DE LA IA (Oculto por defecto) */
    .panel-ia {
      width: 0;
      padding: 0; 
      opacity: 0;
      pointer-events: none; /* Escudo: No estorba clics si está oculto */
      overflow: hidden;
      background: #1e293b;
      border-radius: 10px;
      border-left: 0px solid #f97316; 
      transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
      box-shadow: 0 10px 30px rgba(0,0,0,0.5);
      height: auto;
      max-height: 80vh;
      display: flex;
      flex-direction: column;
    }

    /* ESTADO ACTIVO: Cuando presionas el botón de Evaluar */
    .main-wrapper.modo-evaluacion .panel-ia {
      width: 450px; 
      padding: 20px;
      opacity: 1;
      pointer-events: auto; /* Se vuelve clickeable */
      border-left: 4px solid #f97316;
    }

    .panel-ia h2 { color: #f97316; margin-top: 0; font-size: 1.5rem; }
    #respuesta-ia { overflow-y: auto; flex: 1; color: #cbd5e1; line-height: 1.5; padding-right: 10px; }
    #respuesta-ia h3 { color: #60a5fa; font-size: 1.1rem; }
    
    .btn-cerrar-ia {
      background: #ef4444; color: white; border: none; padding: 10px; width: 100%;
      border-radius: 6px; font-weight: bold; cursor: pointer; margin-top: 15px;
    }
    .btn-cerrar-ia:hover { background: #dc2626; }

    /* DISEÑO RESPONSIVO (Para móviles) */
    @media (max-width: 900px) {
      .main-wrapper { flex-direction: column; }
      .main-wrapper.modo-evaluacion .panel-ia { width: 100%; }
    }
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
    // 1. Configuración del Editor Quill
    const toolbarOptions = [
      [{ 'header': [1, 2, 3, false] }],
      ['bold', 'italic', 'underline', 'strike'],
      [{ 'color': [] }, { 'background': [] }],
      [{ 'list': 'ordered' }, { 'list': 'bullet' }],
      ['code-block'],
      ['clean']
    ];

    const quill = new Quill('#editor', {
      theme: 'snow',
      modules: { toolbar: toolbarOptions }
    });

    document.querySelector('.ql-editor').style.color = '#000';
    document.querySelector('.ql-editor').style.backgroundColor = '#fff';

    // Al guardar el formulario, pasa el HTML de Quill al input oculto
    document.getElementById('formCrearClase').onsubmit = function() {
      document.getElementById('descripcionInput').value = quill.root.innerHTML.trim();
    };

    // ==========================================
    // 2. LÓGICA DE LA IA EVALUADORA (RUTA CORREGIDA)
    // ==========================================
    const wrapper = document.getElementById('wrapper-principal');
    const btnEvaluar = document.getElementById('btn-evaluar-ia');
    const btnCerrar = document.getElementById('btn-cerrar-ia');
    const cajaRespuesta = document.getElementById('respuesta-ia');

    // Botón para cerrar y ocultar el panel
    btnCerrar.addEventListener('click', () => {
      wrapper.classList.remove('modo-evaluacion');
    });

    // Botón para evaluar la clase
    btnEvaluar.addEventListener('click', async () => {
      const titulo = document.getElementById('titulo').value;
      const descripcion = quill.getText().trim(); // Lee el texto limpio

      if (!titulo || !descripcion) {
        alert("¡Escribe un título y una descripción primero para que la IA tenga algo que analizar!");
        return;
      }

      // Despliega el panel rojo
      wrapper.classList.add('modo-evaluacion');
      cajaRespuesta.innerHTML = "<p><i>Analizando la pedagogía de tu clase... ⏳</i></p>";

      // Prepara el mensaje para la IA
      const consultaPrompt = `Por favor analiza la siguiente clase que estoy preparando para mis alumnos.\n\nTítulo de la clase: ${titulo}\n\nDescripción de la clase: ${descripcion}`;

      try {
        const response = await fetch('Conexión_IA_API/Evaluador_Clases.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ mensaje: consultaPrompt })
        });

        // RAYOS X: Leemos el texto crudo exactamente como llega del servidor
        const textoCrudo = await response.text(); 
        console.log("Respuesta oculta del servidor:", textoCrudo);

        try {
            // Intentamos convertirlo a JSON
            const data = JSON.parse(textoCrudo);
            
            if(data.respuesta) {
                cajaRespuesta.innerHTML = marked.parse(data.respuesta);
            } else {
                cajaRespuesta.innerHTML = `<p>⚠️ Error técnico: ${JSON.stringify(data)}</p>`;
            }
        } catch (parseError) {
            // Si falla, IMPRIMIMOS EL TEXTO CRUDO EN EL PANEL ROJO
            cajaRespuesta.innerHTML = `
                <p>⚠️ <b>El servidor no devolvió JSON. Devolvió esto:</b></p>
                <div style="background: #000; color: #0f0; padding: 10px; border-radius: 5px; font-family: monospace; overflow-x: auto;">
                    ${textoCrudo.substring(0, 500)} </div>
                <p>Revisa qué dice el texto verde arriba. Si es código HTML, la ruta sigue mal. Si es un "Fatal error" o "Warning", el problema está dentro de tu PHP.</p>
            `;
        }
        
      } catch (e) {
        cajaRespuesta.innerHTML = `<p>⚠️ <b>Error de conexión de red:</b> ${e.message}</p>`;
      }
    });
  </script>

</body>
</html>
