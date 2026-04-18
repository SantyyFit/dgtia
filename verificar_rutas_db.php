<?php
// Script para consultar rutas actuales en BD
require_once 'includes/PDOdb.php';

try {
    $pdo = Conectarse();
    $stmt = $pdo->query("SELECT id_insignia, nombre, imagen FROM insignias ORDER BY id_insignia");
    $insignias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Rutas actualmente guardadas en la BD:</h2>";
    echo "<table border='1' style='border-collapse: collapse; padding: 10px;'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Ruta en BD</th></tr>";
    
    foreach ($insignias as $row) {
        echo "<tr>";
        echo "<td>{$row['id_insignia']}</td>";
        echo "<td>{$row['nombre']}</td>";
        echo "<td><code>{$row['imagen']}</code></td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
