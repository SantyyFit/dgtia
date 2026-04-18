/*async function llamarIA() {
    const input = document.getElementById("input_ia");
    const output = document.getElementById("resultado_ia");
    const texto = input.value;

    if (!texto) return;
    output.innerText = "Pensando...";

    try {
        // Llamamos al archivo PHP que subiste a Neubox
        const response = await fetch('API.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ mensaje: texto })
        });

        const data = await response.json();
        output.innerText = data.respuesta;
    } catch (e) {
        output.innerText = "Error en la conexión.";
    }
}*/
/*
async function llamarIA() {
    const input = document.getElementById("input_ia");
    const output = document.getElementById("resultado_ia");
    const texto = input.value;

    if (!texto) return;
    
    output.innerHTML = "Pensando..."; 

    try {
        const response = await fetch('API.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ mensaje: texto })
        });

        // 1. Leemos la respuesta cruda del servidor primero
        const textoCrudo = await response.text();

        try {
            // 2. Intentamos convertirlo a JSON
            const data = JSON.parse(textoCrudo);
            
            // 3. Verificamos si la librería Marked se cargó correctamente en el HTML
            if (typeof marked !== 'undefined') {
                output.innerHTML = marked.parse(data.respuesta);
            } else {
                output.innerHTML = "⚠️ Te faltó poner la librería Marked en el HTML. Respuesta cruda:<br><br>" + data.respuesta;
            }

        } catch (errorParseo) {
            // 4. Si falla la conversión, es culpa de PHP. Mostramos qué dijo PHP.
            output.innerHTML = "⚠️ Error del servidor PHP:<br>" + textoCrudo;
        }
        
    } catch (e) {
        // 5. Errores críticos de red
        output.innerHTML = "⚠️ Error crítico de Javascript: " + e.message;
    }
}*/

async function llamarIA() {
    const input = document.getElementById("input_ia");
    const chatBox = document.getElementById("chat");
    const texto = input.value;

    if (!texto.trim()) return; // Si está vacío, no hace nada

    // 1. Crear y mostrar la burbuja del USUARIO
    chatBox.innerHTML += `<div class="message user">${texto}</div>`;
    input.value = ""; 
    chatBox.scrollTop = chatBox.scrollHeight; 

    // 2. Crear burbuja temporal de la IA ("Pensando...")
    const idTemporal = "ia_" + Date.now(); 
    chatBox.innerHTML += `<div class="message bot" id="${idTemporal}"><i>Pensando...</i></div>`;
    chatBox.scrollTop = chatBox.scrollHeight;

    try {
        const response = await fetch('API.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ mensaje: texto })
        });

        const data = await response.json();
        
        // 3. Insertar la respuesta real con formato (Markdown)
        const burbujaIA = document.getElementById(idTemporal);
        burbujaIA.innerHTML = marked.parse(data.respuesta);

        // 4. Transformar las fórmulas matemáticas (MathJax)
        if (window.MathJax) {
            MathJax.typesetPromise();
        }

        chatBox.scrollTop = chatBox.scrollHeight; 
        
    } catch (e) {
        document.getElementById(idTemporal).innerHTML = "⚠️ Error de conexión con el servidor.";
    }
}

// Función extra para enviar el mensaje con la tecla Enter
function manejarEnter(event) {
    if (event.key === "Enter") {
        llamarIA();
    }
}