<?php
session_start();
include("includes/PDOdb.php");

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idusuario'])) {
    header("Location: index.php");
    exit();
}

$mi_id = $_SESSION['idusuario'];

// Conectarse a la base de datos con PDO
$pdo = Conectarse(); // Asegúrate de que esta función devuelva una instancia PDO

// Preparar y ejecutar la consulta con PDO
$stmt = $pdo->prepare("SELECT idusuario, nombre, img_perfil FROM usuarios WHERE idusuario != :mi_id");
$stmt->execute([':mi_id' => $mi_id]);

// Obtener los resultados
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

//obtener usuario

$stmt = $pdo->prepare("SELECT idusuario, usuario, img_perfil FROM usuarios WHERE MD5(idusuario) = :id_encriptado");
$stmt->execute([':id_encriptado' => $_GET['i']]);

if ($f = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $idusuarioLimpio = $f['idusuario'];
    $Nombre = $f['usuario'];
    $rutaimagen = $f['img_perfil'];
} else {
    $idusuarioLimpio = null;
    $Nombre = "Usuario no encontrado";
    $rutaimagen = "ruta/por_defecto.jpg";
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="imagenes/logo.png">
    <title>Usuarios</title>
    <link rel="stylesheet" href="css/usuariosChatt.css">
</head>

<body class="usuarios-body">
    <header class="usuarios-header">
        <h2>Usuarios disponibles</h2>
    </header>
    <div class="usuarios-container">
        <?php foreach ($usuarios as $u): ?>
            <div class="usuario">
                <a href="chat.php?id=<?= htmlspecialchars($u['idusuario']) ?>&user=<?= urlencode($_GET['user']) ?>&UsuarioB=<?= urlencode($u['nombre']) ?>&idUsuarioB=<?= $u['idusuario'] ?>&i=<?= urlencode($_GET['i']) ?>">

                    <img src="<?= htmlspecialchars($u['img_perfil']) ?>" alt="Foto de <?= htmlspecialchars($u['nombre']) ?>" class="foto-perfil">
                    <?= htmlspecialchars($u['nombre']) ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>


</body>
<?php include_once 'includes/header.php'; ?>

</html>
