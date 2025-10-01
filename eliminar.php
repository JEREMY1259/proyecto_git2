<?php
// === CONFIGURACIÓN DE LA BASE DE DATOS ===
// Valores típicos para entornos locales como XAMPP, WAMP o MAMP
$host = 'localhost';        // Servidor de la base de datos
$usuario = 'root';          // Usuario por defecto en entornos locales
$contraseña = '';           // Contraseña vacía por defecto (cámbiala en producción)
$bd = 'mi_crud';            // Nombre de la base de datos a usar

// Crear una nueva conexión a MySQL usando la extensión MySQLi (orientada a objetos)
$conexion = new mysqli($host, $usuario, $contraseña, $bd);

// Verificar si la conexión falló y detener la ejecución si es así
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Establecer el juego de caracteres a UTF-8 para evitar problemas con acentos o caracteres especiales
$conexion->set_charset("utf8");

// === PROCESAMIENTO DE LA SOLICITUD DE ELIMINACIÓN ===
// Solo procesar si el método es POST y se envió el campo 'id'
if ($_POST && isset($_POST['id'])) {
    $id = $_POST['id']; // Obtener el ID del formulario (sin sanitizar aún)

    // === VALIDACIÓN DEL ID ===
    // El ID debe ser un número y mayor que cero (IDs válidos en MySQL son positivos)
    if (!is_numeric($id) || $id <= 0) {
        $error = "ID inválido.";
    } else {
        // === VERIFICAR QUE EL REGISTRO EXISTA ANTES DE ELIMINARLO ===
        // Esto evita intentar borrar un registro inexistente y mejora la experiencia de usuario
        $check = $conexion->prepare("SELECT id FROM usuarios WHERE id = ?");
        $check->bind_param("i", $id); // "i" indica que el parámetro es un entero
        $check->execute();
        
        // Si no se encuentra ningún registro con ese ID
        if ($check->get_result()->num_rows === 0) {
            $error = "Registro no encontrado.";
        } else {
            // === EJECUTAR LA ELIMINACIÓN ===
            // Usar una sentencia preparada para evitar inyección SQL
            $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $id); // Vincular el ID como entero
            
            // Ejecutar la consulta; si falla, capturar el error
            if (!$stmt->execute()) {
                $error = "Error al eliminar.";
            }
            
            // Liberar los recursos de la consulta (buena práctica)
            $stmt->close();
        }
        // Cerrar la consulta de verificación
        $check->close();
    }
} else {
    // Si no se envió el ID en la solicitud POST
    $error = "ID no proporcionado.";
}

// Cerrar la conexión a la base de datos (libera recursos del servidor)
$conexion->close();

// === REDIRECCIÓN CON MENSAJE DE RESULTADO ===
// Si no hubo errores durante el proceso, redirigir con mensaje de éxito
if (!isset($error)) {
    header("Location: index.php?mensaje=eliminado");
} else {
    // Si hubo un error, codificarlo en la URL para mostrarlo en index.php
    header("Location: index.php?error=" . urlencode($error));
}

// Terminar la ejecución del script inmediatamente para evitar salida adicional
exit;
?>