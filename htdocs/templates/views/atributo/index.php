<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<main class="content">
<style>
:root{--caoba:#5c2d0a;--caoba2:#7a3e14;--amb:#b8722a;--lino:#f7f0e6;--lino2:#ede4d4;--papel:#fdfaf6;--tinta:#2c1a0e;--tinta2:#4a3020;--g1:#8a7560;--borde:#d4c4aa;--borde2:#e8dcc8;--sombra:rgba(92,45,10,.08);--rojo:#c0392b;--verde:#2e7d32;}
*{box-sizing:border-box;}
.sk-page{font-family:'Source Sans 3',Georgia,sans-serif;color:var(--tinta);}
.sk-hd{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1rem;}
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

.sk-tabs{display:flex;gap:0;border:1.5px solid var(--borde);border-radius:8px;overflow:hidden;margin-bottom:1.1rem;flex-wrap:wrap;}
.sk-tab{padding:.5rem 1rem;font-size:.84rem;font-weight:600;cursor:pointer;background:var(--lino2);color:var(--g1);border:none;border-right:1px solid var(--borde);font-family:'Source Sans 3',sans-serif;text-decoration:none;transition:background .15s;}
.sk-tab:last-child{border-right:none;}
.sk-tab.active{background:var(--caoba);color:#fff;}
.sk-tab:hover:not(.active){background:var(--borde);}

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
.td-desc{font-size:.82rem;color:var(--g1);font-style:italic;max-width:260px;}
.td-acc{display:flex;gap:.3rem;}
.badge-prod{display:inline-block;padding:2px 10px;border-radius:20px;font-size:.78rem;font-weight:700;background:#e3f2fd;color:#1565c0;border:1px solid #90caf9;}
.badge-prod.cero{background:var(--lino2);color:var(--g1);border-color:var(--borde);}
.badge-tipos{display:inline-block;padding:2px 10px;border-radius:20px;font-size:.76rem;font-weight:700;background:#f3ebe0;color:#7a4e2d;border:1px solid #d4b896;}
.badge-tipos.cero{background:#fdf0f0;color:var(--rojo);border-color:#f5c6c6;}
.sk-vacio{text-align:center;padding:3rem 1rem;color:var(--g1);}
.sk-vacio span{font-size:2.5rem;opacity:.25;display:block;margin-bottom:.5rem;}
.modal-bg{display:none;position:fixed;inset:0;background:rgba(44,26,14,.45);z-index:1000;align-items:center;justify-content:center;padding:1rem;}
.modal-bg.open{display:flex;}
.modal{background:var(--papel);border-radius:12px;width:100%;max-width:480px;box-shadow:0 8px 40px rgba(0,0,0,.25);overflow:hidden;animation:modalIn .15s ease;max-height:90vh;display:flex;flex-direction:column;}
@keyframes modalIn{from{opacity:0;transform:translateY(-12px)}to{opacity:1;transform:translateY(0)}}
.modal-hd{background:linear-gradient(to right,var(--caoba),var(--caoba2));padding:1rem 1.2rem;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;}
.modal-hd.hd-baja{background:linear-gradient(to right,var(--rojo),#d9564a);}
.modal-hd h3{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:#fff;margin:0;}
.modal-close{background:none;border:none;color:rgba(255,255,255,.7);font-size:1.3rem;cursor:pointer;line-height:1;padding:0;}
.modal-close:hover{color:#fff;}
.modal-body{padding:1.2rem;font-size:.92rem;line-height:1.6;overflow-y:auto;}
.fld{margin-bottom:1rem;}
.fld label{display:block;margin-bottom:.3rem;font-size:.78rem;font-weight:700;color:var(--tinta2);text-transform:uppercase;letter-spacing:.03em;}
.fld input,.fld textarea{width:100%;background:var(--lino);border:1.5px solid var(--borde);border-radius:7px;padding:.5rem .85rem;font-family:'Source Sans 3',sans-serif;font-size:.9rem;color:var(--tinta);}
.fld input:focus,.fld textarea:focus{outline:none;border-color:var(--amb);background:#fff;}
.fld textarea{resize:vertical;min-height:60px;}
.fld-hint{font-size:.76rem;color:var(--g1);margin-top:.25rem;}
.checklist{display:grid;grid-template-columns:1fr 1fr;gap:.4rem;max-height:220px;overflow-y:auto;background:var(--lino);border:1.5px solid var(--borde);border-radius:7px;padding:.7rem .85rem;}
.checklist label{display:flex;align-items:center;gap:.4rem;font-size:.85rem;color:var(--tinta2);font-weight:400;text-transform:none;letter-spacing:0;cursor:pointer;}
.checklist input[type=checkbox]{width:15px;height:15px;accent-color:var(--caoba);flex-shrink:0;}
.modal-ft{padding:.9rem 1.2rem;border-top:1px solid var(--borde2);background:var(--lino);display:flex;gap:.5rem;justify-content:flex-end;flex-shrink:0;}
</style>

<div class="sk-page">
  <div class="sk-hd">
    <div>
      <h1>Atributos de producto</h1>
      <div class="sk-hd-sub">Catálogos que se usan al cargar o sugerir un producto: <?= count($items) ?> registro<?= count($items) !== 1 ? 's' : '' ?> en esta sección.</div>
    </div>
    <button class="btn-sk btn-sk-primary" onclick="abrirModal()">Nuevo</button>
  </div>

  <?= Toast::flash() ?>

  <div class="sk-tabs">
    <?php foreach ($tabs as $key => $label): ?>
      <a href="<?= URL ?>atributo?tab=<?= $key ?>" class="sk-tab <?= $tab === $key ? 'active' : '' ?>"><?= htmlspecialchars($label) ?></a>
    <?php endforeach; ?>
  </div>

  <form class="sk-search" method="GET" action="<?= URL ?>atributo">
    <input type="hidden" name="tab" value="<?= $tab ?>">
    <input type="text" name="buscar" value="<?= htmlspecialchars($buscar ?? '') ?>" placeholder="Buscar en <?= htmlspecialchars($tabs[$tab]) ?>…">
    <button type="submit" class="btn-sk btn-sk-primary">Buscar</button>
    <?php if (!empty($buscar)): ?>
      <a href="<?= URL ?>atributo?tab=<?= $tab ?>" class="btn-sk btn-sk-secondary">Limpiar</a>
    <?php endif; ?>
  </form>

  <div class="sk-table-wrap">
    <table class="sk-table" id="tablaAtributos">
      <thead><tr>
        <th>Nombre</th>
        <?php if ($tieneDescripcion): ?><th>Descripción</th><?php endif; ?>
        <?php if ($tieneChecklist): ?><th>Tipos que lo pueden usar</th><?php endif; ?>
        <th>Productos usándolo</th>
        <th style="width:150px;">Acciones</th>
      </tr></thead>
      <tbody>
      <?php if (!empty($items)): ?>
        <?php foreach ($items as $it): ?>
        <tr>
          <td class="td-main"><?= htmlspecialchars($it['Nombre']) ?></td>
          <?php if ($tieneDescripcion): ?>
          <td class="td-desc"><?= !empty($it['Descripcion']) ? htmlspecialchars($it['Descripcion']) : '—' ?></td>
          <?php endif; ?>
          <?php if ($tieneChecklist): ?>
          <td>
            <?php $cantTipos = count($it['TiposVinculados'] ?? []); ?>
            <span class="badge-tipos <?= $cantTipos === 0 ? 'cero' : '' ?>">
              <?= $cantTipos ?> tipo<?= $cantTipos !== 1 ? 's' : '' ?>
            </span>
          </td>
          <?php endif; ?>
          <td>
            <span class="badge-prod <?= $it['CantidadProductos'] == 0 ? 'cero' : '' ?>">
              <?= (int)$it['CantidadProductos'] ?> producto<?= $it['CantidadProductos'] != 1 ? 's' : '' ?>
            </span>
          </td>
          <td><div class="td-acc">
            <button class="btn-fila"
                    onclick="abrirModalEditar(<?= htmlspecialchars(json_encode($it)) ?>)">Editar</button>
            <?php if (puedeEliminar()): ?>
              <?php if ((int)$it['CantidadProductos'] === 0): ?>
              <button class="btn-fila peligro"
                      onclick="abrirBaja(<?= $it['Id'] ?>, '<?= htmlspecialchars(addslashes($it['Nombre'])) ?>')">
                Dar de baja
              </button>
              <?php else: ?>
              <button class="btn-fila peligro" disabled
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
      <h3 id="modalTitulo">Nuevo</h3>
      <button class="modal-close" onclick="cerrarModal()">✕</button>
    </div>
    <form method="POST" action="<?= URL ?>atributo/guardar" id="formAtributo">
      <input type="hidden" name="Id" id="fId" value="0">
      <input type="hidden" name="Tab" value="<?= $tab ?>">
      <div class="modal-body">
        <div class="fld">
          <label>Nombre *</label>
          <input type="text" name="Nombre" id="fNombre" maxlength="30" placeholder="Ej: Moderno" required>
          <div class="fld-hint"><span id="charCount">0</span>/30</div>
        </div>

        <?php if ($tieneDescripcion): ?>
        <div class="fld">
          <label>Descripción <span style="font-weight:400;text-transform:none;">(opcional)</span></label>
          <textarea name="Descripcion" id="fDescripcion" maxlength="300" placeholder="Detalle breve, se muestra en la ficha del producto"></textarea>
        </div>
        <?php endif; ?>

        <?php if ($tieneChecklist): ?>
        <div class="fld">
          <label>¿Qué tipos de producto lo pueden usar?</label>
          <div class="checklist">
            <?php foreach ($todosTipos as $tp): ?>
            <label>
              <input type="checkbox" name="TiposVinculados[]" value="<?= $tp['Id'] ?>" class="chkTipo">
              <?= htmlspecialchars($tp['Nombre']) ?>
            </label>
            <?php endforeach; ?>
          </div>
          <div class="fld-hint">Si no tildás ninguno, no va a aparecer disponible para ningún producto.</div>
        </div>
        <?php endif; ?>
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
      <h3>Dar de baja</h3>
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
const TAB_LABEL = <?= json_encode($tabs[$tab]) ?>;
const TIENE_DESC = <?= json_encode($tieneDescripcion) ?>;
const TIENE_CHECK = <?= json_encode($tieneChecklist) ?>;

const fNombre = document.getElementById('fNombre');
const charCount = document.getElementById('charCount');
fNombre.addEventListener('input', () => charCount.textContent = fNombre.value.length);

function limpiarChecklist() {
  document.querySelectorAll('.chkTipo').forEach(chk => chk.checked = false);
}

function abrirModal() {
  document.getElementById('modalTitulo').textContent = 'Nuevo — ' + TAB_LABEL;
  document.getElementById('fId').value = 0;
  fNombre.value = ''; charCount.textContent = 0;
  if (TIENE_DESC) document.getElementById('fDescripcion').value = '';
  if (TIENE_CHECK) limpiarChecklist();
  document.getElementById('modalBg').classList.add('open');
  setTimeout(() => fNombre.focus(), 50);
}

function abrirModalEditar(it) {
  document.getElementById('modalTitulo').textContent = 'Editar — ' + TAB_LABEL;
  document.getElementById('fId').value = it.Id;
  fNombre.value = it.Nombre; charCount.textContent = it.Nombre.length;
  if (TIENE_DESC) document.getElementById('fDescripcion').value = it.Descripcion || '';
  if (TIENE_CHECK) {
    limpiarChecklist();
    (it.TiposVinculados || []).forEach(id => {
      const chk = document.querySelector('.chkTipo[value="' + id + '"]');
      if (chk) chk.checked = true;
    });
  }
  document.getElementById('modalBg').classList.add('open');
  setTimeout(() => fNombre.focus(), 50);
}

function cerrarModal() {
  document.getElementById('modalBg').classList.remove('open');
}
document.getElementById('modalBg').addEventListener('click', function(e){ if(e.target===this) cerrarModal(); });

function abrirBaja(id, nombre) {
  document.getElementById('bajaNombre').textContent = nombre;
  document.getElementById('bajaLink').href = '<?= URL ?>atributo/baja/' + id + '?tab=<?= $tab ?>';
  document.getElementById('modalBaja').classList.add('open');
}
function cerrarBaja() {
  document.getElementById('modalBaja').classList.remove('open');
}
document.getElementById('modalBaja').addEventListener('click', function(e){ if(e.target===this) cerrarBaja(); });

$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#tablaAtributos')) {
        $('#tablaAtributos').DataTable().clear().destroy();
    }

    $('#tablaAtributos').DataTable({
        language: {
            processing:     "Procesando...",
            search:         "Buscar:",
            lengthMenu:     "Mostrar _MENU_ registros",
            info:           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            infoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
            infoFiltered:   "(filtrado de un total de _MAX_ registros)",
            loadingRecords: "Cargando...",
            zeroRecords:    "No se encontraron resultados",
            emptyTable:     "No hay registros cargados en <?= htmlspecialchars($tabs[$tab]) ?> todavía.",
            paginate: {
                first:      "Primero",
                previous:   "Anterior",
                next:       "Siguiente",
                last:       "Último"
            }
        },
        pageLength: 10,
        searching: false,   
        lengthChange: false,
        paging: true,
        info: false,
        autoWidth: false,
        dom: 'tp',
        columnDefs: [
            { orderable: false, targets: -1 } 
        ]
    });
});
</script>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
<?php include INCLUDES . 'admin' . DS . 'modals_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'scripts_admin.php'; ?>
