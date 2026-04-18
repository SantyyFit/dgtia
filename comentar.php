<?php
include_once 'includes/session.php';
include_once 'includes/PDOdb.php';
include_once 'includes/head.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener usuario
$stmt = $pdo->prepare("SELECT idusuario, usuario, img_perfil FROM usuarios WHERE idusuario = :id");
$stmt->execute([':id' => $_GET['i']]);

$nombre1 = $_GET['user'];

if ($f = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $idusuarioLimpio = $f['idusuario'];
    $Nombre = $f['usuario'];
    $rutaimagen = $f['img_perfil'];
} else {
    $idusuarioLimpio = null;
    $Nombre = "Usuario no encontrado";
    $rutaimagen = "ruta/por_defecto.jpg";
}

$esp = isset($_GET['esp']) ? trim($_GET['esp']) : (isset($_POST['esp']) ? trim($_POST['esp']) : '');
if ($esp === '') {
    $esp = 'Programacion';
}

// Procesar comentario si es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idpost = isset($_POST['id_publicacion']) ? (int)$_POST['id_publicacion'] : 0;
    $id_padre = isset($_POST['id_padre']) && $_POST['id_padre'] !== '' ? (int)$_POST['id_padre'] : null;
    $comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';
    
    if ($idpost > 0 && !empty($comentario) && !empty($idusuarioLimpio)) {
        try {
            if ($id_padre) {
                $stmt = $pdo->prepare("INSERT INTO comentarios (id_publicacion, id_usuario, comentario, id_padre, fecha) 
                                      VALUES (?, ?, ?, ?, NOW())");
                $stmt->execute([$idpost, $idusuarioLimpio, $comentario, $id_padre]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO comentarios (id_publicacion, id_usuario, comentario, fecha) 
                                      VALUES (?, ?, ?, NOW())");
                $stmt->execute([$idpost, $idusuarioLimpio, $comentario]);
            }

            // Crear notificación para el autor del post
            $stmtPost = $pdo->prepare("SELECT idusuario FROM posts WHERE idPost = ?");
            $stmtPost->execute([$idpost]);
            $autorPost = $stmtPost->fetchColumn();

            if ($autorPost && $autorPost != $idusuarioLimpio) {
                // Obtener nombre del comentarista
                $stmtUser = $pdo->prepare("SELECT usuario FROM usuarios WHERE idusuario = ?");
                $stmtUser->execute([$idusuarioLimpio]);
                $nombreComent = $stmtUser->fetchColumn();

                // Crear notificación
                $mensaje = "$nombreComent comentó en tu publicación";
                $url = "comentar.php?user=" . urlencode($_GET['user']) . "&i=" . urlencode($_GET['i']) . "&esp=" . urlencode($esp);
                $stmtNotif = $pdo->prepare("INSERT INTO notificaciones (idusuario, tipo, mensaje, url, visto) VALUES (?, 'comentario', ?, ?, 0)");
                $stmtNotif->execute([$autorPost, $mensaje, $url]);
            }
            
            // Redirigir de vuelta a la misma página
            header("Location: comentar.php?user=" . urlencode($_GET['user']) . "&i=" . urlencode($_GET['i']) . "&esp=" . urlencode($esp));
            exit();
        } catch (PDOException $e) {
            echo "Error al guardar comentario: " . $e->getMessage();
        }
    }
}

// Obtener posts según la especialidad
$stmt = $pdo->prepare("SELECT posts.*, usuarios.usuario AS usuario_nombre, usuarios.img_perfil AS usuario_foto, usuarios.idusuario AS idusuarioP
    FROM posts
    JOIN usuarios ON posts.idusuario = usuarios.idusuario
    WHERE LOWER(posts.skill) = LOWER(:esp)
    ORDER BY posts.fecha DESC");
$stmt->execute([':esp' => $esp]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/<?= strtolower($esp) ?>.css">

<style>
    .comentarios-contenedor {
        display: none;
        margin-top: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
    }
    .comentario {
        margin-bottom: 15px;
        padding: 10px;
        background: white;
        border-radius: 8px;
        border-left: 3px solid #4a90e2;
    }
    .comentario-flex {
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }
    .comentario-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    .comentario-usuario {
        font-weight: bold;
        color: #333;
        text-decoration: none;
    }
    .comentario-usuario:hover {
        color: #4a90e2;
    }
    .comentario-fecha {
        font-size: 11px;
        color: #999;
    }
    .btn-responder {
        background: none;
        border: none;
        color: #4a90e2;
        cursor: pointer;
        font-size: 12px;
        margin-top: 5px;
        padding: 0;
    }
    .btn-responder:hover {
        text-decoration: underline;
    }
    .respuesta-form {
        display: none;
        margin-top: 10px;
        margin-left: 20px;
    }
    .textarea-respuesta {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 5px;
        resize: vertical;
    }
    .btn-enviar {
        background: #4a90e2;
        color: white;
        border: none;
        padding: 5px 15px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 5px;
    }
    .form-comentario {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #ddd;
    }
    .textarea-comentario {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        resize: vertical;
    }
    .btn-comentar {
        background: #4a90e2;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
    }
    .btn-ver-comentarios {
        background: none;
        border: none;
        color: #4a90e2;
        cursor: pointer;
        font-size: 14px;
        margin-top: 10px;
        padding: 5px 10px;
    }
    .btn-ver-comentarios:hover {
        background: #e9ecef;
        border-radius: 5px;
    }
</style>

<body class="videojuegos-body">
    <header class="header-Relaciones">
        <p class="videojuegos-title"><?= htmlspecialchars(ucfirst(strtolower($esp))); ?></p>
    </header>

    <div class="publicaciones-container">
        <div class="publicar-contenedor">
            <div class="publicar-box">
                <a href="crearPost.php?user=<?= urlencode($_GET['user']) ?>&i=<?= urlencode($_GET['i']) ?>&esp=<?= urlencode($esp) ?>" class="publicar-link">
                    <img src="<?= $rutaimagen ?>" alt="" class="publicar-img">
                </a>
            </div>
        </div>

        <?php
        // Función para mostrar comentarios y respuestas
        function mostrarComentarios($pdo, $idPublicacion, $idPadre = null, $margen = 0, $user, $i, $esp) {
            if ($idPadre === null) {
                $stmt = $pdo->prepare("
                    SELECT c.*, u.usuario, u.img_perfil, u.idusuario
                    FROM comentarios c
                    JOIN usuarios u ON c.id_usuario = u.idusuario
                    WHERE c.id_publicacion = ? AND (c.id_padre IS NULL OR c.id_padre = 0)
                    ORDER BY c.fecha ASC
                ");
                $stmt->execute([$idPublicacion]);
            } else {
                $stmt = $pdo->prepare("
                    SELECT c.*, u.usuario, u.img_perfil, u.idusuario
                    FROM comentarios c
                    JOIN usuarios u ON c.id_usuario = u.idusuario
                    WHERE c.id_publicacion = ? AND c.id_padre = ?
                    ORDER BY c.fecha ASC
                ");
                $stmt->execute([$idPublicacion, $idPadre]);
            }
            
            while ($coment = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="comentario" style="margin-left: <?= $margen ?>px;">
                    <div class="comentario-flex">
                        <img src="<?= htmlspecialchars($coment['img_perfil'] ?? 'default-avatar.jpg') ?>" class="comentario-img">
                        <div style="flex:1">
                            <strong>
                                <a href="perfilUsuario.php?user=<?= urlencode($user) ?>&UsuarioB=<?= urlencode($coment['usuario']) ?>&idUsuarioB=<?= $coment['idusuario'] ?>&i=<?= urlencode($i) ?>" class="comentario-usuario">
                                    <?= htmlspecialchars($coment['usuario']) ?>
                                </a>
                            </strong><br>
                            <span><?= nl2br(htmlspecialchars($coment['comentario'])) ?></span><br>
                            <small class="comentario-fecha"><?= $coment['fecha'] ?></small>
                            <div>
                                <button onclick="mostrarRespuesta(<?= $coment['id'] ?>)" class="btn-responder">Responder</button>
                                <div id="respuesta-<?= $coment['id'] ?>" class="respuesta-form">
                                    <form action="comentar.php?user=<?= urlencode($user) ?>&i=<?= urlencode($i) ?>&esp=<?= urlencode($esp) ?>" method="POST">
                                        <input type="hidden" name="id_publicacion" value="<?= $idPublicacion ?>">
                                        <input type="hidden" name="id_padre" value="<?= $coment['id'] ?>">
                                        <textarea name="comentario" rows="2" required class="textarea-respuesta" placeholder="Escribe tu respuesta..."></textarea>
                                        <button type="submit" class="btn-enviar">Enviar respuesta</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                mostrarComentarios($pdo, $idPublicacion, $coment['id'], $margen + 30, $user, $i, $esp);
            }
        }
        ?>

        <?php foreach ($posts as $post): ?>
            <div class="post-card" id="post-<?= $post['idPost']; ?>">
                <div class="user-info">
                    <img src="<?= htmlspecialchars($post['usuario_foto'] ?? 'default-avatar.jpg'); ?>" class="profile-pic">
                    <div>
                        <h3 class="username">
                            <a href="perfilUsuario.php?user=<?= $_GET['user'] ?>&UsuarioB=<?= urlencode($post['usuario_nombre']) ?>&idUsuarioB=<?= $post['idusuario'] ?>&i=<?= $_GET['i'] ?>" class="username-link">
                                <?= htmlspecialchars($post['usuario_nombre']); ?>
                            </a>
                        </h3>
                        <span class="skill">Habilidad: <?= nl2br(htmlspecialchars($post['skill'])); ?></span>
                    </div>
                </div>

                <strong class="titulo"><?= htmlspecialchars($post['titulo']); ?></strong>
                <p class="post-content"><?= nl2br(htmlspecialchars($post['contenido'])); ?></p>

                <div class="post-footer">
                    <span class="post-date"><?= $post['fecha']; ?></span>
                </div>

                <button onclick="toggleComentarios(<?= $post['idPost'] ?>)" class="btn-ver-comentarios">
                    💬 Ver comentarios
                </button>

                <div id="comentarios-<?= $post['idPost'] ?>" class="comentarios-contenedor">
                    <h4>Comentarios:</h4>
                    <?php mostrarComentarios($pdo, $post['idPost'], null, 0, $_GET['user'], $_GET['i'], $esp); ?>

                    <form action="comentar.php?user=<?= urlencode($_GET['user']) ?>&i=<?= urlencode($_GET['i']) ?>&esp=<?= urlencode($esp) ?>" method="POST" class="form-comentario">
                        <input type="hidden" name="id_publicacion" value="<?= $post['idPost'] ?>">
                        <input type="hidden" name="id_padre" value="">
                        <textarea name="comentario" rows="2" placeholder="Escribe tu comentario..." required class="textarea-comentario"></textarea>
                        <button type="submit" class="btn-comentar">Comentar</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function toggleComentarios(idPost) {
            const div = document.getElementById("comentarios-" + idPost);
            if (div.style.display === "none" || div.style.display === "") {
                div.style.display = "block";
            } else {
                div.style.display = "none";
            }
        }

        function mostrarRespuesta(idComentario) {
            const div = document.getElementById("respuesta-" + idComentario);
            if (div.style.display === "none" || div.style.display === "") {
                div.style.display = "block";
            } else {
                div.style.display = "none";
            }
        }
    </script>
</body>
<?php include_once 'includes/header.php'; ?>
</html>