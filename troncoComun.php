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
        <h1>Tronco Común</h1>
    </header>

    <section class="portfolio">
        <div class="portfolio_project-container">

            <div class="portfolio_project" style="background-image: linear-gradient(#0006, #0006), url(imagenes/lengua.jpeg)">
                <h2><a href="lenguaje1.php?user=<?= $_GET["user"]; ?>&i=<?= $_GET["i"]; ?>&mat=Lenguaje y Comunicación">Lenguaje y Comunicación </a></h2>
            </div>

            <div class="portfolio_project" style="background-image: linear-gradient(#0006, #0006), url(imagenes/mate.jpeg)">
                <h2><a href="publicacionesTC.php?user=<?= $_GET["user"]; ?>&i=<?= $_GET["i"]; ?>&mat=Pensamiento Matemático">Pensamiento Matemático </a></h2>
            </div>

            <div class="portfolio_project" style="background-image: linear-gradient(#0006, #0006), url(imagenes/cultura.jpeg)">
                <h2><a href="publicacionesTC.php?user=<?= $_GET["user"]; ?>&i=<?= $_GET["i"]; ?>&mat=Cultura Digital">Cultura Digital </a></h2>
            </div>

            <div class="portfolio_project" style="background-image: linear-gradient(#0006, #0006), url(imagenes/lamateria.jpeg)">
                <h2><a href="publicacionesTC.php?user=<?= $_GET["user"]; ?>&i=<?= $_GET["i"]; ?>&mat=LaMateria y sus Interacciones">La Materia y sus Interacciones</a></h2>
            </div>

            <div class="portfolio_project" style="background-image: linear-gradient(#0006, #0006), url(imagenes/humani.jpeg)">
                <h2><a href="publicacionesTC.php?user=<?= $_GET["user"]; ?>&i=<?= $_GET["i"]; ?>&mat=Humanidades">Humanidades </a></h2>
            </div>

            <div class="portfolio_project" style="background-image: linear-gradient(#0006, #0006), url(imagenes/sociales.jpeg)">
                <h2><a href="publicacionesTC.php?user=<?= $_GET["user"]; ?>&i=<?= $_GET["i"]; ?>&mat=Ciencias Sociales">Ciencias Sociales </a></h2>
            </div>

            <div class="portfolio_project" style="background-image: linear-gradient(#0006, #0006), url(imagenes/forma.jpeg)">
                <h2><a href="publicacionesTC.php?user=<?= $_GET["user"]; ?>&i=<?= $_GET["i"]; ?>&mat=Formación Socioemocional">Formación Socioemocional </a></h2>
            </div>

            <div class="portfolio_project" style="background-image: linear-gradient(#0006, #0006), url(imagenes/ingles.jpeg)">
                <h2><a href="publicacionesTC.php?user=<?= $_GET["user"]; ?>&i=<?= $_GET["i"]; ?>&mat=Inglés">Inglés </a></h2>
            </div>

        </div>
    </section>

    <?
    include_once 'includes/header.php';
    ?>

</body>
