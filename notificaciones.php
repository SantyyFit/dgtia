<?php
// NO session_start()
// NO require de PDO

function crearNotificacion($idusuario, $tipo, $mensaje, $url, $pdo) {
    $sql = "INSERT INTO notificaciones (idusuario, tipo, mensaje, url, visto)
            VALUES (:idusuario, :tipo, :mensaje, :url, 0)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':idusuario' => $idusuario,
        ':tipo'      => $tipo,
        ':mensaje'   => $mensaje,
        ':url'       => $url
    ]);
}
