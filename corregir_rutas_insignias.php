<?php
// Script para corregir rutas de imágenes en insignias
header('Content-Type: text/html; charset=utf-8');

require_once 'includes/PDOdb.php';

try {
    $pdo = new PDOdb();
    
    // Obtener todas las insignias
    $stmt = $pdo->prepare("SELECT id_insignia, imagen FROM insignias");
    $stmt->execute();
    $insignias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Corrigiendo rutas de imágenes en insignias...</h2>";
    echo "<table border='1' style='border-collapse: collapse; padding: 10px;'>";
    echo "<tr><th>ID</th><th>Ruta Original</th><th>Ruta Corregida</th><th>Estado</th></tr>";
    
    $actualizado = 0;
    foreach ($insignias as $insignia) {
        $rutaOriginal = $insignia['imagen'];
        $rutaCorregida = $rutaOriginal;
        
        // Si la ruta no contiene 'Insignias/', agregarla
        if (strpos($rutaOriginal, 'Insignias/') === false && strpos($rutaOriginal, 'insignias/') === false) {
            $rutaCorregida = 'Insignias/' . $rutaOriginal;
            
            // Actualizar en la BD
            $updateStmt = $pdo->prepare("UPDATE insignias SET imagen = ? WHERE id_insignia = ?");
            if ($updateStmt->execute([$rutaCorregida, $insignia['id_insignia']])) {
                $status = "✓ Actualizado";
                $actualizado++;
            } else {
                $status = "✗ Error";
            }
        } else {
            $status = "- Ya tiene ruta correcta";
        }
        
        echo "<tr>";
        echo "<td>{$insignia['id_insignia']}</td>";
        echo "<td>{$rutaOriginal}</td>";
        echo "<td>{$rutaCorregida}</td>";
        echo "<td>{$status}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "<h3>Total actualizado: $actualizado</h3>";
    echo "<p><a href='perfil.php' style='color: blue; text-decoration: underline;'>Ver perfil</a></p>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
