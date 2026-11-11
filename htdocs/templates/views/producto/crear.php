<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<main class="content">

<link rel="stylesheet" href="<?= CSS ?>producto.css">

<style>
.img-grid { display:grid; grid-template-columns:1fr 1fr; gap:.5rem; }
.prod-layout-grid { display:grid; grid-template-columns:1fr 280px; gap:1.25rem; align-items:start; }
.prod-atributos-grid { display:grid; grid-template-columns:1fr 1fr 1fr; gap:.4rem .5rem; margin-bottom:.75rem; }
.prod-dimensiones-grid { display:grid; grid-template-columns:1fr 1fr 1fr; gap:.45rem; }

@media (max-width: 900px) {
    .prod-layout-grid { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
    .prod-atributos-grid { grid-template-columns: 1fr 1fr; }
    .prod-dimensiones-grid { grid-template-columns: 1fr 1fr; }
    .img-grid { grid-template-columns: 1fr 1fr; }
}
.img-slot {
    border:2px dashed var(--borde); border-radius:var(--radius-sm);
    aspect-ratio:1/1; display:flex; flex-direction:column;
    align-items:center; justify-content:center; cursor:pointer;
    overflow:hidden; transition:border-color var(--trans-fast);
    background:var(--lino2); position:relative;
}
.img-slot:hover { border-color:var(--amb); }
.img-slot img   { width:100%; height:100%; object-fit:cover; display:block; }
.img-slot-label {
    position:absolute; bottom:0; left:0; right:0;
    background:rgba(92,45,10,.5); color:#fff; font-size:.65rem;
    font-family:var(--font-body); text-align:center; padding:2px 0;
}
.img-slot-ph {
    display:flex; flex-direction:column; align-items:center;
    gap:.25rem; color:var(--g1); font-size:.74rem;
    font-family:var(--font-body); pointer-events:none;
}
</style>

<div class="pg-back">
    <a href="<?= URL ?>producto" class="btn-volver">Volver</a>
    <div>
        <h1><?= $creaProductosDirecto ? 'Agregar producto' : 'Sugerir producto' ?></h1>
        <small>Completa los datos del nuevo producto</small>
    </div>
</div>

<?= Toast::flash() ?>

<form action="<?= URL ?>producto/crear" method="POST" enctype="multipart/form-data">
<div class="row g-3">

<div class="col-lg-8">

    <div class="sp-card" style="margin-bottom:1.1rem;">

        <div class="sp-hd" style="background:linear-gradient(to right,var(--caoba),var(--caoba2));
                                   border-bottom:2.5px solid var(--dorado);">
            <span style="color:#f5e8d4;font-family:'Playfair Display',serif;
                         font-weight:600;font-size:.95rem;">Datos del producto</span>
        </div>

        <div class="sp-bd">
        <div class="prod-layout-grid">

            <div>

                <div style="margin-bottom:.75rem;">
                    <label class="form-label"
                           style="font-size:.95rem;font-weight:700;color:var(--caoba);">
                        Nombre del producto
                    </label>
                    <input type="text" name="NombredelProducto" class="form-control"
                           style="font-size:1.05rem;padding:.58rem .85rem;font-weight:500;
                                  border-color:var(--amb) !important;"
                           value="<?= htmlspecialchars($_POST['NombredelProducto'] ?? '') ?>"
                           placeholder="Ej: Mesa Comedor Roble Clasica" required>
                </div>

                <div style="margin-bottom:.75rem;">
                    <label class="form-label">
                        Descripcion <span class="opt-label">(opcional)</span>
                    </label>
                    <textarea name="Descripcion" class="form-control" rows="7"
                              style="resize:vertical;min-height:140px;"
                              placeholder="Describí características y terminaciones (ej: unas 150-250 palabras)"><?= htmlspecialchars($_POST['Descripcion'] ?? '') ?></textarea>
                </div>

                <div class="prod-atributos-grid">
                    <?php
                    $sels = [
                        ['name'=>'IdCategoria',           'label'=>'Categoria',      'opts'=>$categorias,          'blank'=>'—',                 'key'=>'IdCategoria'],
                        ['name'=>'IdTipodeProducto',       'label'=>'Tipo',           'opts'=>$tiposProducto,       'blank'=>'—',                 'key'=>'IdTipodeProducto'],
                        ['name'=>'IdTipodeDiseño',         'label'=>'Diseno',         'opts'=>$tiposDiseño,         'blank'=>'—',                 'key'=>'IdTipodeDiseño'],
                        ['name'=>'IdTipodeAcabado',        'label'=>'Acabado',        'opts'=>$tiposAcabado,        'blank'=>'—',                 'key'=>'IdTipodeAcabado'],
                        ['name'=>'IdTipodeHerraje',        'label'=>'Herraje',        'opts'=>$tiposHerraje,        'blank'=>'Sin herraje',       'key'=>'IdTipodeHerraje'],
                        ['name'=>'IdTipodeAlmacenamiento', 'label'=>'Almacenamiento', 'opts'=>$tiposAlmacenamiento, 'blank'=>'Sin almacenamiento','key'=>'IdTipodeAlmacenamiento'],
                    ];
                    foreach ($sels as $s):
                        $val = $_POST[$s['name']] ?? '';
                    ?>
                    <div>
                        <label class="form-label" style="font-size:.77rem;"><?= $s['label'] ?></label>
                        <select name="<?= $s['name'] ?>" id="<?= $s['name'] ?>" class="form-select form-select-sm">
                            <option value=""><?= $s['blank'] ?></option>
                            <?php foreach ($s['opts'] as $o): ?>
                            <option value="<?= $o['Id'] ?>" <?= $val == $o['Id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($o['Nombre']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div id="wrapColorAcabado" style="display:none; margin-top:.6rem;">
                    <label class="form-label" style="font-size:.77rem;">Color de Acabado</label>
                    <select name="IdColorAcabado" id="IdColorAcabado" class="form-select form-select-sm">
                        <option value="">—</option>
                        <?php foreach ($coloresAcabado as $c): $valColor = $_POST['IdColorAcabado'] ?? ''; ?>
                        <option value="<?= $c['Id'] ?>" data-hex="<?= htmlspecialchars($c['ColorHex']) ?>" <?= $valColor == $c['Id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['Nombre']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="background:var(--lino2);border:1.5px solid var(--borde);
                            border-radius:8px;padding:.65rem .85rem;">
                    <div style="font-size:.72rem;font-weight:700;color:var(--amb);
                                text-transform:uppercase;letter-spacing:.07em;margin-bottom:.45rem;">
                        Dimensiones (cm)
                    </div>
                    <div class="prod-dimensiones-grid">
                        <?php foreach (['Ancho','Largo','Alto'] as $d): ?>
                        <div>
                            <label class="form-label" style="font-size:.77rem;margin-bottom:.18rem;">
                                <?= $d ?>
                            </label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" min="0" name="<?= $d ?>"
                                       class="form-control"
                                       value="<?= htmlspecialchars($_POST[$d] ?? '') ?>"
                                       placeholder="0">
                                <span class="input-group-text" style="font-size:.74rem;">cm</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <div>
                <div style="background:linear-gradient(160deg,var(--lino2) 0%,var(--papel) 100%);
                            border:1.5px solid var(--borde);border-radius:10px;overflow:hidden;">

                    <div style="background:linear-gradient(to right,var(--lino3),var(--lino2));
                                padding:.48rem .85rem;border-bottom:1.5px solid var(--borde);">
                        <span style="font-family:'Playfair Display',serif;font-size:.88rem;
                                     font-weight:600;color:var(--caoba);">Precio de venta</span>
                    </div>

                    <div style="padding:.8rem .85rem;">

                        <div style="margin-bottom:.5rem;">
                            <label class="form-label" style="font-size:.77rem;">
                                Precio por hora (mano de obra)
                                <?php if (!$puedeEditarPrecioHora): ?>
                                <span style="font-weight:400;text-transform:none;color:var(--g1);">— solo Admin/Ventas</span>
                                <?php endif; ?>
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">$</span>
                                <input type="number" id="inpPrecioPorHora" name="PrecioPorHora"
                                       min="0" step="0.01"
                                       class="form-control"
                                       value="<?= htmlspecialchars($_POST['PrecioPorHora'] ?? $precioPorHora) ?>"
                                       oninput="actualizarPrecioPorHoraJS(this.value)"
                                       <?= $puedeEditarPrecioHora ? '' : 'disabled' ?>>
                                <?php if ($puedeEditarPrecioHora): ?>
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                        onclick="guardarTarifaGlobal()">Guardar</button>
                                <?php endif; ?>
                            </div>
                            <?php if ($puedeEditarPrecioHora): ?>
                            <div id="msgTarifa" style="font-size:.72rem;margin-top:.25rem;min-height:1em;"></div>
                            <small style="color:var(--g1);font-size:.7rem;display:block;">
                                Es una tarifa única para todo el catálogo. Al guardarla se recalculan
                                todos los productos ya cargados, no solo este.
                            </small>
                            <?php endif; ?>
                        </div>

                        <div style="margin-bottom:.5rem;">
                            <label class="form-label" style="font-size:.77rem;">Margen de ganancia</label>
                            <div class="input-group input-group-sm">
                                <input type="number" id="inpMargen" name="PorcentajeGanancia"
                                       min="0" max="9999" step="1"
                                       class="form-control" value="30"
                                       oninput="aplicarMargen()">
                                <span class="input-group-text">%</span>
                            </div>
                            <small style="color:var(--g1);font-size:.7rem;display:block;margin-top:.2rem;">
                                Se guarda junto con el producto (todavía no existe para guardarlo aparte).
                            </small>
                        </div>

                        <button type="button" onclick="aplicarMargen()"
                                style="width:100%;padding:.36rem;margin-bottom:.6rem;
                                       background:var(--lino3);border:1.5px solid var(--borde);
                                       border-radius:6px;font-family:'Source Sans 3',sans-serif;
                                       font-size:.81rem;font-weight:600;color:var(--caoba2);
                                       cursor:pointer;transition:background .15s;"
                                onmouseover="this.style.background='var(--borde)'"
                                onmouseout="this.style.background='var(--lino3)'">
                            Calcular precio
                        </button>

                        <label class="form-label" style="font-size:.77rem;">Precio final</label>
                        <div class="input-group">
                            <span class="input-group-text"
                                  style="font-weight:700;font-size:.95rem;
                                         background:var(--caoba) !important;
                                         color:#f5e8d4 !important;
                                         border-color:var(--caoba) !important;">$</span>
                            <input type="number" step="0.01" min="0"
                                   name="PrecioVenta" id="inpPrecioVenta"
                                   class="form-control"
                                   style="font-family:'Playfair Display',serif;
                                          font-size:1.35rem;font-weight:700;
                                          color:var(--caoba);
                                          background:var(--lino) !important;
                                          padding:.45rem .7rem !important;"
                                   value="<?= htmlspecialchars($_POST['PrecioVenta'] ?? '') ?>"
                                   placeholder="0,00" readonly>
                        </div>

                        <div style="margin-top:.4rem;font-size:.71rem;color:var(--g1);line-height:1.4;">
                            (Materiales + mano de obra) &times; (1 + margen%)
                        </div>

                    </div>
                </div>
            </div>

        </div>
        </div>

    </div>

    <div class="sp-card mt-3">
        <div class="sp-hd sp-hd-toggle" onclick="toggleSec('secCostos','chevCostos')">
            <span>Costos de fabricacion</span>
            <span id="chevCostos" class="chev open">▼</span>
        </div>
        <div id="secCostos">
        <div class="sp-bd">

            <div id="barraTotal">
                <span>Tiempo: <b id="btTiempo">0 hs</b></span>
                <span class="sep">|</span>
                <span>Materiales: <b id="btMats">$0</b></span>
                <span class="sep">|</span>
                <span>Mano de obra: <b id="btManoObra">$0</b></span>
                <span class="sep">|</span>
                <span>Costo total: <b id="btTotal">$0</b></span>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-sm-5">
                    <label class="form-label">Tiempo estimado de fabricacion</label>
                    <div class="input-group">
                        <input type="number" step="0.5" min="0" name="TiempoFabricacionHoras"
                               id="inpTiempo" class="form-control"
                               value="<?= htmlspecialchars($_POST['TiempoFabricacionHoras'] ?? '') ?>"
                               placeholder="0" oninput="recalc()">
                        <span class="input-group-text">horas</span>
                    </div>
                </div>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.55rem;">
                <label class="form-label mb-0">Materiales utilizados</label>
                <span id="contador" class="badge-cnt">0 items</span>
            </div>

            <div id="emptyState" class="empty-st">
                <span class="ei">—</span>
                Todavia no agregaste materiales.
            </div>

            <div id="wrapTabla" style="display:none;overflow-x:auto;">
            <table class="tm">
                <thead><tr>
                    <th>Material</th>
                    <th style="width:100px">Cantidad</th>
                    <th style="width:150px">Precio unit. (stock)</th>
                    <th style="width:115px">Subtotal</th>
                    <th style="width:34px"></th>
                </tr></thead>
                <tbody id="bodyMats"></tbody>
                <tfoot><tr>
                    <td colspan="3" style="text-align:right;color:var(--g1);font-size:.85rem;">Total materiales</td>
                    <td id="tfTotal">$0</td>
                    <td></td>
                </tr></tfoot>
            </table>
            </div>

            <div style="border:1.5px solid var(--borde);border-radius:var(--radius-sm);
                        padding:.75rem;margin-top:.75rem;background:var(--lino2);">
                <div style="display:flex;align-items:flex-end;gap:.5rem;margin-bottom:.6rem;flex-wrap:wrap;">
                    <div style="flex:1;min-width:180px;">
                        <label style="font-size:.78rem;color:var(--g1);margin-bottom:.2rem;display:block;">Madera</label>
                        <select id="selMadera" class="form-select form-select-sm">
                            <option value="">Seleccionar madera</option>
                            <?php foreach ($maderas as $m): ?>
                            <option value="m_<?= $m['Id'] ?>"
                                    data-tipo="madera"
                                    data-id="<?= $m['Id'] ?>"
                                    data-nombre="<?= htmlspecialchars($m['Nombre']) ?>"
                                    data-precio="<?= (float)($m['PrecioUnitario'] ?? 0) ?>"
                                    data-stock="<?= (float)($m['StockActual'] ?? 0) ?>">
                                <?= htmlspecialchars($m['Nombre']) ?>
                                — Stock: <?= number_format($m['StockActual'] ?? 0) ?> u.
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="width:90px;">
                        <label style="font-size:.78rem;color:var(--g1);margin-bottom:.2rem;display:block;">Cantidad</label>
                        <input type="number" id="cantMadera" step="0.01" min="0.01"
                               class="form-control form-control-sm" value="1">
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary"
                            onclick="agregarDesde('madera')" style="white-space:nowrap;">
                        Agregar madera
                    </button>
                </div>
                <div style="display:flex;align-items:flex-end;gap:.5rem;flex-wrap:wrap;">
                    <div style="flex:1;min-width:180px;">
                        <label style="font-size:.78rem;color:var(--g1);margin-bottom:.2rem;display:block;">Insumo</label>
                        <select id="selInsumo" class="form-select form-select-sm">
                            <option value="">Seleccionar insumo</option>
                            <?php foreach ($insumos as $ins): ?>
                            <option value="i_<?= $ins['Id'] ?>"
                                    data-tipo="insumo"
                                    data-id="<?= $ins['Id'] ?>"
                                    data-nombre="<?= htmlspecialchars($ins['Nombre']) ?><?= !empty($ins['TipoMaterial']) ? ' (' . htmlspecialchars($ins['TipoMaterial']) . ')' : '' ?>"
                                    data-precio="<?= (float)($ins['PrecioUnitario'] ?? 0) ?>"
                                    data-stock="<?= (float)($ins['StockActual'] ?? 0) ?>">
                                <?= htmlspecialchars($ins['Nombre']) ?>
                                <?= !empty($ins['TipoMaterial']) ? '(' . htmlspecialchars($ins['TipoMaterial']) . ')' : '' ?>
                                — Stock: <?= number_format($ins['StockActual'] ?? 0) ?> u.
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="width:90px;">
                        <label style="font-size:.78rem;color:var(--g1);margin-bottom:.2rem;display:block;">Cantidad</label>
                        <input type="number" id="cantInsumo" step="0.01" min="0.01"
                               class="form-control form-control-sm" value="1">
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="agregarDesde('insumo')" style="white-space:nowrap;">
                        Agregar insumo
                    </button>
                </div>
            </div>

        </div>
        </div>
    </div>

</div>

<div class="col-lg-4">
    <div class="sp-card" style="position:sticky;top:1rem;">
        <div class="sp-hd sp-hd-toggle" onclick="toggleSec('secImagenes','chevImg')">
            <span>Imagenes del producto</span>
            <span id="chevImg" class="chev">▼</span>
        </div>
        <div id="secImagenes" style="display:none;">
        <div class="sp-bd">

            <div class="img-grid">
                <div id="prev0" class="img-slot" onclick="document.getElementById('img0').click()">
                    <div class="img-slot-ph">
                        <span style="font-size:1.2rem;opacity:.35;">+</span>
                        <span>Principal</span>
                    </div>
                    <span class="img-slot-label">Principal *</span>
                </div>
                <input type="file" id="img0" name="imagen_principal"
                       accept="image/jpeg,image/png,image/webp,image/gif"
                       class="d-none" required onchange="prevImg(this,'prev0','Principal *')">

                <?php for ($i = 1; $i <= 3; $i++): ?>
                <div id="prev<?= $i ?>" class="img-slot"
                     onclick="document.getElementById('imgx<?= $i ?>').click()">
                    <div class="img-slot-ph">
                        <span style="font-size:1.2rem;opacity:.35;">+</span>
                        <span>Adicional <?= $i ?></span>
                    </div>
                    <span class="img-slot-label">Adicional <?= $i ?></span>
                </div>
                <input type="file" id="imgx<?= $i ?>" name="imagen_extra_<?= $i ?>"
                       accept="image/jpeg,image/png,image/webp,image/gif"
                       class="d-none" onchange="prevImg(this,'prev<?= $i ?>','Adicional <?= $i ?>')">
                <?php endfor; ?>
            </div>

            <small style="color:var(--g1);display:block;margin-top:.6rem;font-size:.74rem;">
                JPG, PNG, WebP — maximo 5 MB por imagen
            </small>

        </div>
        </div>
    </div>
</div>

</div>

<div class="form-footer">
    <a href="<?= URL ?>producto" class="btn-volver">Cancelar</a>
    <button type="submit" class="btn-guardar"><?= $creaProductosDirecto ? 'Guardar producto' : 'Enviar sugerencia' ?></button>
    <?php if (!$creaProductosDirecto): ?>
    <p style="font-size:.78rem;color:var(--g1);margin-top:.5rem;">
        Este producto quedará <strong>pendiente de aprobación</strong> — no aparece en el catálogo público hasta que Admin, Gerente o Ventas lo aprueben.
    </p>
    <?php endif; ?>
</div>
</form>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
<?php include INCLUDES . 'admin' . DS . 'scripts_admin.php'; ?>

<script>
let filas = [], uid = 0;
let precioPorHora = <?= json_encode((float)$precioPorHora) ?>;

function actualizarPrecioPorHoraJS(valor) {
    precioPorHora = parseFloat(valor) || 0;
    recalc();
}

function guardarTarifaGlobal() {
    var valor = document.getElementById('inpPrecioPorHora').value;
    var msg = document.getElementById('msgTarifa');
    if (!msg) return;
    msg.style.color = 'var(--g1)';
    msg.textContent = 'Guardando...';

    fetch('<?= URL ?>producto/guardarPrecioPorHora', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'PrecioPorHora=' + encodeURIComponent(valor)
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.ok) {
            precioPorHora = data.precioPorHora;
            recalc();
            msg.style.color = '#2e7d32';
            msg.textContent = 'Tarifa guardada — se actualizaron ' + data.actualizados + ' producto(s) del catálogo.';
        } else {
            msg.style.color = '#c0392b';
            msg.textContent = data.error || 'No se pudo guardar.';
        }
    })
    .catch(function() {
        msg.style.color = '#c0392b';
        msg.textContent = 'Error de conexión.';
    });
}

function toggleSec(secId, chevId) {
    var s = document.getElementById(secId);
    var c = document.getElementById(chevId);
    var open = s.style.display !== 'none';
    s.style.display = open ? 'none' : 'block';
    if (c) c.classList.toggle('open', !open);
}

function agregarDesde(tipo) {
    var selId  = tipo === 'madera' ? 'selMadera'  : 'selInsumo';
    var cantId = tipo === 'madera' ? 'cantMadera' : 'cantInsumo';
    var sel    = document.getElementById(selId);
    var opt    = sel.options[sel.selectedIndex];
    if (!opt || !opt.value) { sel.focus(); return; }
    var cant   = parseFloat(document.getElementById(cantId).value) || 1;
    var precio = parseFloat(opt.dataset.precio) || 0;
    filas.push({
        uid: uid++, tipo: opt.dataset.tipo,
        id: parseInt(opt.dataset.id),
        nombre: opt.dataset.nombre,
        precio: precio, cant: cant,
    });
    renderTabla();
    sel.value = '';
    document.getElementById(cantId).value = '1';
}

function renderTabla() {
    var tbody = document.getElementById('bodyMats');
    var wrap  = document.getElementById('wrapTabla');
    var empty = document.getElementById('emptyState');
    var cnt   = document.getElementById('contador');
    tbody.innerHTML = '';
    cnt.textContent = filas.length + (filas.length === 1 ? ' item' : ' items');

    if (!filas.length) {
        wrap.style.display  = 'none';
        empty.style.display = 'block';
        recalc(); return;
    }
    wrap.style.display  = 'block';
    empty.style.display = 'none';

    filas.forEach(function(f) {
        var pfx = f.tipo === 'madera' ? 'madera' : 'insumo';
        var tr  = document.createElement('tr');
        tr.innerHTML =
          '<td style="font-weight:600;color:var(--tinta);">' + f.nombre + '</td>' +
          '<td>' +
            '<input type="number" step="0.01" min="0.01" name="' + pfx + '_cantidad[]"' +
            '       value="' + f.cant + '" class="form-control form-control-sm" style="width:82px;"' +
            '       oninput="cambiarCant(' + f.uid + ',this)">' +
            '<input type="hidden" name="' + pfx + '_id[]"    value="' + f.id + '">' +
            '<input type="hidden" name="' + pfx + '_costo[]" value="' + f.precio.toFixed(2) + '">' +
            '<input type="hidden" name="' + pfx + '_obs[]"   value="">' +
          '</td>' +
          '<td>$' + f.precio.toFixed(2) + '</td>' +
          '<td id="sub' + f.uid + '" style="font-weight:700;color:var(--caoba);font-family:var(--font-display);">' +
            fmt(f.cant * f.precio) +
          '</td>' +
          '<td><button type="button" class="btn-del-f" onclick="quitarFila(' + f.uid + ')">x</button></td>';
        tbody.appendChild(tr);
    });
    recalc();
}

function cambiarCant(u, inp) {
    var f = filas.find(function(x) { return x.uid === u; });
    if (!f) return;
    f.cant = parseFloat(inp.value) || 0;
    var el = document.getElementById('sub' + f.uid);
    if (el) el.textContent = fmt(f.cant * f.precio);
    recalc();
}

function quitarFila(u) {
    filas = filas.filter(function(x) { return x.uid !== u; });
    renderTabla();
}

function recalc() {
    var tot = filas.reduce(function(a, f) { return a + f.cant * f.precio; }, 0);
    var t   = parseFloat(document.getElementById('inpTiempo').value) || 0;
    var manoObra = t * precioPorHora;
    document.getElementById('tfTotal').textContent  = fmt(tot);
    document.getElementById('btMats').textContent   = fmt(tot);
    document.getElementById('btManoObra').textContent = fmt(manoObra);
    document.getElementById('btTiempo').textContent = t + ' hs';
    document.getElementById('btTotal').textContent  = fmt(tot + manoObra);
    aplicarMargen();
}

function aplicarMargen() {
    var tot    = filas.reduce(function(a, f) { return a + f.cant * f.precio; }, 0);
    var t      = parseFloat(document.getElementById('inpTiempo').value) || 0;
    var manoObra = t * precioPorHora;
    var base   = tot + manoObra;
    var margen = parseFloat(document.getElementById('inpMargen').value) || 0;
    var precio = base > 0 ? base * (1 + margen / 100) : 0;
    document.getElementById('inpPrecioVenta').value = precio > 0 ? precio.toFixed(2) : '';
}

function fmt(n) {
    return '$' + Number(n).toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function prevImg(input, id, label) {
    var box = document.getElementById(id);
    if (input.files && input.files[0]) {
        var r = new FileReader();
        r.onload = function(e) {
            box.innerHTML =
                '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;display:block;">' +
                '<span class="img-slot-label">' + label + '</span>';
        };
        r.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    renderTabla();
    document.getElementById('cantMadera').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); agregarDesde('madera'); }
    });
    document.getElementById('cantInsumo').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); agregarDesde('insumo'); }
    });

    document.getElementById('IdTipodeProducto').addEventListener('change', function() {
        filtrarAtributosPorTipo();
    });
    if (document.getElementById('IdTipodeProducto').value) {
        filtrarAtributosPorTipo();
    }
});
var ACABADOS_CON_COLOR = ['2', '9', '10'];
function actualizarColorAcabado() {
    var idAcabado = document.getElementById('IdTipodeAcabado').value;
    var wrap = document.getElementById('wrapColorAcabado');
    var sel  = document.getElementById('IdColorAcabado');
    if (ACABADOS_CON_COLOR.includes(idAcabado)) {
        wrap.style.display = 'block';
    } else {
        wrap.style.display = 'none';
        sel.value = ''; 
    }
}
document.getElementById('IdTipodeAcabado').addEventListener('change', actualizarColorAcabado);
document.addEventListener('DOMContentLoaded', actualizarColorAcabado);

