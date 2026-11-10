<header class="topbar">
    <div class="topbar-left">
        <button class="menu-toggle" id="menuToggle">☰</button>
        <h2><?= $title ?? 'Panel Admin' ?></h2>
    </div>

    <div class="topbar-right">
        <?php include INCLUDES . 'notificaciones_dropdown.php'; ?>

        <span class="welcome-text">
            <?= htmlspecialchars($_SESSION['nombre_usuario'] ?? 'Admin') ?>
        </span>
        <a href="<?= URL ?>cliente/perfil" class="btn-view-site">Ver perfil</a>
        <a href="<?= URL ?>" class="btn-view-site">Ver sitio</a>
    </div>
</header>