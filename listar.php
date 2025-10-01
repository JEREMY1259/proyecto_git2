<?php
// === PROPÓSITO DEL ARCHIVO ===
// Este script se incluye en index.php y genera HTML dinámico
// para mostrar todos los registros de la tabla 'usuarios'.

// === CONFIGURACIÓN DE LA BASE DE DATOS ===
// Valores típicos para entornos locales (XAMPP, WAMP, etc.)
$host = 'localhost';        // Servidor de la base de datos
$usuario = 'root';          // Usuario de MySQL (cambia en producción)
$contraseña = '';           // Contraseña del usuario (vacía por defecto en entornos locales)
$bd = 'mi_crud';            // Nombre de la base de datos

// Establecer conexión con MySQL usando MySQLi (estilo orientado a objetos)
$conexion = new mysqli($host, $usuario, $contraseña, $bd);

// Verificar si la conexión falló y mostrar un mensaje de error en HTML
if ($conexion->connect_error) {
    die("<p style='color:red;'>Error de conexión: " . $conexion->connect_error . "</p>");
}

// Establecer el juego de caracteres a UTF-8 para evitar problemas con acentos o caracteres especiales
$conexion->set_charset("utf8");

// === CONSULTA A LA BASE DE DATOS ===
// Seleccionar todos los registros de la tabla 'usuarios', ordenados por ID ascendente
$resultado = $conexion->query("SELECT id, nombre, email FROM usuarios ORDER BY id");

// === GENERAR SALIDA HTML ===
if ($resultado && $resultado->num_rows > 0) {
    // Si hay registros, recorrer cada fila y mostrarla en formato legible
    while ($fila = $resultado->fetch_assoc()) {
        // Usar htmlspecialchars() para prevenir XSS (inyección de código HTML/JS)
        echo "<p><strong>ID:</strong> {$fila['id']} | <strong>Nombre:</strong> " . 
             htmlspecialchars($fila['nombre']) . " | <strong>Email:</strong> " . 
             htmlspecialchars($fila['email']) . "</p>\n";
    }
} else {
    // Si no hay registros, mostrar un mensaje amigable
    echo "<p>No hay registros.</p>";
}

// Cerrar la conexión a la base de datos (libera recursos del servidor)
$conexion->close();
?>