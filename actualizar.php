<?php
// Configuración de la base de datos
$host = 'localhost';
$usuario = 'root';
$contraseña = '';
$bd = 'mi_crud';

$conexion = new mysqli($host, $usuario, $contraseña, $bd);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
$conexion->set_charset("utf8");

// Procesar formulario
if ($_POST) {
    $id = $_POST['id'] ?? null;
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Validaciones
    if (!$id || !is_numeric($id)) {
        $error = "ID inválido.";
    } elseif (empty($nombre) && empty($email)) {
        $error = "Debes proporcionar al menos un campo para actualizar.";
    } else {
        // Preparar campos a actualizar
        $campos = [];
        $params = [];
        $types = "";

        if (!empty($nombre)) {
            $campos[] = "nombre = ?";
            $params[] = $nombre;
            $types .= "s";
        }
        if (!empty($email)) {
            $campos[] = "email = ?";
            $params[] = $email;
            $types .= "s";
        }

        // Verificar que el registro exista
        $check = $conexion->prepare("SELECT id FROM usuarios WHERE id = ?");
        $check->bind_param("i", $id);
        $check->execute();
        if ($check->get_result()->num_rows === 0) {
            $error = "No se encontró un registro con ese ID.";
        } else {
            // Ejecutar actualización
            $sql = "UPDATE usuarios SET " . implode(", ", $campos) . " WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $params[] = $id;
            $types .= "i";
            $stmt->bind_param($types, ...$params);

            if (!$stmt->execute()) {
                $error = "Error al actualizar.";
            }
            $stmt->close();
        }
        $check->close();
    }
}

$conexion->close();

// Redirigir con mensaje
if (!isset($error)) {
    header("Location: index.php?mensaje=actualizado");
} else {
    header("Location: index.php?error=" . urlencode($error));
}
exit;
?>