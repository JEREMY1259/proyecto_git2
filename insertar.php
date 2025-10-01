<?php
// insertar.php - Inserta un nuevo registro en la tabla 'usuarios'

$host = 'localhost';
$usuario = 'root';
$contraseña = '';
$bd = 'mi_crud';

$conexion = new mysqli($host, $usuario, $contraseña, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");

if ($_POST && isset($_POST['nombre']) && isset($_POST['email'])) {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);

    // Validaciones básicas
    if (empty($nombre)) {
        $error = "El nombre es obligatorio.";
    } elseif (empty($email)) {
        $error = "El correo es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo no es válido.";
    } else {
        // Verificar si el email ya existe (opcional, pero recomendado)
        $check = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = "Ya existe un registro con ese correo.";
        } else {
            // Insertar
            $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email) VALUES (?, ?)");
            $stmt->bind_param("ss", $nombre, $email);
            if ($stmt->execute()) {
                $exito = true;
            } else {
                $error = "Error al guardar el registro.";
            }
            $stmt->close();
        }
        $check->close();
    }
} else {
    $error = "Faltan datos del formulario.";
}

$conexion->close();

// Redirigir con mensaje
if (isset($exito)) {
    header("Location: index.php?mensaje=insertado");
} else {
    $mensaje = urlencode($error ?? "Error desconocido.");
    header("Location: index.php?error=" . $mensaje);
}
exit;
?>