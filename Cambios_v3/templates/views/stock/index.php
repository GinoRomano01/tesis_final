<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script></style>
<main class="content">
<style>
:root{--caoba:#5c2d0a;--caoba2:#7a3e14;--amb:#b8722a;--lino:#f7f0e6;--lino2:#ede4d4;--papel:#fdfaf6;--tinta:#2c1a0e;--tinta2:#4a3020;--g1:#8a7560;--borde:#d4c4aa;--borde2:#e8dcc8;--sombra:rgba(92,45,10,.08);--rojo:#c0392b;--verde:#2e7d32;}
*{box-sizing:border-box;}
.sk-page{font-family:'Source Sans 3',Georgia,sans-serif;color:var(--tinta);}
.sk-hd{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1.2rem;}
.sk-hd h1{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--caoba);margin:0;}
.sk-hd-actions{display:flex;gap:.5rem;flex-wrap:wrap;}
.btn-sk{display:inline-flex;align-items:center;gap:.4rem;padding:.48rem 1rem;border-radius:7px;font-size:.86rem;font-weight:600;font-family:'Source Sans 3',sans-serif;text-decoration:none;border:none;cursor:pointer;transition:background .15s,transform .12s;}
.btn-sk:hover{transform:translateY(-1px);}
.btn-sk-primary{background:var(--caoba);color:#fff;}
.btn-sk-primary:hover{background:var(--caoba2);color:#fff;}
.btn-sk-green{background:#2e7d32;color:#fff;}
.btn-sk-green:hover{background:#1b5e20;color:#fff;}
.btn-sk-secondary{background:var(--lino2);color:var(--caoba);border:1.5px solid var(--borde);}
.btn-sk-secondary:hover{background:var(--borde);}
.btn-sk-danger{background:#fdf0f0;color:var(--rojo);border:1.5px solid #f5c6c6;}
.btn-sk-danger:hover{background:#fad7d7;}
.btn-sk-sm{padding:.28rem .6rem;font-size:.78rem;}
.sk-filtros{display:flex;gap:.5rem;margin-bottom:1.1rem;flex-wrap:wrap;align-items:center;}
.sk-filtros input{flex:1;min-width:180px;background:var(--papel);border:1.5px solid var(--borde);border-radius:7px;padding:.44rem .9rem;font-family:'Source Sans 3',sans-serif;font-size:.9rem;color:var(--tinta);}
.sk-filtros input:focus{outline:none;border-color:var(--amb);}
.sk-tabs{display:flex;gap:0;border:1.5px solid var(--borde);border-radius:8px;overflow:hidden;}
.sk-tab{padding:.42rem .9rem;font-size:.82rem;font-weight:600;cursor:pointer;background:var(--lino2);color:var(--g1);border:none;border-right:1px solid var(--borde);font-family:'Source Sans 3',sans-serif;transition:background .15s;}
.sk-tab:last-child{border-right:none;}
.sk-tab.active{background:var(--caoba);color:#fff;}
.sk-table-wrap{background:var(--papel);border:1.5px solid var(--borde);border-radius:10px;overflow:hidden;box-shadow:0 2px 12px var(--sombra);}
.sk-table{width:100%;border-collapse:collapse;}
.sk-table thead tr{background:linear-gradient(to right,var(--caoba),var(--caoba2));}
.sk-table thead th{padding:.68rem 1rem;text-align:left;font-size:.74rem;font-weight:700;letter-spacing:.06em;color:rgba(255,255,255,.9);text-transform:uppercase;border-right:1px solid rgba(255,255,255,.12);}
.sk-table thead th:last-child{border-right:none;}
.sk-table tbody tr{border-bottom:1px solid var(--borde2);transition:background .12s;}
.sk-table tbody tr:hover{background:var(--lino);}
.sk-table tbody td{padding:.62rem 1rem;font-size:.86rem;color:var(--tinta2);border-right:1px solid var(--borde2);vertical-align:middle;}
.sk-table tbody td:last-child{border-right:none;}
.td-tipo{display:inline-block;padding:2px 8px;border-radius:5px;font-size:.73rem;font-weight:700;}
.td-tipo.madera{background:#f3ebe0;color:#7a4e2d;border:1px solid #d4b896;}
.td-tipo.insumo{background:#e8f0f8;color:#2a3a4a;border:1px solid #c8d8e8;}
.td-main{font-weight:700;color:var(--caoba);}
.td-num{font-family:'Playfair Display',serif;font-size:.95rem;}
.td-monto{font-family:'Playfair Display',serif;color:var(--verde);font-weight:700;}
.td-fecha{font-size:.8rem;color:var(--g1);}
.td-acc{display:flex;gap:.3rem;}
.sk-vacio{text-align:center;padding:3rem 1rem;color:var(--g1);}
.sk-vacio span{font-size:2.5rem;opacity:.25;display:block;margin-bottom:.5rem;}
.modal-bg{display:none;position:fixed;inset:0;background:rgba(44,26,14,.45);z-index:1000;align-items:center;justify-content:center;padding:1rem;}
.modal-bg.open{display:flex;}
.modal{background:var(--papel);border-radius:12px;width:100%;max-width:520px;box-shadow:0 8px 40px rgba(0,0,0,.25);overflow:hidden;animation:modalIn .2s ease;max-height:90vh;overflow-y:auto;}
@keyframes modalIn{from{opacity:0;transform:translateY(-12px)}to{opacity:1;transform:translateY(0)}}
.modal-hd{background:linear-gradient(to right,var(--caoba),var(--caoba2));padding:1rem 1.2rem;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:1;}
.modal-hd h3{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:#fff;margin:0;}
.modal-close{background:none;border:none;color:rgba(255,255,255,.7);font-size:1.3rem;cursor:pointer;line-height:1;padding:0;}
.modal-close:hover{color:#fff;}
.modal-body{padding:1.2rem;}
.fld{margin-bottom:1rem;}
.fld label{display:block;margin-bottom:.3rem;font-size:.78rem;font-weight:700;color:var(--tinta2);text-transform:uppercase;letter-spacing:.03em;}
.fld input,.fld select{width:100%;background:var(--lino);border:1.5px solid var(--borde);border-radius:7px;padding:.5rem .85rem;font-family:'Source Sans 3',sans-serif;font-size:.9rem;color:var(--tinta);}
.fld input:focus,.fld select:focus{outline:none;border-color:var(--amb);background:#fff;box-shadow:0 0 0 3px rgba(184,114,42,.1);}
.fld-row{display:grid;grid-template-columns:1fr 1fr;gap:.75rem;}
.fld-hint{font-size:.78rem;color:var(--g1);margin:-.3rem 0 .8rem;line-height:1.5;}
.fld-hint a{color:var(--caoba);font-weight:600;}
.modal-ft{padding:.9rem 1.2rem;border-top:1px solid var(--borde2);background:var(--lino);display:flex;gap:.5rem;justify-content:flex-end;position:sticky;bottom:0;}
.panel{display:none;}.panel.show{display:block;}
.tipo-tabs{display:flex;gap:0;margin-bottom:1rem;border:1.5px solid var(--borde);border-radius:8px;overflow:hidden;}
.tipo-tab{flex:1;padding:.5rem;text-align:center;cursor:pointer;font-size:.86rem;font-weight:600;font-family:'Source Sans 3',sans-serif;background:var(--lino2);color:var(--g1);border:none;border-right:1px solid var(--borde);transition:background .15s;}
.tipo-tab:last-child{border-right:none;}
.tipo-tab.active{background:var(--caoba);color:#fff;}
.excel-info{background:#f0f7f0;border:1.5px solid #a8d5a8;border-radius:8px;padding:.9rem 1.1rem;font-size:.84rem;color:#2e5e2e;margin-bottom:1rem;line-height:1.6;}
.excel-info code{background:#ddeedd;padding:1px 5px;border-radius:3px;font-size:.82rem;}
.drop-area{border:2.5px dashed var(--borde);border-radius:10px;padding:2rem;text-align:center;cursor:pointer;transition:border-color .2s,background .2s;}
.drop-area:hover,.drop-area.drag{border-color:var(--amb);background:var(--lino);}
.drop-area input[type=file]{display:none;}
.drop-area p{margin:.5rem 0 0;font-size:.85rem;color:var(--g1);}
.import-errores{background:#fdf0f0;border:1.5px solid #f5c6c6;border-radius:8px;padding:.85rem 1rem;margin-bottom:1rem;}
.import-errores h4{font-size:.85rem;font-weight:700;color:var(--rojo);margin:0 0 .5rem;}
.import-errores ul{margin:0;padding-left:1.2rem;font-size:.82rem;color:var(--rojo);}

</style>

<div class="sk-page">

  <div class="sk-hd">
    <h1> Stock de Materiales</h1>
    <div class="sk-hd-actions">
      <a href="<?= URL ?>stock/exportarexcel?buscar=<?= urlencode($buscar) ?>&tipo=<?= $filtro ?>"
         class="btn-sk btn-sk-secondary">⬇ Exportar Excel</a>
      <button class="btn-sk btn-sk-green" onclick="abrirModalExcel()">⬆ Importar Excel</button>
      <button class="btn-sk btn-sk-primary" onclick="abrirModal()"> Carga manual</button>
    </div>
  </div>
  <div style="display:flex;gap:.5rem;margin-bottom:1.2rem;flex-wrap:wrap;">
    <form method="POST" action="<?= URL ?>stock/generarDiagnostico" style="margin:0;"
          onsubmit="return confirm('Esto puede tardar 15-30 segundos. ¿Generar diagnóstico ahora?');">
        <button type="submit" class="btn-sk btn-sk-primary">
             Generar diagnóstico con IA
        </button>
    </form>
    <a href="<?= URL ?>stock/historialDiagnosticos" class="btn-sk btn-sk-secondary">
         Ver diagnósticos anteriores
    </a>
  </div>
  <?php if (!empty($_SESSION['import_errores'])):
      $errs = $_SESSION['import_errores'];
      unset($_SESSION['import_errores']);
  ?>
  <div class="import-errores">
    <h4> Errores en la importación (<?= count($errs) ?> fila(s))</h4>
    <ul>
      <?php foreach ($errs as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>

  <form class="sk-filtros" method="GET" action="<?= URL ?>stock" id="frmFiltro">
    <input type="hidden" name="tipo" id="fTipoHidden" value="<?= (int)$filtro ?>">
    <input type="text" name="buscar"
           value="<?= htmlspecialchars($buscar) ?>"
           placeholder="Buscar material…">
    <button type="submit" class="btn-sk btn-sk-primary">Buscar</button>
    <?php if (!empty($buscar) || $filtro): ?>
      <a href="<?= URL ?>stock" class="btn-sk btn-sk-secondary">✕ Limpiar</a>
    <?php endif; ?>
    <div class="sk-tabs">
      <button type="button" class="sk-tab <?= $filtro == 0 ? 'active' : '' ?>"
              onclick="setFiltroTipo(0)">Todos</button>
      <button type="button" class="sk-tab <?= $filtro == 1 ? 'active' : '' ?>"
              onclick="setFiltroTipo(1)"> Maderas</button>
      <button type="button" class="sk-tab <?= $filtro == 2 ? 'active' : '' ?>"
              onclick="setFiltroTipo(2)"> Insumos</button>
    </div>
  </form>

  <div class="sk-table-wrap">
    <table class="sk-table">
      <thead>
        <tr>
          <th>Tipo</th>
          <th>Material</th>
          <th>Cantidad</th>
          <th>Precio Unit.</th>
          <th>Monto Total</th>
          <th>Fecha Ingreso</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($stocks)): ?>
        <tr>
          <td colspan="7">
            <div class="sk-vacio">
              <span></span>
              <p>No hay registros de stock.</p>
            </div>
          </td>
        </tr>
      <?php else: ?>
        <?php foreach ($stocks as $s):
            $esMadera = (int)$s['TipoMaterial'] === 1;
            $nombre   = $esMadera
                ? ($s['NombreMadera'] ?? '—') . ' '
                  . number_format($s['Largo'], 1) . '×'
                  . number_format($s['Ancho'], 1) . '×'
                  . number_format($s['Alto'],  1) . ' cm'
                : ($s['NombreInsumo'] ?? '—');
        ?>
        <tr>
          <td>
            <?php if ($esMadera): ?>
              <span class="td-tipo madera"> Madera</span>
            <?php else: ?>
              <span class="td-tipo insumo"> Insumo</span>
            <?php endif; ?>
          </td>
          <td class="td-main"><?= htmlspecialchars($nombre) ?></td>
          <td class="td-num"><?= number_format($s['Cantidad'],      2, ',', '.') ?></td>
          <td class="td-num">$<?= number_format($s['PrecioUnitario'], 2, ',', '.') ?></td>
          <td class="td-monto">$<?= number_format($s['MontoTotal'],   2, ',', '.') ?></td>
          <td class="td-fecha"><?= date('d/m/Y H:i', strtotime($s['FechaIngreso'])) ?></td>
          <td>
            <div class="td-acc">
              <button class="btn-sk btn-sk-secondary btn-sk-sm"
                      onclick="abrirModalEditar(<?= htmlspecialchars(json_encode($s)) ?>)">✏️</button>
              <a href="<?= URL ?>stock/eliminarstock/<?= $s['Id'] ?>"
                 class="btn-sk btn-sk-danger btn-sk-sm"
                 onclick="return confirm('¿Eliminar este registro de stock?')">🗑️</a>
            </div>
          </td>
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
      <h3 id="modalTitulo">Nuevo ingreso de stock</h3>
      <button class="modal-close" onclick="cerrarModal('modalBg')">✕</button>
    </div>
    <form method="POST" action="<?= URL ?>stock/guardarstock">
      <input type="hidden" name="Id"           id="fId"           value="0">
      <input type="hidden" name="TipoMaterial" id="fTipoMaterial" value="1">
      <input type="hidden" name="IdMaterial"   id="fIdMaterial"   value="0">
      <div class="modal-body">

        <div class="tipo-tabs">
          <button type="button" class="tipo-tab active" onclick="setTipo(1)"> Madera</button>
          <button type="button" class="tipo-tab"        onclick="setTipo(2)"> Insumo</button>
        </div>

        <div class="panel show" id="panelMadera">
          <div class="fld">
            <label>Madera *</label>
            <select id="selMadera" onchange="seleccionarMaterial(this, 1)">
              <option value="">— Seleccioná —</option>
              <?php foreach ($maderas as $m): ?>
                <option value="<?= $m['Id'] ?>"
                        data-precio="<?= $m['UltimoPrecio'] ?>"
                        data-stock="<?= $m['CantidadActual'] ?>">
                  <?= htmlspecialchars($m['TipoMadera'] ?? '') ?>
                  — <?= number_format($m['Alto'],1) ?>×<?= number_format($m['Largo'],1) ?>×<?= number_format($m['Ancho'],1) ?> cm
                  (Stock: <?= number_format($m['CantidadActual'],0) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="panel" id="panelInsumo">
          <div class="fld">
            <label>Insumo *</label>
            <select id="selInsumo" onchange="seleccionarMaterial(this, 2)">
              <option value="">— Seleccioná —</option>
              <?php foreach ($insumos as $ins): ?>
                <option value="<?= $ins['Id'] ?>"
                        data-precio="<?= $ins['UltimoPrecio'] ?>"
                        data-stock="<?= $ins['CantidadActual'] ?>">
                  <?= htmlspecialchars($ins['Descripcion']) ?>
                  (Stock: <?= number_format($ins['CantidadActual'],0) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="fld-row">
          <div class="fld">
            <label>Cantidad a ingresar *</label>
            <input type="number" name="Cantidad" id="fCantidad"
                   min="0.01" step="0.01" placeholder="0" required>
          </div>
          <div class="fld">
            <label>Fecha de ingreso</label>
            <input type="date" name="FechaIngreso" id="fFecha" value="<?= date('Y-m-d') ?>">
          </div>
        </div>

        <div class="fld">
          <label>Precio actual del material</label>
          <input type="text" id="fPrecioInfo" readonly
                 style="background:#f0f0f0;cursor:default;" placeholder="—">
        </div>
        <p class="fld-hint">
          💡 El precio se administra desde el catálogo de
          <a href="<?= URL ?>stock/maderas">Maderas</a> o
          <a href="<?= URL ?>stock/insumos">Insumos</a>, no acá.
          Esta carga solo suma <strong>cantidad</strong> al stock.
        </p>
      </div>
      <div class="modal-ft">
        <button type="button" class="btn-sk btn-sk-secondary" onclick="cerrarModal('modalBg')">Cancelar</button>
        <button type="submit" class="btn-sk btn-sk-primary">💾 Guardar</button>
      </div>
    </form>
  </div>
</div>

<div class="modal-bg" id="modalExcel">
  <div class="modal">
    <div class="modal-hd">
      <h3>⬆️ Importar stock desde Excel</h3>
      <button class="modal-close" onclick="cerrarModal('modalExcel')">✕</button>
    </div>
    <form method="POST" action="<?= URL ?>stock/importarexcel" enctype="multipart/form-data">
      <input type="hidden" name="uri" value="stock/importarexcel">
      <div class="modal-body">

        <div class="excel-info">
          <strong>Formato requerido</strong> — Usá el Excel exportado como base, tiene 3 hojas:<br><br>

          <strong>① Hoja "Stock"</strong> — para sumar cantidad:<br>
          • <code>Tipo</code>: escribí <strong>madera</strong> o <strong>insumo</strong><br>
          • <code>Nombre Material</code>: NO lo escribas a mano. Pará el cursor en la celda,
            escribí <code>=</code> y hacé clic en el nombre correspondiente de la hoja
            <strong>Ref Maderas</strong> o <strong>Ref Insumos</strong> (como una fórmula normal de Excel).
            Así queda idéntico al catálogo y sin usar ningún ID.<br>
          • <code>Cantidad</code>: la cantidad a sumar al stock (obligatorio)<br>
          • <code>Precio Unitario</code> y <code>Monto Total</code>: son solo informativos,
            se ignoran al importar — podés calcular el Monto Total vos mismo con una fórmula
            si querés, no afecta nada<br>
          • <code>Fecha Ingreso</code>: opcional, formato dd/mm/aaaa (vacío = hoy)<br><br>

          <strong>② y ③ Hojas "Ref Maderas" / "Ref Insumos"</strong> — para actualizar precio:<br>
          • Modificá la columna <code>Precio Unitario</code> de la fila que quieras actualizar<br>
          • No toques el resto de las columnas (Nombre Material / Tipo / dimensiones):
            se usan para identificar qué material actualizar<br>
          • Si dejás <code>Precio Unitario</code> vacío en una fila, no se modifica nada de esa fila<br>
          • Estas hojas <strong>no crean</strong> maderas o insumos nuevos (eso se hace desde
            <a href="<?= URL ?>stock/maderas">Maderas</a> / <a href="<?= URL ?>stock/insumos">Insumos</a>)<br><br>

           <strong>Tip:</strong> Exportá primero con <em>⬇️ Exportar Excel</em> y editá ese
          mismo archivo — ya tiene los nombres y las 3 hojas armadas.
        </div>

        <div class="drop-area" id="dropArea"
             onclick="document.getElementById('fArchivo').click()">
          <span style="font-size:2rem">📁</span>
          <p>Hacé clic o arrastrá tu archivo .xlsx aquí</p>
          <p id="nombreArchivo"
             style="font-weight:700;color:var(--caoba);margin-top:.3rem;"></p>
          <input type="file" name="archivo_excel" id="fArchivo"
                 accept=".xlsx,.xls,.csv"
                 onchange="mostrarNombreArchivo(this)">
        </div>

      </div>
      <div class="modal-ft">
        <button type="button" class="btn-sk btn-sk-secondary"
                onclick="cerrarModal('modalExcel')">Cancelar</button>
        <button type="submit" class="btn-sk btn-sk-green">📤 Importar</button>
      </div>
    </form>
  </div>
</div>

<script>
const maderasData = <?= json_encode(array_column($maderas ?? [], null, 'Id')) ?>;
const insumosData = <?= json_encode(array_column($insumos ?? [], null, 'Id')) ?>;

function setFiltroTipo(tipo) {
    document.getElementById('fTipoHidden').value = tipo;
    document.querySelectorAll('.sk-tab').forEach((t, i) => {
        t.classList.toggle('active', i === tipo);
    });
    document.getElementById('frmFiltro').submit();
}

function cerrarModal(id) {
    document.getElementById(id).classList.remove('open');
}

document.querySelectorAll('.modal-bg').forEach(el => {
    el.addEventListener('click', function (e) {
        if (e.target === this) this.classList.remove('open');
    });
});

function abrirModal() {
    document.getElementById('modalTitulo').textContent = 'Nuevo ingreso de stock';
    document.getElementById('fId').value         = 0;
    document.getElementById('fIdMaterial').value = 0;
    document.getElementById('fCantidad').value   = '';
    document.getElementById('fPrecioInfo').value = '';
    document.getElementById('fFecha').value      = '<?= date('Y-m-d') ?>';
    document.getElementById('selMadera').value   = '';
    document.getElementById('selInsumo').value   = '';
    setTipo(1);
    document.getElementById('modalBg').classList.add('open');
}

function abrirModalEditar(s) {
    document.getElementById('modalTitulo').textContent = 'Editar ingreso #' + s.Id;
    document.getElementById('fId').value       = s.Id;
    document.getElementById('fCantidad').value = s.Cantidad;
    document.getElementById('fFecha').value    = s.FechaIngreso ? s.FechaIngreso.substring(0, 10) : '';

    const tipo  = parseInt(s.TipoMaterial);
    const idMat = parseInt(s.IdMaterial);
    setTipoUI(tipo);
    document.getElementById('fTipoMaterial').value = tipo;
    document.getElementById('fIdMaterial').value   = idMat;
    if (tipo === 1) document.getElementById('selMadera').value = idMat;
    else            document.getElementById('selInsumo').value = idMat;

    const precio = parseFloat(s.PrecioUnitario) || 0;
    document.getElementById('fPrecioInfo').value = precio > 0
        ? '$' + precio.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, '.')
        : '—';

    document.getElementById('modalBg').classList.add('open');
}

function setTipoUI(tipo) {
    document.getElementById('fTipoMaterial').value = tipo;
    document.getElementById('panelMadera').classList.toggle('show', tipo === 1);
    document.getElementById('panelInsumo').classList.toggle('show', tipo === 2);
    document.querySelectorAll('#modalBg .tipo-tab').forEach((t, i) => {
        t.classList.toggle('active', (i === 0 && tipo === 1) || (i === 1 && tipo === 2));
    });
}

function setTipo(tipo) {
    setTipoUI(tipo);
    document.getElementById('selMadera').value   = '';
    document.getElementById('selInsumo').value   = '';
    document.getElementById('fIdMaterial').value = 0;
    document.getElementById('fPrecioInfo').value = '';
}

function seleccionarMaterial(sel, tipo) {
    const id   = sel.value;
    const data = tipo === 1 ? maderasData : insumosData;
    document.getElementById('fIdMaterial').value = id || 0;
    if (id && data[id]) {
        const precio = parseFloat(data[id].UltimoPrecio) || 0;
        document.getElementById('fPrecioInfo').value = precio > 0
            ? '$' + precio.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, '.')
            : '— (sin precio definido en el catálogo)';
    } else {
        document.getElementById('fPrecioInfo').value = '';
    }
}

function abrirModalExcel() {
    document.getElementById('fArchivo').value           = '';
    document.getElementById('nombreArchivo').textContent = '';
    document.getElementById('modalExcel').classList.add('open');
}

function mostrarNombreArchivo(input) {
    const nombre = input.files[0]?.name || '';
    document.getElementById('nombreArchivo').textContent = nombre ? '📄 ' + nombre : '';
}

const dropArea = document.getElementById('dropArea');
if (dropArea) {
    ['dragenter', 'dragover'].forEach(ev =>
        dropArea.addEventListener(ev, e => { e.preventDefault(); dropArea.classList.add('drag'); })
    );
    ['dragleave', 'drop'].forEach(ev =>
        dropArea.addEventListener(ev, e => { e.preventDefault(); dropArea.classList.remove('drag'); })
    );
    dropArea.addEventListener('drop', e => {
        const file = e.dataTransfer.files[0];
        if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('fArchivo').files = dt.files;
            document.getElementById('nombreArchivo').textContent = '📄 ' + file.name;
        }
    });
}

$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('.sk-table')) {
        $('.sk-table').DataTable().clear().destroy();
    }

    $('.sk-table').DataTable({
        language: {
            processing:     "Procesando...",
            search:         "Buscar:",
            lengthMenu:     "Mostrar _MENU_ registros",
            info:           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            infoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
            infoFiltered:   "(filtrado de un total de _MAX_ registros)",
            loadingRecords: "Cargando...",
            zeroRecords:    "No se encontraron resultados",
            emptyTable:     "No hay registros de stock.",
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
            { orderable: false, targets: [0, 6] }
        ]
    });
});
</script>
</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
<?php include INCLUDES . 'admin' . DS . 'modals_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'scripts_admin.php'; ?>