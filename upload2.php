<?php
// Recibir datos
$idLimpio = $_GET["idLimpio"] ?? $_POST["idLimpio"] ?? 0;
$user = $_GET["user"] ?? $_POST["user"] ?? '';
$i = $_GET["i"] ?? $_POST["i"] ?? '';

if (!$idLimpio) {
    echo '<div class="alert alert-danger">Error: No se especificó el usuario</div>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_FILES["fileToUpload"])) {
    
    $target_dir = "fotosPerfil/" . $idLimpio . "/";
    $carpeta = $target_dir;
    
    // Crear carpeta si no existe
    if (!file_exists($carpeta)) {
        mkdir($carpeta, 0777, true);
    }
    
    // Función para comprimir imagen
    function compressImage($source, $destination, $quality) {
        $imgInfo = getimagesize($source);
        $mime = $imgInfo['mime'];
        
        switch($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($source);
                break;
            default:
                $image = imagecreatefromjpeg($source);
        }
        
        imagejpeg($image, $destination, $quality);
        imagedestroy($image);
        return $destination;
    }
    
    $nombre_archivo = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $carpeta . $nombre_archivo;
    $uploadOk = 1;
    $errors = [];
    $messages = [];
    
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Verificar si es una imagen real
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check === false) {
        $errors[] = "El archivo no es una imagen.";
        $uploadOk = 0;
    }
    
    // Verificar tamaño (máximo 6MB)
    if ($_FILES["fileToUpload"]["size"] > 6054234) {
        $errors[] = "El archivo es demasiado grande. Tamaño máximo: 6 MB";
        $uploadOk = 0;
    }
    
    // Permitir solo ciertos formatos
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed)) {
        $errors[] = "Solo archivos JPG, JPEG, PNG, GIF son permitidos.";
        $uploadOk = 0;
    }
    
    if ($uploadOk == 0) {
        $errors[] = "Tu archivo no fue subido.";
    } else {
        $imageTemp = $_FILES["fileToUpload"]["tmp_name"];
        
        // Comprimir la imagen
        $compressedImage = compressImage($imageTemp, $target_file, 50);
        
        if ($compressedImage) {
            // Actualizar base de datos
            include_once 'includes/dbconexion.php';
            
            // Usar mysqli (no mysql_query que está obsoleto)
            $query = "UPDATE usuarios SET img_perfil = '$target_file' WHERE idusuario = '$idLimpio'";
            $result = mysqli_query($conexion, $query);
            
            if ($result) {
                $messages[] = "Foto de perfil actualizada correctamente";
            } else {
                $errors[] = "Error al actualizar la base de datos: " . mysqli_error($conexion);
            }
        } else {
            $errors[] = "Hubo un error al procesar la imagen.";
        }
    }
    
    // Mostrar mensajes
    if (!empty($errors)) {
        ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Error!</strong>
            <?php foreach ($errors as $error) { echo "<p>$error</p>"; } ?>
        </div>
        <?php
    }
    
    if (!empty($messages)) {
        ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Éxito!</strong>
            <?php foreach ($messages as $message) { echo "<p>$message</p>"; } ?>
        </div>
        <?php
    }
}
?>

<script>
// Recargar la página padre y cerrar la ventana
if (window.opener && !window.opener.closed) {
    window.opener.location.reload();
}
setTimeout(function() {
    window.close();
}, 2000);
</script>