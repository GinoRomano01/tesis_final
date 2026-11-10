<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'San Plácido' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= URL ?>templates/assets/css/tokens.css">
    <link rel="stylesheet" href="<?= URL ?>templates/assets/css/publico.css">
    <link rel="stylesheet" href="<?= URL ?>templates/assets/css/notificaciones.css">

    
</head>
<body>

<header class="hdr-main">
    <div class="hdr-main-inner">

        <a class="sp-logo" href="<?= URL ?>">
            <span>SAN</span> PLÁCIDO
        </a>

        <div class="hdr-actions">

            <?php if (isset($_SESSION['cliente_id']) || isset($_SESSION['usuario_id'])): ?>

                <?php include INCLUDES . 'notificaciones_dropdown.php'; ?>

                <?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] != 2): ?>
                <a href="<?= URL ?>admin" class="btn-admin" title="Panel de administrador">
                    <i class="fas fa-tools"></i>
                    <span>Admin</span>
                </a>
                <?php endif; ?>

                <button onclick="openCarritoModal()" class="btn-carrito" title="Mi carrito">
                    <i class="fas fa-shopping-cart"></i>
                    <?php
                    $cantCarrito = $_SESSION['carrito_items'] ?? 0;
                    if ($cantCarrito > 0):
                    ?>
                        <span class="badge-cart"><?= $cantCarrito ?></span>
                    <?php endif; ?>
                </button>

                <div class="usr-dropdown">
                    <button class="usr-btn" id="usrBtn" onclick="toggleUsrMenu()" aria-haspopup="true">
                        <span class="usr-avatar">
                            <?= strtoupper(mb_substr($_SESSION['nombre_completo'] ?? 'U', 0, 1)) ?>
                        </span>
                        <span class="usr-name">
                            <?= htmlspecialchars($_SESSION['nombre_completo'] ?? 'Mi Cuenta') ?>
                        </span>
                        <i class="fas fa-chevron-down usr-chevron"></i>
                    </button>

                    <div class="usr-menu" id="usrMenu">
                        <div class="usr-menu-head">
                            <div class="um-label">Hola,</div>
                            <div class="um-nombre"><?= htmlspecialchars($_SESSION['nombre_completo'] ?? 'Mi Cuenta') ?></div>
                        </div>
                        <a href="<?= URL ?>cliente/perfil">
                            <i class="fas fa-user"></i> Mi Perfil
                        </a>
                        <a href="<?= URL ?>pedidocliente">
                            <i class="fas fa-shopping-bag"></i> Mis Pedidos
                        </a>
                        <div class="um-sep"></div>
                        <a href="<?= URL ?>login/logout" class="um-logout">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                    </div>
                </div>

            <?php else: ?>

                <a href="<?= URL ?>login" class="btn-ingresar">
                    <i class="fas fa-sign-in-alt"></i> Ingresar
                </a>

            <?php endif; ?>
        </div>
    </div>
</header>

<nav class="hdr-sec" aria-label="Navegación secundaria">
    <div class="hdr-sec-inner">

        <ul class="hdr-sec-nav">
            <li>
                <a href="<?= URL ?>catalogo">Catálogo</a>
            </li>
            <li>
                <a href="<?= URL ?>infocliente/nosotros"
                class="<?= (CONTROLLER === 'infocliente' && METHOD === 'nosotros') ? 'sec-active' : '' ?>">
                    Quiénes Somos
                </a>
            </li>
            <li>
                <a href="<?= URL ?>infocliente/ubicacion"
                class="<?= (CONTROLLER === 'infocliente' && METHOD === 'ubicacion') ? 'sec-active' : '' ?>">
                    Dónde Estamos
                </a>
            </li>
        </ul>

        <div class="hdr-sec-right">
            <a href="<?= URL ?>configurador">
                <i class="fas fa-couch"></i> Creá tu mueble
            </a>
        </div>

    </div>
</nav>

<div class="toast-wrap">
    <?= Toast::flash() ?>
</div>

<script>
function toggleUsrMenu() {
    const btn  = document.getElementById('usrBtn');
    const menu = document.getElementById('usrMenu');
    btn.classList.toggle('active');
    menu.classList.toggle('show');
}

document.addEventListener('click', function(e) {
    const wrap = document.querySelector('.usr-dropdown');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('usrBtn')?.classList.remove('active');
        document.getElementById('usrMenu')?.classList.remove('show');
    }
});

function toggleSecDrop(id) {
    const li = document.getElementById(id);
    const wasOpen = li.classList.contains('open');
    document.querySelectorAll('.hdr-sec-nav li').forEach(l => l.classList.remove('open'));
    if (!wasOpen) li.classList.add('open');
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.hdr-sec-nav li')) {
        document.querySelectorAll('.hdr-sec-nav li').forEach(l => l.classList.remove('open'));
    }
});
</script>

<script>const URL_BASE = "<?= URL ?>";</script>
<script src="<?= URL ?>templates/assets/js/notificaciones.js"></script>

<?php include INCLUDES . 'modal_carrito.php'; ?>