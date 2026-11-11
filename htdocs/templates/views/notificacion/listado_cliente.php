<?php include INCLUDES . 'header_cliente.php'; ?>

<?php // Todas las notificaciones del cliente?>
<div class="nlc-page">
  <div class="nlc-inner">

    <div class="nlc-hd">
      <h1>Mis notificaciones</h1>
      <a href="<?= URL ?>pedidocliente" class="nlc-link">
        <i class="fas fa-shopping-bag" aria-hidden="true"></i> Ver mis pedidos
      </a>
    </div>

    <div class="nlc-list">
      <?php if (empty($notificaciones)): ?>
        <div class="nlc-vacio">
          <i class="fas fa-bell" aria-hidden="true"></i>
          <p>No tenés notificaciones todavía.</p>
          <p class="nlc-vacio-sub">Acá te vamos a avisar cuando cambie el estado de tus pedidos.</p>
        </div>
      <?php else: ?>
        <?php foreach ($notificaciones as $n): ?>
          <?php $icono = preg_match('/^fa-[a-z0-9-]+$/', (string)$n['Icono']) ? $n['Icono'] : 'fa-bell'; ?>
          <a href="<?= $n['UrlDestino'] ? URL . htmlspecialchars($n['UrlDestino']) : '#' ?>"
             class="nlc-item <?= (int)$n['Leida'] === 0 ? 'nlc-item--unread' : '' ?>">
            <span class="nlc-icon"><i class="fas <?= $icono ?>" aria-hidden="true"></i></span>
            <span class="nlc-body">
              <span class="nlc-title"><?= htmlspecialchars($n['Titulo']) ?></span>
              <?php if (!empty($n['Contenido'])): ?>
                <span class="nlc-content"><?= htmlspecialchars($n['Contenido']) ?></span>
              <?php endif; ?>
              <span class="nlc-date">
                <?= (new DateTime($n['FechaCreacion']))->format('d/m/Y H:i') ?> · <?= timeAgo($n['FechaCreacion']) ?>
              </span>
            </span>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <?php if ($totalPaginas > 1): ?>
    <nav class="nlc-pag" aria-label="Páginas">
      <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
        <a href="<?= URL ?>notificacion/listado?ctx=cliente&pagina=<?= $p ?>"
           class="<?= $p === $pagina ? 'pag-active' : '' ?>"
           <?= $p === $pagina ? 'aria-current="page"' : '' ?>>
          <?= $p ?>
        </a>
      <?php endfor; ?>
    </nav>
    <?php endif; ?>

  </div>
</div>

<?php include INCLUDES . 'footer_cliente.php'; ?>
