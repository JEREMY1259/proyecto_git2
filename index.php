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
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: linear-gradient(135deg, #0f172a, #1e293b);
    color: #f1f5f9;
    padding: 24px;
    min-height: 100vh;
  }

  .container {
    max-width: 920px;
    margin: 0 auto;
  }

  header {
    text-align: center;
    margin-bottom: 36px;
  }

  h1 {
    font-weight: 700;
    font-size: 32px;
    background: linear-gradient(90deg, #60a5fa, #93c5fd);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    letter-spacing: -0.5px;
  }

  .menu {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 20px;
    margin-bottom: 36px;
  }

  .card {
    background: rgba(30, 41, 59, 0.6);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 24px;
    text-align: center;
    text-decoration: none;
    color: #e2e8f0;
    border: 1px solid rgba(148, 163, 184, 0.2);
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  .card:hover {
    transform: translateY(-4px);
    background: rgba(30, 41, 59, 0.8);
    border-color: rgba(96, 165, 250, 0.5);
    color: #ffffff;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
  }

  .card h2 {
    font-size: 16px;
    font-weight: 600;
    margin-top: 12px;
    color: #cbd5e1;
  }

  .panel {
    background: rgba(15, 23, 42, 0.7);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 16px;
    padding: 28px;
    margin-bottom: 28px;
    border: 1px solid rgba(100, 116, 139, 0.3);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
  }

  .panel h2 {
    font-size: 20px;
    margin-bottom: 20px;
    color: #93c5fd;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  input, button {
    width: 100%;
    padding: 12px 16px;
    margin: 8px 0;
    border: none;
    border-radius: 10px;
    font-family: inherit;
    font-size: 15px;
    background: rgba(30, 41, 59, 0.8);
    color: #f1f5f9;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3);
  }

  input::placeholder {
    color: #94a3b8;
  }

  input:focus {
    outline: 2px solid #60a5fa;
    background: rgba(30, 41, 59, 1);
  }

  button {
    background: linear-gradient(120deg, #3b82f6, #1d4ed8);
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
  }

  button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    background: linear-gradient(120deg, #2563eb, #1e40af);
  }

  .delete-btn {
    background: linear-gradient(120deg, #ef4444, #b91c1c);
    box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
  }

  .delete-btn:hover {
    background: linear-gradient(120deg, #dc2626, #991b1b);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
  }

  /* Mensajes de éxito/error (si los agregas después) */
  .mensaje {
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 20px;
    text-align: center;
    font-weight: 500;
  }
  .exito { background: rgba(16, 185, 129, 0.2); color: #a7f3d0; border: 1px solid #059669; }
  .error { background: rgba(220, 38, 38, 0.2); color: #fca5a5; border: 1px solid #b91c1c; }
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