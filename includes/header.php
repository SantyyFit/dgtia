<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Contar notificaciones no vistas
$totalNotificaciones = 0;
if (isset($_SESSION['idusuario'])) {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=newskillcom_newskill;charset=utf8", "newskillcom_santy", "Diciembre1224#*");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM notificaciones WHERE idusuario = ? AND visto = 0");
        $stmt->execute([$_SESSION['idusuario']]);
        $totalNotificaciones = $stmt->fetchColumn();
    } catch (PDOException $e) {
        $totalNotificaciones = 0;
    }
}
?>

<link rel="stylesheet" href="css/headerr.css">

<header class="header">
    <div class="listacontainer">
        <ul class="header_nav-list">
            <li class="header_nav-item">
                <a href="perfil.php?user=<?= $_GET["user"]; ?>&i=<?= $_GET["i"]; ?>"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'perfil.php' ? 'active' : '' ?>">
                    <img src="imagenes/usuarioIcono4.png">
                </a>
            </li>

            <li class="header_nav-item">
                <a href="busqueda.php?user=<?= $_GET["user"]; ?>&i=<?= $_GET["i"]; ?>"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'busqueda.php' ? 'active' : '' ?>">
                    <img src="imagenes/busquedaIcono.png">
                </a>
            </li>

            <li class="header_nav-item">
                <a href="clases.php?user=<?= $_GET["user"]; ?>&i=<?= $_GET["i"]; ?>"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'clases.php' ? 'active' : '' ?>">
                    <img src="imagenes/relacionesIcono.png">
                </a>
            </li>

            <li class="header_nav-item">
                <a href="inicio.php?user=<?= $_GET["user"]; ?>&i=<?= $_GET["i"]; ?>"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'inicio.php' ? 'active' : '' ?>">
                    <img src="imagenes/hogar.png">
                </a>
            </li>

            <li class="header_nav-item" style="position: relative;">
                <a href="ver_notificaciones.php?user=<?= $_GET["user"]; ?>&i=<?= $_GET["i"]; ?>"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'ver_notificaciones.php' ? 'active' : '' ?>">
                    <img src="imagenes/campana.png">
                    <?php if ($totalNotificaciones > 0): ?>
                        <span style="
                            position: absolute;
                            top: -5px;
                            right: -5px;
                            background: red;
                            color: white;
                            font-size: 12px;
                            padding: 2px 6px;
                            border-radius: 50%;
                        "><?= $totalNotificaciones ?></span>
                    <?php endif; ?>
                </a>
            </li>

            <li class="header_nav-item">
                <a href="cursos.php?user=<?= $_GET["user"]; ?>&i=<?= $_GET["i"]; ?>"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'cursos.php' ? 'active' : '' ?>">
                    <img src="imagenes/clasesIcono.png" alt="Cursos">
                </a>
            </li>
        </ul>
    </div>
</header>
