<?php
// actualizar.php - Actualiza un registro en la tabla 'usuarios'

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

// Verificar que se envió el formulario
if ($_POST) {
    $id = $_POST['id'] ?? null;
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Validar que el ID sea un número y que al menos uno de los campos tenga valor
    if (!$id || !is_numeric($id)) {
        $error = "ID inválido.";
    } elseif (empty($nombre) && empty($email)) {
        $error = "Debes proporcionar al menos un campo para actualizar.";
    } else {
        // Construir la consulta dinámicamente
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
            // Actualizar
            $sql = "UPDATE usuarios SET " . implode(", ", $campos) . " WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $params[] = $id;
            $types .= "i";
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                $exito = true;
            } else {
                $error = "Error al actualizar: " . $stmt->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}

$conexion->close();

// Redirigir con mensaje
if (isset($exito)) {
    header("Location: index.php?mensaje=actualizado");
} else {
    $mensaje = urlencode($error ?? "Error desconocido.");
    header("Location: index.php?error=" . $mensaje);
}
exit;
?>