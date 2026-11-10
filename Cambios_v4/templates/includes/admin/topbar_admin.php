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
    </div>
</header>