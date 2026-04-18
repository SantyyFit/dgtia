<?php
include_once 'includes/session.php';
include_once 'includes/PDOdb.php';

// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar los parámetros GET
if (!isset($_GET['user'], $_GET['i']) || empty($_GET['user']) || empty($_GET['i'])) {
    die("Parámetros inválidos. Faltan user o i.");
}

$usuario = htmlspecialchars(trim($_GET['user']));
$hashId = htmlspecialchars(trim($_GET['i']));

// Determinar si es especialidad o materia
if (isset($_GET['esp'])) {
    $tipo = 'skill';
    $valor = trim($_GET['esp']);
} elseif (isset($_GET['mat'])) {
    $tipo = 'materia';
    $valor = trim($_GET['mat']);
} else {
    $valor = 'Programacion';
    $tipo = 'skill';
}

// Verificar si se recibió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Validar y sanitizar los datos del formulario
    $titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
    $contenido = isset($_POST['contenido']) ? trim($_POST['contenido']) : '';
    
    // Validar campos obligatorios
    $errores = [];
    if (empty($titulo)) {
        $errores[] = "El título es obligatorio.";
    }
    if (empty($contenido)) {
        $errores[] = "El contenido es obligatorio.";
    }
    
    // Si hay errores, mostrarlos y detener
    if (!empty($errores)) {
        echo "<div style='color: red; padding: 20px;'>";
        echo "<h3>Error al guardar la publicación:</h3>";
        foreach ($errores as $error) {
            echo "<p>- $error</p>";
        }
        echo "<a href='javascript:history.back()'>Volver atrás</a>";
        echo "</div>";
        exit();
    }
    
    try {
        // Verificar si el ID corresponde a algún idusuario en la tabla `usuarios`
        $stmt = $pdo->prepare("SELECT idusuario FROM usuarios WHERE idusuario = :id");
        $stmt->execute([':id' => $hashId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("El ID proporcionado no es válido o el usuario no existe.");
        }

        $userId = $user['idusuario'];

        // Insertar el post en la columna correspondiente
        $stmt = $pdo->prepare("INSERT INTO posts (idusuario, titulo, $tipo, contenido) VALUES (:idusuario, :titulo, :valor, :contenido)");
        $stmt->execute([
            ':idusuario' => $userId,
            ':titulo' => $titulo,
            ':valor' => $valor,
            ':contenido' => $contenido
        ]);

        // Determinar la URL de redirección
        $param = $tipo === 'skill' ? 'esp' : 'mat';
        $redirectUrl = isset($_POST['redirect']) && !empty($_POST['redirect']) 
            ? $_POST['redirect'] 
            : "programacion.php?user=" . urlencode($usuario) . "&i=" . urlencode($hashId) . "&$param=" . urlencode($valor);

        // Redirigir
        header("Location: $redirectUrl");
        exit();
        
    } catch (PDOException $e) {
        // Manejo de errores
        echo "<div style='color: red; padding: 20px;'>";
        echo "<h3>Error al guardar la publicación:</h3>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<a href='javascript:history.back()'>Volver atrás</a>";
        echo "</div>";
        exit();
    }
} else {
    die("Método no permitido. Se esperaba una solicitud POST.");
}
?>