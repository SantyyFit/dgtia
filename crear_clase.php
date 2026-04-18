<?php
// Activar errores solo en desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'includes/PDOdb.php';
include_once 'includes/insignias_helper.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit();
}



$id_usuario = $_SESSION['idusuario'];

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_clase'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $visibilidad = $_POST['visibilidad'];

    // Obtener conexión PDO
    $pdo = Conectarse();

    // 1. Insertar clase
    $stmt = $pdo->prepare("
        INSERT INTO clases (id_creador, titulo, descripcion, fecha_creacion, visibilidad)
        VALUES (?, ?, ?, NOW(), ?)
    ");
    $stmt->execute([$id_usuario, $titulo, $descripcion, $visibilidad]);
    $id_clase = $pdo->lastInsertId();

    // 2. Insertar materiales (si hay)
    if (!empty($_FILES['materiales']['name'][0])) {
        foreach ($_FILES['materiales']['tmp_name'] as $index => $tmpName) {
            if (is_uploaded_file($tmpName)) {
                $nombreArchivo = basename($_FILES['materiales']['name'][$index]);
                $ext = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
                $nombreUnico = uniqid() . '.' . $ext;
                $ruta = 'uploads/' . $nombreUnico;

                if (move_uploaded_file($tmpName, $ruta)) {
                    $tipo = mime_content_type($ruta);
                    $tipoMaterial = 'otro';

                    if (strpos($tipo, 'image/') === 0) {
                        $tipoMaterial = 'imagen';
                    } elseif ($tipo === 'application/pdf') {
                        $tipoMaterial = 'pdf';
                    } elseif (strpos($tipo, 'video/') === 0) {
                        $tipoMaterial = 'video';
                    }

                    $stmt = $pdo->prepare("
                        INSERT INTO materiales_clase (id_clase, tipo, contenido)
                        VALUES (?, ?, ?)
                    ");
                    $stmt->execute([$id_clase, $tipoMaterial, $ruta]);
                }
            }
        }
    }

    // 3. Agregar clase al repositorio personal como editable
    $stmt = $pdo->prepare("
        INSERT INTO repositorio (id_usuario, id_clase, permiso, fecha_agregado)
        VALUES (?, ?, 'editable', NOW())
    ");
    $stmt->execute([$id_usuario, $id_clase]);

    // 4. Asignar insignias por crear clase
    asignarInsignia($pdo, $id_usuario, 'compartidas');

    // 5. Redirigir de vuelta al repositorio
    echo "<script>
        alert('Clase creada correctamente.');
        window.history.back();
    </script>";
    exit();
}
?>
