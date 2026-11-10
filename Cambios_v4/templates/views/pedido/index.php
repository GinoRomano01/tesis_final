<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<main class="content">

<style>
.eg-page { font-family: var(--font-body, system-ui, -apple-system, sans-serif); color: var(--tinta, #2c1a0e); padding: 1.5rem; }
.eg-hd { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem; margin-bottom: 1.4rem; }
.eg-hd h1 { font-family: var(--font-display, inherit); font-size: 1.7rem; font-weight: 700; color: var(--caoba, #6b2d14); margin: 0; }

.stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.4rem; }
.stat-card {
    display: flex; align-items: center; gap: 1rem; padding: 1.1rem 1.3rem; border-radius: 14px;
    text-decoration: none; color: #fff; box-shadow: 0 4px 14px rgba(0,0,0,.1);
    transition: transform .15s ease, box-shadow .15s ease; border: 2px solid transparent;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.16); color: #fff; }
.stat-card.activo { border-color: rgba(255,255,255,.85); }
.stat-card-icon { width: 46px; height: 46px; border-radius: 12px; background: rgba(255,255,255,.22); display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0; }
.stat-card-num { font-family: var(--font-display, inherit); font-size: 1.7rem; font-weight: 800; line-height: 1.1; }
.stat-card-label { font-size: .82rem; font-weight: 600; opacity: .92; }
.stat-card.c-todos    { background: linear-gradient(135deg, var(--caoba, #6b2d14), #8a3a1b); }
.stat-card.c-pendiente{ background: linear-gradient(135deg, #d99a1f, #b78103); }
.stat-card.c-produccion{ background: linear-gradient(135deg, #4a97e8, #1565c0); }
.stat-card.c-cancelado{ background: linear-gradient(135deg, #dd6b58, #c0392b); }
.stat-card.c-listo    { background: linear-gradient(135deg, #52ab63, #2e7d32); }

.eg-filters { display: flex; gap: .5rem; flex-wrap: wrap; margin-bottom: 1.2rem; align-items: center; }
.eg-filters input, .eg-filters select {
    background: var(--papel, #fff); border: 1.5px solid var(--borde, #e2d9ce); border-radius: 8px;
    padding: .5rem 1rem; font-size: .9rem; color: var(--tinta, #2c1a0e); transition: border-color 0.2s;
}
.eg-filters input { flex: 1; min-width: 220px; }
.eg-filters input:focus, .eg-filters select:focus { outline: none; border-color: var(--caoba, #6b2d14); box-shadow: 0 0 0 3px rgba(107,45,20,.1); }
.eg-filters label { font-size: .8rem; color: var(--g1, #8a7560); white-space: nowrap; }

.btn-eg { display: inline-flex; align-items: center; gap: .4rem; padding: .5rem 1.1rem; border-radius: 8px; font-size: .86rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: transform 0.1s ease, background 0.2s; }
.btn-eg:hover { transform: translateY(-1px); }
.btn-eg-primary { background: var(--caoba, #6b2d14); color: #fff; }
.btn-eg-primary:hover { background: #52210e; color: #fff; }
.btn-eg-secondary { background: var(--lino2, #f3f0ea); color: var(--caoba, #6b2d14); border: 1.5px solid var(--borde, #e2d9ce); }
.btn-eg-secondary:hover { background: var(--borde, #e2d9ce); }
.btn-eg-sm { padding: .3rem .65rem; font-size: .78rem; }

.eg-vacio { text-align: center; padding: 2rem 1rem; color: #888; }
.eg-vacio span { font-size: 2rem; opacity: .3; display: block; margin-bottom: .4rem; }

.modal-bg { display: none; position: fixed; inset: 0; background: rgba(44,26,14,.5); z-index: 1000; align-items: center; justify-content: center; padding: 1rem; }
.modal-bg.open { display: flex; }
.modal { background: var(--papel, #fff); border-radius: 14px; width: 100%; max-width: 520px; max-height: 90vh; overflow-y: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
.modal-hd { background: linear-gradient(to right, var(--caoba, #6b2d14), #8a3a1b); padding: 1.1rem 1.4rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; border-radius: 14px 14px 0 0; }
.modal-hd h3 { font-size: 1.05rem; font-weight: 700; color: #fff; margin: 0; }
.modal-close { background: none; border: none; color: rgba(255,255,255,.8); font-size: 1.2rem; cursor: pointer; }
.modal-close:hover { color: #fff; }
.modal-body { padding: 1.4rem; }
.modal-ft { padding: 1rem 1.4rem; border-top: 1px solid var(--borde, #e2d9ce); background: var(--lino, #faf8f5); display: flex; gap: .5rem; justify-content: flex-end; border-radius: 0 0 14px 14px; }
.modal-info { background: var(--lino2, #f3f0ea); border: 1px solid var(--borde, #e2d9ce); border-radius: 10px; padding: .85rem 1.1rem; margin-bottom: 1.1rem; font-size: .84rem; color: var(--tinta2, #554438); }
.modal-info strong { color: var(--caoba, #6b2d14); }

.fld { margin-bottom: 1.1rem; }
.fld label { display: block; margin-bottom: .35rem; font-size: .77rem; font-weight: 700; color: var(--tinta2, #554438); text-transform: uppercase; }
.fld input, .fld select, .fld textarea { width: 100%; background: var(--lino, #faf8f5); border: 1.5px solid var(--borde, #e2d9ce); border-radius: 8px; padding: .55rem .9rem; font-size: .9rem; color: var(--tinta, #2c1a0e); }
.fld input:focus, .fld select:focus, .fld textarea:focus { outline: none; border-color: var(--caoba, #6b2d14); background: #fff; box-shadow: 0 0 0 3px rgba(107,45,20,.1); }

.bulk-actions-bar { display: none; align-items: center; justify-content: space-between; background: var(--lino2, #f3f0ea); border: 1.5px solid var(--caoba, #6b2d14); border-radius: 12px; padding: .8rem 1.3rem; margin-bottom: 1.1rem; }
.bulk-actions-bar.active { display: flex; }

.kanban-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.1rem; align-items: start; margin-bottom: 1.3rem; }
@media (max-width: 900px) { .kanban-row { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 560px) { .kanban-row { grid-template-columns: 1fr; } }

.kanban-col {
    background: var(--papel, #fff); border: 1.5px solid var(--borde, #e2d9ce); border-radius: 14px;
    overflow: hidden; min-width: 0; transition: background .15s, border-color .15s;
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
}
.kanban-col.drag-over { background: #fdf3e7; border-color: var(--amb, #b8722a); }
.kanban-col-hd {
    display: flex; justify-content: space-between; align-items: center;
    padding: .85rem 1.1rem; color: #fff;
}
.kanban-col[data-estado="Pendiente"]     .kanban-col-hd { background: linear-gradient(135deg, #d99a1f, #b78103); }
.kanban-col[data-estado="En producción"] .kanban-col-hd { background: linear-gradient(135deg, #4a97e8, #1565c0); }
.kanban-col[data-estado="Cancelado"]     .kanban-col-hd { background: linear-gradient(135deg, #dd6b58, #c0392b); }
.kanban-col-hd-title { font-weight: 700; font-size: .84rem; letter-spacing: .02em; }
.kanban-col-hd-count { background: rgba(255,255,255,.28); border-radius: 20px; padding: 2px 10px; font-size: .76rem; font-weight: 700; }
.kanban-col-cards { padding: .9rem; }

.seccion-listo {
    background: var(--papel, #fff); border: 1.5px solid #a5d6a7; border-radius: 14px; overflow: hidden;
    transition: background .15s, border-color .15s; box-shadow: 0 2px 10px rgba(0,0,0,.04);
}
.seccion-listo.drag-over { background: #e3f5e5; border-color: #2e7d32; }
.seccion-listo-hd { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.2rem; background: linear-gradient(135deg, #52ab63, #2e7d32); color: #fff; }
.seccion-listo-hd-title { font-weight: 700; font-size: .92rem; letter-spacing: .02em; }
.seccion-listo-hd small { display: block; font-weight: 400; color: rgba(255,255,255,.85); font-size: .74rem; margin-top: 2px; }
.seccion-listo-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: .8rem; padding: 1.1rem; }

.ped-card {
    background: var(--papel, #fff); border: 1.5px solid var(--borde, #e2d9ce); border-radius: 12px;
    padding: .9rem 1rem; margin-bottom: .7rem; cursor: grab; transition: box-shadow .15s, transform .1s, opacity .15s;
    position: relative;
}
.kanban-col-cards .ped-card:last-child { margin-bottom: 0; }
.ped-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,.1); transform: translateY(-1px); border-color: var(--caoba, #6b2d14); }
.ped-card.dragging { opacity: .4; }
.ped-card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: .55rem; gap: .5rem; }
.ped-card-cliente { font-weight: 700; color: var(--caoba, #6b2d14); font-size: .92rem; line-height: 1.25; }
.ped-pill { flex-shrink: 0; padding: 2px 10px; border-radius: 20px; font-size: .68rem; font-weight: 700; white-space: nowrap; }
.ped-pill.pendiente  { background: #fff3d6; color: #b78103; }
.ped-pill.en-curso    { background: #dceafc; color: #1565c0; }
.ped-pill.cancelada   { background: #fde3df; color: #c0392b; }
.ped-pill.finalizada  { background: #dff2e1; color: #2e7d32; }
.ped-card-rows { display: flex; flex-direction: column; gap: .3rem; }
.ped-card-row { display: flex; justify-content: space-between; align-items: baseline; font-size: .78rem; gap: .5rem; }
.ped-card-row .k { color: var(--g1, #8a7560); flex-shrink: 0; }
.ped-card-row .v { color: var(--tinta2, #554438); text-align: right; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.ped-card-row .v.monto { font-weight: 700; color: #2e7d32; font-family: var(--font-display, inherit); font-size: .9rem; }
.ped-card-check { position: absolute; top: .7rem; right: .7rem; width: 16px; height: 16px; cursor: pointer; accent-color: var(--caoba, #6b2d14); display: none; }
.selection-mode .ped-card-check { display: block; }
.selection-mode .ped-card-top { padding-right: 1.6rem; }

.kanban-col-vacio, .seccion-listo-vacio { text-align: center; padding: 1.6rem .5rem; color: #999; font-size: .8rem; grid-column: 1 / -1; }
.kanban-col-pag, .pag-general { display: flex; justify-content: center; flex-wrap: wrap; gap: .3rem; margin-top: .7rem; padding: 0 .9rem .9rem; }
.kanban-col-pag a, .kanban-col-pag span.actual, .pag-general a, .pag-general span.actual {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 26px; height: 26px; border-radius: 7px; font-size: .76rem;
    text-decoration: none; color: var(--tinta2, #554438); border: 1px solid var(--borde, #e2d9ce); background: var(--lino, #faf8f5);
}
.kanban-col-pag a:hover, .pag-general a:hover { background: var(--lino2, #f3f0ea); }
.kanban-col-pag span.actual, .pag-general span.actual { background: var(--caoba, #6b2d14); color: #fff; border-color: var(--caoba, #6b2d14); font-weight: 700; }

.seccion-unica-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: .9rem; margin-bottom: 1.3rem; }

.drag-hint { text-align: center; font-size: .8rem; color: var(--g1, #8a7560); margin-bottom: 1rem; background: var(--lino2, #f3f0ea); padding: .55rem 1rem; border-radius: 10px; }
</style>

<?php
$clsBadge = function(string $e): string {
    return match($e) {
        'Pendiente'     => 'pendiente',
        'En producción' => 'en-curso',
        'Listo'         => 'finalizada',
        'Cancelado'     => 'cancelada',
        default         => 'all',
    };
};
$totalGen = array_sum(array_intersect_key($conteos, array_flip($estadosKanban)));
$estadosAsignables = puedeCancelarPedido() ? $estadosKanban : array_diff($estadosKanban, ['Cancelado']);

$ordenesDisponibles = [
    'fecha_desc'  => 'Fecha: más nueva primero',
    'fecha_asc'   => 'Fecha: más antigua primero',
    'precio_desc' => 'Precio: mayor a menor',
    'precio_asc'  => 'Precio: menor a mayor',
    'tiempo_desc' => 'Tiempo de fabricación: mayor a menor',
    'tiempo_asc'  => 'Tiempo de fabricación: menor a mayor',
];

$urlFiltro = function(string $est = '') use ($buscar, $orden): string {
    $qs = [];
    if ($est !== '') $qs['estado'] = $est;
    if ($buscar !== '') $qs['buscar'] = $buscar;
    if ($orden !== 'fecha_desc') $qs['orden'] = $orden;
    return URL . 'pedido' . (!empty($qs) ? '?' . http_build_query($qs) : '');
};

$urlPagCol = function(string $claveCol, int $pagina) use ($buscar, $orden): string {
    $qs = [];
    if ($buscar !== '') $qs['buscar'] = $buscar;
    if ($orden !== 'fecha_desc') $qs['orden'] = $orden;
    foreach (['pendiente', 'en_produccion', 'cancelado', 'listo'] as $k) {
        $key = 'p_' . $k;
        if ($k === $claveCol) {
            $qs[$key] = $pagina;
        } elseif (!empty($_GET[$key])) {
            $qs[$key] = $_GET[$key];
        }
    }
    return URL . 'pedido?' . http_build_query($qs);
};

$urlPagGeneral = function(int $pagina) use ($buscar, $estadoFiltro, $orden): string {
    $qs = ['estado' => $estadoFiltro, 'pagina' => $pagina];
    if ($buscar !== '') $qs['buscar'] = $buscar;
    if ($orden !== 'fecha_desc') $qs['orden'] = $orden;
    return URL . 'pedido?' . http_build_query($qs);
};

$renderCard = function(array $p) use ($clsBadge) {
    $dias = floor(($p['HorasDesdeVenta'] ?? 0) / 24);
    $tiempoFab = (float)($p['TiempoFabricacionTotal'] ?? 0);
    $pillCls = $clsBadge($p['Estado'] ?? '');
    ?>
    <div class="ped-card" draggable="true" data-id="<?= $p['Id'] ?>" data-estado="<?= htmlspecialchars($p['Estado']) ?>"
         onclick="abrirEditar(<?= htmlspecialchars(json_encode($p)) ?>)">
        <input type="checkbox" class="row-checkbox ped-card-check" value="<?= $p['Id'] ?>"
               onclick="event.stopPropagation()" onchange="actualizarBarraMasiva()">
        <div class="ped-card-top">
            <div class="ped-card-cliente"><?= htmlspecialchars($p['ClienteNombre'] . ' ' . $p['ClienteApellido']) ?></div>
            <span class="ped-pill <?= $pillCls ?>"><?= htmlspecialchars($p['Estado']) ?></span>
        </div>
        <div class="ped-card-rows">
            <div class="ped-card-row"><span class="k">Venta</span><span class="v">#<?= htmlspecialchars($p['NumerodeVenta']) ?></span></div>
            <div class="ped-card-row"><span class="k">Productos</span><span class="v" title="<?= htmlspecialchars($p['Productos'] ?? '—') ?>"><?= htmlspecialchars($p['Productos'] ?? '—') ?></span></div>
            <?php if ($tiempoFab > 0): ?>
            <div class="ped-card-row"><span class="k">Fabricación</span><span class="v"><?= number_format($tiempoFab, 0) ?>h</span></div>
            <?php endif; ?>
            <div class="ped-card-row"><span class="k">Hace</span><span class="v"><?= $dias ?> día<?= $dias !== 1 ? 's' : '' ?></span></div>
            <div class="ped-card-row"><span class="k">Total</span><span class="v monto">$<?= number_format($p['MontoTotal'] ?? 0, 0, ',', '.') ?></span></div>
        </div>
    </div>
    <?php
};

$configColumnas = [
    'pendiente'     => 'Pendiente',
    'en_produccion' => 'En producción',
    'cancelado'     => 'Cancelado',
    'listo'         => 'Listo',
];
?>

<div class="eg-page">

    <div class="eg-hd">
        <h1>Pedidos</h1>
        <button class="btn-eg btn-eg-primary" onclick="abrirCrear()">Nuevo pedido</button>
    </div>

    <?= Toast::flash() ?>

    <?php if (!empty($ventasElegibles)): ?>
    <div class="modal-info" style="margin-bottom: 1.2rem; text-align:center;">
        Hay <strong><?= count($ventasElegibles) ?></strong>
        venta<?= count($ventasElegibles) > 1 ? 's' : '' ?> disponible<?= count($ventasElegibles) > 1 ? 's' : '' ?>
        para convertir en pedido.
    </div>
    <?php endif; ?>

    <div class="stats-row">
        <a href="<?= $urlFiltro() ?>" class="stat-card c-todos <?= $estadoFiltro === '' ? 'activo' : '' ?>">
            <div class="stat-card-icon">📋</div>
            <div><div class="stat-card-num"><?= $totalGen ?></div><div class="stat-card-label">Todos</div></div>
        </a>
        <?php
        $iconosEstado = ['Pendiente' => '⏳', 'En producción' => '🔨', 'Cancelado' => '✕', 'Listo' => '✓'];
        $clasesEstado = ['Pendiente' => 'c-pendiente', 'En producción' => 'c-produccion', 'Cancelado' => 'c-cancelado', 'Listo' => 'c-listo'];
        foreach ($estadosKanban as $est):
        ?>
        <a href="<?= $urlFiltro($est) ?>" class="stat-card <?= $clasesEstado[$est] ?> <?= $estadoFiltro === $est ? 'activo' : '' ?>">
            <div class="stat-card-icon"><?= $iconosEstado[$est] ?></div>
            <div><div class="stat-card-num"><?= $conteos[$est] ?? 0 ?></div><div class="stat-card-label"><?= htmlspecialchars($est) ?></div></div>
        </a>
        <?php endforeach; ?>
    </div>

    <form class="eg-filters" method="GET" action="<?= URL ?>pedido">
        <?php if ($estadoFiltro !== ''): ?><input type="hidden" name="estado" value="<?= htmlspecialchars($estadoFiltro) ?>"><?php endif; ?>
        <input type="text" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
               placeholder="Buscar por cliente, producto, responsable o número de venta...">
        <label for="selOrden">Ordenar por</label>
        <select name="orden" id="selOrden" onchange="this.form.submit()">
            <?php foreach ($ordenesDisponibles as $val => $label): ?>
            <option value="<?= $val ?>" <?= $orden === $val ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-eg btn-eg-primary">Buscar</button>
        <?php if (!empty($buscar) || $estadoFiltro !== '' || $orden !== 'fecha_desc'): ?>
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
            <button type="button" class="btn-eg btn-eg-primary btn-eg-sm" onclick="ejecutarAccionMasiva('cambiar_estado')">Cambiar estado</button>
            <?php if (puedeEliminar()): ?>
            <button type="button" class="btn-eg btn-eg-secondary btn-eg-sm" style="color: var(--rojo, #d32f2f);" onclick="ejecutarAccionMasiva('baja')">Dar de baja</button>
            <?php endif; ?>
        </div>
    </div>

    <div id="zonaSeleccion">
    <?php if ($estadoFiltro !== ''): ?>

        <?php if (empty($items)): ?>
            <div class="eg-vacio"><span>—</span><p>No hay pedidos en "<?= htmlspecialchars($estadoFiltro) ?>"<?= !empty($buscar) ? ' para "'.htmlspecialchars($buscar).'"' : '' ?>.</p></div>
        <?php else: ?>
            <div class="seccion-unica-grid">
                <?php foreach ($items as $p) $renderCard($p); ?>
            </div>
            <?php if ($totalPaginas > 1): ?>
            <nav class="pag-general">
                <?php if ($pagina > 1): ?><a href="<?= $urlPagGeneral($pagina - 1) ?>">‹</a><?php endif; ?>
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <?php if ($i === $pagina): ?><span class="actual"><?= $i ?></span>
                    <?php else: ?><a href="<?= $urlPagGeneral($i) ?>"><?= $i ?></a><?php endif; ?>
                <?php endfor; ?>
                <?php if ($pagina < $totalPaginas): ?><a href="<?= $urlPagGeneral($pagina + 1) ?>">›</a><?php endif; ?>
            </nav>
            <?php endif; ?>
        <?php endif; ?>

    <?php else: ?>

        <p class="drag-hint">💡 Arrastrá una tarjeta a otra columna (o a "Listos") para cambiarle el estado.</p>

        <div class="kanban-row">
            <?php foreach ($estadosColumnas as $est):
                $clave = array_search($est, $configColumnas);
                $col = $columnas[$est];
            ?>
            <div class="kanban-col" data-estado="<?= htmlspecialchars($est) ?>" data-clave="<?= $clave ?>">
                <div class="kanban-col-hd">
                    <span class="kanban-col-hd-title"><?= htmlspecialchars($est) ?></span>
                    <span class="kanban-col-hd-count"><?= $col['total'] ?></span>
                </div>
                <div class="kanban-col-cards">
                    <?php if (empty($col['items'])): ?>
                        <div class="kanban-col-vacio">Sin pedidos acá</div>
                    <?php else: ?>
                        <?php foreach ($col['items'] as $p) $renderCard($p); ?>
                    <?php endif; ?>
                </div>
                <?php if ($col['totalPaginas'] > 1): ?>
                <nav class="kanban-col-pag">
                    <?php if ($col['pagina'] > 1): ?><a href="<?= $urlPagCol($clave, $col['pagina'] - 1) ?>">‹</a><?php endif; ?>
                    <span class="actual"><?= $col['pagina'] ?>/<?= $col['totalPaginas'] ?></span>
                    <?php if ($col['pagina'] < $col['totalPaginas']): ?><a href="<?= $urlPagCol($clave, $col['pagina'] + 1) ?>">›</a><?php endif; ?>
                </nav>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="seccion-listo" data-estado="Listo" data-clave="listo">
            <div class="seccion-listo-hd">
                <div>
                    <span class="seccion-listo-hd-title">Listos</span>
                    <small>Producción terminada — a la espera de generar la entrega</small>
                </div>
                <span class="kanban-col-hd-count"><?= $seccionListo['total'] ?></span>
            </div>
            <div class="seccion-listo-grid">
                <?php if (empty($seccionListo['items'])): ?>
                    <div class="seccion-listo-vacio">No hay pedidos listos por ahora</div>
                <?php else: ?>
                    <?php foreach ($seccionListo['items'] as $p) $renderCard($p); ?>
                <?php endif; ?>
            </div>
            <?php if ($seccionListo['totalPaginas'] > 1): ?>
            <nav class="pag-general">
                <?php if ($seccionListo['pagina'] > 1): ?><a href="<?= $urlPagCol('listo', $seccionListo['pagina'] - 1) ?>">‹</a><?php endif; ?>
                <?php for ($i = 1; $i <= $seccionListo['totalPaginas']; $i++): ?>
                    <?php if ($i === $seccionListo['pagina']): ?><span class="actual"><?= $i ?></span>
                    <?php else: ?><a href="<?= $urlPagCol('listo', $i) ?>"><?= $i ?></a><?php endif; ?>
                <?php endfor; ?>
                <?php if ($seccionListo['pagina'] < $seccionListo['totalPaginas']): ?><a href="<?= $urlPagCol('listo', $seccionListo['pagina'] + 1) ?>">›</a><?php endif; ?>
            </nav>
            <?php endif; ?>
        </div>

    <?php endif; ?>
    </div>

</div>

<div class="modal-bg" id="modalMasivoEstado">
    <div class="modal">
        <div class="modal-hd"><h3>Cambiar estado masivo</h3><button class="modal-close" onclick="cerrarMasivoEstado()">✕</button></div>
        <form method="POST" action="<?= URL ?>pedido/estadoMasivo">
            <input type="hidden" name="ids" id="masivoIds">
            <div class="modal-body">
                <div class="modal-info">Se actualizará el estado de <strong id="masivoCountText">0</strong> pedido(s) seleccionado(s).</div>
                <div class="fld">
                    <label>Nuevo Estado</label>
                    <select name="Estado" required>
                        <?php foreach ($estadosAsignables as $est): ?>
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
        <div class="modal-hd"><h3>Nuevo pedido</h3><button class="modal-close" onclick="cerrarCrear()">✕</button></div>
        <form method="POST" action="<?= URL ?>pedido/guardar">
            <input type="hidden" name="Id" value="0">
            <div class="modal-body">
                <?php if (empty($ventasElegibles)): ?>
                <div style="text-align:center;padding:2rem;color:var(--g1);font-size:.9rem;">No hay ventas aprobadas disponibles para asignar como pedido.</div>
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
                        <?php foreach ($estadosAsignables as $est): ?>
                        <option value="<?= htmlspecialchars($est) ?>" <?= $est === 'Pendiente' ? 'selected' : '' ?>><?= htmlspecialchars($est) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="fld">
                    <label>Responsable</label>
                    <input type="text" name="Responsable" maxlength="50" placeholder="Nombre del responsable (opcional)">
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-ft">
                <button type="button" class="btn-eg btn-eg-secondary" onclick="cerrarCrear()">Cancelar</button>
                <?php if (!empty($ventasElegibles)): ?><button type="submit" class="btn-eg btn-eg-primary">Crear pedido</button><?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="modal-bg" id="modalEditar">
    <div class="modal">
        <div class="modal-hd"><h3 id="editTitulo">Editar pedido</h3><button class="modal-close" onclick="cerrarEditar()">✕</button></div>
        <form method="POST" action="<?= URL ?>pedido/guardar">
            <input type="hidden" name="Id" id="eId">
            <input type="hidden" name="IdVenta" id="eIdVenta">
            <div class="modal-body">
                <div class="modal-info" id="editInfo"></div>
                <div class="fld">
                    <label>Estado</label>
                    <select name="Estado" id="eEstado" required>
                        <?php foreach ($estadosAsignables as $est): ?>
                        <option value="<?= htmlspecialchars($est) ?>"><?= htmlspecialchars($est) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="fld">
                    <label>Responsable</label>
                    <input type="text" name="Responsable" id="eResponsable" maxlength="50" placeholder="Nombre del responsable">
                </div>
            </div>
            <div class="modal-ft">
                <?php if (puedeEliminar()): ?>
                <a href="#" id="eBajaLink" class="btn-eg btn-eg-secondary" style="color:var(--rojo,#d32f2f);margin-right:auto;"
                   onclick="return confirm('¿Dar de baja el pedido?')">Dar de baja</a>
                <?php endif; ?>
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
function cerrarCrear() { document.getElementById('modalCrear').classList.remove('open'); }

function mostrarInfoVenta() {
    var sel = document.getElementById('fVenta');
    var opt = sel.options[sel.selectedIndex];
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
    document.getElementById('editTitulo').textContent = 'Pedido #' + p.NumerodeVenta;
    document.getElementById('editInfo').innerHTML =
        '<strong>Venta:</strong> ' + p.NumerodeVenta +
        ' &nbsp;&middot;&nbsp; <strong>Cliente:</strong> ' + (p.ClienteNombre ?? '') + ' ' + (p.ClienteApellido ?? '') +
        '<br><strong>Productos:</strong> ' + (p.Productos ?? '—') +
        '<br><strong>Total:</strong> $' + Number(p.MontoTotal ?? 0).toLocaleString('es-AR') +
        (p.CodigoEntrega ? '<br><strong>Entrega:</strong> ' + p.CodigoEntrega + ' (' + (p.EstadoEntrega ?? '—') + ')' : '');
    var bajaLink = document.getElementById('eBajaLink');
    if (bajaLink) bajaLink.href = '<?= URL ?>pedido/baja/' + p.Id;
    document.getElementById('modalEditar').classList.add('open');
}
function cerrarEditar() { document.getElementById('modalEditar').classList.remove('open'); }

['modalCrear', 'modalEditar', 'modalMasivoEstado'].forEach(function (id) {
    document.getElementById(id).addEventListener('click', function (e) {
        if (e.target === this) this.classList.remove('open');
    });
});

function toggleSelectionMode() {
    const zona = document.getElementById('zonaSeleccion');
    const bar  = document.getElementById('bulkActionsBar');
    const btn  = document.getElementById('btnToggleSelection');
    zona.classList.toggle('selection-mode');
    if (!zona.classList.contains('selection-mode')) {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
        bar.classList.remove('active');
        btn.textContent = 'Selección múltiple';
    } else {
        btn.textContent = 'Cancelar selección';
    }
}
function actualizarBarraMasiva() {
    const checkboxes = document.querySelectorAll('.row-checkbox:checked');
    const bar = document.getElementById('bulkActionsBar');
    document.getElementById('selectedCount').textContent = checkboxes.length;
    bar.classList.toggle('active', checkboxes.length > 0);
}
function ejecutarAccionMasiva(accion) {
    const seleccionados = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
    if (seleccionados.length === 0) { alert('Por favor seleccioná al menos un pedido.'); return; }
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
function cerrarMasivoEstado() { document.getElementById('modalMasivoEstado').classList.remove('open'); }
function enviarAccionPorPost(url, ids) {
    const form = document.createElement('form');
    form.method = 'POST'; form.action = url;
    const input = document.createElement('input');
    input.type = 'hidden'; input.name = 'ids'; input.value = JSON.stringify(ids);
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

let cardArrastrada = null;

document.querySelectorAll('.ped-card').forEach(function (card) {
    card.addEventListener('dragstart', function (e) {
        if (document.getElementById('zonaSeleccion').classList.contains('selection-mode')) {
            e.preventDefault();
            return;
        }
        cardArrastrada = card;
        card.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
    });
    card.addEventListener('dragend', function () {
        card.classList.remove('dragging');
        cardArrastrada = null;
    });
});

document.querySelectorAll('.kanban-col, .seccion-listo').forEach(function (zona) {
    zona.addEventListener('dragover', function (e) {
        e.preventDefault();
        zona.classList.add('drag-over');
    });
    zona.addEventListener('dragleave', function () {
        zona.classList.remove('drag-over');
    });
    zona.addEventListener('drop', function (e) {
        e.preventDefault();
        zona.classList.remove('drag-over');
        if (!cardArrastrada) return;

        const idPedido     = cardArrastrada.dataset.id;
        const estadoActual = cardArrastrada.dataset.estado;
        const estadoNuevo  = zona.dataset.estado;

        if (estadoActual === estadoNuevo) return;

        fetch('<?= URL ?>pedido/cambiarEstadoRapido', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(idPedido) + '&estado=' + encodeURIComponent(estadoNuevo)
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                location.reload();
            } else {
                alert(data.error || 'No se pudo cambiar el estado.');
            }
        })
        .catch(() => alert('Error de conexión al cambiar el estado.'));
    });
});
</script>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
<?php include INCLUDES . 'admin' . DS . 'modals_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'scripts_admin.php'; ?>