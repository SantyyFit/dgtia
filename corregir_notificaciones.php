<?php
// Script para corregir notificaciones existentes con visto = NULL
header('Content-Type: text/html; charset=utf-8');

require_once 'includes/PDOdb.php';

try {
    $pdo = Conectarse();

    // Contar notificaciones con visto = NULL
    $countStmt = $pdo->query("SELECT COUNT(*) as total FROM notificaciones WHERE visto IS NULL");
    $totalNull = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    echo "<h2>Corrigiendo notificaciones existentes...</h2>";
    echo "<p>Notificaciones con visto = NULL encontradas: <strong>$totalNull</strong></p>";

    if ($totalNull > 0) {
        // Actualizar todas las notificaciones con visto = NULL a visto = 0
        $updateStmt = $pdo->prepare("UPDATE notificaciones SET visto = 0 WHERE visto IS NULL");
        $affected = $updateStmt->execute();

        echo "<p>✓ Actualizadas: <strong>$affected</strong> notificaciones</p>";
    } else {
        echo "<p>✓ No hay notificaciones con visto = NULL</p>";
    }

    // Mostrar estadísticas finales
    $statsStmt = $pdo->query("SELECT
        COUNT(*) as total,
        SUM(CASE WHEN visto = 0 THEN 1 ELSE 0 END) as no_vistas,
        SUM(CASE WHEN visto = 1 THEN 1 ELSE 0 END) as vistas
        FROM notificaciones");
    $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);

    echo "<h3>Estadísticas de notificaciones:</h3>";
    echo "<ul>";
    echo "<li>Total: <strong>{$stats['total']}</strong></li>";
    echo "<li>No vistas: <strong>{$stats['no_vistas']}</strong></li>";
    echo "<li>Vistas: <strong>{$stats['vistas']}</strong></li>";
    echo "</ul>";

    echo "<p><a href='ver_notificaciones.php' style='color: blue; text-decoration: underline;'>Ver notificaciones</a></p>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>