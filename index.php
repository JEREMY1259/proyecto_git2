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
      font-family: 'Orbitron', 'Courier New', monospace;
      background: #0a0a0f;
      color: #e0e0ff;
      padding: 24px;
      min-height: 100vh;
      background-image: 
        radial-gradient(circle at 10% 20%, rgba(128, 0, 128, 0.15) 0%, transparent 20%),
        radial-gradient(circle at 90% 80%, rgba(0, 200, 255, 0.1) 0%, transparent 20%);
    }

    .container {
      max-width: 900px;
      margin: 0 auto;
    }

    header {
      text-align: center;
      margin-bottom: 36px;
      text-shadow: 0 0 10px #ff00ff, 0 0 20px #ff00ff;
    }

    h1 {
      font-weight: 700;
      font-size: 28px;
      letter-spacing: 2px;
      color: #ff00ff;
      text-transform: uppercase;
    }

    .menu {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
      gap: 18px;
      margin-bottom: 36px;
    }

    .card {
      background: rgba(10, 10, 20, 0.7);
      border: 1px solid #ff00ff;
      border-radius: 8px;
      padding: 20px;
      text-align: center;
      text-decoration: none;
      color: #00ffff;
      transition: all 0.3s;
      box-shadow: 0 0 8px rgba(255, 0, 255, 0.4);
      position: relative;
      overflow: hidden;
    }

    .card::before {
      content: '';
      position: absolute;
      top: -2px;
      left: -2px;
      right: -2px;
      bottom: -2px;
      background: linear-gradient(45deg, #ff00ff, #00ffff, #ff00ff);
      z-index: -1;
      animation: borderGlow 3s linear infinite;
      opacity: 0.6;
    }

    .card:hover {
      transform: scale(1.03);
      box-shadow: 0 0 20px rgba(0, 255, 255, 0.7);
      color: white;
    }

    .card h2 {
      font-size: 14px;
      font-weight: 600;
      margin-top: 10px;
      color: #00ffff;
      text-shadow: 0 0 5px #00ffff;
    }

    .panel {
      background: rgba(15, 15, 30, 0.85);
      border: 1px solid #00ffff;
      border-radius: 8px;
      padding: 24px;
      margin-bottom: 24px;
      box-shadow: 0 0 12px rgba(0, 255, 255, 0.3);
    }

    .panel h2 {
      font-size: 18px;
      margin-bottom: 16px;
      color: #ff00ff;
      font-weight: 700;
      text-shadow: 0 0 6px #ff00ff;
    }

    input, button {
      width: 100%;
      padding: 10px 12px;
      margin: 6px 0;
      border: 1px solid #00ffff;
      border-radius: 4px;
      font-family: 'Orbitron', monospace;
      background: rgba(0, 0, 10, 0.6);
      color: #00ffff;
      outline: none;
      box-shadow: inset 0 0 5px rgba(0, 255, 255, 0.3);
    }

    input::placeholder {
      color: #6666aa;
    }

    input:focus {
      border-color: #ff00ff;
      box-shadow: inset 0 0 8px rgba(255, 0, 255, 0.6), 0 0 8px rgba(255, 0, 255, 0.4);
    }

    button {
      background: linear-gradient(to right, #ff00ff, #9900cc);
      color: white;
      font-weight: bold;
      cursor: pointer;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 14px;
      transition: all 0.2s;
    }

    button:hover {
      box-shadow: 0 0 15px #ff00ff;
      transform: translateY(-2px);
    }

    .delete-btn {
      background: linear-gradient(to right, #ff0055, #cc0044);
    }

    .delete-btn:hover {
      box-shadow: 0 0 15px #ff0055;
    }

    @keyframes borderGlow {
      0% { filter: hue-rotate(0deg); }
      100% { filter: hue-rotate(360deg); }
    }

    /* Fallback para fuentes */
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap');
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