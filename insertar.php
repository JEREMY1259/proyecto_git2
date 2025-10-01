<?php
// === CONFIGURACIÓN DE LA BASE DE DATOS ===
// Estos valores deben coincidir con tu entorno local (XAMPP, WAMP, etc.)
$host = 'localhost';        // Servidor de la base de datos (normalmente 'localhost')
$usuario = 'root';          // Usuario predeterminado en entornos locales
$contraseña = '';           // Contraseña vacía por defecto (¡cámbiala en producción!)
$bd = 'mi_crud';            // Nombre de la base de datos que contiene la tabla 'usuarios'

// Establecer conexión con MySQL usando la extensión MySQLi (estilo orientado a objetos)
$conexion = new mysqli($host, $usuario, $contraseña, $bd);

// Verificar si hubo un error al conectar y detener la ejecución si es así
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Establecer el juego de caracteres a UTF-8 para soportar acentos, ñ, emojis, etc.
$conexion->set_charset("utf8");

// === PROCESAMIENTO DEL FORMULARIO DE INSERCIÓN ===
// Solo procesar si la solicitud es POST y se enviaron ambos campos: 'nombre' y 'email'
if ($_POST && isset($_POST['nombre']) && isset($_POST['email'])) {
    // Sanitizar entradas: eliminar espacios innecesarios al inicio y al final
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);

    // === VALIDACIONES DE DATOS ===
    if (empty($nombre)) {
        $error = "El nombre es obligatorio.";
    } elseif (empty($email)) {
        $error = "El correo es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Usar la función nativa de PHP para validar formato de correo electrónico
        $error = "El correo no es válido.";
    } else {
        // === EVITAR REGISTROS DUPLICADOS POR CORREO ===
        // Asumimos que el correo debe ser único (coherente con la lógica del CRUD)
        $check = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
        $check->bind_param("s", $email); // "s" = string
        $check->execute();
        
        // Si ya existe un registro con ese correo
        if ($check->get_result()->num_rows > 0) {
            $error = "Ya existe un registro con ese correo.";
        } else {
            // === INSERTAR NUEVO REGISTRO ===
            // Usar sentencia preparada para prevenir inyección SQL
            $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email) VALUES (?, ?)");
            $stmt->bind_param("ss", $nombre, $email); // Ambos parámetros son strings
            
            // Ejecutar la inserción; si falla, capturar el error
            if (!$stmt->execute()) {
                $error = "Error al guardar.";
            }
            
            // Liberar recursos de la consulta (buena práctica)
            $stmt->close();
        }
        // Cerrar la consulta de verificación de duplicados
        $check->close();
    }
} else {
    // Si no se enviaron los campos requeridos en la solicitud POST
    $error = "Faltan datos del formulario.";
}

// Cerrar la conexión a la base de datos para liberar recursos
$conexion->close();

// === REDIRECCIÓN CON MENSAJE DE RESULTADO ===
// Si no se generó ningún error durante el proceso
if (!isset($error)) {
    // Redirigir a index.php con mensaje de éxito
    header("Location: index.php?mensaje=insertado");
} else {
    // Redirigir con mensaje de error (codificado para URLs seguras)
    header("Location: index.php?error=" . urlencode($error));
}

// Terminar la ejecución inmediatamente para evitar salida adicional
exit;
?>