<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include("includes/PDOdb.php");

if (!isset($_SESSION['idusuario'])) {
    header("Location: index.php");
    exit();
}

$mi_id = $_SESSION['idusuario'];
$pdo = Conectarse();

if (!isset($_GET['id'])) {
    header("Location: usuarios.php");
    exit();
}

$otro_id = intval($_GET['id']);

// Obtener datos del usuario receptor
$stmt = $pdo->prepare("SELECT nombre, img_perfil FROM usuarios WHERE idusuario = :id");
$stmt->execute([':id' => $otro_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Chat</title>
    <link rel="stylesheet" href="css/chat.css">
</head>

<body class="chat-body">

    <div class="chat-container">

        <header class="chat-header">
            <a href="javascript:history.back()" class="volver">←</a>
            <div class="perfil-info">
                <img src="<?= htmlspecialchars($usuario['img_perfil']) ?>" class="foto-perfil">
                <h3><?= htmlspecialchars($usuario['nombre']) ?></h3>
            </div>
        </header>

        <div id="chat-box" class="chat-box"></div>

        <div class="input-area">
            <input type="text" id="mensaje" placeholder="Escribe un mensaje...">
            <button onclick="enviarMensaje()">➤</button>
        </div>

    </div>

    <script>
        let receptorId = <?= $otro_id ?>;

        function enviarMensaje() {
            let input = document.getElementById('mensaje');
            let mensaje = input.value;

            if (mensaje.trim() === '') return;

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "sendMessage.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                cargarMensajes();
            };

            xhr.send("mensaje=" + encodeURIComponent(mensaje) + "&receptor=" + receptorId);

            input.value = "";
        }

        function cargarMensajes() {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "getMessages.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                let box = document.getElementById("chat-box");
                box.innerHTML = this.responseText;
                box.scrollTop = box.scrollHeight;
            };

            xhr.send("receptor=" + receptorId);
        }

        // cargar cada 2 segundos
        setInterval(cargarMensajes, 2000);
        window.onload = cargarMensajes;
    </script>

</body>

</html>
