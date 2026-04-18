<?php
session_start();
require "includes/PDOdb.php";

// Asegurarse de que el usuario esté logueado
if (!isset($_SESSION['idusuario'])) {
    http_response_code(401);
    echo "No autorizado";
    exit;
}

$id_autor = $_SESSION['idusuario'];
$id_perfil = isset($_POST['id_perfil']) ? intval($_POST['id_perfil']) : 0;
$comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';

if ($id_perfil <= 0) {
    http_response_code(400);
    echo "Perfil inválido";
    exit;
}

if ($comentario === "") {
    http_response_code(400);
    echo "Comentario vacío";
    exit;
}

// Inserción segura
$sql = "INSERT INTO perfil_comentarios (id_autor, id_perfil, comentario) 
        VALUES (:autor, :perfil, :comentario)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':autor' => $id_autor,
    ':perfil' => $id_perfil,
    ':comentario' => $comentario
]);

echo "ok";
