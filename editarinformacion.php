<?php
include_once 'includes/session.php';
include_once 'includes/dbconexion.php';

// ─── Validar parámetros ───────────────────────────────────────────────────────
if (!isset($_GET["i"]) || !isset($_POST["nombre"])) {
    die("Acceso no válido.");
}

// ID como entero directo (igual que editarPerfil.php)
$i    = (int) $_GET["i"];
$user = isset($_GET["user"]) ? trim($_GET["user"]) : '';

if ($i <= 0) {
    die("ID de usuario no válido.");
}

// Datos del formulario
$nombre      = trim($_POST["nombre"]      ?? '');
$descripcion = trim($_POST["descripcion"] ?? '');
$nivel       = trim($_POST["nivel"]       ?? '');
$telefono    = trim($_POST["telefono"]    ?? '');

// ─── Actualizar datos del usuario ─────────────────────────────────────────────
// Se reemplazó mysql_query (obsoleto y sin protección) por mysqli prepared statement
$stmtUpdate = $conexion->prepare(
    "UPDATE usuarios
     SET usuario = ?, descripcion = ?, nivel = ?, numero_telefono = ?
     WHERE idusuario = ?"
);

if (!$stmtUpdate) {
    header("Location: editarPerfil.php?user=" . urlencode($user) . "&i=$i&status=error");
    exit;
}

$stmtUpdate->bind_param("ssssi", $nombre, $descripcion, $nivel, $telefono, $i);
$updateOk = $stmtUpdate->execute();
$stmtUpdate->close();

if (!$updateOk) {
    header("Location: editarPerfil.php?user=" . urlencode($user) . "&i=$i&status=error");
    exit;
}

// ─── Eliminar habilidades anteriores ──────────────────────────────────────────
$stmtDelete = $conexion->prepare("DELETE FROM habilidades WHERE id_usuario = ?");
if ($stmtDelete) {
    $stmtDelete->bind_param("i", $i);
    $stmtDelete->execute();
    $stmtDelete->close();
}

// ─── Insertar nuevas habilidades ──────────────────────────────────────────────
if (isset($_POST['habilidades_nombre']) && is_array($_POST['habilidades_nombre'])) {

    $stmtInsert = $conexion->prepare(
        "INSERT INTO habilidades (id_usuario, nombre, nivel) VALUES (?, ?, ?)"
    );

    if ($stmtInsert) {
        foreach ($_POST['habilidades_nombre'] as $index => $nombre_habilidad) {
            $nombre_habilidad = trim($nombre_habilidad);
            $nivel_habilidad  = trim($_POST['habilidades_nivel'][$index] ?? '');

            // Solo insertar si el nombre no está vacío
            if (!empty($nombre_habilidad) && !empty($nivel_habilidad)) {
                $stmtInsert->bind_param("iss", $i, $nombre_habilidad, $nivel_habilidad);
                $stmtInsert->execute();
            }
        }
        $stmtInsert->close();
    }
}

// ─── Redirigir con éxito ──────────────────────────────────────────────────────
header("Location: editarPerfil.php?user=" . urlencode($user) . "&i=$i&status=ok");
exit;
