<?php
// Script de prueba para verificar notificaciones
header('Content-Type: text/html; charset=utf-8');
session_start();

require_once 'includes/PDOdb.php';
require_once 'notificaciones.php';

try {
    $pdo = Conectarse();

    echo "<h2>Prueba de creación de notificaciones</h2>";

    // Crear una notificación de prueba
    if (isset($_SESSION['idusuario'])) {
        $idUsuario = $_SESSION['idusuario'];

        // Crear notificación de prueba
        crearNotificacion($idUsuario, 'prueba', 'Esta es una notificación de prueba', 'index.php', $pdo);

        echo "<p>✓ Notificación de prueba creada para el usuario ID: $idUsuario</p>";
        echo "<p><a href='ver_notificaciones.php' style='color: blue; text-decoration: underline;'>Ver notificaciones</a></p>";
    } else {
        echo "<p style='color: red;'>Debes iniciar sesión para probar las notificaciones.</p>";
        echo "<p><a href='login.php'>Iniciar sesión</a></p>";
    }

    // Mostrar últimas 5 notificaciones
    echo "<h3>Últimas 5 notificaciones en la BD:</h3>";
    $stmt = $pdo->query("SELECT id, idusuario, tipo, mensaje, visto, fecha FROM notificaciones ORDER BY id DESC LIMIT 5");
    $notifs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($notifs) {
        echo "<table border='1' style='border-collapse: collapse; padding: 5px;'>";
        echo "<tr><th>ID</th><th>Usuario</th><th>Tipo</th><th>Mensaje</th><th>Visto</th><th>Fecha</th></tr>";
        foreach ($notifs as $n) {
            $vistoText = $n['visto'] === null ? 'NULL' : ($n['visto'] == 0 ? 'No' : 'Sí');
            $vistoColor = $n['visto'] === null ? 'red' : ($n['visto'] == 0 ? 'orange' : 'green');
            echo "<tr>";
            echo "<td>{$n['id']}</td>";
            echo "<td>{$n['idusuario']}</td>";
            echo "<td>{$n['tipo']}</td>";
            echo "<td>{$n['mensaje']}</td>";
            echo "<td style='color: $vistoColor'>$vistoText</td>";
            echo "<td>{$n['fecha']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay notificaciones en la base de datos.</p>";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>