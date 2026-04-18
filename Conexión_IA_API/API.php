<?php
header('Content-Type: application/json');

$api_key = 'AIzaSyBH631OdSR1JEpGn_hHn5yMaB7nB0ELCSI'; 

$input = file_get_contents('php://input');
$data = json_decode($input, true);
$pregunta = $data['mensaje'] ?? '';

if (empty($pregunta)) {
    echo json_encode(['respuesta' => 'Error: Mensaje vacío.']);
    exit;
}

// EL PAQUETE DE DATOS DEGET-IA
$payload = json_encode([
    "systemInstruction" => [
        "parts" => [
            [ 
                "text" => "Eres el asistente oficial de IA del proyecto degetIA. 
                Eres un experto en Historia, Humanidades, Ciencias Sociales, Español, Quimica, Física, Biologia, Matemáticas, Inglés, Programación, Construcción, Inteligencia Artificial, Laboratorista Clinico y Contabilidad
                Tus respuestas deben ser directas, amigables y MUY breves, a menos de que se requiera una explicación profunda. 
                Si te preguntan de otra cosa, responde amablemente que tu función exclusiva es asistir con degetIA. 
                REGLA DE EJEMPLOS: Cuando expliques matemáticas, química o programación, adjunta un ejemplo y su solución paso a paso. 
                REGLA DE FORMATO MATEMÁTICO: DEBES usar formato LaTeX para todas las fórmulas y ecuaciones. 
                Usa doble signo de dólar para ecuaciones en su propia línea ($$ ecuación $$) 
                y un solo signo para ecuaciones en el texto ($ ecuación $)."
            ]
        ]
    ],
    "contents" => [
        [
            "parts" => [
                ["text" => $pregunta]
            ]
        ]
    ],
]);
// EL RADAR INTELIGENTE
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

// SISTEMA DE REDUNDANCIA: Si un modelo está saturado, intenta con el siguiente
$modelos_de_rescate = [
    'gemini-3.1-flash-lite-preview', 
    'gemini-2.5-flash',              
    'gemini-2.0-flash-lite-001'      
];

$texto_final = null;
$ultimo_error = "";

foreach ($modelos_de_rescate as $modelo) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/$modelo:generateContent?key=" . $api_key;
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    $resData = json_decode($response, true);

    // Si el modelo NO dio error (no está saturado ni bloqueado)
    if (!isset($resData['error'])) {
        $texto_final = extraerTexto($resData);
        if ($texto_final) {
            break; // ¡Éxito! Rompemos el ciclo y dejamos de buscar
        }
    } else {
        $ultimo_error = $resData['error']['message'];
    }
}

// SALIDA AL HTML
if ($texto_final) {
    echo json_encode(['respuesta' => $texto_final]);
} else {
    echo json_encode(['respuesta' => 'Todos los servidores están saturados en este momento. Intenta en un minuto. (Error: ' . $ultimo_error . ')']);
}
?>