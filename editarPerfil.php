<?php include_once 'includes/session.php'; ?>
<?php include_once 'includes/dbconexion.php'; ?>
<?php include_once 'includes/head.php'; ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');

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

    /* ─── RESET ─────────────────────────────────────────────────────────────────── */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* ─── BODY ───────────────────────────────────────────────────────────────────── */
    body.editar-body {
        font-family: 'Montserrat', sans-serif;
        background: linear-gradient(135deg, var(--vino) 0%, var(--rojo-principal) 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 100vh;
        padding-bottom: 40px;
    }

    /* ─── HEADER ─────────────────────────────────────────────────────────────────── */
    .editar-header {
        width: 100%;
        background: rgba(105, 28, 50, 0.88);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        padding: 18px 30px;
        text-align: center;
        border-bottom: 2px solid var(--dorado-oscuro);
        margin-bottom: 32px;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .editar-header p {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--dorado-claro);
        letter-spacing: 3px;
        text-transform: uppercase;
    }

    /* ─── MENSAJE DE ESTADO ──────────────────────────────────────────────────────── */
    #mensaje-estado {
        width: 100%;
        max-width: 480px;
        padding: 12px 18px;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        text-align: center;
        margin-bottom: 14px;
        animation: fadeIn 0.4s ease;
    }

    /* ─── FORMULARIO ─────────────────────────────────────────────────────────────── */
    form {
        width: 100%;
        max-width: 480px;
        padding: 0 16px;
    }

    /* ─── TARJETA PRINCIPAL ──────────────────────────────────────────────────────── */
    .ePerfil-contenedor {
        background: var(--dorado-claro);
        border-radius: 22px;
        padding: 32px 28px;
        box-shadow:
            0 12px 40px rgba(0, 0, 0, 0.35),
            0 2px 8px rgba(105, 28, 50, 0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 14px;
    }

    /* ─── FOTO DE PERFIL ─────────────────────────────────────────────────────────── */
    .ePerfil-contenedor>img {
        width: 106px;
        height: 106px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--dorado-oscuro);
        box-shadow: 0 4px 14px rgba(105, 28, 50, 0.3);
        transition: transform 0.2s;
    }

    .ePerfil-contenedor>img:hover {
        transform: scale(1.04);
    }

    /* ─── ENLACE EDITAR FOTO ─────────────────────────────────────────────────────── */
    .editar-nombre {
        margin-top: -4px;
        margin-bottom: 4px;
    }

    .editar-nombre a {
        font-size: 0.78rem;
        color: var(--vino);
        font-weight: 600;
        text-decoration: none;
        letter-spacing: 0.5px;
        transition: color 0.2s;
    }

    .editar-nombre a:hover {
        color: var(--rojo-principal);
        text-decoration: underline;
    }

    /* ─── INPUTS ─────────────────────────────────────────────────────────────────── */
    .input-container {
        display: flex;
        align-items: center;
        width: 100%;
        background: #faf6f0;
        border-radius: 10px;
        padding: 11px 14px;
        border: 1.5px solid #e0d3b8;
        transition: border-color 0.25s, box-shadow 0.25s, background 0.2s;
    }

    .input-container:focus-within {
        border-color: var(--rojo-principal);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(167, 32, 31, 0.12);
    }

    .input-icon {
        color: var(--vino);
        font-size: 0.95rem;
        margin-right: 10px;
        flex-shrink: 0;
        opacity: 0.85;
    }

    .input-container input,
    .input-container select {
        flex: 1;
        border: none;
        outline: none;
        background: transparent;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.9rem;
        color: #2e2e2e;
    }

    .input-container input::placeholder {
        color: var(--gris-claro);
        font-weight: 400;
    }

    .input-container select {
        cursor: pointer;
        color: #2e2e2e;
    }

    /* ─── SECCIÓN HABILIDADES ────────────────────────────────────────────────────── */
    .habilidades-section {
        width: 100%;
        background: rgba(255, 255, 255, 0.42);
        border-radius: 14px;
        padding: 18px 16px;
        border: 1.5px solid var(--dorado-oscuro);
    }

    .habilidades-section h3 {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--vino);
        margin-bottom: 14px;
        letter-spacing: 1px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    #habilidades-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    /* ─── FILA DE HABILIDAD ──────────────────────────────────────────────────────── */
    .habilidad-input {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .habilidad-input .input-container {
        flex: 1;
        min-width: 110px;
    }

    /* ─── BOTÓN ELIMINAR HABILIDAD ───────────────────────────────────────────────── */
    .eliminar-habilidad {
        background: var(--rojo-principal);
        border: none;
        color: var(--blanco);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 0.82rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        box-shadow: 0 2px 6px rgba(167, 32, 31, 0.3);
    }

    .eliminar-habilidad:hover {
        background: var(--vino);
        transform: scale(1.12);
        box-shadow: 0 4px 10px rgba(105, 28, 50, 0.4);
    }

    /* ─── BOTÓN AGREGAR HABILIDAD ────────────────────────────────────────────────── */
    .agregar-habilidad-btn {
        margin-top: 12px;
        width: 100%;
        padding: 10px;
        border: 2px dashed var(--dorado-oscuro);
        border-radius: 10px;
        background: transparent;
        color: var(--vino);
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, color 0.2s, border-style 0.1s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .agregar-habilidad-btn:hover {
        background: var(--dorado-oscuro);
        color: var(--blanco);
        border-style: solid;
    }

    /* ─── BOTÓN ACTUALIZAR ───────────────────────────────────────────────────────── */
    .ePerfil-contenedor>button[type="submit"] {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 10px;
        background: linear-gradient(90deg, var(--rojo-principal), var(--vino));
        color: var(--blanco);
        font-family: 'Montserrat', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 1.2px;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s, filter 0.2s;
        box-shadow: 0 4px 16px rgba(105, 28, 50, 0.4);
        margin-top: 6px;
        text-transform: uppercase;
    }

    .ePerfil-contenedor>button[type="submit"]:hover {
        transform: scale(1.03);
        box-shadow: 0 6px 22px rgba(105, 28, 50, 0.55);
        filter: brightness(1.05);
    }

    .ePerfil-contenedor>button[type="submit"]:active {
        transform: scale(0.98);
    }

    /* ─── ANIMACIONES ────────────────────────────────────────────────────────────── */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ─── RESPONSIVE ─────────────────────────────────────────────────────────────── */
    @media (max-width: 520px) {
        .editar-header p {
            font-size: 1.1rem;
            letter-spacing: 2px;
        }

        .ePerfil-contenedor {
            padding: 24px 18px;
        }

        .ePerfil-contenedor>img {
            width: 90px;
            height: 90px;
        }

        .habilidad-input {
            flex-direction: column;
            align-items: stretch;
        }

        .habilidad-input .input-container {
            min-width: 100%;
        }

        .eliminar-habilidad {
            align-self: flex-end;
        }
    }
</style>

<script>
    function abrirmontos(url) {
        window.open(url, '', 'top=50,left=30,width=1100,height=650');
    }

    function agregarHabilidad() {
        const contenedor = document.getElementById('habilidades-container');
        const nuevaHabilidad = document.createElement('div');
        nuevaHabilidad.className = 'habilidad-input';
        nuevaHabilidad.innerHTML = `
            <div class="input-container">
                <i class="fas fa-tools input-icon"></i>
                <input type="text" placeholder="Nombre de la habilidad" name="habilidades_nombre[]">
            </div>
            <div class="input-container">
                <i class="fas fa-signal input-icon"></i>
                <select name="habilidades_nivel[]">
                    <option value="Principiante">Principiante</option>
                    <option value="Intermedio">Intermedio</option>
                    <option value="Avanzado">Avanzado</option>
                    <option value="Experto">Experto</option>
                </select>
            </div>
            <button type="button" class="eliminar-habilidad" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        contenedor.appendChild(nuevaHabilidad);
    }

    window.onload = function() {
        setTimeout(function() {
            const mensaje = document.getElementById("mensaje-estado");
            if (mensaje) mensaje.style.display = 'none';
        }, 3000);
    }
</script>

<link rel="stylesheet" href="css/editarPerfil.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<body class="editar-body">
    <header class="editar-header">
        <p>Editar Perfil</p>
    </header>

    <?php
    // ─── Validar parámetro obligatorio ───────────────────────────────────────────
    if (!isset($_GET["i"]) || empty($_GET["i"])) {
        die("Acceso no válido.");
    }

    // Castear a entero — si viene "40" queda 40, si viene texto malicioso queda 0
    $i    = (int) $_GET["i"];
    $user = isset($_GET["user"]) ? trim($_GET["user"]) : '';

    // Validar que el ID sea positivo
    if ($i <= 0) {
        die("ID de usuario no válido.");
    }

    // ─── Buscar usuario por ID entero con Prepared Statement ─────────────────────
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE idusuario = ?");
    if (!$stmt) {
        die("Error en la consulta.");
    }
    $stmt->bind_param("i", $i); // "i" = integer, protege contra SQL Injection
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) {
        die("Usuario no encontrado.");
    }

    $f               = $result->fetch_assoc();
    $stmt->close();

    $idusuarioLimpio = (int) $f["idusuario"];
    $Nombre          = htmlspecialchars($f["usuario"]          ?? '', ENT_QUOTES, 'UTF-8');
    $imgRaw          = !empty($f["img_perfil"]) ? $f["img_perfil"] : 'imagenes/default.png';
    $rutaimagen      = htmlspecialchars('uploads/' . $imgRaw,      ENT_QUOTES, 'UTF-8');
    $descripcion     = htmlspecialchars($f["descripcion"]      ?? '', ENT_QUOTES, 'UTF-8');
    $nivel           = htmlspecialchars((string)($f["nivel"]           ?? ''), ENT_QUOTES, 'UTF-8');
    $telefono        = htmlspecialchars((string)($f["numero_telefono"] ?? ''), ENT_QUOTES, 'UTF-8');

    // ─── Habilidades ─────────────────────────────────────────────────────────────
    $stmtHab = $conexion->prepare("SELECT * FROM habilidades WHERE id_usuario = ?");
    if (!$stmtHab) {
        die("Error al cargar habilidades.");
    }
    $stmtHab->bind_param("i", $idusuarioLimpio);
    $stmtHab->execute();
    $habilidades_result     = $stmtHab->get_result();
    $habilidades_existentes = [];
    while ($hab = $habilidades_result->fetch_assoc()) {
        $habilidades_existentes[] = $hab;
    }
    $stmtHab->close();

    // ─── Mensaje de estado ───────────────────────────────────────────────────────
    if (isset($_GET['status'])) {
        if ($_GET['status'] === 'ok') {
            echo "<div id='mensaje-estado' style='color:#2e7d32;background:#e8f5e9;border:1px solid #a5d6a7;padding:10px 16px;border-radius:10px;max-width:480px;margin:0 auto 14px;text-align:center;font-weight:600;'>✔ Datos actualizados correctamente</div>";
        } else {
            echo "<div id='mensaje-estado' style='color:#b71c1c;background:#ffebee;border:1px solid #ef9a9a;padding:10px 16px;border-radius:10px;max-width:480px;margin:0 auto 14px;text-align:center;font-weight:600;'>✖ Error al actualizar</div>";
        }
    }

    $userEsc = htmlspecialchars($user, ENT_QUOTES, 'UTF-8');
    $iEsc    = $idusuarioLimpio; // ya es entero, no necesita escape
    ?>

    <form action="editarinformacion.php?user=<?= $userEsc ?>&i=<?= $iEsc ?>" method="post">
        <div class="ePerfil-contenedor">

            <img src="<?= $rutaimagen ?>" alt="Foto de perfil">
            <div class="editar-nombre">
                <a href="javascript:abrirmontos('adjuntar2.php?user=<?= $userEsc ?>&i=<?= $iEsc ?>')">
                    <i class="fas fa-camera" style="margin-right:4px;"></i>Editar Foto
                </a>
            </div>

            <div class="input-container">
                <i class="fas fa-user input-icon"></i>
                <input type="text" placeholder="Nombre de usuario" name="nombre" value="<?= $Nombre ?>">
            </div>

            <div class="input-container">
                <i class="fas fa-align-left input-icon"></i>
                <input type="text" placeholder="Descripción" name="descripcion" value="<?= $descripcion ?>">
            </div>

            <div class="input-container">
                <i class="fas fa-signal input-icon"></i>
                <input type="text" placeholder="Nivel" name="nivel" value="<?= $nivel ?>">
            </div>

            <div class="input-container">
                <i class="fas fa-phone input-icon"></i>
                <input type="text" placeholder="123 456 7890" name="telefono"
                    value="<?= $telefono ?>" maxlength="20">
            </div>

            <div class="habilidades-section">
                <h3><i class="fas fa-tools"></i> Habilidades</h3>
                <div id="habilidades-container">
                    <?php if (count($habilidades_existentes) > 0): ?>
                        <?php foreach ($habilidades_existentes as $habilidad): ?>
                            <div class="habilidad-input">
                                <div class="input-container">
                                    <i class="fas fa-tools input-icon"></i>
                                    <input type="text" placeholder="Nombre de la habilidad"
                                        name="habilidades_nombre[]"
                                        value="<?= htmlspecialchars($habilidad['nombre'], ENT_QUOTES, 'UTF-8') ?>">
                                </div>
                                <div class="input-container">
                                    <i class="fas fa-signal input-icon"></i>
                                    <select name="habilidades_nivel[]">
                                        <?php foreach (['Principiante', 'Intermedio', 'Avanzado', 'Experto'] as $lvl): ?>
                                            <option value="<?= $lvl ?>" <?= $habilidad['nivel'] === $lvl ? 'selected' : '' ?>>
                                                <?= $lvl ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="button" class="eliminar-habilidad" onclick="this.parentElement.remove()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="habilidad-input">
                            <div class="input-container">
                                <i class="fas fa-tools input-icon"></i>
                                <input type="text" placeholder="Nombre de la habilidad" name="habilidades_nombre[]">
                            </div>
                            <div class="input-container">
                                <i class="fas fa-signal input-icon"></i>
                                <select name="habilidades_nivel[]">
                                    <option value="Principiante">Principiante</option>
                                    <option value="Intermedio">Intermedio</option>
                                    <option value="Avanzado">Avanzado</option>
                                    <option value="Experto">Experto</option>
                                </select>
                            </div>
                            <button type="button" class="eliminar-habilidad" onclick="this.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="button" class="agregar-habilidad-btn" onclick="agregarHabilidad()">
                    <i class="fas fa-plus"></i> Agregar Habilidad
                </button>
            </div>

            <button type="submit">Actualizar</button>
        </div>
    </form>

    <?php include_once 'includes/header.php'; ?>
</body>
