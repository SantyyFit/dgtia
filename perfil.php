<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Verificación de acceso (solo si quieres proteger el acceso)
if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit();
}

include_once 'includes/head.php';
include_once 'includes/PDOdb.php';
include_once 'includes/headerPerfil.php';

// Verificamos que se reciba el parámetro 'i' (aunque ahora no se usa)
if (!isset($_GET['i'])) {
    // No hacer nada, usaremos la sesión
}

$idUsuario = $_SESSION['idusuario'];

// Obtener datos del usuario con PDO
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE idusuario = ?");
$stmt->execute([$idUsuario]);
$f = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$f) {
    echo "<p>No se encontró el usuario con ID: $idUsuario. Verifica la URL o contacta al administrador.</p>";
    exit();
}

$idusuarioLimpio = $f["idusuario"];
$Nombre = $f["usuario"];
$rutaimagen = $f["img_perfil"];
$descripcion = $f["descripcion"];
$nivel = $f["nivel"];
$telefono = $f["numero_telefono"];

$yo = isset($_SESSION['idusuario']) ? $_SESSION['idusuario'] : 0;

// Contadores seguidores y seguidos
$seguidoresStmt = $pdo->prepare("SELECT COUNT(*) FROM seguidores WHERE id_seguido = ?");
$seguidoresStmt->execute([$idusuarioLimpio]);
$seguidores_count = $seguidoresStmt->fetchColumn();

$seguidosStmt = $pdo->prepare("SELECT COUNT(*) FROM seguidores WHERE id_usuario = ?");
$seguidosStmt->execute([$idusuarioLimpio]);
$seguidos_count = $seguidosStmt->fetchColumn();

// Obtener insignias del usuario
$insigniasStmt = $pdo->prepare("SELECT i.nombre, i.descripcion, i.imagen FROM usuarios_insignias ui JOIN insignias i ON ui.id_insignia = i.id_insignia WHERE ui.id_usuario = ?");
$insigniasStmt->execute([$idusuarioLimpio]);
$insignias = $insigniasStmt->fetchAll(PDO::FETCH_ASSOC);

function normalizarRutaInsignia($ruta) {
    $ruta = trim(str_replace('\\', '/', $ruta));
    if ($ruta === '') {
        return '';
    }

    if (preg_match('#^(https?://|/|Insignias/|insignias/)#i', $ruta)) {
        return $ruta;
    }

    return 'Insignias/' . $ruta;
}
?>

<style>
/* PALETA DE COLORES */
:root {
    --rojo-principal: #a7201f;
    --rojo-secundario: #9f2241;
    --vino: #691c32;
    --dorado-claro: #ddc9a3;
    --dorado-oscuro: #bc955c;
    --gris-claro: #98989a;
    --gris-oscuro: #6f7271;
    --blanco: #ffffff;
}

/* ESTILOS PRINCIPALES CON !important PARA SOBREESCRIBIR */
.body-perfil {
    background: linear-gradient(135deg, var(--vino) 0%, var(--rojo-principal) 100%) !important;
    min-height: 100vh !important;
}

.main-perfil {
    background: transparent !important;
}

.perfil-wrapper {
    background: var(--vino) !important;
    backdrop-filter: blur(10px) !important;
    border-radius: 20px !important;
    padding: 20px !important;
    margin: 20px !important;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2) !important;
    border: 2px solid var(--dorado-claro) !important;
}

.datos-perfil_contenedor p a,
.link-seguidores,
.link-seguidos {
    color: var(--dorado-claro) !important;
    font-weight: bold !important;
    text-decoration: none !important;
}

.datos-perfil_contenedor p a:hover,
.link-seguidores:hover,
.link-seguidos:hover {
    color: var(--dorado-oscuro) !important;
}

.descripcion {
    background: rgba(0, 0, 0, 0.4) !important;
    border-radius: 15px !important;
    padding: 15px !important;
    margin: 15px 0 !important;
}

.descripcion p {
    color: var(--blanco) !important;
}

.descripcion i {
    color: var(--dorado-claro) !important;
    margin-right: 10px !important;
}

