<?php
// listar.php - devuelve HTML

// Configuración de la base de datos
$host = 'localhost';
$usuario = 'root';
$contraseña = '';
$bd = 'mi_crud';

$conexion = new mysqli($host, $usuario, $contraseña, $bd);

if ($conexion->connect_error) {
    die("<p style='color:red;'>Error de conexión: " . $conexion->connect_error . "</p>");
}

$conexion->set_charset("utf8");

$resultado = $conexion->query("SELECT id, nombre, email FROM usuarios ORDER BY id");

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        echo "<p><strong>ID:</strong> {$fila['id']} | <strong>Nombre:</strong> " . 
             htmlspecialchars($fila['nombre']) . " | <strong>Email:</strong> " . 
             htmlspecialchars($fila['email']) . "</p>\n";
    }
} else {
    echo "<p>No hay registros.</p>";
}

$conexion->close();
?>