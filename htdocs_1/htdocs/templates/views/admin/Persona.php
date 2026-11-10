<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/controllers/personaController.php';
$controller = new PersonaController();

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['accion'] ?? '') {
        case 'agregar':
            $controller->guardarNuevo($_POST);
            $mensaje = "Usuario agregado ✅";
            break;
        case 'editar':
            $controller->actualizar($_POST);
            $mensaje = "Usuario actualizado ✅";
            break;
        case 'eliminar':
            $controller->eliminar($_POST['Id'] ?? 0);
            $mensaje = "Usuario eliminado ✅";
            break;
    }
}

$usuarios = $controller->listarUsuarios();
$tiposUsuario = $controller->model->obtenerTiposDeUsuario();
$tiposRol = $controller->model->obtenerTiposDeRol();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"rel="stylesheet"integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"crossorigin="anonymous"/>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Carpintería San Plácido - Panel</title>
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <meta name="theme-color" content="#162232">
  <link rel="stylesheet" href="../assets/css/style.css"/>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="sidebar-top">
        <div class="brand-block">
          <div class="brand-top">
            <img src="assets/img/Logo_1.png" alt="Logo San Plácido" class="logo-sp">
            <div class="brand-name">San Plácido</div>
          </div>
        </div>

        <nav class="nav-block">
          <ul class="menu">

            <li class="nav-item">
              <a href="#" id="linkEntregas">🚚 Entregas</a>
            </li>

            <li class="nav-group">
              <button class="nav-toggle" aria-expanded="false">
                <span>📊 Estadísticas</span><span class="nav-chevron"></span>
              </button>
              <ul class="submenu">
                <li><a href="pages/estadisticas.html?tab=clientes">👤 Cliente</a></li>
                <li><a href="pages/estadisticas.html?tab=stock">📦 Stock</a></li>
                <li><a href="pages/estadisticas.html?tab=ventas">💰 Ventas</a></li>
                <li><a href="pages/estadisticas.html?tab=visitas">📈 Visitas</a></li>
              </ul>
            </li>

            <li class="nav-group">
              <button class="nav-toggle" aria-expanded="false">
                <span>🪚 Producción</span><span class="nav-chevron"></span>
              </button>
              <ul class="submenu">
                <li><a href="#">🪵 Encargos</a></li>
                <li><a href="pages/corte.html">🧰 Muebles</a></li>
              </ul>
            </li>

            <li class="nav-group">
              <button class="nav-toggle" aria-expanded="false">
                <span>📦 Productos</span><span class="nav-chevron"></span>
              </button>
              <ul class="submenu">
                <li><a href="pages/productos.html">🗂️ Lista de productos</a></li>
                <li><a href="pages/pedidos.html">🧾 Pedidos</a></li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="pages/stock.html">📦 Stock</a>
            </li>

            <li class="nav-group">
              <button class="nav-toggle" aria-expanded="false">
                <span>💳 Transacciones</span><span class="nav-chevron"></span>
              </button>
              <ul class="submenu">
                <li><a href="#">💵 Caja</a></li>
                <li><a href="#">💰 Cobros</a></li>
                <li><a href="#">🔄 Movimientos</a></li>
                <li><a href="#">📄 Remitos</a></li>
              </ul>
            </li>

            <li class="nav-item">
              <a href="../../app/vistas/Persona.php">👥 Usuarios</a>
            </li>

            <li class="nav-group">
              <button class="nav-toggle" aria-expanded="false">
                <span>🧾 Ventas</span><span class="nav-chevron"></span>
              </button>
              <ul class="submenu">
                <li><a href="#">📃 Facturas</a></li>
                <li><a href="#">📋 Listado de ventas</a></li>
                <li><a href="#">⚠️ Reclamos</a></li>
              </ul>
            </li>

          </ul>
        </nav>
      </div>

      <div class="sidebar-bottom">
        <div class="sidebar-user-row">
          <span class="user-label">Usuario</span>
          <a class="logout-btn" href="../../app/controladores/logout.php">Salir</a>
        </div>
        <div style="margin-top:8px;">© 2025 San Plácido</div>
      </div>
    </aside>

    <div class="main-area">
      <header class="topbar">
        <div class="topbar-left">
         <div class="env-loc">
            <a href="https://www.google.com/maps/search/?api=1&query=Z%C3%A1rate+1920,+C%C3%B3rdoba,+Argentina"
              target="_blank" rel="noopener noreferrer">
              📍 Córdoba • Barrio Zumarán • Calle Zárate 1920
            </a>
          </div>
        </div>
        <div class="topbar-right">
          <button id="menuToggle" class="icon-btn" title="Menú">☰</button>
          <button id="themeToggle" class="theme-toggle-btn" title="Cambiar tema">☀️</button>
          <button id="notifBtn" class="icon-btn notif-bell" title="Notificaciones">
            🔔<span class="notif-badge" id="notifCount">3</span>
          </button>
          <div class="notif-panel" id="notifPanel">
            <div class="notif-header">Notificaciones</div>
            <ul class="notif-list">
              <li><strong>Pedido #1023</strong> listo para corte</li>
              <li><strong>Stock</strong> Placa MDF 18mm bajo</li>
              <li><strong>Cliente</strong> García confirmó presupuesto</li>
            </ul>
            <div class="notif-footer"><a href="#">Ver todas</a></div>
          </div>
        </div>
      </header>



    <div class="container mt-5">
        <h2>Gestión de Usuarios</h2>

        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>

        <button class="btn btn-success mb-3" data-bs-toggle="collapse" data-bs-target="#formAgregar">➕ Agregar Usuario</button>

        <div id="formAgregar" class="collapse">
            <form method="POST">
                <input type="hidden" name="accion" value="agregar">
                <div class="row mb-2">
                    <div class="col-md-3">
                        <input type="text" name="NombredeUsuario" class="form-control" placeholder="Usuario" required>
                    </div>
                    <div class="col-md-3">
                        <input type="password" name="Contraseña" class="form-control" placeholder="Contraseña" required>
                    </div>
                    <div class="col-md-3">
                        <input type="email" name="CorreoElectronico" class="form-control" placeholder="Correo" required>
                    </div>
                    <div class="col-md-3">
                        <select name="IdTipodeUsuario" class="form-select" required>
                            <option value="">Tipo de Usuario</option>
                            <?php foreach ($tiposUsuario as $t): ?>
                                <option value="<?= $t['Id'] ?>"><?= $t['Nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mt-2">
                        <select name="IdTipodeRol" class="form-select" required>
                            <option value="">Tipo de Rol</option>
                            <?php foreach ($tiposRol as $r): ?>
                                <option value="<?= $r['Id'] ?>"><?= $r['Nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 mt-2">
                        <button class="btn btn-primary w-100">Guardar</button>
                    </div>
                </div>
            </form>
        </div>

        <table class="table table-striped table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Tipo Usuario</th>
                    <th>Tipo Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['Id'] ?></td>
                    <td><?= htmlspecialchars($u['NombredeUsuario']) ?></td>
                    <td><?= htmlspecialchars($u['CorreoElectronico']) ?></td>
                    <td><?= htmlspecialchars($u['TipoUsuario']) ?></td>
                    <td><?= htmlspecialchars($u['TipoRol']) ?></td>
                    <td>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="accion" value="editar">
                            <input type="hidden" name="Id" value="<?= $u['Id'] ?>">
                            <button class="btn btn-warning btn-sm">Editar</button>
                        </form>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="Id" value="<?= $u['Id'] ?>">
                            <button class="btn btn-danger btn-sm">Borrar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <footer class="footer">
        <small>San Plácido - Córdoba, Barrio Zumarán, Calle Zárate | Sistema interno 2025</small>
    </footer>
    </div>
  </div>

  <div id="toast" class="toast"></div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"crossorigin="anonymous"></script>
  <script src="../assets/js/app.js"></script>
</body>
</html>