<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/includes/PDOdb.php';
require_once __DIR__ . '/notificaciones.php';

if (!isset($_SESSION['idusuario'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'No autenticado']);
    exit();
}

$pdo = Conectarse();

$emisor = intval($_SESSION['idusuario']);
$receptor = isset($_POST['receptor']) ? intval($_POST['receptor']) : 0;
$mensaje = trim($_POST['mensaje'] ?? '');

if ($receptor <= 0 || $mensaje === '' || $receptor === $emisor) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos de mensaje inválidos']);
    exit();
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO mensajes (id_emisor, id_receptor, mensaje, created_at, visto) VALUES (?, ?, ?, NOW(), 0)"
    );
    $stmt->execute([$emisor, $receptor, $mensaje]);

    $stmtNombre = $pdo->prepare("SELECT usuario FROM usuarios WHERE idusuario = ?");
    $stmtNombre->execute([$emisor]);
    $nombre_emisor = $stmtNombre->fetchColumn() ?: 'Usuario';

    $stmtReceptor = $pdo->prepare("SELECT usuario FROM usuarios WHERE idusuario = ?");
    $stmtReceptor->execute([$receptor]);
    $nombre_receptor = $stmtReceptor->fetchColumn() ?: 'Usuario';

    $i = md5($receptor);
    $url_chat = "chat.php?id=$emisor&user=" . urlencode($nombre_receptor) . "&UsuarioB=" . urlencode($nombre_emisor) . "&idUsuarioB=$emisor&i=$i";

    try {
        crearNotificacion(
            $receptor,
            'mensaje',
            "$nombre_emisor te envió un mensaje",
            $url_chat,
            $pdo
        );
    } catch (PDOException $e) {
        error_log('Error notificación mensaje: ' . $e->getMessage());
        echo json_encode(['success' => true, 'warning' => 'Mensaje enviado, notificación falló']);
        exit();
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    error_log('Error al enviar mensaje: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error al enviar mensaje']);
}
