<?php
session_start();
include("includes/PDOdb.php");

if (!isset($_SESSION['idusuario'])) {
    exit();
}

$pdo = Conectarse();

$mi_id = $_SESSION['idusuario'];
$otro_id = $_POST['receptor'] ?? null;

if (!$otro_id) exit();

// Marcar mensajes como vistos
$stmt = $pdo->prepare("
    UPDATE mensajes
    SET visto = 1
    WHERE id_emisor = ? AND id_receptor = ? AND visto = 0
");
$stmt->execute([$otro_id, $mi_id]);

// Obtener mensajes
$stmt = $pdo->prepare("
    SELECT * FROM mensajes
    WHERE (id_emisor = ? AND id_receptor = ?)
    OR (id_emisor = ? AND id_receptor = ?)
    ORDER BY created_at ASC
");
$stmt->execute([$mi_id, $otro_id, $otro_id, $mi_id]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $clase = ($row['id_emisor'] == $mi_id) ? "saliente" : "entrante";
    $hora = date('H:i', strtotime($row['created_at']));
    $visto = ($row['visto'] == 1) ? "✔✔" : "✔";

    echo "<div class='mensaje $clase'>";
    echo htmlspecialchars($row['mensaje']);
    echo "<div class='meta'>$hora";

    if ($clase == "saliente") {
        echo " <span>$visto</span>";
    }

    echo "</div></div>";
}
