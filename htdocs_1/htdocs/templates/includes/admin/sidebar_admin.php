<aside class="sidebar">
  <div class="sidebar-top">

    <div class="brand-block">
      <div class="brand-top">
        <img src="<?= URL ?>templates/assets/logo/Logo_1.png"
             alt="Logo San Placido" class="logo-sp">
        <div class="brand-name">San Placido</div>
      </div>
    </div>

    <nav class="nav-block">
      <ul class="menu">

        <li class="nav-item <?= (CONTROLLER === 'admin') ? 'active' : '' ?>">
          <a href="<?= URL ?>admin/LobbyAdmin">Resumen</a>
        </li>

        <?php if (puedeVerEstadisticas()): ?>
        <li class="nav-group <?= (CONTROLLER === 'estadisticas') ? 'open' : '' ?>">
          <button class="nav-toggle"
                  aria-expanded="<?= (CONTROLLER === 'estadisticas') ? 'true' : 'false' ?>">
            <span>Estadisticas</span>
            <span class="nav-chevron"></span>
          </button>
          <ul class="submenu">
            <li>
              <a href="<?= URL ?>estadisticas"
                 class="<?= (CONTROLLER === 'estadisticas' && METHOD === 'index') ? 'active' : '' ?>">
                Dashboard
              </a>
            </li>
            <li>
              <a href="<?= URL ?>estadisticas/ventas"
                 class="<?= (CONTROLLER === 'estadisticas' && METHOD === 'ventas') ? 'active' : '' ?>">
                Ventas
              </a>
            </li>
            <li>
              <a href="<?= URL ?>estadisticas/visitas"
                 class="<?= (CONTROLLER === 'estadisticas' && METHOD === 'visitas') ? 'active' : '' ?>">
                Visitas
              </a>
            </li>
            <li>
              <a href="<?= URL ?>estadisticas/busquedas"
                 class="<?= (CONTROLLER === 'estadisticas' && METHOD === 'busquedas') ? 'active' : '' ?>">
                Busquedas
              </a>
            </li>
            <li>
                <a href="<?= URL ?>estadisticas/stock"
                   class="<?= (CONTROLLER === 'estadisticas' && METHOD === 'stock') ? 'active' : '' ?>">
                  Estadísticas Stock
                </a>
            </li>
            <li>
              <a href="<?= URL ?>estadisticas/resenas"
                 class="<?= (CONTROLLER === 'estadisticas' && METHOD === 'resenas') ? 'active' : '' ?>">
                Reseñas
              </a>
            </li>
          </ul>
        </li>
        <?php endif; ?>

        <?php if (puedeVerEntregas()): ?>
        <li class="nav-group <?= (CONTROLLER === 'entrega') ? 'open' : '' ?>">
          <button class="nav-toggle"
                  aria-expanded="<?= (CONTROLLER === 'entrega') ? 'true' : 'false' ?>">
            <span>Entregas</span>
            <span class="nav-chevron"></span>
          </button>
          <ul class="submenu">
            <li>
              <a href="<?= URL ?>entrega"
                 class="<?= (CONTROLLER === 'entrega' && empty($_GET['estado'])) ? 'active' : '' ?>">
                Todas
              </a>
            </li>
            <li>
              <a href="<?= URL ?>entrega?estado=1"
                 class="<?= (CONTROLLER === 'entrega' && ($_GET['estado'] ?? '') == '1') ? 'active' : '' ?>">
                Pendientes
              </a>
            </li>
            <li>
              <a href="<?= URL ?>entrega?estado=2"
                 class="<?= (CONTROLLER === 'entrega' && ($_GET['estado'] ?? '') == '2') ? 'active' : '' ?>">
                En curso
              </a>
            </li>
            <li>
              <a href="<?= URL ?>entrega?estado=3"
                 class="<?= (CONTROLLER === 'entrega' && ($_GET['estado'] ?? '') == '3') ? 'active' : '' ?>">
                Finalizadas
              </a>
            </li>
          </ul>
        </li>
        <?php endif; ?>

        <?php if (puedeVerProduccion()): ?>
        <li class="nav-group <?= (CONTROLLER === 'produccion' || CONTROLLER === 'pedido') ? 'open' : '' ?>">
          <button class="nav-toggle"
                  aria-expanded="<?= (CONTROLLER === 'produccion' || CONTROLLER === 'pedido') ? 'true' : 'false' ?>">
            <span>Produccion</span>
            <span class="nav-chevron"></span>
          </button>
          <ul class="submenu">
            <li>
              <a href="<?= URL ?>pedido"
                 class="<?= (CONTROLLER === 'pedido') ? 'active' : '' ?>">
                Pedidos
              </a>
            </li>
          </ul>
        </li>
        <?php endif; ?>

        <?php if (puedeVerVentas()): ?>
        <li class="nav-group <?= (CONTROLLER === 'producto') ? 'open' : '' ?>">
          <button class="nav-toggle"
                  aria-expanded="<?= (CONTROLLER === 'producto') ? 'true' : 'false' ?>">
            <span>Productos</span>
            <span class="nav-chevron"></span>
          </button>
          <ul class="submenu">
            <li>
              <a href="<?= URL ?>producto"
                 class="<?= (CONTROLLER === 'producto' && METHOD === 'index') ? 'active' : '' ?>">
                Lista de productos
              </a>
            </li>
            <li>
              <a href="<?= URL ?>producto/crear"
                 class="<?= (CONTROLLER === 'producto' && METHOD === 'crear') ? 'active' : '' ?>">
                Agregar producto
              </a>
            </li>
          </ul>
        </li>
        <?php endif; ?>

        <?php if (puedeVerStock()): ?>
        <li class="nav-group <?= (CONTROLLER === 'stock') ? 'open' : '' ?>">
          <button class="nav-toggle"
                  aria-expanded="<?= (CONTROLLER === 'stock') ? 'true' : 'false' ?>">
            <span>Stock</span>
            <span class="nav-chevron"></span>
          </button>
          <ul class="submenu">
            <li>
              <a href="<?= URL ?>stock"
                 class="<?= (CONTROLLER === 'stock' && METHOD === 'index') ? 'active' : '' ?>">
                Stock general
              </a>
            </li>
            <li>
              <a href="<?= URL ?>stock/maderas"
                 class="<?= (CONTROLLER === 'stock' && METHOD === 'maderas') ? 'active' : '' ?>">
                Maderas
              </a>
            </li>
            <li>
              <a href="<?= URL ?>stock/insumos"
                 class="<?= (CONTROLLER === 'stock' && METHOD === 'insumos') ? 'active' : '' ?>">
                Insumos de carpinteria
              </a>
            </li>
          </ul>
        </li>
        <?php endif; ?>

        <?php if (puedeVerVentas()): ?>
        <li class="nav-group <?= (CONTROLLER === 'venta') ? 'open' : '' ?>">
          <button class="nav-toggle"
                  aria-expanded="<?= (CONTROLLER === 'venta') ? 'true' : 'false' ?>">
            <span>Ventas</span>
            <span class="nav-chevron"></span>
          </button>
          <ul class="submenu">
            <li>
              <a href="<?= URL ?>venta"
                 class="<?= (CONTROLLER === 'venta' && empty($_GET['estado'])) ? 'active' : '' ?>">
                Todas las ventas
              </a>
            </li>

            <li>
              <a href="<?= URL ?>venta?estado=1"
                 class="<?= (CONTROLLER === 'venta' && ($_GET['estado'] ?? '') == '1') ? 'active' : '' ?>">
                Pendientes
              </a>
            </li>

            <li>
              <a href="<?= URL ?>venta?estado=2"
                 class="<?= (CONTROLLER === 'venta' && ($_GET['estado'] ?? '') == '2') ? 'active' : '' ?>">
                Aprobadas
              </a>
            </li>
            <li>
              <a href="<?= URL ?>venta?estado=3"
                 class="<?= (CONTROLLER === 'venta' && ($_GET['estado'] ?? '') == '3') ? 'active' : '' ?>">
                Rechazadas
              </a>
            </li>
          </ul>
        </li>
        <?php endif; ?>

        <?php if (puedeVerUsuarios()): ?>
        <li class="nav-item <?= (CONTROLLER === 'usuarioadmin') ? 'active' : '' ?>">
          <a href="<?= URL ?>usuarioadmin">Usuarios</a>
        </li>
        <?php endif; ?>

      </ul>
    </nav>
  </div>

  <div class="sidebar-bottom">
    <div class="sidebar-user-row">
      <span class="user-label">
        <?= htmlspecialchars($_SESSION['nombre_completo'] ?? 'Usuario') ?>
      </span>
      <span style="font-size:.72rem;color:var(--g1);display:block;margin-top:2px;">
        <?php
        $roles = [1=>'Gerente',2=>'Cliente',3=>'Repartidor',4=>'Vendedor',5=>'Carpintero'];
        echo htmlspecialchars($roles[getRol()] ?? 'Empleado');
        ?>
      </span>
      <a class="logout-btn" href="<?= URL ?>login/logout">Salir</a>
    </div>
    <div style="margin-top:6px;">
      <?= date('Y') ?> San Placido
    </div>
  </div>
</aside>