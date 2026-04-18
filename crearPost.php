<?php
include_once 'includes/session.php';
include_once 'includes/PDOdb.php';
include_once 'includes/head.php';

// Validar parámetros
if (!isset($_GET['user'], $_GET['i']) || empty($_GET['user']) || empty($_GET['i'])) {
    die("Parámetros inválidos.");
}

if (!isset($_GET['esp']) && !isset($_GET['mat'])) {
    die("Especialidad o materia no especificada.");
}

$usuario = htmlspecialchars(trim($_GET['user']));
$hashId = htmlspecialchars(trim($_GET['i']));

// Determinar si es especialidad o materia
if (isset($_GET['esp'])) {
    $tipo = 'esp';
    $valor = htmlspecialchars(trim($_GET['esp']));
} elseif (isset($_GET['mat'])) {
    $tipo = 'mat';
    $valor = htmlspecialchars(trim($_GET['mat']));
}

$especialidad = $valor; // Para compatibilidad
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'programacion.php?user=' . urlencode($usuario) . '&i=' . urlencode($hashId) . '&' . $tipo . '=' . urlencode($valor);

// Fondo según la especialidad
$fondo = "default.jpg";
switch (strtolower($especialidad)) {
    case 'artes':
        $fondo = "pexels-cottonbro-3777876.jpg";
        break;
    case 'deportes':
        $fondo = "freepik__candid-image-photography-natural-textures-highly-r__56599.jpeg";
        break;
    case 'matematicas':
        $fondo = "pexels-silverkblack-22690752.jpg";
        break;
    case 'videojuegos':
        $fondo = "pexels-lulizler-3165335.jpg";
        break;
    case 'programacion':
        $fondo = "pexels-markusspiske-965345.jpg";
        break;
    default:
        $fondo = "default.jpg";
}
?>

<link rel="stylesheet" href="css/crearpost.css">

<body class="crearpost-body" style="background-image: url('imagenes/<?= $fondo ?>'); background-size: cover; background-position: center;">
    <div class="crearpost-container">
        <h1>Crear nueva publicación</h1>
        <p class="especialidad-info">Especialidad: <strong><?= ucfirst($especialidad) ?></strong></p>
        
        <form action="guardarPost.php?user=<?= urlencode($usuario) ?>&i=<?= urlencode($hashId) ?>&<?= $tipo ?>=<?= urlencode($valor) ?>" method="POST" class="crearpost-form">

            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" required placeholder="Escribe un título para tu publicación">
            </div>

            <!-- Campo skill eliminado - la especialidad se toma de esp -->

            <div class="form-group">
                <label for="contenido">Contenido:</label>
                <textarea id="contenido" name="contenido" rows="5" required placeholder="Escribe el contenido de tu publicación..."></textarea>
            </div>

            <input type="hidden" name="redirect" value="<?= htmlspecialchars($referer) ?>">
            <input type="hidden" name="esp" value="<?= $especialidad ?>">

            <div class="form-buttons">
                <button type="submit" class="btn-publicar">Publicar</button>
                <a href="<?= htmlspecialchars($referer) ?>" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>

    <style>
        .crearpost-container {
            max-width: 600px;
            margin: 50px auto;
            background: rgba(140, 51, 51, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        }
        .crearpost-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .especialidad-info {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background: #4a90e2;
            color: white;
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 150px;
        }
        .form-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 25px;
        }
        .btn-publicar {
            background: #4a90e2;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .btn-publicar:hover {
            background: #357abd;
        }
        .btn-cancelar {
            background: #6c757d;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
            display: inline-block;
        }
        .btn-cancelar:hover {
            background: #5a6268;
            color: white;
        }
    </style>
</body>
<?php include_once 'includes/header.php'; ?>
</html>