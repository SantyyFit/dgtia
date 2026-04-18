<?php
session_start();
include_once 'includes/PDOdb.php';

header('Content-Type: application/json');

if (!isset($_SESSION['idusuario']) || !isset($_GET['id'])) {
    echo json_encode(['error' => 'Acceso denegado']);
    exit();
}

$id_usuario = intval($_SESSION['idusuario']);
$id_seguido = intval($_GET['id']);

if ($id_usuario == $id_seguido) {
    echo json_encode(['error' => 'No puedes seguirte a ti mismo']);
    exit();
}

try {
    // Obtener nombre usuario que hace follow para el mensaje
    $stmt = $pdo->prepare("SELECT usuario FROM usuarios WHERE idusuario = ?");
    $stmt->execute([$id_usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $nombre_usuario = $user ? $user['usuario'] : 'Alguien';

    // Verificar si ya sigue
    $check = $pdo->prepare("SELECT 1 FROM seguidores WHERE id_usuario = ? AND id_seguido = ?");
    $check->execute([$id_usuario, $id_seguido]);

    if ($check->rowCount() > 0) {
        // Dejar de seguir
        $stmt = $pdo->prepare("DELETE FROM seguidores WHERE id_usuario = ? AND id_seguido = ?");
        $stmt->execute([$id_usuario, $id_seguido]);
        $accion = 'seguir';

        $mensaje = "$nombre_usuario ha dejado de seguirte.";
        $url_notif = "verSeguidores.php?id=$id_seguido";
        
        // Crear notificación
        $notif = $pdo->prepare("INSERT INTO notificaciones (idusuario, tipo, mensaje, url, visto) VALUES (?, ?, ?, ?, 0)");
        $notif->execute([$id_seguido, "seguidores", $mensaje, $url_notif]);
    } else {
        // Seguir
        $stmt = $pdo->prepare("INSERT INTO seguidores (id_usuario, id_seguido) VALUES (?, ?)");
        $stmt->execute([$id_usuario, $id_seguido]);
        $accion = 'siguiendo';

        $mensaje = "$nombre_usuario ha comenzado a seguirte.";
        $url_notif = "verSeguidores.php?id=$id_seguido";
        
        // Crear notificación
        $notif = $pdo->prepare("INSERT INTO notificaciones (idusuario, tipo, mensaje, url, visto) VALUES (?, ?, ?, ?, 0)");
        $notif->execute([$id_seguido, "seguidores", $mensaje, $url_notif]);
    }

    // Obtener contadores actualizados
    $seg_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM seguidores WHERE id_seguido = ?");
    $seg_stmt->execute([$id_seguido]);
    $seguidores_count = $seg_stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $seguidos_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM seguidores WHERE id_usuario = ?");
    $seguidos_stmt->execute([$id_usuario]);
    $seguidos_count = $seguidos_stmt->fetch(PDO::FETCH_ASSOC)['total'];

    echo json_encode([
        'accion' => $accion,
        'seguidores_count' => $seguidores_count,
        'seguidos_count' => $seguidos_count,
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
