<?php
include_once 'includes/session.php';
include_once 'includes/PDOdb.php';

// Establecer el tipo de contenido ANTES de cualquier output
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    if (!isset($_SESSION['idusuario'])) {
        throw new Exception('Usuario no autenticado');
    }

    if (!isset($_POST['id_clase']) || empty($_POST['id_clase'])) {
        throw new Exception('ID de clase no proporcionado');
    }

    $id_usuario = $_SESSION['idusuario'];
    $id_clase = $_POST['id_clase'];

    // Solo eliminar del repositorio del usuario
    $stmt = $pdo->prepare("DELETE FROM repositorio WHERE id_clase = ? AND id_usuario = ?");
    $stmt->execute([$id_clase, $id_usuario]);
    
    $rows_affected = $stmt->rowCount();
    
    // También eliminar de favoritos si existe
    $stmt = $pdo->prepare("DELETE FROM favoritos WHERE id_clase = ? AND id_usuario = ?");
    $stmt->execute([$id_clase, $id_usuario]);

    if ($rows_affected > 0) {
        echo json_encode(['success' => true, 'message' => 'Clase removida de tu repositorio']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró la clase en tu repositorio']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
