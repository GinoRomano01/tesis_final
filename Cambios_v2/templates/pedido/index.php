<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<main class="content">

<style>
.pd-page { font-family: var(--font-body); color: var(--tinta); }

.pd-hd {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .75rem; margin-bottom: 1.2rem;
}
.pd-hd h1 {
    font-family: var(--font-display); font-size: 1.6rem;
    font-weight: 700; color: var(--caoba); margin: 0;
}

.badges-row { display: flex; gap: .6rem; flex-wrap: wrap; margin-bottom: 1.2rem; }
.pd-badge {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .32rem .9rem; border-radius: var(--radius-pill);
    font-size: .8rem; font-weight: 700; border: 1.5px solid transparent;
    text-decoration: none; transition: all var(--trans-fast);
    font-family: var(--font-body);
}
.pd-badge.all       { background: var(--lino2); color: var(--tinta2); border-color: var(--borde); }
.pd-badge.all.active,
.pd-badge.all:hover { background: var(--caoba); color: #fff; border-color: var(--caoba); }
.pd-badge.pendiente { background: #fff8e1; color: var(--amarillo); border-color: #ffe082; }
.pd-badge.pendiente.active,
.pd-badge.pendiente:hover { background: var(--amarillo); color: #fff; }
.pd-badge.produccion { background: #e3f2fd; color: var(--azul); border-color: #90caf9; }
.pd-badge.produccion.active,
.pd-badge.produccion:hover { background: var(--azul); color: #fff; }
.pd-badge.listo { background: #f3e5f5; color: var(--purpura); border-color: #ce93d8; }
.pd-badge.listo.active,
.pd-badge.listo:hover { background: var(--purpura); color: #fff; }
.pd-badge.entregado { background: #e8f5e9; color: var(--verde2); border-color: #a5d6a7; }
.pd-badge.entregado.active,
.pd-badge.entregado:hover { background: var(--verde2); color: #fff; }
.pd-badge.cancelado { background: #fdf0f0; color: var(--rojo2); border-color: #f5c6c6; }
.pd-badge.cancelado.active,
.pd-badge.cancelado:hover { background: var(--rojo2); color: #fff; }
.badge-count { background: rgba(0,0,0,.12); border-radius: 10px; padding: 1px 7px; font-size: .73rem; }

.pd-aviso {
    display: flex; align-items: center; gap: .6rem;
    background: #fff8e1; border: 1.5px solid #ffe082;
    border-radius: var(--radius-md); padding: .65rem 1rem;
    margin-bottom: 1.1rem; font-size: .86rem; color: #5f4200;
    font-family: var(--font-body);
}

.pd-filters {
    display: flex; gap: .5rem; flex-wrap: wrap;
    margin-bottom: 1.2rem; align-items: center;
}
.pd-filters input {
    flex: 1; min-width: 220px;
    background: var(--papel); border: 1.5px solid var(--borde);
    border-radius: var(--radius-sm); padding: .46rem 1rem;
    font-family: var(--font-body); font-size: .9rem; color: var(--tinta);
    transition: border-color var(--trans-fast);
}
.pd-filters input:focus {
    outline: none; border-color: var(--amb);
    box-shadow: 0 0 0 3px rgba(184,114,42,.1);
}

.btn-pd {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .46rem 1rem; border-radius: var(--radius-sm);
    font-size: .86rem; font-weight: 600; font-family: var(--font-body);
    text-decoration: none; border: none; cursor: pointer;
    transition: background var(--trans-fast), transform var(--trans-fast);
}
.btn-pd:hover { transform: translateY(-1px); }
.btn-pd-primary   { background: var(--caoba); color: #fff; }
.btn-pd-primary:hover { background: var(--caoba2); color: #fff; }
.btn-pd-secondary { background: var(--lino2); color: var(--caoba); border: 1.5px solid var(--borde); }
.btn-pd-secondary:hover { background: var(--borde); }
.btn-pd-danger    { background: #fdf0f0; color: var(--rojo2); border: 1.5px solid #f5c6c6; }
.btn-pd-danger:hover { background: #fad7d7; }
.btn-pd-sm { padding: .26rem .6rem; font-size: .78rem; }

.pd-table-wrap {
    background: var(--papel); border: 1.5px solid var(--borde);
    border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm);
}
.pd-table { width: 100%; border-collapse: collapse; }
.pd-table thead tr { background: linear-gradient(to right, var(--caoba), var(--caoba2)); }
.pd-table thead th {
    padding: .72rem 1rem; text-align: left;
    font-size: .74rem; font-weight: 700; letter-spacing: .06em;
    color: rgba(255,255,255,.9); text-transform: uppercase;
    border-right: 1px solid rgba(255,255,255,.12);
}
.pd-table thead th:last-child { border-right: none; }
.pd-table tbody tr { border-bottom: 1px solid var(--borde2); transition: background var(--trans-fast); }
.pd-table tbody tr:last-child { border-bottom: none; }
.pd-table tbody tr:hover { background: var(--lino); }
.pd-table tbody td {
    padding: .65rem 1rem; font-size: .86rem; color: var(--tinta2);
    border-right: 1px solid var(--borde2); vertical-align: middle;
}
.pd-table tbody td:last-child { border-right: none; }

.td-main  { font-weight: 700; color: var(--caoba); }
.td-tag   { display: inline-block; padding: 2px 9px; border-radius: var(--radius-pill); font-size: .72rem; font-weight: 700; background: var(--lino2); color: var(--tinta2); border: 1px solid var(--borde); }
.td-fecha { font-size: .8rem; color: var(--g1); }
.td-acc   { display: flex; gap: .3rem; flex-wrap: wrap; }

.pill { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: var(--radius-pill); font-size: .73rem; font-weight: 700; font-family: var(--font-body); }
.pill-Pendiente    { background: #fff8e1; color: var(--amarillo); border: 1px solid #ffe082; }
.pill-Enproduccion { background: #e3f2fd; color: var(--azul);     border: 1px solid #90caf9; }
.pill-Listo        { background: #f3e5f5; color: var(--purpura);  border: 1px solid #ce93d8; }
.pill-Entregado    { background: #e8f5e9; color: var(--verde2);   border: 1px solid #a5d6a7; }
.pill-Cancelado    { background: #fdf0f0; color: var(--rojo2);    border: 1px solid #f5c6c6; }

.pd-vacio { text-align: center; padding: 3.5rem 1rem; color: var(--g1); }
.pd-vacio span { font-size: 2.5rem; opacity: .2; display: block; margin-bottom: .6rem; }

.modal-bg { display: none; position: fixed; inset: 0; background: rgba(44,26,14,.48); z-index: 1000; align-items: center; justify-content: center; padding: 1rem; overflow-y: auto; }
.modal-bg.open { display: flex; }
.modal { background: var(--papel); border-radius: var(--radius-lg); width: 100%; max-width: 520px; box-shadow: 0 8px 40px rgba(0,0,0,.28); overflow: hidden; animation: mIn .2s ease; margin: auto; }
@keyframes mIn { from { opacity:0; transform:translateY(-14px); } to { opacity:1; transform:translateY(0); } }
.modal-hd { background: linear-gradient(to right, var(--caoba), var(--caoba2)); padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; }
.modal-hd h3 { font-family: var(--font-display); font-size: 1.05rem; font-weight: 700; color: #fff; margin: 0; }
.modal-close { background: none; border: none; color: rgba(255,255,255,.7); font-size: 1.3rem; cursor: pointer; padding: 0; line-height: 1; transition: color var(--trans-fast); }
.modal-close:hover { color: #fff; }
.modal-body { padding: 1.25rem; max-height: 75vh; overflow-y: auto; }
.modal-ft { padding: .9rem 1.25rem; border-top: 1px solid var(--borde2); background: var(--lino); display: flex; gap: .5rem; justify-content: flex-end; }

.fld { margin-bottom: .95rem; }
.fld label { display: block; margin-bottom: .28rem; font-size: .77rem; font-weight: 700; color: var(--tinta2); text-transform: uppercase; letter-spacing: .03em; }
.fld input,
.fld select {
    width: 100%; background: var(--lino); border: 1.5px solid var(--borde);
    border-radius: var(--radius-sm); padding: .48rem .85rem;
    font-family: var(--font-body); font-size: .9rem; color: var(--tinta);
    transition: border-color var(--trans-fast);
}
.fld input:focus,
.fld select:focus { outline: none; border-color: var(--amb); background: #fff; box-shadow: 0 0 0 3px rgba(184,114,42,.1); }

.modal-info { background: var(--lino2); border: 1px solid var(--borde); border-radius: var(--radius-sm); padding: .7rem 1rem; margin-bottom: 1rem; font-size: .84rem; color: var(--tinta2); }
.modal-info strong { color: var(--caoba); }
.modal-divider { font-size: .73rem; text-transform: uppercase; letter-spacing: .07em; color: var(--g1); font-weight: 700; margin: .1rem 0 .75rem; border-bottom: 1px solid var(--borde2); padding-bottom: .3rem; }
</style>

<?php
$clsBadge = function(string $e): string {
    return match($e) {
        'Pendiente'     => 'pendiente',
        'En producción' => 'produccion',
        'Listo'         => 'listo',
        'Entregado'     => 'entregado',
        'Cancelado'     => 'cancelado',
        default         => 'all',
    };
};
$clsPill = fn(string $e) => 'pill-' . str_replace([' ', 'ó', 'ú'], ['', 'o', 'u'], $e);
$totalGen = array_sum($conteos);
?>

<div class="pd-page">

    <div class="pd-hd">
        <h1>Pedidos</h1>
        <button class="btn-pd btn-pd-primary" onclick="abrirCrear()">Nuevo pedido</button>
    </div>

    <?php if (!empty($ventasElegibles)): ?>
    <div class="pd-aviso">
        Hay <strong><?= count($ventasElegibles) ?></strong>
        venta<?= count($ventasElegibles) > 1 ? 's' : '' ?> disponible<?= count($ventasElegibles) > 1 ? 's' : '' ?>
        para convertir en pedido.
    </div>
    <?php endif; ?>

    <div class="badges-row">
        <a href="<?= URL ?>pedido<?= !empty($buscar) ? '?buscar='.urlencode($buscar) : '' ?>"
           class="pd-badge all <?= $estado === '' ? 'active' : '' ?>">
            Todos <span class="badge-count"><?= $totalGen ?></span>
        </a>
        <?php foreach ($estados as $est):
            $cls   = $clsBadge($est);
            $count = $conteos[$est] ?? 0;
            $href  = URL . 'pedido?estado=' . urlencode($est) . (!empty($buscar) ? '&buscar='.urlencode($buscar) : '');
        ?>
        <a href="<?= $href ?>"
           class="pd-badge <?= $cls ?> <?= $estado === $est ? 'active' : '' ?>">
            <?= htmlspecialchars($est) ?>
            <span class="badge-count"><?= $count ?></span>
        </a>
        <?php endforeach; ?>
    </div>

    <form class="pd-filters" method="GET" action="<?= URL ?>pedido">
        <?php if ($estado !== ''): ?>
        <input type="hidden" name="estado" value="<?= htmlspecialchars($estado) ?>">
        <?php endif; ?>
        <input type="text" name="buscar"
               value="<?= htmlspecialchars($buscar) ?>"
               placeholder="Buscar por cliente, producto, responsable o numero de venta">
        <button type="submit" class="btn-pd btn-pd-primary">Buscar</button>
        <?php if (!empty($buscar) || $estado !== ''): ?>
        <a href="<?= URL ?>pedido" class="btn-pd btn-pd-secondary">Limpiar</a>
        <?php endif; ?>
    </form>

    <div class="pd-table-wrap">
        <table class="pd-table">
            <thead>
                <tr>
                    <th>Venta</th>
                    <th>Cliente</th>
                    <th>Productos</th>
                    <th>Estado</th>
                    <th>Responsable</th>
                    <th>Fecha venta</th>
                    <th>Entrega</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($pedidos)): ?>
                <tr>
                    <td colspan="8">
                        <div class="pd-vacio">
                            <span>—</span>
                            <p>No hay pedidos<?= !empty($buscar) ? ' para "'.htmlspecialchars($buscar).'"' : ($estado !== '' ? ' con estado "'.htmlspecialchars($estado).'"' : '') ?>.</p>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($pedidos as $p):
                    $pillCls = $clsPill($p['Estado'] ?? '');
                    $dias    = floor(($p['HorasDesdeVenta'] ?? 0) / 24);
                ?>
                <tr>
                    <td>
                        <span class="td-tag"><?= $p['NumerodeVenta'] ?></span>
                        <div style="font-size:.75rem;color:var(--g1);margin-top:2px;">
                            $<?= number_format($p['MontoTotal'] ?? 0, 0, ',', '.') ?>
                        </div>
                    </td>

                    <td class="td-main">
                        <?= htmlspecialchars($p['ClienteNombre'] . ' ' . $p['ClienteApellido']) ?>
                        <?php if ($p['ClienteTelefono']): ?>
                        <div style="font-size:.75rem;font-weight:400;color:var(--g1);">
                            <?= htmlspecialchars($p['ClienteTelefono']) ?>
                        </div>
                        <?php endif; ?>
                    </td>

                    <td style="max-width:180px;font-size:.83rem;">
                        <?= htmlspecialchars($p['Productos'] ?? '—') ?>
                    </td>

                    <td>
                        <span class="pill <?= $pillCls ?>">
                            <?= htmlspecialchars($p['Estado'] ?? '—') ?>
                        </span>
                    </td>

                    <td style="font-size:.83rem;">
                        <?php if (!empty($p['Responsable'])): ?>
                            <?= htmlspecialchars($p['Responsable']) ?>
                        <?php else: ?>
                            <span style="color:var(--g1);">—</span>
                        <?php endif; ?>
                    </td>

                    <td class="td-fecha">
                        <?= !empty($p['FechadeEmision']) ? date('d/m/Y', strtotime($p['FechadeEmision'])) : '—' ?>
                        <div style="font-size:.73rem;color:var(--g1);">
                            <?= $dias ?> dia<?= $dias !== 1 ? 's' : '' ?> atras
                        </div>
                    </td>

                    <td style="font-size:.82rem;">
                        <?php if (!empty($p['CodigoEntrega'])): ?>
                        <span style="font-family:monospace;background:var(--lino2);padding:2px 6px;border-radius:var(--radius-sm);border:1px solid var(--borde);">
                            <?= htmlspecialchars($p['CodigoEntrega']) ?>
                        </span>
                        <?php if (!empty($p['EstadoEntrega'])): ?>
                        <div style="font-size:.73rem;color:var(--g1);margin-top:2px;">
                            <?= htmlspecialchars($p['EstadoEntrega']) ?>
                        </div>
                        <?php endif; ?>
                        <?php else: ?>
                        <span style="color:var(--g1);">—</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <div class="td-acc">
                            <button class="btn-pd btn-pd-secondary btn-pd-sm"
                                    onclick="abrirEditar(<?= htmlspecialchars(json_encode($p)) ?>)">
                                Editar
                            </button>
                            <a href="<?= URL ?>pedido/baja/<?= $p['Id'] ?>"
                               class="btn-pd btn-pd-danger btn-pd-sm"
                               onclick="return confirm('Dar de baja el pedido?')">
                                Dar de baja
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>


<div class="modal-bg" id="modalCrear">
    <div class="modal">
        <div class="modal-hd">
            <h3>Nuevo pedido</h3>
            <button class="modal-close" onclick="cerrarCrear()">✕</button>
        </div>
        <form method="POST" action="<?= URL ?>pedido/guardar">
            <input type="hidden" name="Id" value="0">
            <div class="modal-body">
                <?php if (empty($ventasElegibles)): ?>
                <div style="text-align:center;padding:2rem;color:var(--g1);font-size:.9rem;">
                    No hay ventas aprobadas disponibles para asignar como pedido.
                </div>
                <?php else: ?>
                <div class="fld">
                    <label>Venta</label>
                    <select name="IdVenta" id="fVenta" required onchange="mostrarInfoVenta()">
                        <option value="">Seleccionar una venta</option>
                        <?php foreach ($ventasElegibles as $v):
                            $horas = (int)$v['HorasTranscurridas'];
                            $dias  = floor($horas / 24);
                            $label = $dias > 0 ? $dias . ' dia' . ($dias !== 1 ? 's' : '') : $horas . 'hs';
                        ?>
                        <option value="<?= $v['Id'] ?>"
                                data-cliente="<?= htmlspecialchars($v['ClienteNombre'] . ' ' . $v['ClienteApellido']) ?>"
                                data-productos="<?= htmlspecialchars($v['Productos'] ?? '—') ?>"
                                data-monto="<?= number_format($v['MontoTotal'], 0, ',', '.') ?>"
                                data-dias="<?= $label ?>">
                            <?= $v['NumerodeVenta'] ?> — <?= htmlspecialchars($v['ClienteNombre'] . ' ' . $v['ClienteApellido']) ?> (hace <?= $label ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="modal-info" id="infoVenta" style="display:none;">
                    <strong>Cliente:</strong> <span id="iCliente">—</span><br>
                    <strong>Productos:</strong> <span id="iProductos">—</span><br>
                    <strong>Total:</strong> $<span id="iMonto">—</span>
                    &nbsp;·&nbsp;
                    <strong>Desde la venta:</strong> <span id="iDias">—</span>
                </div>
                <div class="fld">
                    <label>Estado inicial</label>
                    <select name="Estado" required>
                        <?php foreach ($estados as $est): ?>
                        <option value="<?= htmlspecialchars($est) ?>"
                            <?= $est === 'Pendiente' ? 'selected' : '' ?>>
                            <?= htmlspecialchars($est) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="fld">
                    <label>Responsable</label>
                    <input type="text" name="Responsable" maxlength="50"
                           placeholder="Nombre del responsable (opcional)">
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-ft">
                <button type="button" class="btn-pd btn-pd-secondary" onclick="cerrarCrear()">Cancelar</button>
                <?php if (!empty($ventasElegibles)): ?>
                <button type="submit" class="btn-pd btn-pd-primary">Crear pedido</button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>


<div class="modal-bg" id="modalEditar">
    <div class="modal">
        <div class="modal-hd">
            <h3 id="editTitulo">Editar pedido</h3>
            <button class="modal-close" onclick="cerrarEditar()">✕</button>
        </div>
        <form method="POST" action="<?= URL ?>pedido/guardar">
            <input type="hidden" name="Id"      id="eId">
            <input type="hidden" name="IdVenta" id="eIdVenta">
            <div class="modal-body">
                <div class="modal-info" id="editInfo"></div>
                <div class="fld">
                    <label>Estado</label>
                    <select name="Estado" id="eEstado" required>
                        <?php foreach ($estados as $est): ?>
                        <option value="<?= htmlspecialchars($est) ?>"><?= htmlspecialchars($est) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="fld">
                    <label>Responsable</label>
                    <input type="text" name="Responsable" id="eResponsable"
                           maxlength="50" placeholder="Nombre del responsable">
                </div>
            </div>
            <div class="modal-ft">
                <button type="button" class="btn-pd btn-pd-secondary" onclick="cerrarEditar()">Cancelar</button>
                <button type="submit" class="btn-pd btn-pd-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirCrear() {
    var info = document.getElementById('infoVenta');
    if (info) info.style.display = 'none';
    document.getElementById('modalCrear').classList.add('open');
}
function cerrarCrear() { document.getElementById('modalCrear').classList.remove('open'); }

function mostrarInfoVenta() {
    var sel  = document.getElementById('fVenta');
    var opt  = sel.options[sel.selectedIndex];
    var info = document.getElementById('infoVenta');
    if (!sel.value) { info.style.display = 'none'; return; }
    document.getElementById('iCliente').textContent   = opt.dataset.cliente   ?? '—';
    document.getElementById('iProductos').textContent = opt.dataset.productos  ?? '—';
    document.getElementById('iMonto').textContent     = opt.dataset.monto      ?? '—';
    document.getElementById('iDias').textContent      = opt.dataset.dias       ?? '—';
    info.style.display = 'block';
}

function abrirEditar(p) {
    document.getElementById('eId').value          = p.Id;
    document.getElementById('eIdVenta').value     = p.IdVenta;
    document.getElementById('eEstado').value      = p.Estado ?? 'Pendiente';
    document.getElementById('eResponsable').value = p.Responsable ?? '';
    document.getElementById('editInfo').innerHTML =
        '<strong>Venta:</strong> ' + p.NumerodeVenta +
        ' &nbsp;&middot;&nbsp; <strong>Cliente:</strong> ' +
        (p.ClienteNombre ?? '') + ' ' + (p.ClienteApellido ?? '') +
        '<br><strong>Productos:</strong> ' + (p.Productos ?? '—');
    document.getElementById('modalEditar').classList.add('open');
}
function cerrarEditar() { document.getElementById('modalEditar').classList.remove('open'); }

['modalCrear', 'modalEditar'].forEach(function (id) {
    document.getElementById(id).addEventListener('click', function (e) {
        if (e.target === this) this.classList.remove('open');
    });
});
</script>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
<?php include INCLUDES . 'admin' . DS . 'modals_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'scripts_admin.php'; ?>