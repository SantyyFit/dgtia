<?php
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cargando...</title>
    <link rel="shortcut icon" href="imagenes/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/Cpantalla.css" />
</head>

<body>
    <div class="splash-container">
        <img src="imagenes/logo.png" alt="Logo CBTis 199" class="logo" />
        <div class="loader"></div>
        <p class="loading-text">Cargando plataforma...</p>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = 'login.php';
        }, 4000);
    </script>
</body>

</html>
