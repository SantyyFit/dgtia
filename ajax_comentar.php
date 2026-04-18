<?php
session_start();
header('Content-Type: application/json');
include_once 'includes/PDOdb.php';

if (!isset($_SESSION['idusuario'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$idpost = $_POST['id_post'];
$comentario = trim($_POST['comentario']);
$id_padre = isset($_POST['id_padre']) && $_POST['id_padre'] !== '' ? (int)$_POST['id_padre'] : null;
$idusuario = $_SESSION['idusuario'];

if (empty($comentario)) {
    echo json_encode(['success' => false, 'message' => 'Comentario vacío']);
    exit;
}

// Insertar comentario
if ($id_padre) {
    $stmt = $pdo->prepare("INSERT INTO comentarios (id_publicacion, id_usuario, comentario, id_padre, fecha) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$idpost, $idusuario, $comentario, $id_padre]);
} else {
    $stmt = $pdo->prepare("INSERT INTO comentarios (id_publicacion, id_usuario, comentario, fecha) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$idpost, $idusuario, $comentario]);
}

// Crear notificación para el autor del post
$stmtPost = $pdo->prepare("SELECT idusuario FROM posts WHERE idPost = ?");
$stmtPost->execute([$idpost]);
$autorPost = $stmtPost->fetchColumn();

if ($autorPost && $autorPost != $idusuario) {
    // Obtener nombre del comentarista
    $stmtUser = $pdo->prepare("SELECT usuario FROM usuarios WHERE idusuario = ?");
    $stmtUser->execute([$idusuario]);
    $nombreComent = $stmtUser->fetchColumn();

    // Crear notificación
    $mensaje = "$nombreComent comentó en tu publicación";
    $url = "publicaciones.php?user=" . urlencode($_GET['user'] ?? '') . "&i=" . urlencode($_GET['i'] ?? '') . "&esp=" . urlencode($_GET['esp'] ?? '');
    $stmtNotif = $pdo->prepare("INSERT INTO notificaciones (idusuario, tipo, mensaje, url, visto) VALUES (?, 'comentario', ?, ?, 0)");
    $stmtNotif->execute([$autorPost, $mensaje, $url]);
}

// Obtener nombre del usuario y fecha
$stmtUser = $pdo->prepare("SELECT usuario FROM usuarios WHERE idusuario = ?");
$stmtUser->execute([$idusuario]);
$usuario = $stmtUser->fetchColumn();

echo json_encode([
    'success' => true,
    'usuario' => $usuario,
    'comentario' => htmlspecialchars($comentario),
    'fecha' => date('Y-m-d H:i:s')
]);
?>
