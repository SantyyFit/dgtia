<?php // Datos de conexión directa (evita includes conflictivos)

include_once 'includes/PDOdb.php';

// Obtener ID de la notificación y URL de destino
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$url = isset($_GET['url']) ? $_GET['url'] : 'index.php'; // Redirección por defecto

if ($id) {
    try {
        $stmt = $pdo->prepare("UPDATE notificaciones SET visto = 1 WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        die("Error al marcar como vista: " . $e->getMessage());
    }
}

// Redirigir a la URL original (usamos header)
header("Location: " . $url);
exit;
?>
