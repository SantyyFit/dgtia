<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$usuario = $_GET['user'];



?>
<style>
    .perfil-header {
        width: 100%;
        padding: 15px 0;

        background: none;

        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }

    /* NOMBRE USUARIO */
    .Nombre-Usuario {
        background: rgba(221, 201, 163, 0.25);
        /* dorado transparente */
        backdrop-filter: blur(10px);

        color: var(--blanco);
        padding: 10px 20px;
        border-radius: 20px;

        font-weight: bold;
        letter-spacing: 1px;

        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
</style>

<header class="perfil-header">
    <nav class="navbar-perfil" style="display: flex; justify-content:center;">
        <p class="Nombre-Usuario"><?php echo htmlspecialchars($usuario) ?></p>
    </nav>
</header>
