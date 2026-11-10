<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<main class="content">
<style>
:root{--caoba:#5c2d0a;--caoba2:#7a3e14;--amb:#b8722a;--lino:#f7f0e6;--lino2:#ede4d4;--papel:#fdfaf6;--tinta:#2c1a0e;--tinta2:#4a3020;--g1:#8a7560;--borde:#d4c4aa;--borde2:#e8dcc8;--sombra:rgba(92,45,10,.08);--rojo:#c0392b;--verde:#2e7d32;}
*{box-sizing:border-box;}
.sk-page{font-family:'Source Sans 3',Georgia,sans-serif;color:var(--tinta);}
.sk-hd{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1.2rem;}
.sk-hd h1{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--caoba);margin:0;}
.sk-hd-sub{font-size:.82rem;color:var(--g1);margin-top:.15rem;}
.btn-sk{display:inline-flex;align-items:center;gap:.4rem;padding:.48rem 1rem;border-radius:7px;font-size:.86rem;font-weight:600;font-family:'Source Sans 3',sans-serif;text-decoration:none;border:none;cursor:pointer;transition:background .15s,transform .12s;}
.btn-sk:hover{transform:translateY(-1px);}
.btn-sk-primary{background:var(--caoba);color:#fff;}
.btn-sk-primary:hover{background:var(--caoba2);color:#fff;}
.btn-sk-secondary{background:var(--lino2);color:var(--caoba);border:1.5px solid var(--borde);}
.btn-sk-secondary:hover{background:var(--borde);}
.btn-sk-danger{background:#fdf0f0;color:var(--rojo);border:1.5px solid #f5c6c6;}
.btn-sk-danger:hover{background:#fad7d7;}
.btn-sk-sm{padding:.28rem .6rem;font-size:.78rem;}
.sk-search{display:flex;gap:.5rem;margin-bottom:1.2rem;flex-wrap:wrap;}
.sk-search input{flex:1;min-width:200px;background:var(--papel);border:1.5px solid var(--borde);border-radius:7px;padding:.46rem 1rem;font-family:'Source Sans 3',sans-serif;font-size:.9rem;color:var(--tinta);}
.sk-search input:focus{outline:none;border-color:var(--amb);}
.sk-table-wrap{background:var(--papel);border:1.5px solid var(--borde);border-radius:10px;overflow:hidden;box-shadow:0 2px 12px var(--sombra);}
.sk-table{width:100%;border-collapse:collapse;}
.sk-table thead tr{background:linear-gradient(to right,var(--caoba),var(--caoba2));}
.sk-table thead th{padding:.72rem 1rem;text-align:left;font-size:.74rem;font-weight:700;letter-spacing:.06em;color:rgba(255,255,255,.92);text-transform:uppercase;border-right:1px solid rgba(255,255,255,.12);}
.sk-table thead th:last-child{border-right:none;}
.sk-table tbody tr{border-bottom:1px solid var(--borde2);transition:background .12s;}
.sk-table tbody tr:hover{background:var(--lino);}
.sk-table tbody td{padding:.68rem 1rem;font-size:.87rem;color:var(--tinta2);border-right:1px solid var(--borde2);vertical-align:middle;}
.sk-table tbody td:last-child{border-right:none;}
.td-main{font-weight:700;color:var(--caoba);}
.td-num{font-family:'Playfair Display',serif;font-size:.95rem;}
.td-acc{display:flex;gap:.3rem;}
.badge-prod{display:inline-block;padding:2px 10px;border-radius:20px;font-size:.78rem;font-weight:700;background:#e3f2fd;color:#1565c0;border:1px solid #90caf9;}
.badge-prod.cero{background:var(--lino2);color:var(--g1);border-color:var(--borde);}
.sk-vacio{text-align:center;padding:3rem 1rem;color:var(--g1);}
.sk-vacio span{font-size:2.5rem;opacity:.25;display:block;margin-bottom:.5rem;}
.modal-bg{display:none;position:fixed;inset:0;background:rgba(44,26,14,.45);z-index:1000;align-items:center;justify-content:center;padding:1rem;}
.modal-bg.open{display:flex;}
.modal{background:var(--papel);border-radius:12px;width:100%;max-width:440px;box-shadow:0 8px 40px rgba(0,0,0,.25);overflow:hidden;animation:modalIn .15s ease;}
@keyframes modalIn{from{opacity:0;transform:translateY(-12px)}to{opacity:1;transform:translateY(0)}}
.modal-hd{background:linear-gradient(to right,var(--caoba),var(--caoba2));padding:1rem 1.2rem;display:flex;align-items:center;justify-content:space-between;}
.modal-hd.hd-baja{background:linear-gradient(to right,var(--rojo),#d9564a);}
.modal-hd h3{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:#fff;margin:0;}
.modal-close{background:none;border:none;color:rgba(255,255,255,.7);font-size:1.3rem;cursor:pointer;line-height:1;padding:0;}
.modal-close:hover{color:#fff;}
.modal-body{padding:1.2rem;font-size:.92rem;line-height:1.6;}
.fld{margin-bottom:1rem;}
.fld label{display:block;margin-bottom:.3rem;font-size:.78rem;font-weight:700;color:var(--tinta2);text-transform:uppercase;letter-spacing:.03em;}
.fld input{width:100%;background:var(--lino);border:1.5px solid var(--borde);border-radius:7px;padding:.5rem .85rem;font-family:'Source Sans 3',sans-serif;font-size:.9rem;color:var(--tinta);}
.fld input:focus{outline:none;border-color:var(--amb);background:#fff;}
.fld-hint{font-size:.76rem;color:var(--g1);margin-top:.25rem;}
.modal-ft{padding:.9rem 1.2rem;border-top:1px solid var(--borde2);background:var(--lino);display:flex;gap:.5rem;justify-content:flex-end;}
</style>

<div class="sk-page">
  <div class="sk-hd">
    <div>
      <h1>Categorías</h1>
      <div class="sk-hd-sub"><?= count($categorias) ?> categoría<?= count($categorias) !== 1 ? 's' : '' ?> — se usan al cargar o sugerir un producto.</div>
    </div>
    <button class="btn-sk btn-sk-primary" onclick="abrirModal()">Nueva categoría</button>
  </div>

  <?= Toast::flash() ?>

  <form class="sk-search" method="GET" action="<?= URL ?>categoria">
    <input type="text" name="buscar" value="<?= htmlspecialchars($buscar ?? '') ?>" placeholder="Buscar categoría…">
    <button type="submit" class="btn-sk btn-sk-primary">Buscar</button>
    <?php if (!empty($buscar)): ?>
      <a href="<?= URL ?>categoria" class="btn-sk btn-sk-secondary">Limpiar</a>
    <?php endif; ?>
  </form>

  <div class="sk-table-wrap">
    <table class="sk-table">
      <thead><tr>
        <th>Nombre</th>
        <th>Productos usando esta categoría</th>
        <th style="width:150px;">Acciones</th>
      </tr></thead>
      <tbody>
      <?php if (empty($categorias)): ?>
        <tr><td colspan="3"><div class="sk-vacio">
          <span>—</span>
          <p>No hay categorías cargadas todavía.</p>
        </div></td></tr>
      <?php else: ?>
        <?php foreach ($categorias as $c): ?>
        <tr>
          <td class="td-main"><?= htmlspecialchars($c['Nombre']) ?></td>
          <td>
            <span class="badge-prod <?= $c['CantidadProductos'] == 0 ? 'cero' : '' ?>">
              <?= (int)$c['CantidadProductos'] ?> producto<?= $c['CantidadProductos'] != 1 ? 's' : '' ?>
            </span>
          </td>
          <td><div class="td-acc">
            <button class="btn-sk btn-sk-secondary btn-sk-sm"
                    onclick="abrirModalEditar(<?= htmlspecialchars(json_encode($c)) ?>)">Editar</button>
            <?php if (puedeEliminar()): ?>
              <?php if ((int)$c['CantidadProductos'] === 0): ?>
              <button class="btn-sk btn-sk-danger btn-sk-sm"
                      onclick="abrirBaja(<?= $c['Id'] ?>, '<?= htmlspecialchars(addslashes($c['Nombre'])) ?>')">
                Dar de baja
              </button>
              <?php else: ?>
              <button class="btn-sk btn-sk-danger btn-sk-sm" disabled
                      style="opacity:.4;cursor:not-allowed;"
                      title="Tiene productos asignados — reasignalos primero">
                Dar de baja
              </button>
              <?php endif; ?>
            <?php endif; ?>
          </div></td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="modal-bg" id="modalBg">
  <div class="modal">
    <div class="modal-hd">
      <h3 id="modalTitulo">Nueva categoría</h3>
      <button class="modal-close" onclick="cerrarModal()">✕</button>
    </div>
    <form method="POST" action="<?= URL ?>categoria/guardar">
      <input type="hidden" name="Id" id="fId" value="0">
      <div class="modal-body">
        <div class="fld">
          <label>Nombre *</label>
          <input type="text" name="Nombre" id="fNombre" maxlength="30" placeholder="Ej: Muebles de cocina" required>
          <div class="fld-hint"><span id="charCount">0</span>/30</div>
        </div>
      </div>
      <div class="modal-ft">
        <button type="button" class="btn-sk btn-sk-secondary" onclick="cerrarModal()">Cancelar</button>
        <button type="submit" class="btn-sk btn-sk-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<div class="modal-bg" id="modalBaja">
  <div class="modal">
    <div class="modal-hd hd-baja">
      <h3>Dar de baja categoría</h3>
      <button class="modal-close" onclick="cerrarBaja()">✕</button>
    </div>
    <div class="modal-body">
      ¿Confirmás dar de baja <strong id="bajaNombre"></strong>? No va a estar disponible para asignar a nuevos productos.
    </div>
    <div class="modal-ft">
      <button type="button" class="btn-sk btn-sk-secondary" onclick="cerrarBaja()">Cancelar</button>
      <a href="#" id="bajaLink" class="btn-sk btn-sk-danger">Sí, dar de baja</a>
    </div>
  </div>
</div>

<script>
const fNombre = document.getElementById('fNombre');
const charCount = document.getElementById('charCount');
fNombre.addEventListener('input', () => charCount.textContent = fNombre.value.length);

function abrirModal() {
  document.getElementById('modalTitulo').textContent = 'Nueva categoría';
  document.getElementById('fId').value = 0;
  fNombre.value = ''; charCount.textContent = 0;
  document.getElementById('modalBg').classList.add('open');
  setTimeout(() => fNombre.focus(), 50);
}
function abrirModalEditar(c) {
  document.getElementById('modalTitulo').textContent = 'Editar categoría';
  document.getElementById('fId').value = c.Id;
  fNombre.value = c.Nombre; charCount.textContent = c.Nombre.length;
  document.getElementById('modalBg').classList.add('open');
  setTimeout(() => fNombre.focus(), 50);
}
function cerrarModal() {
  document.getElementById('modalBg').classList.remove('open');
}
document.getElementById('modalBg').addEventListener('click', function(e){ if(e.target===this) cerrarModal(); });

function abrirBaja(id, nombre) {
  document.getElementById('bajaNombre').textContent = nombre;
  document.getElementById('bajaLink').href = '<?= URL ?>categoria/baja/' + id;
  document.getElementById('modalBaja').classList.add('open');
}
function cerrarBaja() {
  document.getElementById('modalBaja').classList.remove('open');
}
document.getElementById('modalBaja').addEventListener('click', function(e){ if(e.target===this) cerrarBaja(); });
</script>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
<?php include INCLUDES . 'admin' . DS . 'modals_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'scripts_admin.php'; ?>
