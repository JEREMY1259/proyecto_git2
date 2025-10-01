
<?php
// eliminar.php - Elimina un registro de la tabla 'usuarios'

$host = 'localhost';
$usuario = 'root';
$contraseña = '';
$bd = 'mi_crud';

$conexion = new mysqli($host, $usuario, $contraseña, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");

if ($_POST && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Validar que sea un número entero positivo
    if (!is_numeric($id) || $id <= 0) {
        $error = "ID inválido.";
    } else {
        // Verificar que el registro exista (opcional, pero recomendado)
        $check = $conexion->prepare("SELECT id FROM usuarios WHERE id = ?");
        $check->bind_param("i", $id);
        $check->execute();
        if ($check->get_result()->num_rows === 0) {
            $error = "No se encontró un registro con ese ID.";
        } else {
            // Eliminar
            $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $exito = true;
            } else {
                $error = "Error al eliminar el registro.";
            }
            $stmt->close();
        }
        $check->close();
    }
} else {
    $error = "No se recibió un ID válido.";
}

$conexion->close();

// Redirigir con mensaje
if (isset($exito)) {
    header("Location: index.php?mensaje=eliminado");
} else {
    $mensaje = urlencode($error ?? "Error desconocido.");
    header("Location: index.php?error=" . $mensaje);
}
exit;
?>