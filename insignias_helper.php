<?php
function asignarInsignia($pdo, $idUsuario, $tipo) {
    if (!$pdo) {
        error_log("Error: PDO no disponible en asignarInsignia");
        return false;
    }

    $metas = [
        'compartidas' => [
            1 => 1,
            2 => 10,
            3 => 30,
            4 => 50,
            5 => 70,
            6 => 100
        ],
        'recibidas' => [
            7 => 1,
            8 => 10,
            9 => 30,
            10 => 50,
            11 => 70,
            12 => 100
        ]
    ];

    try {
        // Determinar nombre de columna para usuarios_insignias
        $colUsuario = 'id_usuario';
        $stmt = $pdo->query("SHOW COLUMNS FROM usuarios_insignias LIKE 'id_usuario'");
        if ($stmt->rowCount() === 0) {
            $colUsuario = 'idusuario';
        }

        if ($tipo === 'compartidas') {
            // Contar clases creadas usando la columna que exista
            $stmtCreadas = $pdo->prepare("SELECT COUNT(*) FROM clases WHERE id_creador = ?");
            $stmtCreadas->execute([$idUsuario]);
            $creardasCount = (int) $stmtCreadas->fetchColumn();

            if ($creardasCount === 0) {
                $stmtFallback = $pdo->prepare("SELECT COUNT(*) FROM clases WHERE id_usuario = ?");
                $stmtFallback->execute([$idUsuario]);
                $creardasCount = (int) $stmtFallback->fetchColumn();
                if ($creardasCount > 0) {
                    error_log("asignarInsignia: columna id_creador no existe, usando id_usuario para contar clases creadas");
                }
            }

            // Contar clases compartidas
            $stmtCompartidas = $pdo->prepare("SELECT COUNT(*) FROM clases_compartidas WHERE id_emisor = ?");
            $stmtCompartidas->execute([$idUsuario]);
            $compartidassCount = (int) $stmtCompartidas->fetchColumn();

            $total = $creardasCount + $compartidassCount;
            error_log("Usuario $idUsuario - Compartidas: Creadas=$creardasCount, Compartidas=$compartidassCount, Total=$total");
        } else {
            // Contar clases recibidas
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM clases_compartidas WHERE id_receptor = ?");
            $stmt->execute([$idUsuario]);
            $total = (int) $stmt->fetchColumn();
            error_log("Usuario $idUsuario - Recibidas: Total=$total");
        }

        // Obtenemos las insignias que ya tiene el usuario
        $insigniasActuales = $pdo->prepare("SELECT id_insignia FROM usuarios_insignias WHERE {$colUsuario} = ?");
        $insigniasActuales->execute([$idUsuario]);
        $existentes = array_column($insigniasActuales->fetchAll(PDO::FETCH_ASSOC), 'id_insignia');

        // Asignamos nuevas insignias
        foreach ($metas[$tipo] as $idInsignia => $requerido) {
            if ($total >= $requerido && !in_array($idInsignia, $existentes)) {
                $insert = $pdo->prepare("INSERT INTO usuarios_insignias ({$colUsuario}, id_insignia, fecha_obtenida) VALUES (?, ?, NOW())");
                $insert->execute([$idUsuario, $idInsignia]);
                error_log("Insignia $idInsignia asignada a usuario $idUsuario (requerido: $requerido)");
            }
        }
        return true;
    } catch (PDOException $e) {
        error_log("Error en asignarInsignia: " . $e->getMessage());
        return false;
    }
}
?>