.perzonalizar-contenedor .Editar-perfil a,
.perzonalizar-contenedor .Compartir-perfil a {
    background: var(--rojo-secundario) !important;
    color: var(--blanco) !important;
    padding: 8px 16px !important;
    border-radius: 25px !important;
    text-decoration: none !important;
    transition: all 0.3s ease !important;
    display: inline-block !important;
}

.perzonalizar-contenedor .Editar-perfil a:hover,
.perzonalizar-contenedor .Compartir-perfil a:hover {
    background: var(--rojo-principal) !important;
    transform: translateY(-2px) !important;
}

.boton-seguir {
    background: var(--dorado-oscuro) !important;
    color: var(--vino) !important;
    border: none !important;
    padding: 8px 16px !important;
    border-radius: 25px !important;
    cursor: pointer !important;
    font-weight: bold !important;
}

.boton-seguir:hover {
    background: var(--dorado-claro) !important;
    transform: translateY(-2px) !important;
}

.habilidades-contenedor p {
    color: var(--blanco) !important;
}

.insignias-contenedor h3 {
    color: var(--dorado-claro) !important;
}

#btnVerTodas {
    background: var(--rojo-secundario) !important;
    color: var(--blanco) !important;
    border: none !important;
    padding: 8px 16px !important;
    border-radius: 6px !important;
    cursor: pointer !important;
    font-weight: bold !important;
}

#btnVerTodas:hover {
    background: var(--rojo-principal) !important;
}

#cerrarModal {
    background: var(--rojo-secundario) !important;
    color: var(--blanco) !important;
    border: none !important;
    padding: 8px 20px !important;
    border-radius: 6px !important;
    cursor: pointer !important;
    font-weight: bold !important;
}

#cerrarModal:hover {
    background: var(--rojo-principal) !important;
}

.contenedor-recomendaciones {
    background: rgba(0, 0, 0, 0.4) !important;
    border-radius: 15px !important;
    padding: 15px !important;
    margin-top: 20px !important;
}

.contenedor-recomendaciones p {
    color: var(--dorado-claro) !important;
    font-size: 18px !important;
    font-weight: bold !important;
}

.modal-foto {
    background: rgba(0, 0, 0, 0.9) !important;
}

.cerrar-modal {
    color: var(--dorado-claro) !important;
}

.cerrar-modal:hover {
    color: var(--blanco) !important;
}

/* Sobrescribir cualquier estilo azul de otros CSS */
.perfil-wrapper, .perfil-wrapper * {
    background-color: transparent;
}

.perfil-wrapper {
    background: var(--vino) !important;
}

.btn-ver-comentarios, .btn-responder, .btn-enviar, .btn-comentar {
    background: var(--rojo-secundario) !important;
    color: var(--blanco) !important;
    border: none !important;
}

.btn-ver-comentarios:hover, .btn-responder:hover, .btn-enviar:hover, .btn-comentar:hover {
    background: var(--rojo-principal) !important;
}

.comentario {
    background: rgba(0, 0, 0, 0.3) !important;
    border-left: 3px solid var(--dorado-claro) !important;
}

.comentario-usuario {
    color: var(--dorado-claro) !important;
}
</style>

<link rel="stylesheet" href="css/perfill.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.core.min.css" />
<script src="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/glide.min.js"></script>

