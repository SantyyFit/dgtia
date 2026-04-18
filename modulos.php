<?php
include_once 'includes/session.php'
?>

<?php
$usuario = $_GET['user'];
?>

<?
include_once 'includes/dbconexion.php'
?>

<?php
include_once './includes/head.php';
?>

<link rel="stylesheet" href="css/modulosss.css">

<body>

    <header class="header-foro">
        <h1>Módulos</h1>
    </header>

    <section class="portfolio">
        <div class="portfolio_project-container">

            <div class="portfolio_project" style="background-image: linear-gradient(#0006, #0006), url(imagenes/construccion.jpeg)">
                <h2><a href="publicaciones.php?user=<?= urlencode($_GET["user"]); ?>&i=<?= urlencode($_GET["i"]); ?>&esp=construccion">Construcción</a></h2>
            </div>

            <div class="portfolio_project" style="background-image: linear-gradient(#0006, #0006), url(imagenes/contabilidad.jpeg)">
                <h2><a href="publicaciones.php?user=<?= urlencode($_GET["user"]); ?>&i=<?= urlencode($_GET["i"]); ?>&esp=contabilidad">Contabilidad</a></h2>
            </div>

            <div class="portfolio_project" style="background-image: linear-gradient(#0006, #0006), url(imagenes/laboratorio.jpeg)">
                <h2><a href="publicaciones.php?user=<?= urlencode($_GET["user"]); ?>&i=<?= urlencode($_GET["i"]); ?>&esp=laboratorio">Laboratorista Clínico</a></h2>
            </div>

            <div class="portfolio_project" style="background-image: linear-gradient(#0006, #0006), url(imagenes/programacion.jpeg)">
                <h2><a href="publicaciones.php?user=<?= urlencode($_GET["user"]); ?>&i=<?= urlencode($_GET["i"]); ?>&esp=programacion">Programación</a></h2>
            </div>

            <div class="portfolio_project" style="background-image: linear-gradient(#0006, #0006), url(imagenes/ia.jpeg)">
                <h2><a href="publicaciones.php?user=<?= urlencode($_GET["user"]); ?>&i=<?= urlencode($_GET["i"]); ?>&esp=ia">Inteligencia Artificial</a></h2>
            </div>

        </div>
    </section>

    <?
    include_once 'includes/header.php';
    ?>

</body>
