<?php
// === CONFIGURACIÓN DE LA BASE DE DATOS ===
// Estos valores deben coincidir con tu entorno local o de producción
$host = 'localhost';        // Servidor de la base de datos (normalmente 'localhost')
$usuario = 'root';          // Usuario de MySQL (cambia en producción)
$contraseña = '';           // Contraseña del usuario (vacía por defecto en XAMPP/WAMP)
$bd = 'mi_crud';            // Nombre de la base de datos

// Establecer conexión con MySQL usando la extensión MySQLi
$conexion = new mysqli($host, $usuario, $contraseña, $bd);

// Verificar si hubo un error al conectar
if ($conexion->connect_error) {
    // Detener la ejecución y mostrar un mensaje de error claro
    die("Error de conexión: " . $conexion->connect_error);
}

// Establecer el juego de caracteres a UTF-8 para soportar acentos y caracteres especiales
$conexion->set_charset("utf8");

// === PROCESAMIENTO DEL FORMULARIO ===
// Verificar que la solicitud sea de tipo POST (envío de formulario)
if ($_POST) {
    // Obtener y sanitizar los datos del formulario
    $id = $_POST['id'] ?? null;                    // ID del registro a actualizar
    $nombre = trim($_POST['nombre'] ?? '');        // Nombre (sin espacios al inicio/final)
    $email = trim($_POST['email'] ?? '');          // Email (sin espacios al inicio/final)

    // === VALIDACIONES INICIALES ===
    if (!$id || !is_numeric($id)) {
        // El ID es obligatorio y debe ser un número
        $error = "ID inválido.";
    } elseif (empty($nombre) && empty($email)) {
        // Al menos uno de los campos (nombre o email) debe tener valor
        $error = "Debes proporcionar al menos un campo para actualizar.";
    } else {
        // === CONSTRUCCIÓN DINÁMICA DE LA CONSULTA SQL ===
        // Solo se actualizarán los campos que no estén vacíos
        $campos = [];   // Almacena las columnas a actualizar (ej: "nombre = ?")
        $params = [];   // Almacena los valores reales para los parámetros
        $types = "";    // Define los tipos de datos para bind_param ("s"=string, "i"=int)

        if (!empty($nombre)) {
            $campos[] = "nombre = ?";
            $params[] = $nombre;
            $types .= "s"; // 's' para string
        }
        if (!empty($email)) {
            $campos[] = "email = ?";
            $params[] = $email;
            $types .= "s";
        }

        // === VERIFICAR QUE EL REGISTRO EXISTA ANTES DE ACTUALIZAR ===
        $check = $conexion->prepare("SELECT id FROM usuarios WHERE id = ?");
        $check->bind_param("i", $id); // "i" porque el ID es entero
        $check->execute();
        
        // Si no hay resultados, el registro no existe
        if ($check->get_result()->num_rows === 0) {
            $error = "No se encontró un registro con ese ID.";
        } else {
            // === EJECUTAR LA ACTUALIZACIÓN ===
            // Construir la consulta: "UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?"
            $sql = "UPDATE usuarios SET " . implode(", ", $campos) . " WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            
            // Añadir el ID al final de los parámetros y su tipo
            $params[] = $id;
            $types .= "i"; // ID es entero
            
            // Vincular parámetros dinámicamente (uso de ... para "splat operator")
            $stmt->bind_param($types, ...$params);
            
            // Ejecutar y verificar si falla
            if (!$stmt->execute()) {
                $error = "Error al actualizar.";
            }
            
            // Liberar recursos de la consulta
            $stmt->close();
        }
        // Cerrar la consulta de verificación
        $check->close();
    }
}

// Cerrar la conexión a la base de datos (buena práctica)
$conexion->close();

// === REDIRECCIÓN CON MENSAJE ===
// Si no hubo errores, redirigir con mensaje de éxito
if (!isset($error)) {
    header("Location: index.php?mensaje=actualizado");
} else {
    // Si hubo error, codificarlo en la URL y redirigir
    header("Location: index.php?error=" . urlencode($error));
}

// Asegurar que el script termine aquí (evita salida adicional)
exit;
?>