<body class="body-perfil">
    <main class="main-perfil">
        <div class="perfil-wrapper">

            <div class="foto-perfi-contenedor">
                <img src="<?= htmlspecialchars($rutaimagen) ?>" id="perfil-foto" class="perfil-img" alt="Foto de perfil">
            </div>

            <!-- Modal para ampliar imagen -->
            <div id="modal-foto" class="modal-foto">
                <span class="cerrar-modal">&times;</span>
                <img class="modal-contenido" id="img-ampliada" alt="Imagen ampliada">
            </div>

            <div class="datos-perfil_contenedor">
                <p><a href="verSeguidores.php?id=<?= $idusuarioLimpio ?>&user=<?= urlencode($_GET['user']) ?>&i=<?= urlencode($_GET['i']) ?>">Seguidores <?= $seguidores_count ?></a></p>
                <p><a href="verSeguidos.php?id=<?= $idusuarioLimpio ?>&user=<?= urlencode($_GET['user']) ?>&i=<?= urlencode($_GET['i']) ?>">Seguidos <?= $seguidos_count ?></a></p>
            </div>

            <div class="descripcion">
                <p><i class="fas fa-align-left"></i> "<?= htmlspecialchars($descripcion ?? '') ?>"</p>
                <p><i class="fas fa-phone"></i> Teléfono: <?= htmlspecialchars($telefono ?? '') ?></p>
            </div>

            <div class="perzonalizar-contenedor">
                <div class="Editar-perfil">
                    <p><a href="editarPerfil.php?user=<?= urlencode($_GET["user"]) ?>&i=<?= urlencode($_GET["i"]) ?>">Editar Perfil</a></p>
                </div>
                <div class="Compartir-perfil">
                    <p><a href="proximamente.php">Boletas</a></p>
                </div>
            </div>

            <div class="habilidades-contenedor" style="text-align: center; margin: 20px 0; padding: 15px;">
                <p style="font-size: 18px; font-weight: bold; color: var(--dorado-claro); margin-bottom: 15px; text-transform: uppercase;">Materias</p>
                <?php
                $habilidadesStmt = $pdo->prepare("SELECT nombre, nivel FROM habilidades WHERE id_usuario = ?");
                $habilidadesStmt->execute([$idusuarioLimpio]);
                $habilidades = $habilidadesStmt->fetchAll(PDO::FETCH_ASSOC);
                
                if ($habilidades): 
                    $color_index = 0;
                    foreach ($habilidades as $habilidad): 
                        $colors = [
                            'background: linear-gradient(135deg, var(--rojo-principal) 0%, var(--rojo-secundario) 100%);',
                            'background: linear-gradient(135deg, var(--rojo-secundario) 0%, var(--vino) 100%);',
                            'background: linear-gradient(135deg, var(--vino) 0%, var(--rojo-principal) 100%);',
                            'background: linear-gradient(135deg, var(--dorado-oscuro) 0%, var(--rojo-secundario) 100%);',
                            'background: linear-gradient(135deg, var(--rojo-principal) 0%, var(--dorado-oscuro) 100%);',
                            'background: linear-gradient(135deg, var(--vino) 0%, var(--dorado-claro) 100%);'
                        ];
                        $current_color = $colors[$color_index % count($colors)];
                        $color_index++;
                ?>
                <div style="<?= $current_color ?> border-radius: 12px; padding: 15px; margin: 10px; min-width: 150px; display: inline-block; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); transition: all 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.1);" 
                     onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(0, 0, 0, 0.3)';" 
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.2)';">
                    <p style="font-size: 16px; font-weight: bold; color: var(--blanco); margin: 0 0 5px 0; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);"><?= htmlspecialchars($habilidad['nombre']) ?></p>
                    <p style="font-size: 12px; color: var(--dorado-claro); margin: 0; font-style: italic;">Nivel: <?= htmlspecialchars($habilidad['nivel']) ?></p>
                </div>
                <?php 
                    endforeach;
                else: 
                ?>
                <div style="background: linear-gradient(135deg, var(--rojo-principal) 0%, var(--vino) 100%); border-radius: 12px; padding: 15px; margin: 10px; min-width: 150px; display: inline-block; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.1);">
                    <p style="font-size: 16px; font-weight: bold; color: var(--blanco); margin: 0 0 5px 0; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);">No hay habilidades registradas</p>
                    <p style="font-size: 12px; color: var(--dorado-claro); margin: 0; font-style: italic;">Agrega tus Materias en editar perfil</p>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="insignias-contenedor" style="max-width: 500px; margin: 20px auto; text-align:center;">
                <h3 style="color: var(--dorado-claro); margin-bottom: 10px;">Insignias</h3>
                <?php if ($insignias): ?>
                    <?php 
                    $maxVisible = 4;
                    $totalInsignias = count($insignias);
                    ?>
                    <div style="display:flex; justify-content:center; gap:10px; overflow:hidden;">
                        <?php for ($i = 0; $i < min($maxVisible, $totalInsignias); $i++): ?>
                            <div style="text-align:center; margin-right:10px;">
                                <?php 
                                  $rutaInsignia = str_replace('\\', '/', $insignias[$i]['imagen']);
                                  if (strpos($rutaInsignia, 'Insignias/') === false && strpos($rutaInsignia, 'insignias/') === false) {
                                      $rutaInsignia = 'Insignias/' . $rutaInsignia;
                                  }
                                ?>
                                <img src="<?= htmlspecialchars($rutaInsignia) ?>" 
                                     alt="<?= htmlspecialchars($insignias[$i]['nombre']) ?>" 
                                     style="width:100%; height:100px; object-fit:contain; background:var(--blanco); border-radius:8px; cursor:pointer;"
                                     title="<?= htmlspecialchars($insignias[$i]['nombre']) ?>">
                            </div>
                        <?php endfor; ?>
                    </div>
                    <?php if ($totalInsignias > $maxVisible): ?>
                        <button id="btnVerTodas">Ver todas las insignias (<?= $totalInsignias ?>)</button>
                    <?php endif; ?>
                <?php else: ?>
                    <p style="color: var(--gris-claro);">Este usuario aún no tiene insignias.</p>
                <?php endif; ?>
            </div>

            <div id="modalInsignias" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.8); justify-content:center; align-items:center; z-index:1000;">
                <div style="background:var(--vino); padding:20px; border-radius:10px; max-width:90vw; max-height:80vh; overflow:auto;">
                    <h2 style="text-align:center; margin-bottom:20px; color:var(--dorado-claro);">Todas las Insignias</h2>
                    <div style="display:flex; flex-wrap: nowrap; overflow-x: auto; gap:15px;">
                        <?php foreach ($insignias as $insignia): ?>
                            <div style="text-align:center; flex:0 0 auto; border-radius:10%; justify-content:center;">
                                <?php 
                                  $rutaInsignia = str_replace('\\', '/', $insignia['imagen']);
                                  if (strpos($rutaInsignia, 'Insignias/') === false && strpos($rutaInsignia, 'insignias/') === false) {
                                      $rutaInsignia = 'Insignias/' . $rutaInsignia;
                                  }
                                ?>
                                <img src="<?= htmlspecialchars($rutaInsignia) ?>" 
                                     alt="<?= htmlspecialchars($insignia['nombre']) ?>" 
                                     style="width:100px; height:100px; object-fit:contain; background:var(--blanco); border-radius:8px; box-shadow:4px 4px 4px var(--dorado-oscuro); cursor:pointer; margin-right:10px;">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button id="cerrarModal">Cerrar</button>
                </div>
            </div>

            <div class="contenedor-recomendaciones">
                <p>Críticas y recomendaciones</p>
                <div id="comentariosPerfil"></div>
            </div>
        </div>
    </main>

    <script>
    function cargarComentariosPerfil(){
        fetch(`cargar_comentarios.php?id=<?= $idusuarioLimpio ?>&user=<?= urlencode($_GET['user']) ?>&i=<?= urlencode($_GET['i']) ?>`)
        .then(res => res.text())
        .then(html => {
            document.getElementById("comentariosPerfil").innerHTML = html;
        })
        .catch(error => {
            console.error('Error cargando comentarios:', error);
            document.getElementById("comentariosPerfil").innerHTML = "<p>Error al cargar comentarios</p>";
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        cargarComentariosPerfil();
    });

    const modal = document.getElementById("modal-foto");
    const img = document.getElementById("perfil-foto");
    const modalImg = document.getElementById("img-ampliada");
    const cerrar = document.getElementsByClassName("cerrar-modal")[0];

    if(img) {
        img.onclick = function () {
            modal.style.display = "block";
            modalImg.src = this.src;
        }
    }

    if(cerrar) {
        cerrar.onclick = function () {
            modal.style.display = "none";
        }
    }

    window.onclick = function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }

    document.getElementById('btnVerTodas')?.addEventListener('click', () => {
        document.getElementById('modalInsignias').style.display = 'flex';
    });

    document.getElementById('cerrarModal')?.addEventListener('click', () => {
        document.getElementById('modalInsignias').style.display = 'none';
    });

    document.getElementById('modalInsignias')?.addEventListener('click', (e) => {
        if (e.target === e.currentTarget) {
            document.getElementById('modalInsignias').style.display = 'none';
        }
    });
    </script>
</body>
<?php include_once 'includes/header.php'; ?>