function filtrarAtributosPorTipo() {
    var idTipo = document.getElementById('IdTipodeProducto').value;

    var selDiseño = document.getElementById('IdTipodeDiseño');
    var selAcabado = document.getElementById('IdTipodeAcabado');
    var selHerraje = document.getElementById('IdTipodeHerraje');
    var selAlmacen = document.getElementById('IdTipodeAlmacenamiento');
    var actual = {
        diseño: selDiseño.value,
        acabado: selAcabado.value,
        herraje: selHerraje.value,
        almacenamiento: selAlmacen.value
    };

    if (!idTipo) {
        [selDiseño, selAcabado, selHerraje, selAlmacen].forEach(function(s) {
            s.innerHTML = '<option value="">—</option>';
        });
        return;
    }

    fetch('<?= URL ?>producto/atributosPorTipo/' + idTipo)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            llenarSelectAtributo(selDiseño, data['diseños'] || [], '—', actual.diseño);
            llenarSelectAtributo(selAcabado, data['acabados'] || [], '—', actual.acabado);
            llenarSelectAtributo(selHerraje, data['herrajes'] || [], 'Sin herraje', actual.herraje);
            llenarSelectAtributo(selAlmacen, data['almacenamientos'] || [], 'Sin almacenamiento', actual.almacenamiento);
        })
        .catch(function() {
        });
}

function llenarSelectAtributo(select, opciones, blankLabel, valorPrevio) {
    var html = '<option value="">' + blankLabel + '</option>';
    var existeValorPrevio = false;
    opciones.forEach(function(o) {
        var esSel = (String(o.Id) === String(valorPrevio));
        if (esSel) existeValorPrevio = true;
        html += '<option value="' + o.Id + '"' + (esSel ? ' selected' : '') + '>' +
                escapeHtml(o.Nombre) + '</option>';
    });
    select.innerHTML = html;
    if (!existeValorPrevio) select.value = '';
}

function escapeHtml(str) {
    var div = document.createElement('div');
    div.textContent = str == null ? '' : String(str);
    return div.innerHTML;
}
</script>