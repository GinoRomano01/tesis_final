<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<main class="content">
<style>
:root{--caoba:#5c2d0a;--caoba2:#7a3e14;--amb:#b8722a;--lino:#f7f0e6;--lino2:#ede4d4;--papel:#fdfaf6;--tinta:#2c1a0e;--tinta2:#4a3020;--g1:#8a7560;--borde:#d4c4aa;--borde2:#e8dcc8;--sombra:rgba(92,45,10,.08);--rojo:#c0392b;--verde:#2e7d32;--madera:#7a4e2d;}
*{box-sizing:border-box;}
.sk-page{font-family:'Source Sans 3',Georgia,sans-serif;color:var(--tinta);}
.sk-hd{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1.2rem;}
.sk-hd h1{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--madera);margin:0;}
.sk-hd-sub{font-size:.82rem;color:var(--g1);margin-top:.15rem;}
.sk-hd-actions{display:flex;gap:.5rem;}
.btn-sk{display:inline-flex;align-items:center;gap:.4rem;padding:.48rem 1rem;border-radius:7px;font-size:.86rem;font-weight:600;font-family:'Source Sans 3',sans-serif;text-decoration:none;border:none;cursor:pointer;transition:background .15s,transform .12s;}
.btn-sk:hover{transform:translateY(-1px);}
.btn-sk-primary{background:var(--madera);color:#fff;}
.btn-sk-primary:hover{background:#5c3318;color:#fff;}
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
.sk-table thead tr{background:linear-gradient(to right,var(--madera),#9a5e38);}
.sk-table thead th{padding:.72rem 1rem;text-align:left;font-size:.74rem;font-weight:700;letter-spacing:.06em;color:rgba(255,255,255,.92);text-transform:uppercase;border-right:1px solid rgba(255,255,255,.12);}
.sk-table thead th:last-child{border-right:none;}
.sk-table tbody tr{border-bottom:1px solid var(--borde2);transition:background .12s;}
.sk-table tbody tr:hover{background:var(--lino);}
.sk-table tbody td{padding:.68rem 1rem;font-size:.87rem;color:var(--tinta2);border-right:1px solid var(--borde2);vertical-align:middle;}
.sk-table tbody td:last-child{border-right:none;}
.td-main{font-weight:700;color:var(--madera);}
.td-dim{font-size:.82rem;color:var(--g1);font-style:italic;}
.td-num{font-family:'Playfair Display',serif;font-size:.95rem;}
.td-precio{font-family:'Playfair Display',serif;color:var(--verde);font-weight:700;}
.td-stock-low{color:var(--rojo);font-weight:700;}
.td-acc{display:flex;gap:.3rem;}
.td-formato{display:inline-block;padding:2px 9px;border-radius:5px;font-size:.74rem;font-weight:700;}
.td-formato.plancha{background:#e3f2fd;color:#1565c0;border:1px solid #90caf9;}
.td-formato.tablon{background:#f3ebe0;color:#7a4e2d;border:1px solid #d4b896;}
.sk-vacio{text-align:center;padding:3rem 1rem;color:var(--g1);}
.sk-vacio i{font-size:2.5rem;opacity:.25;display:block;margin-bottom:.5rem;color:var(--madera);}
.info-box{background:#f3ebe0;border:1.5px solid var(--borde);border-radius:8px;padding:.8rem 1rem;font-size:.84rem;color:var(--tinta2);margin-bottom:1rem;}
.info-box strong{color:var(--madera);}
.modal-bg{display:none;position:fixed;inset:0;background:rgba(44,26,14,.45);z-index:1000;align-items:center;justify-content:center;padding:1rem;}
.modal-bg.open{display:flex;}
.modal{background:var(--papel);border-radius:12px;width:100%;max-width:500px;box-shadow:0 8px 40px rgba(0,0,0,.25);overflow:hidden;animation:modalIn .2s ease;}
@keyframes modalIn{from{opacity:0;transform:translateY(-12px)}to{opacity:1;transform:translateY(0)}}
.modal-hd{background:linear-gradient(to right,var(--madera),#9a5e38);padding:1rem 1.2rem;display:flex;align-items:center;justify-content:space-between;}
.modal-hd h3{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:#fff;margin:0;}
.modal-close{background:none;border:none;color:rgba(255,255,255,.7);font-size:1.3rem;cursor:pointer;line-height:1;padding:0;}
.modal-close:hover{color:#fff;}
.modal-body{padding:1.2rem;}
.fld{margin-bottom:1rem;}
.fld label{display:block;margin-bottom:.3rem;font-size:.78rem;font-weight:700;color:var(--tinta2);text-transform:uppercase;letter-spacing:.03em;}
.fld input,.fld select{width:100%;background:var(--lino);border:1.5px solid var(--borde);border-radius:7px;padding:.5rem .85rem;font-family:'Source Sans 3',sans-serif;font-size:.9rem;color:var(--tinta);}
.fld input:focus,.fld select:focus{outline:none;border-color:var(--amb);background:#fff;}
.fld-row3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:.75rem;}
.fld-hint{font-size:.76rem;color:var(--g1);margin-top:.25rem;line-height:1.4;}
.modal-ft{padding:.9rem 1.2rem;border-top:1px solid var(--borde2);background:var(--lino);display:flex;gap:.5rem;justify-content:flex-end;}
</style>

<div class="sk-page">
  <div class="sk-hd">
    <div>
      <h1>Maderas — Catálogo</h1>
      <div class="sk-hd-sub">
        Definí qué tipos de madera trabaja el taller, con su precio unitario y formato. El stock
        (cantidades en depósito) se carga desde
        <a href="<?= URL ?>stock" style="color:var(--madera);font-weight:700;">Stock de Materiales</a>.
      </div>
    </div>
    <div class="sk-hd-actions">
      <a href="<?= URL ?>stock" class="btn-sk btn-sk-secondary">← Volver al Stock</a>
      <button class="btn-sk btn-sk-primary" onclick="abrirModal()">Nueva madera</button>
    </div>
  </div>

  <div class="info-box">
    <strong>¿Cómo funciona?</strong>
    Aquí registrás los <strong>tipos de madera</strong> (tipo + formato + dimensiones + precio unitario).
    <strong>Plancha</strong> = corte ancho y fino, para placares, mesas de noche, paneles.
    <strong>Tablón</strong> = corte grueso y angosto, para patas de mesa, sillas, estructuras.
    La <strong>cantidad real en depósito</strong> se registra en la sección
    <a href="<?= URL ?>stock" style="color:var(--madera);">Stock → Carga manual o Excel</a>.
    Si cambia el precio de una madera, actualizalo acá.
  </div>

  <form class="sk-search" method="GET" action="<?= URL ?>stock/maderas">
    <input type="text" name="buscar" value="<?= htmlspecialchars($buscar) ?>" placeholder="Buscar por tipo de madera…">
    <button type="submit" class="btn-sk btn-sk-primary">Buscar</button>
    <?php if (!empty($buscar)): ?>
      <a href="<?= URL ?>stock/maderas" class="btn-sk btn-sk-secondary">Limpiar</a>
    <?php endif; ?>
  </form>

  <div class="sk-table-wrap">
    <table class="sk-table">
      <thead><tr>
        <th>Tipo de Madera</th>
        <th>Formato</th>
        <th>Dimensiones (cm)</th>
        <th>Precio Unitario</th>
        <th>Stock actual</th>
        <th>Acciones</th>
      </tr></thead>
      <tbody>
      <?php if (empty($maderas)): ?>
        <tr><td colspan="6"><div class="sk-vacio">
          <i class="fas fa-tree"></i>
          <p>No hay maderas en el catálogo.</p>
        </div></td></tr>
      <?php else: ?>
        <?php foreach ($maderas as $m): $formato = $m['Formato'] ?? 'tablon'; ?>
        <tr>
          <td class="td-main"><?= htmlspecialchars($m['TipoMadera'] ?? '—') ?></td>
          <td>
            <span class="td-formato <?= $formato ?>">
              <?= $formato === 'plancha' ? 'Plancha' : 'Tablón' ?>
            </span>
          </td>
          <td class="td-dim">
            <?= number_format($m['Alto'],1) ?> × <?= number_format($m['Largo'],1) ?> × <?= number_format($m['Ancho'],1) ?> cm
          </td>
          <td class="td-precio">
            <?= ($m['PrecioUnitario'] ?? 0) > 0
                ? '$' . number_format($m['PrecioUnitario'], 2, ',', '.')
                : '<span style="color:var(--g1);font-size:.82rem">Sin precio</span>' ?>
          </td>
          <td class="td-num <?= ($m['CantidadStock'] ?? 0) <= 0 ? 'td-stock-low' : '' ?>">
            <?= number_format($m['CantidadStock'] ?? 0) ?> u.
            <?php if (($m['CantidadStock'] ?? 0) <= 0): ?>
              <span style="font-size:.75rem;color:var(--rojo);">sin stock</span>
            <?php endif; ?>
          </td>
          <td><div class="td-acc">
            <button class="btn-fila"
              onclick="abrirModalEditar(<?= htmlspecialchars(json_encode($m)) ?>)">Editar</button>
            <a href="<?= URL ?>stock/eliminarmaderas/<?= $m['Id'] ?>"
               class="btn-fila peligro"
               onclick="return confirm('¿Eliminar esta madera del catálogo?')">Eliminar</a>
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
      <h3 id="modalTitulo">Nueva madera</h3>
      <button class="modal-close" onclick="document.getElementById('modalBg').classList.remove('open')">✕</button>
    </div>
    <form method="POST" action="<?= URL ?>stock/guardarmaderas">
      <input type="hidden" name="Id" id="fId" value="0">
      <div class="modal-body">
        <div class="fld">
          <label>Tipo de madera *</label>
          <select name="IdTipodeMadera" id="fTipo" required>
            <option value="">— Seleccioná —</option>
            <?php foreach ($tipos as $t): ?>
              <option value="<?= $t['Id'] ?>"><?= htmlspecialchars($t['Nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="fld">
          <label>Formato *</label>
          <select name="Formato" id="fFormato" required>
            <option value="tablon">Tablón (grueso y angosto — patas, sillas, estructuras)</option>
            <option value="plancha">Plancha (fino y ancho — placares, mesas de noche, paneles)</option>
          </select>
        </div>
        <div class="fld-row3">
          <div class="fld">
            <label>Alto (cm)</label>
            <input type="number" name="Alto" id="fAlto" min="0" step="0.01" placeholder="0.00">
          </div>
          <div class="fld">
            <label>Largo (cm)</label>
            <input type="number" name="Largo" id="fLargo" min="0" step="0.01" placeholder="0.00">
          </div>
          <div class="fld">
            <label>Ancho (cm)</label>
            <input type="number" name="Ancho" id="fAncho" min="0" step="0.01" placeholder="0.00">
          </div>
        </div>
        <div class="fld-hint">
          💡 Orientativo: <strong>Tablón</strong> suele ser Alto 4-8cm, Ancho 8-25cm.
          <strong>Plancha</strong> suele ser Alto 0.5-3cm, Ancho 100-122cm.
        </div>
        <div class="fld">
          <label>Precio Unitario ($) *</label>
          <input type="number" name="PrecioUnitario" id="fPrecio" min="0" step="0.01" placeholder="0.00" required>
        </div>
        <p style="font-size:.8rem;color:var(--g1);margin:-.3rem 0 .8rem;">
          La cantidad en depósito se carga en
          <a href="<?= URL ?>stock" style="color:var(--madera);">Stock de Materiales</a>.
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
function abrirModal() {
  document.getElementById('modalTitulo').textContent = 'Nueva madera';
  document.getElementById('fId').value = 0;
  ['fTipo','fAlto','fLargo','fAncho','fPrecio'].forEach(id => document.getElementById(id).value = '');
  document.getElementById('fFormato').value = 'tablon';
  document.getElementById('modalBg').classList.add('open');
}
function abrirModalEditar(m) {
  document.getElementById('modalTitulo').textContent = 'Editar madera';
  document.getElementById('fId').value     = m.Id;
  document.getElementById('fTipo').value   = m.IdTipodeMadera;
  document.getElementById('fFormato').value = m.Formato || 'tablon';
  document.getElementById('fAlto').value   = m.Alto;
  document.getElementById('fLargo').value  = m.Largo;
  document.getElementById('fAncho').value  = m.Ancho;
  document.getElementById('fPrecio').value = m.PrecioUnitario;
  document.getElementById('modalBg').classList.add('open');
}
document.getElementById('modalBg').addEventListener('click', function(e){ if(e.target===this) this.classList.remove('open'); });
</script>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
<?php include INCLUDES . 'admin' . DS . 'modals_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'scripts_admin.php'; ?>