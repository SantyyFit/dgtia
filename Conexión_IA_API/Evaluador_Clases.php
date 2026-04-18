<?php
// 1. ESCUDO ANTI-ERRORES HTML: Forzamos a que cualquier error salga como JSON
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

// 2. TU LLAVE (Asegúrate de pegar tu llave real de Google aquí)
$api_key = ''; 

// 3. RECIBIR DATOS Y DESENCRIPTAR (Magia anti-firewall)
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Recibimos el texto encriptado en Base64
$pregunta_encriptada = $data['mensaje'] ?? '';

// Lo desencriptamos de vuelta a texto normal para la IA
$pregunta = base64_decode($pregunta_encriptada);

// Si entra vacío o hubo un error al desencriptar
if (empty($pregunta)) {
    echo json_encode(['respuesta' => '⚠️ Error: No recibí ningún texto válido desde el formulario.']);
    exit;
}

// 4. EL CEREBRO DE LA IA (El Prompt)
$payload = json_encode([
    "systemInstruction" => [
        "parts" => [
            [
                "text" => "Eres un experto en pedagogía, diseño instruccional y técnicas de educación moderna. Tu tarea es analizar el Título y la Descripción de una clase que un profesor está creando. Debes dar recomendaciones constructivas, amigables y muy específicas sobre cómo hacer la clase más atractiva, clara y pedagógicamente efectiva para los alumnos. Usa viñetas, negritas y un tono motivador. No uses formato LaTeX, solo texto normal y Markdown."
            ]
        ]
    ],
    "contents" => [ [ "parts" => [ ["text" => $pregunta] ] ] ],
    "generationConfig" => [ "maxOutputTokens" => 800 ]
]);

// 5. SISTEMA DE RESCATE
$modelos_de_rescate = [
    'gemini-3.1-flash-lite-preview', 
    'gemini-2.5-flash', 
    'gemini-2.0-flash-lite-001'
];

$texto_final = null;
$ultimo_error = "Desconocido";

function extraerTexto($array) {
    if (is_array($array)) {
        if (isset($array['text'])) return $array['text'];
        foreach ($array as $valor) {
            $resultado = extraerTexto($valor);
            if ($resultado !== null) return $resultado;
        }
    }
    return null;
}

// 6. CONEXIÓN CON GOOGLE
foreach ($modelos_de_rescate as $modelo) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/$modelo:generateContent?key=" . $api_key;
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    // Muy importante para servidores reales como cruzsantiago.com:
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    
    $response = curl_exec($ch);
    
    if ($response === false) {
        $ultimo_error = "Error interno del servidor (cURL falló).";
        curl_close($ch);
        continue;
    }
    
    curl_close($ch);
    $resData = json_decode($response, true);

    if (!isset($resData['error'])) {
        $texto_final = extraerTexto($resData);
        if ($texto_final) {
            break; // Éxito
        }
    } else {
        $ultimo_error = $resData['error']['message'] ?? "Google rechazó la conexión.";
    }
}

// 7. RESPUESTA FINAL A JAVASCRIPT (Siempre en JSON)
if ($texto_final) {
    echo json_encode(['respuesta' => $texto_final]);
} else {
    echo json_encode(['respuesta' => '⚠️ Error de Google: ' . $ultimo_error]);
}
?>