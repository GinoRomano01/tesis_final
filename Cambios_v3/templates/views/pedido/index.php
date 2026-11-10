<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script></style>
<main class="content">

<style>
.eg-page {
    font-family: var(--font-body, system-ui, -apple-system, sans-serif);
    color: var(--tinta, #2c1a0e);
    padding: 1.5rem;
}

.eg-hd {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: .75rem;
    margin-bottom: 1.2rem;
}
.eg-hd h1 {
    font-family: var(--font-display, inherit);
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--caoba, #6b2d14);
    margin: 0;
}

.badges-row {
    display: flex;
    gap: .6rem;
    flex-wrap: wrap;
    margin-bottom: 1.2rem;
}
.eg-badge {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .32rem .9rem;
    border-radius: var(--radius-pill, 50px);
    font-size: .8rem;
    font-weight: 700;
    border: 1.5px solid transparent;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
}
.eg-badge.all { 
    background: var(--lino2, #f3f0ea); 
    color: var(--tinta2, #554438); 
    border-color: var(--borde, #e2d9ce); 
}
.eg-badge.all.active,
.eg-badge.all:hover { 
    background: var(--caoba, #6b2d14); 
    color: #fff; 
    border-color: var(--caoba, #6b2d14); 
}
.eg-badge.pendiente { 
    background: #fff8e1; 
    color: #b78103; 
    border-color: #ffe082; 
}
.eg-badge.pendiente.active,
.eg-badge.pendiente:hover { 
    background: #b78103; 
    color: #fff; 
}
.eg-badge.en-curso { 
    background: #e3f2fd; 
    color: #1565c0; 
    border-color: #90caf9; 
}
.eg-badge.en-curso.active,
.eg-badge.en-curso:hover { 
    background: #1565c0; 
    color: #fff; 
}
.eg-badge.finalizada { 
    background: #e8f5e9; 
    color: #2e7d32; 
    border-color: #a5d6a7; 
}
.eg-badge.finalizada.active,
.eg-badge.finalizada:hover { 
    background: #2e7d32; 
    color: #fff; 
}
.badge-count { 
    background: rgba(0,0,0,.1); 
    border-radius: 10px; 
    padding: 1px 6px; 
    font-size: .73rem; 
}

.eg-filters {
    display: flex;
    gap: .5rem;
    flex-wrap: wrap;
    margin-bottom: 1.2rem;
    align-items: center;
}
.eg-filters input {
    flex: 1;
    min-width: 220px;
    background: var(--papel, #fff);
    border: 1.5px solid var(--borde, #e2d9ce);
    border-radius: var(--radius-sm, 6px);
    padding: .46rem 1rem;
    font-size: .9rem;
    color: var(--tinta, #2c1a0e);
    transition: border-color 0.2s;
}
.eg-filters input:focus {
    outline: none;
    border-color: var(--caoba, #6b2d14);
    box-shadow: 0 0 0 3px rgba(107,45,20,.1);
}

.btn-eg {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .46rem 1rem;
    border-radius: var(--radius-sm, 6px);
    font-size: .86rem;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: transform 0.1s ease, background 0.2s;
}
.btn-eg:hover { transform: translateY(-1px); }
.btn-eg-primary { 
    background: var(--caoba, #6b2d14); 
    color: #fff; 
}
.btn-eg-primary:hover { 
    background: #52210e; 
    color: #fff; 
}
.btn-eg-secondary { 
    background: var(--lino2, #f3f0ea); 
    color: var(--caoba, #6b2d14); 
    border: 1.5px solid var(--borde, #e2d9ce); 
}
.btn-eg-secondary:hover { 
    background: var(--borde, #e2d9ce); 
}
.btn-eg-sm { 
    padding: .26rem .6rem; 
    font-size: .78rem; 
}

.eg-table-wrap {
    background: var(--papel, #fff);
    border: 1.5px solid var(--borde, #e2d9ce);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,0.02);
}
.eg-table { 
    width: 100%; 
    border-collapse: collapse; 
}
.eg-table thead tr { 
    background: linear-gradient(to right, var(--caoba, #6b2d14), #8a3a1b); 
}
.eg-table thead th {
    padding: .72rem 1rem;
    text-align: left;
    font-size: .74rem;
    font-weight: 700;
    letter-spacing: .06em;
    color: rgba(255,255,255,.95);
    text-transform: uppercase;
    border-right: 1px solid rgba(255,255,255,.1);
}
.eg-table thead th:last-child { border-right: none; }
.eg-table tbody tr { 
    border-bottom: 1px solid var(--borde, #e2d9ce); 
    transition: background 0.15s; 
}
.eg-table tbody tr:last-child { border-bottom: none; }
.eg-table tbody tr:hover { background: var(--lino, #faf8f5); }
.eg-table tbody td {
    padding: .65rem 1rem;
    font-size: .86rem;
    color: var(--tinta2, #554438);
    border-right: 1px solid var(--borde, #e2d9ce);
    vertical-align: middle;
}
.eg-table tbody td:last-child { border-right: none; }

.td-main { font-weight: 700; color: var(--caoba, #6b2d14); }
.td-tag { 
    display: inline-block; 
    padding: 2px 9px; 
    border-radius: 50px; 
    font-size: .72rem; 
    font-weight: 700; 
    background: var(--lino2, #f3f0ea); 
    color: var(--tinta2, #554438); 
    border: 1px solid var(--borde, #e2d9ce); 
}
.td-acc { display: flex; gap: .3rem; flex-wrap: wrap; }
.td-fecha { font-size: .8rem; color: #888; }
.td-codigo { 
    font-family: monospace; 
    font-size: .83rem; 
    background: var(--lino2, #f3f0ea); 
    padding: 2px 7px; 
    border-radius: 4px; 
    border: 1px solid var(--borde, #e2d9ce); 
}

.pill { 
    display: inline-flex; 
    align-items: center; 
    padding: 3px 10px; 
    border-radius: 50px; 
    font-size: .73rem; 
    font-weight: 700; 
}
.pill-pendiente { background: #fff8e1; color: #b78103; border: 1px solid #ffe082; }
.pill-en-curso  { background: #e3f2fd; color: #1565c0; border: 1px solid #90caf9; }
.pill-finalizada{ background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }

.eg-vacio { text-align: center; padding: 3rem 1rem; color: #888; }
.eg-vacio span { font-size: 2rem; opacity: .3; display: block; margin-bottom: .4rem; }

.modal-bg { 
    display: none; 
    position: fixed; 
    inset: 0; 
    background: rgba(44,26,14,.5); 
    z-index: 1000; 
    align-items: center; 
    justify-content: center; 
    padding: 1rem; 
}
.modal-bg.open { display: flex; }
.modal { 
    background: var(--papel, #fff); 
    border-radius: 10px; 
    width: 100%; 
    max-width: 520px; 
    box-shadow: 0 10px 30px rgba(0,0,0,0.2); 
    overflow: hidden; 
}
.modal-hd { 
    background: linear-gradient(to right, var(--caoba, #6b2d14), #8a3a1b); 
    padding: 1rem 1.25rem; 
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
}
.modal-hd h3 { font-size: 1.05rem; font-weight: 700; color: #fff; margin: 0; }
.modal-close { background: none; border: none; color: rgba(255,255,255,.8); font-size: 1.2rem; cursor: pointer; }
.modal-close:hover { color: #fff; }
.modal-body { padding: 1.25rem; }
.modal-ft { padding: .9rem 1.25rem; border-top: 1px solid var(--borde, #e2d9ce); background: var(--lino, #faf8f5); display: flex; gap: .5rem; justify-content: flex-end; }
.modal-info { background: var(--lino2, #f3f0ea); border: 1px solid var(--borde, #e2d9ce); border-radius: 6px; padding: .75rem 1rem; margin-bottom: 1rem; font-size: .84rem; color: var(--tinta2, #554438); }
.modal-info strong { color: var(--caoba, #6b2d14); }

.fld { margin-bottom: 1rem; }
.fld label { display: block; margin-bottom: .3rem; font-size: .77rem; font-weight: 700; color: var(--tinta2, #554438); text-transform: uppercase; }
.fld input, .fld select, .fld textarea {
    width: 100%; 
    background: var(--lino, #faf8f5); 
    border: 1.5px solid var(--borde, #e2d9ce);
    border-radius: 6px; 
    padding: .5rem .85rem; 
    font-size: .9rem; 
    color: var(--tinta, #2c1a0e);
}
.fld input:focus, .fld select:focus, .fld textarea:focus { 
    outline: none; 
    border-color: var(--caoba, #6b2d14); 
    background: #fff; 
    box-shadow: 0 0 0 3px rgba(107,45,20,.1); 
}

.dataTables_wrapper .bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    font-size: 0.85rem;
    color: var(--tinta2, #554438);
    border-top: 1.5px solid var(--borde, #e2d9ce);
    background: var(--lino, #faf8f5);
}
.dataTables_wrapper .dataTables_paginate {
    display: flex;
    gap: 0.3rem;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.3rem 0.6rem;
    border-radius: 6px;
    border: 1.5px solid var(--borde, #e2d9ce);
    background: var(--papel, #fff);
    cursor: pointer;
    color: var(--tinta, #2c1a0e) !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--caoba, #6b2d14) !important;
    color: #fff !important;
    border-color: var(--caoba, #6b2d14) !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: var(--lino2, #f3f0ea) !important;
    color: var(--caoba, #6b2d14) !important;
}
.eg-table input[type="checkbox"] {
    width: 16px;
    height: 16px;
    cursor: pointer;
    accent-color: var(--caoba, #6b2d14);
}

.eg-table .col-select,
.eg-table .row-select-cell {
    display: none !important;
}

.eg-table-wrap.selection-active .col-select,
.eg-table-wrap.selection-active .row-select-cell {
    display: table-cell !important;
}

.bulk-actions-bar {
    display: none;
    align-items: center;
    justify-content: space-between;
    background: var(--lino2, #f3f0ea);
    border: 1.5px solid var(--caoba, #6b2d14);
    border-radius: var(--radius-md, 8px);
    padding: .75rem 1.2rem;
    margin-bottom: 1rem;
    animation: mIn .2s ease;
}
.bulk-actions-bar.active { 
    display: flex; 
}
</style>

<?php
$clsBadge = function(string $e): string {
    return match($e) {
        'Pendiente'     => 'pendiente',
        'En producción' => 'en-curso',
        'Listo'         => 'finalizada',
        'Entregado'     => 'finalizada',
        'Cancelado'     => 'all',
        default         => 'all',
    };
};
$clsPill = fn(string $e) => 'pill-' . str_replace([' ', 'ó', 'ú'], ['', 'o', 'u'], strtolower($e));
$totalGen = array_sum($conteos);
?>

<div class="eg-page">

    <div class="eg-hd">
        <h1>Pedidos</h1>
        <button class="btn-eg btn-eg-primary" onclick="abrirCrear()">Nuevo pedido</button>
    </div>

    <?php if (!empty($ventasElegibles)): ?>
    <div class="modal-info" style="margin-bottom: 1.2rem;">
        Hay <strong><?= count($ventasElegibles) ?></strong>
        venta<?= count($ventasElegibles) > 1 ? 's' : '' ?> disponible<?= count($ventasElegibles) > 1 ? 's' : '' ?>
        para convertir en pedido.
    </div>
    <?php endif; ?>

    <div class="badges-row">
        <a href="<?= URL ?>pedido<?= !empty($buscar) ? '?buscar='.urlencode($buscar) : '' ?>"
           class="eg-badge all <?= $estado === '' ? 'active' : '' ?>">
            Todos <span class="badge-count"><?= $totalGen ?></span>
        </a>
        <?php foreach ($estados as $est):
            $cls   = $clsBadge($est);
            $count = $conteos[$est] ?? 0;
            $href  = URL . 'pedido?estado=' . urlencode($est) . (!empty($buscar) ? '&buscar='.urlencode($buscar) : '');
        ?>
        <a href="<?= $href ?>"
           class="eg-badge <?= $cls ?> <?= $estado === $est ? 'active' : '' ?>">
            <?= htmlspecialchars($est) ?>
            <span class="badge-count"><?= $count ?></span>
        </a>
        <?php endforeach; ?>
    </div>

    <form class="eg-filters" method="GET" action="<?= URL ?>pedido">
        <?php if ($estado !== ''): ?>
        <input type="hidden" name="estado" value="<?= htmlspecialchars($estado) ?>">
        <?php endif; ?>
        <input type="text" name="buscar"
               value="<?= htmlspecialchars($buscar) ?>"
               placeholder="Buscar por cliente, producto, responsable o número de venta...">
        <button type="submit" class="btn-eg btn-eg-primary">Buscar</button>
        <?php if (!empty($buscar) || $estado !== ''): ?>
        <a href="<?= URL ?>pedido" class="btn-eg btn-eg-secondary">Limpiar</a>
        <?php endif; ?>
    </form>

    <div style="margin-bottom: 0.8rem; display: flex; justify-content: flex-end;">
        <button type="button" class="btn-eg btn-eg-secondary btn-eg-sm" id="btnToggleSelection" onclick="toggleSelectionMode()">
            Selección múltiple
        </button>
    </div>

    <div class="bulk-actions-bar" id="bulkActionsBar">
        <div style="font-size: 0.86rem; font-weight: 700; color: var(--caoba, #6b2d14);">
            <span id="selectedCount">0</span> elemento(s) seleccionado(s)
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <button type="button" class="btn-eg btn-eg-primary btn-eg-sm" onclick="ejecutarAccionMasiva('cambiar_estado')">
                Cambiar estado
            </button>
            <button type="button" class="btn-eg btn-eg-secondary btn-eg-sm" style="color: var(--rojo, #d32f2f);" onclick="ejecutarAccionMasiva('baja')">
                Dar de baja
            </button>
        </div>
    </div>
    <div class="eg-table-wrap" id="egTableWrap">
        <table class="eg-table">
            <thead>
                <tr>
                    <th class="col-select" style="width: 30px; text-align: center;">
                        <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                    </th>
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
                    <td colspan="9">
                        <div class="eg-vacio">
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
                    <td class="row-select-cell" style="text-align: center;">
                        <input type="checkbox" class="row-checkbox" value="<?= $p['Id'] ?>" onchange="actualizarBarraMasiva()">
                    </td>
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
                            <?= $dias ?> día<?= $dias !== 1 ? 's' : '' ?> atrás
                        </div>
                    </td>

                    <td style="font-size:.82rem;">
                        <?php if (!empty($p['CodigoEntrega'])): ?>
                        <span class="td-codigo">
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
                            <button class="btn-eg btn-eg-secondary btn-eg-sm"
                                    onclick="abrirEditar(<?= htmlspecialchars(json_encode($p)) ?>)">
                                Editar
                            </button>
                            <a href="<?= URL ?>pedido/baja/<?= $p['Id'] ?>"
                               class="btn-eg btn-eg-secondary btn-eg-sm" style="color:var(--rojo, #d32f2f);"
                               onclick="return confirm('¿Dar de baja el pedido?')">
                                Baja
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
<div class="modal-bg" id="modalMasivoEstado">
    <div class="modal">
        <div class="modal-hd">
            <h3>Cambiar estado masivo</h3>
            <button class="modal-close" onclick="cerrarMasivoEstado()">✕</button>
        </div>
        <form method="POST" action="<?= URL ?>pedido/estadoMasivo">
            <input type="hidden" name="ids" id="masivoIds">
            <div class="modal-body">
                <div class="modal-info">
                    Se actualizará el estado de <strong id="masivoCountText">0</strong> pedido(s) seleccionado(s).
                </div>
                <div class="fld">
                    <label>Nuevo Estado</label>
                    <select name="Estado" required>
                        <?php foreach ($estados as $est): ?>
                        <option value="<?= htmlspecialchars($est) ?>"><?= htmlspecialchars($est) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-ft">
                <button type="button" class="btn-eg btn-eg-secondary" onclick="cerrarMasivoEstado()">Cancelar</button>
                <button type="submit" class="btn-eg btn-eg-primary">Aplicar cambios</button>
            </div>
        </form>
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
                            $label = $dias > 0 ? $dias . ' día' . ($dias !== 1 ? 's' : '') : $horas . 'hs';
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
                <button type="button" class="btn-eg btn-eg-secondary" onclick="cerrarCrear()">Cancelar</button>
                <?php if (!empty($ventasElegibles)): ?>
                <button type="submit" class="btn-eg btn-eg-primary">Crear pedido</button>
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
                <button type="button" class="btn-eg btn-eg-secondary" onclick="cerrarEditar()">Cancelar</button>
                <button type="submit" class="btn-eg btn-eg-primary">Guardar cambios</button>
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

function cerrarCrear() { 
    document.getElementById('modalCrear').classList.remove('open'); 
}

function mostrarInfoVenta() {
    var sel  = document.getElementById('fVenta');
    var opt  = sel.options[sel.selectedIndex];
    var info = document.getElementById('infoVenta');
    if (!sel.value) { info.style.display = 'none'; return; }
    document.getElementById('iCliente').textContent   = opt.dataset.cliente   ?? '—';
    document.getElementById('iProductos').textContent = opt.dataset.productos ?? '—';
    document.getElementById('iMonto').textContent     = opt.dataset.monto     ?? '—';
    document.getElementById('iDias').textContent      = opt.dataset.dias      ?? '—';
    info.style.display = 'block';
}

function abrirEditar(p) {
    document.getElementById('eId').value          = p.Id;
    document.getElementById('eIdVenta').value     = p.IdVenta;
    document.getElementById('eEstado').value      = p.Estado ?? 'Pendiente';
    document.getElementById('eResponsable').value = p.Responsable ?? '';
    document.getElementById('editTitulo').textContent = 'Editar pedido #' + p.NumerodeVenta;
    document.getElementById('editInfo').innerHTML =
        '<strong>Venta:</strong> ' + p.NumerodeVenta +
        ' &nbsp;&middot;&nbsp; <strong>Cliente:</strong> ' +
        (p.ClienteNombre ?? '') + ' ' + (p.ClienteApellido ?? '') +
        '<br><strong>Productos:</strong> ' + (p.Productos ?? '—');
    document.getElementById('modalEditar').classList.add('open');
}

function cerrarEditar() { 
    document.getElementById('modalEditar').classList.remove('open'); 
}

['modalCrear', 'modalEditar'].forEach(function (id) {
    document.getElementById(id).addEventListener('click', function (e) {
        if (e.target === this) this.classList.remove('open');
    });
});

$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('.eg-table')) {
        $('.eg-table').DataTable().clear().destroy();
    }

   $('.eg-table').DataTable({
        language: {
            processing:     "Procesando...",
            search:         "Buscar:",
            lengthMenu:     "Mostrar _MENU_ registros",
            info:           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            infoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
            infoFiltered:   "(filtrado de un total de _MAX_ registros)",
            loadingRecords: "Cargando...",
            zeroRecords:    "No se encontraron resultados",
            emptyTable:     "No hay pedidos disponibles en esta sección.",
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
        info: true,
        autoWidth: false,
        dom: 't<"bottom"ip>', 
        columnDefs: [
            { orderable: false, targets: [0, 8] }
        ]
    });
});
function toggleSelectionMode() {
    const wrap = document.getElementById('egTableWrap');
    const bar = document.getElementById('bulkActionsBar');
    const btn = document.getElementById('btnToggleSelection');
    
    wrap.classList.toggle('selection-active');
    
    if (!wrap.classList.contains('selection-active')) {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('selectAll').checked = false;
        bar.classList.remove('active');
        btn.textContent = 'Selección múltiple';
    } else {
        btn.textContent = 'Cancelar selección';
    }
}

function toggleSelectAll(master) {
    const checkboxes = document.querySelectorAll('.row-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = master.checked;
    });
    actualizarBarraMasiva();
}

function actualizarBarraMasiva() {
    const checkboxes = document.querySelectorAll('.row-checkbox:checked');
    const bar = document.getElementById('bulkActionsBar');
    const countSpan = document.getElementById('selectedCount');
    
    countSpan.textContent = checkboxes.length;
    
    if (checkboxes.length > 0) {
        bar.classList.add('active');
    } else {
        bar.classList.remove('active');
    }
}

function ejecutarAccionMasiva(accion) {
    const seleccionados = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
    
    if (seleccionados.length === 0) {
        alert('Por favor selecciona al menos un pedido.');
        return;
    }
    
    if (accion === 'baja') {
        if (confirm('¿Estás seguro de dar de baja los ' + seleccionados.length + ' pedidos seleccionados?')) {
            enviarAccionPorPost('<?= URL ?>pedido/bajaMasiva', seleccionados);
        }
    } else if (accion === 'cambiar_estado') {
        document.getElementById('masivoIds').value = JSON.stringify(seleccionados);
        document.getElementById('masivoCountText').textContent = seleccionados.length;
        document.getElementById('modalMasivoEstado').classList.add('open');
    }
}

function cerrarMasivoEstado() {
    document.getElementById('modalMasivoEstado').classList.remove('open');
}

function enviarAccionPorPost(url, ids) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'ids';
    input.value = JSON.stringify(ids);
    form.appendChild(input);
    
    document.body.appendChild(form);
    form.submit();
}

['modalCrear', 'modalEditar', 'modalMasivoEstado'].forEach(function (id) {
    const el = document.getElementById(id);
    if (el) {
        el.addEventListener('click', function (e) {
            if (e.target === this) this.classList.remove('open');
        });
    }
});
</script>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
<?php include INCLUDES . 'admin' . DS . 'modals_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'scripts_admin.php'; ?>