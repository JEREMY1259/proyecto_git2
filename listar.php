<?php
// listar.php - Muestra los registros de la tabla 'usuarios' en formato HTML simple

// Configuración de la base de datos
$host = 'localhost';
$usuario = 'root';      // Cambia si usas otro usuario
$contraseña = '';       // Cambia si tu MySQL tiene contraseña
$bd = 'mi_crud';        // Nombre de tu base de datos

// Conexión
$conexion = new mysqli($host, $usuario, $contraseña, $bd);

// Verificar error de conexión
if ($conexion->connect_error) {
    echo "<p style='color:#b91c1c;'>❌ Error: No se pudo conectar a la base de datos.</p>";
    exit;
}

// Establecer codificación UTF-8
$conexion->set_charset("utf8");

// Consulta SQL
$resultado = $conexion->query("SELECT id, nombre, email FROM usuarios ORDER BY id DESC");

// Mostrar resultados
if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        echo "<p><strong>ID:</strong> " . htmlspecialchars($fila['id']) . 
             " | <strong>Nombre:</strong> " . htmlspecialchars($fila['nombre']) . 
             " | <strong>Email:</strong> " . htmlspecialchars($fila['email']) . "</p>\n";
    }
} else {
    echo "<p>📭 No hay registros aún.</p>";
}

// Cerrar conexión
$conexion->close();
?>