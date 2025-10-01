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

// Procesar eliminación
if ($_POST && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Validar ID
    if (!is_numeric($id) || $id <= 0) {
        $error = "ID inválido.";
    } else {
        // Verificar que el registro exista
        $check = $conexion->prepare("SELECT id FROM usuarios WHERE id = ?");
        $check->bind_param("i", $id);
        $check->execute();
        if ($check->get_result()->num_rows === 0) {
            $error = "Registro no encontrado.";
        } else {
            // Eliminar registro
            $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) {
                $error = "Error al eliminar.";
            }
            $stmt->close();
        }
        $check->close();
    }
} else {
    $error = "ID no proporcionado.";
}

$conexion->close();

// Redirigir con mensaje
if (!isset($error)) {
    header("Location: index.php?mensaje=eliminado");
} else {
    header("Location: index.php?error=" . urlencode($error));
}
exit;
?>