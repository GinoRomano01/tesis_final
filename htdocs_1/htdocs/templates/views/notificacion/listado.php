<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<main class="content">
<style>
:root{--caoba:#5c2d0a;--caoba2:#7a3e14;--amb:#b8722a;--lino:#f7f0e6;--lino2:#ede4d4;--papel:#fdfaf6;--tinta:#2c1a0e;--tinta2:#4a3020;--g1:#8a7560;--borde:#d4c4aa;--borde2:#e8dcc8;--sombra:rgba(92,45,10,.08);--azul:#2563eb;}
.nl-page{font-family:'Source Sans 3',Georgia,sans-serif;color:var(--tinta);}
.nl-hd{margin-bottom:1.2rem;}
.nl-hd h1{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--caoba);margin:0;}
.nl-list{background:var(--papel);border:1.5px solid var(--borde);border-radius:10px;overflow:hidden;box-shadow:0 2px 12px var(--sombra);}
.nl-item{display:flex;gap:.85rem;align-items:flex-start;padding:.9rem 1.1rem;text-decoration:none;color:var(--tinta);border-bottom:1px solid var(--borde2);transition:background .12s;}
.nl-item:last-child{border-bottom:none;}
.nl-item:hover{background:var(--lino);color:var(--tinta);}
.nl-item--unread{background:#eef4ff;}
.nl-item--unread:hover{background:#e3edff;}
.nl-icon{width:38px;height:38px;border-radius:50%;background:var(--lino2);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--caoba);font-size:.95rem;margin-top:.1rem;}
.nl-body{display:flex;flex-direction:column;gap:.15rem;min-width:0;}
.nl-title{font-weight:700;font-size:.92rem;color:var(--tinta);}
.nl-content{font-size:.84rem;color:var(--tinta2);}
.nl-date{font-size:.76rem;color:var(--g1);margin-top:.1rem;}
.nl-vacio{text-align:center;padding:3rem 1rem;color:var(--g1);}
.nl-vacio span{font-size:2.5rem;opacity:.25;display:block;margin-bottom:.5rem;}
.nl-pag{display:flex;gap:.4rem;justify-content:center;margin-top:1.2rem;flex-wrap:wrap;}
.nl-pag a{display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 .6rem;border-radius:7px;background:var(--lino2);color:var(--caoba);text-decoration:none;font-size:.84rem;font-weight:600;border:1.5px solid var(--borde);}
.nl-pag a:hover{background:var(--borde);}
.nl-pag a.pag-active{background:var(--caoba);color:#fff;border-color:var(--caoba);}
</style>

<div class="nl-page">

  <div class="nl-hd">
    <h1> Notificaciones</h1>
  </div>

  <div class="nl-list">
    <?php if (empty($notificaciones)): ?>
      <div class="nl-vacio">
        <span></span>
        <p>No tenés notificaciones todavía.</p>
      </div>
    <?php else: ?>
      <?php foreach ($notificaciones as $n): ?>
        <a href="<?= $n['UrlDestino'] ? URL . $n['UrlDestino'] : '' ?>"
           class="nl-item <?= (int)$n['Leida'] === 0 ? 'nl-item--unread' : '' ?>">
          <span class="nl-icon">
            <i class="fas <?= htmlspecialchars($n['Icono'] ?: 'fa-bell') ?>"></i>
          </span>
          <span class="nl-body">
            <span class="nl-title"><?= htmlspecialchars($n['Titulo']) ?></span>
            <?php if (!empty($n['Contenido'])): ?>
              <span class="nl-content"><?= htmlspecialchars($n['Contenido']) ?></span>
            <?php endif; ?>
            <span class="nl-date">
              <?= (new DateTime($n['FechaCreacion']))->format('d/m/Y H:i') ?>
            </span>
          </span>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <?php if ($totalPaginas > 1): ?>
  <nav class="nl-pag">
    <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
      <a href="<?= URL ?>notificacion/listado?pagina=<?= $p ?>"
         class="<?= $p === $pagina ? 'pag-active' : '' ?>">
        <?= $p ?>
      </a>
    <?php endfor; ?>
  </nav>
  <?php endif; ?>

</div>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
<?php include INCLUDES . 'admin' . DS . 'modals_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'scripts_admin.php'; ?>