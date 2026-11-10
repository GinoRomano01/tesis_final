<?php if (isset($_SESSION['cliente_id'])): ?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
            <i class="fas fa-user"></i> <?= $_SESSION['nombre_completo'] ?? 'Mi Cuenta' ?>
        </a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= URL ?>cliente/perfil">
                <i class="fas fa-user"></i> Mi Perfil
            </a></li>
            <li><a class="dropdown-item" href="<?= URL ?>cliente/pedidos">
                <i class="fas fa-shopping-bag"></i> Mis Pedidos
            </a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="<?= URL ?>cliente/logout">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a></li>
        </ul>
    </li>
<?php else: ?>

    <li class="nav-item">
        <a class="nav-link" href="<?= URL ?>cliente/login">
            <i class="fas fa-sign-in-alt"></i> Ingresar
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= URL ?>registro/cliente">
            <i class="fas fa-user-plus"></i> Registrarse
        </a>
    </li>
<?php endif; ?>