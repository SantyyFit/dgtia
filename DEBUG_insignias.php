<?php
session_start();
include_once 'includes/PDOdb.php';

$pdo = Conectarse();

echo "<h1>Diagnóstico de Insignias</h1>";

// 1. Verificar si la tabla insignias existe
echo "<h2>1. Tabla insignias</h2>";
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM insignias");
    $count = $stmt->fetchColumn();
    echo "✓ Tabla insignias existe con $count registros<br>";
    
    $stmt = $pdo->query("SELECT * FROM insignias LIMIT 5");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>" . json_encode($records, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}

// 2. Verificar si la tabla usuarios_insignias existe
echo "<h2>2. Tabla usuarios_insignias</h2>";
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios_insignias");
    $count = $stmt->fetchColumn();
    echo "✓ Tabla usuarios_insignias existe con $count registros<br>";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}

// 3. Verificar si la tabla clases existe
echo "<h2>3. Tabla clases</h2>";
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM clases");
    $count = $stmt->fetchColumn();
    echo "✓ Tabla clases existe con $count registros<br>";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}

// 4. Verificar si la tabla clases_compartidas existe
echo "<h2>4. Tabla clases_compartidas</h2>";
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM clases_compartidas");
    $count = $stmt->fetchColumn();
    echo "✓ Tabla clases_compartidas existe con $count registros<br>";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}

// 5. Verificar usuario actual
echo "<h2>5. Usuario actual</h2>";
if (isset($_SESSION['idusuario'])) {
    $idUsuario = $_SESSION['idusuario'];
    echo "ID Usuario: $idUsuario<br>";
    
    // Clases creadas
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM clases WHERE id_creador = ?");
    $stmt->execute([$idUsuario]);
    $creardasCount = $stmt->fetchColumn();
    echo "Clases creadas: $creardasCount<br>";
    
    // Clases compartidas (como emisor)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM clases_compartidas WHERE id_emisor = ?");
    $stmt->execute([$idUsuario]);
    $compartidassCount = $stmt->fetchColumn();
    echo "Clases compartidas: $compartidassCount<br>";
    
    // Insignias del usuario
    $stmt = $pdo->prepare("SELECT ui.*, i.nombre FROM usuarios_insignias ui LEFT JOIN insignias i ON ui.id_insignia = i.id_insignia WHERE ui.id_usuario = ?");
    $stmt->execute([$idUsuario]);
    $insignias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Insignias obtenidas: " . count($insignias) . "<br>";
    echo "<pre>" . json_encode($insignias, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
} else {
    echo "No hay usuario logueado. <a href='login.php'>Inicia sesión</a>";
}

// 6. Ver logs de errores
echo "<h2>6. Logs de error recientes</h2>";
$logFile = ini_get('error_log');
if ($logFile && file_exists($logFile)) {
    $lines = file($logFile);
    $recentLines = array_slice($lines, -20);
    echo "<pre>" . htmlspecialchars(implode("", $recentLines)) . "</pre>";
} else {
    echo "No se encontró archivo de logs.";
}
?>
