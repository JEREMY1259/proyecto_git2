<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CRUD - Minimal Dashboard</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Segoe UI', system-ui, sans-serif;
      background: #f9fafb;
      color: #1f2937;
      padding: 24px;
    }
    .container {
      max-width: 900px;
      margin: 0 auto;
    }
    header {
      text-align: center;
      margin-bottom: 32px;
    }
    h1 {
      font-weight: 600;
      font-size: 28px;
      color: #1e3a8a;
    }
    .menu {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
      margin-bottom: 32px;
    }
    .card {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      transition: transform 0.2s, box-shadow 0.2s;
      text-align: center;
      text-decoration: none;
      color: #1f2937;
    }
    .card:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .card h2 {
      font-size: 16px;
      font-weight: 600;
      margin-top: 10px;
      color: #1e40af;
    }
    .panel {
      background: white;
      border-radius: 10px;
      padding: 24px;
      margin-bottom: 24px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .panel h2 {
      font-size: 18px;
      margin-bottom: 16px;
      color: #1e3a8a;
      font-weight: 600;
    }
    input, button {
      width: 100%;
      padding: 10px 12px;
      margin: 6px 0;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      font-family: inherit;
    }
    input:focus {
      outline: none;
      border-color: #3b82f6;
    }
    button {
      background: #1e40af;
      color: white;
      border: none;
      cursor: pointer;
      font-weight: 500;
      transition: background 0.2s;
    }
    button:hover {
      background: #1e3a8a;
    }
    .delete-btn {
      background: #b91c1c;
    }
    .delete-btn:hover {
      background: #991b1b;
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>▍ Panel de Gestión CRUD</h1>
    </header>

    <div class="menu">
      <a href="#listar" class="card">📋<br><h2>Listar</h2></a>
      <a href="#insertar" class="card">➕<br><h2>Insertar</h2></a>
      <a href="#actualizar" class="card">✏️<br><h2>Actualizar</h2></a>
      <a href="#eliminar" class="card">🗑️<br><h2>Eliminar</h2></a>
    </div>

    <!-- SECCIÓN DINÁMICA: LISTAR -->
    <div id="listar" class="panel">
      <h2>📋 Listar Registros</h2>
      <?php
      // Conexión y listado directo (sin archivo externo para mantenerlo en un solo archivo si lo prefieres)
      $host = 'localhost';
      $usuario = 'root';
      $contraseña = '';
      $bd = 'mi_crud';

      $conexion = new mysqli($host, $usuario, $contraseña, $bd);

      if ($conexion->connect_error) {
          echo "<p style='color:#b91c1c;'>❌ Error: No se pudo conectar a la base de datos.</p>";
      } else {
          $conexion->set_charset("utf8");
          $resultado = $conexion->query("SELECT id, nombre, email FROM usuarios ORDER BY id DESC");

          if ($resultado && $resultado->num_rows > 0) {
              while ($fila = $resultado->fetch_assoc()) {
                  echo "<p><strong>ID:</strong> " . htmlspecialchars($fila['id']) . 
                       " | <strong>Nombre:</strong> " . htmlspecialchars($fila['nombre']) . 
                       " | <strong>Email:</strong> " . htmlspecialchars($fila['email']) . "</p>\n";
              }
          } else {
              echo "<p>📭 No hay registros aún.</p>";
          }
          $conexion->close();
      }
      ?>
    </div>

    <!-- INSERTAR -->
    <div id="insertar" class="panel">
      <h2>➕ Insertar Nuevo Registro</h2>
      <form action="insertar.php" method="post">
        <input type="text" name="nombre" placeholder="Nombre completo" required />
        <input type="email" name="email" placeholder="Correo electrónico" required />
        <button type="submit">Guardar</button>
      </form>
    </div>

    <!-- ACTUALIZAR -->
    <div id="actualizar" class="panel">
      <h2>✏️ Actualizar Registro</h2>
      <form action="actualizar.php" method="post">
        <input type="number" name="id" placeholder="ID del registro" required />
        <input type="text" name="nombre" placeholder="Nuevo nombre" />
        <input type="email" name="email" placeholder="Nuevo correo" />
        <button type="submit">Actualizar</button>
      </form>
    </div>

    <!-- ELIMINAR -->
    <div id="eliminar" class="panel">
      <h2>🗑️ Eliminar Registro</h2>
      <form action="eliminar.php" method="post">
        <input type="number" name="id" placeholder="ID a eliminar" required />
        <button type="submit" class="delete-btn">Eliminar</button>
      </form>
    </div>

  </div>
</body>
</html>