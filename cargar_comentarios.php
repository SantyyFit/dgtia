<?php
require "includes/PDOdb.php";

$user = isset($_GET['user']) ? $_GET['user'] : null;
$i = isset($_GET['i']) ? $_GET['i'] : null;
$idPerfil = isset($_GET['idPerfil']) ? $_GET['idPerfil'] : null; // El usuario del perfil que se está viendo

$id_perfil = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_perfil <= 0) {
    echo "<p>No hay comentarios</p>";
    exit;
}

/*
 Asegúrate de usar los nombres reales de columnas de tu tabla usuarios.
 Según tu perfil: idusuario y img_perfil y nombre.
 Ajusté los alias para que el PHP use ['foto'] y ['id'] como antes.
*/
$sql = "SELECT c.comentario, c.fecha, u.nombre, u.img_perfil AS foto, u.idusuario AS id
        FROM perfil_comentarios c
        JOIN usuarios u ON u.idusuario = c.id_autor
        WHERE c.id_perfil = :perfil
        ORDER BY c.fecha DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([':perfil' => $id_perfil]);

echo "<!-- Debug: id_perfil = $id_perfil, resultados = " . $stmt->rowCount() . " -->";

$hay = false;
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $hay = true;
    // Escape para evitar XSS
    $nombre = htmlspecialchars($row['nombre']);
    $foto = htmlspecialchars($row['foto']);
    $autorId = intval($row['id']);
    $fecha = htmlspecialchars($row['fecha']);
    $texto = nl2br(htmlspecialchars($row['comentario']));

  echo "
<div class='comentario-card' style='border:1px solid #ddd; padding:12px; margin-bottom:10px; border-radius:8px; background:#fff;'>
    <div class='comentario-header' style='display:flex; align-items:center; gap:10px;'>
        
        <img src='$foto' style='width:40px; height:40px; border-radius:50%; object-fit:cover;'>

        <div>
            <a href='perfilUsuario.php?user={$user}&UsuarioB={$nombre}&idUsuarioB={$autorId}&i={$i}' 
               class='comentario-autor' 
               style='font-weight:bold; color:#333; text-decoration:none;'>
                {$nombre}
            </a>
            <p class='comentario-fecha' style='margin:0; font-size:12px; color:#777;'>
                {$fecha}
            </p>
        </div>
    </div>

    <p class='comentario-texto' style='margin-top:8px; font-size:14px; color:#444; line-height:1.4;'>
        {$texto}
    </p>
</div>
";


}

if (!$hay) {
    echo "<p style='color:#94a3b8;'>Aún no hay comentarios.</p>";
}
