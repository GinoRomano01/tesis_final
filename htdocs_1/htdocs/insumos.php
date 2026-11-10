<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<main class="content">
<style>
:root{--caoba:#5c2d0a;--caoba2:#7a3e14;--amb:#b8722a;--lino:#f7f0e6;--lino2:#ede4d4;--papel:#fdfaf6;--tinta:#2c1a0e;--tinta2:#4a3020;--g1:#8a7560;--borde:#d4c4aa;--borde2:#e8dcc8;--sombra:rgba(92,45,10,.08);--rojo:#c0392b;--verde:#2e7d32;--acero:#2a3a4a;}
*{box-sizing:border-box;}
.sk-page{font-family:'Source Sans 3',Georgia,sans-serif;color:var(--tinta);}
.sk-hd{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1.2rem;}
.sk-hd h1{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--acero);margin:0;}
.sk-hd-sub{font-size:.82rem;color:var(--g1);margin-top:.15rem;}
.sk-hd-actions{display:flex;gap:.5rem;}
.btn-sk{display:inline-flex;align-items:center;gap:.4rem;padding:.48rem 1rem;border-radius:7px;font-size:.86rem;font-weight:600;font-family:'Source Sans 3',sans-serif;text-decoration:none;border:none;cursor:pointer;transition:background .15s,transform .12s;}
.btn-sk:hover{transform:translateY(-1px);}
.btn-sk-primary{background:var(--acero);color:#fff;}
.btn-sk-primary:hover{background:#3a4a5a;color:#fff;}
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
.sk-table thead tr{background:linear-gradient(to right,var(--acero),#3a4a5a);}
.sk-table thead th{padding:.72rem 1rem;text-align:left;font-size:.74rem;font-weight:700;letter-spacing:.06em;color:rgba(255,255,255,.92);text-transform:uppercase;border-right:1px solid rgba(255,255,255,.12);}
.sk-table thead th:last-child{border-right:none;}
.sk-table tbody tr{border-bottom:1px solid var(--borde2);transition:background .12s;}
.sk-table tbody tr:hover{background:var(--lino);}
.sk-table tbody td{padding:.68rem 1rem;font-size:.87rem;color:var(--tinta2);border-right:1px solid var(--borde2);vertical-align:middle;}
.sk-table tbody td:last-child{border-right:none;}
.td-main{font-weight:700;color:var(--acero);}
.td-num{font-family:'Playfair Display',serif;}
.td-precio{font-family:'Playfair Display',serif;color:var(--verde);font-weight:700;}
.td-stock-low{color:var(--rojo);font-weight:700;}
.td-badge{display:inline-block;padding:2px 8px;border-radius:5px;font-size:.74rem;font-weight:600;background:#e8f0f8;color:var(--acero);border:1px solid #c8d8e8;}
.td-acc{display:flex;gap:.3rem;}
.sk-vacio{text-align:center;padding:3rem 1rem;color:var(--g1);}
.sk-vacio i{font-size:2.5rem;opacity:.25;display:block;margin-bottom:.5rem;}
.info-box{background:#e8f0f8;border:1.5px solid #c8d8e8;border-radius:8px;padding:.8rem 1rem;font-size:.84rem;color:var(--acero);margin-bottom:1rem;}
.info-box strong{color:var(--acero);}
.modal-bg{display:none;position:fixed;inset:0;background:rgba(44,26,14,.45);z-index:1000;align-items:center;justify-content:center;padding:1rem;}
.modal-bg.open{display:flex;}
.modal{background:var(--papel);border-radius:12px;width:100%;max-width:500px;box-shadow:0 8px 40px rgba(0,0,0,.25);overflow:hidden;animation:modalIn .2s ease;}
@keyframes modalIn{from{opacity:0;transform:translateY(-12px)}to{opacity:1;transform:translateY(0)}}
.modal-hd{background:linear-gradient(to right,var(--acero),#3a4a5a);padding:1rem 1.2rem;display:flex;align-items:center;justify-content:space-between;}
.modal-hd h3{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:#fff;margin:0;}
.modal-close{background:none;border:none;color:rgba(255,255,255,.7);font-size:1.3rem;cursor:pointer;line-height:1;padding:0;}
.modal-close:hover{color:#fff;}
.modal-body{padding:1.2rem;}
.fld{margin-bottom:1rem;}
.fld label{display:block;margin-bottom:.3rem;font-size:.78rem;font-weight:700;color:var(--tinta2);text-transform:uppercase;letter-spacing:.03em;}
.fld input,.fld select{width:100%;background:var(--lino);border:1.5px solid var(--borde);border-radius:7px;padding:.5rem .85rem;font-family:'Source Sans 3',sans-serif;font-size:.9rem;color:var(--tinta);}
.fld input:focus,.fld select:focus{outline:none;border-color:var(--amb);background:#fff;}
.fld-row{display:grid;grid-template-columns:1fr 1fr;gap:.75rem;}
.modal-ft{padding:.9rem 1.2rem;border-top:1px solid var(--borde2);background:var(--lino);display:flex;gap:.5rem;justify-content:flex-end;}
.char-hint{font-size:.73rem;color:var(--g1);margin-top:.2rem;}
</style>

<div class="sk-page">
  <div class="sk-hd">
    <div>
      <h1>Insumos — Catálogo</h1>
      <div class="sk-hd-sub">
        Registrá qué insumos usa el taller, con su precio unitario. Las cantidades se cargan desde
        <a href="<?= URL ?>stock" style="color:var(--acero);font-weight:700;">Stock de Materiales</a>.
      </div>
    </div>
    <div class="sk-hd-actions">
      <a href="<?= URL ?>stock" class="btn-sk btn-sk-secondary">← Volver al Stock</a>
      <button class="btn-sk btn-sk-primary" onclick="abrirModal()">Nuevo insumo</button>
    </div>
  </div>

  <div class="info-box">
    <strong>¿Cómo funciona?</strong>
    Aquí definís el <strong>catálogo de insumos</strong> (tornillos, bisagras, etc.) junto con su precio.
    Para cargar cantidades usá
    <a href="<?= URL ?>stock" style="color:var(--acero);">Stock → Carga manual o importación Excel</a>.
    Si cambia el precio de un insumo, actualizalo acá.
  </div>

  <form class="sk-search" method="GET" action="<?= URL ?>stock/insumos">
    <input type="text" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
           placeholder="Buscar por descripción o material…">
    <button type="submit" class="btn-sk btn-sk-primary">Buscar</button>
    <?php if (!empty($buscar)): ?>
      <a href="<?= URL ?>stock/insumos" class="btn-sk btn-sk-secondary">Limpiar</a>
    <?php endif; ?>
  </form>

  <div class="sk-table-wrap">
    <table class="sk-table">
      <thead><tr>
        <th>Descripción</th>
        <th>Tipo de Material</th>
        <th>Corte</th>
        <th>Precio Unitario</th>
        <th>Stock actual</th>
        <th>Acciones</th>
      </tr></thead>
      <tbody>
      <?php if (empty($insumos)): ?>
        <tr><td colspan="6"><div class="sk-vacio">
          <i class="fas fa-screwdriver"></i>
          <p>No hay insumos en el catálogo.</p>
        </div></td></tr>
      <?php else: ?>
        <?php foreach ($insumos as $ins): ?>
        <tr>
          <td class="td-main"><?= htmlspecialchars($ins['Descripcion']) ?></td>
          <td><span class="td-badge"><?= htmlspecialchars($ins['TipoMaterial'] ?? '—') ?></span></td>
          <td><span class="td-badge"><?= htmlspecialchars($ins['TipoCorte']   ?? '—') ?></span></td>
          <td class="td-precio">
            <?= ($ins['PrecioUnitario'] ?? 0) > 0
                ? '$' . number_format($ins['PrecioUnitario'], 2, ',', '.')
                : '<span style="color:var(--g1);font-size:.82rem">Sin precio</span>' ?>
          </td>
          <td class="td-num <?= ($ins['CantidadStock'] ?? 0) <= 0 ? 'td-stock-low' : '' ?>">
            <?= number_format($ins['CantidadStock'] ?? 0) ?> u.
            <?php if (($ins['CantidadStock'] ?? 0) <= 0): ?>
              <span style="font-size:.75rem;color:var(--rojo);">Sin stock</span>
            <?php endif; ?>
          </td>
          <td><div class="td-acc">
            <button class="btn-sk btn-sk-secondary btn-sk-sm"
              onclick="abrirModalEditar(<?= htmlspecialchars(json_encode($ins)) ?>)">Editar</button>
            <a href="<?= URL ?>stock/eliminarinsumos/<?= $ins['Id'] ?>"
               class="btn-sk btn-sk-danger btn-sk-sm"
               onclick="return confirm('¿Eliminar este insumo del catálogo?')">Eliminar</a>
          </div></td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL -->
<div class="modal-bg" id="modalBg">
  <div class="modal">
    <div class="modal-hd">
      <h3 id="modalTitulo">Nuevo insumo</h3>
      <button class="modal-close" onclick="document.getElementById('modalBg').classList.remove('open')">✕</button>
    </div>
    <form method="POST" action="<?= URL ?>stock/guardarinsumos">
      <input type="hidden" name="Id" id="fId" value="0">
      <div class="modal-body">
        <div class="fld">
          <label>Descripción * <span style="font-weight:400;text-transform:none;">(máx. 20 caracteres)</span></label>
          <input type="text" name="Descripcion" id="fDesc" maxlength="20" placeholder="Ej: Tornillo 3mm" required>
          <div class="char-hint"><span id="charCount">0</span>/20</div>
        </div>
        <div class="fld-row">
          <div class="fld">
            <label>Tipo de Material</label>
            <select name="IdTipodeMaterial" id="fMaterial">
              <option value="0">— Sin tipo —</option>
              <?php foreach ($tiposMaterial as $tm): ?>
                <option value="<?= $tm['Id'] ?>"><?= htmlspecialchars($tm['Nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="fld">
            <label>Tipo de Corte</label>
            <select name="IdTipodeCorte" id="fCorte">
              <option value="0">— Sin tipo —</option>
              <?php foreach ($tiposCorte as $tc): ?>
                <option value="<?= $tc['Id'] ?>"><?= htmlspecialchars($tc['Nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="fld">
          <label>Precio Unitario ($) *</label>
          <input type="number" name="PrecioUnitario" id="fPrecio" min="0" step="0.01" placeholder="0.00" required>
        </div>
        <p style="font-size:.8rem;color:var(--g1);margin:-.3rem 0 .8rem;">
          La cantidad en depósito se carga en
          <a href="<?= URL ?>stock" style="color:var(--acero);">Stock de Materiales</a>.
        </p>
      </div>
      <div class="modal-ft">
        <button type="button" class="btn-sk btn-sk-secondary"
                onclick="document.getElementById('modalBg').classList.remove('open')">Cancelar</button>
        <button type="submit" class="btn-sk btn-sk-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<script>
const fDesc = document.getElementById('fDesc');
const charCount = document.getElementById('charCount');
fDesc.addEventListener('input', () => charCount.textContent = fDesc.value.length);

function abrirModal() {
  document.getElementById('modalTitulo').textContent = 'Nuevo insumo';
  document.getElementById('fId').value = 0;
  fDesc.value = ''; charCount.textContent = 0;
  document.getElementById('fMaterial').value = 0;
  document.getElementById('fCorte').value    = 0;
  document.getElementById('fPrecio').value   = '';
  document.getElementById('modalBg').classList.add('open');
}
function abrirModalEditar(ins) {
  document.getElementById('modalTitulo').textContent = 'Editar insumo';
  document.getElementById('fId').value       = ins.Id;
  fDesc.value = ins.Descripcion; charCount.textContent = ins.Descripcion.length;
  document.getElementById('fMaterial').value = ins.IdTipodeMaterial;
  document.getElementById('fCorte').value    = ins.IdTipodeCorte;
  document.getElementById('fPrecio').value   = ins.PrecioUnitario;
  document.getElementById('modalBg').classList.add('open');
}
document.getElementById('modalBg').addEventListener('click', function(e){ if(e.target===this) this.classList.remove('open'); });
</script>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
<?php include INCLUDES . 'admin' . DS . 'modals_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'scripts_admin.php'; ?>