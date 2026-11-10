<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<main class="content">

<style>
.eg-page { font-family: var(--font-body, system-ui, -apple-system, sans-serif); color: var(--tinta, #2c1a0e); padding: 1.5rem; }
.eg-hd { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem; margin-bottom: 1.4rem; }
.eg-hd h1 { font-family: var(--font-display, inherit); font-size: 1.7rem; font-weight: 700; color: var(--caoba, #6b2d14); margin: 0; }
.eg-hd-links { display: flex; gap: .5rem; flex-wrap: wrap; }

.stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.4rem; }
.stat-card {
    display: flex; align-items: center; gap: 1rem; padding: 1.1rem 1.3rem; border-radius: 14px;
    text-decoration: none; color: #fff; box-shadow: 0 4px 14px rgba(0,0,0,.1);
    transition: transform .15s ease, box-shadow .15s ease; border: 2px solid transparent;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.16); color: #fff; }
.stat-card.activo { border-color: rgba(255,255,255,.85); }
.stat-card-icon { width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,.22); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
.stat-card-num { font-family: var(--font-display, inherit); font-size: 1.55rem; font-weight: 800; line-height: 1.1; }
.stat-card-label { font-size: .78rem; font-weight: 600; opacity: .92; }
.stat-card.c-todos      { background: linear-gradient(135deg, var(--caoba, #6b2d14), #8a3a1b); }
.stat-card.c-1 { background: linear-gradient(135deg, #d99a1f, #b78103); }
.stat-card.c-2 { background: linear-gradient(135deg, #7e8fa8, #566384); }
.stat-card.c-3 { background: linear-gradient(135deg, #9b6fd6, #6a3fb5); }
.stat-card.c-4 { background: linear-gradient(135deg, #4a97e8, #1565c0); }
.stat-card.c-5 { background: linear-gradient(135deg, #52ab63, #2e7d32); }

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
.modal-divider { font-size: .75rem; font-weight: 700; text-transform: uppercase; color: var(--g1, #8a7560); letter-spacing: .04em; margin: 1rem 0 .6rem; border-bottom: 1px solid var(--borde, #e2d9ce); padding-bottom: .4rem; }
.fld-row { display: grid; grid-template-columns: 1fr 1fr; gap: .8rem; }
@media (max-width: 480px) { .fld-row { grid-template-columns: 1fr; } }

.fld { margin-bottom: 1.1rem; }
.fld label { display: block; margin-bottom: .35rem; font-size: .77rem; font-weight: 700; color: var(--tinta2, #554438); text-transform: uppercase; }
.fld input, .fld select, .fld textarea { width: 100%; background: var(--lino, #faf8f5); border: 1.5px solid var(--borde, #e2d9ce); border-radius: 8px; padding: .55rem .9rem; font-size: .9rem; color: var(--tinta, #2c1a0e); }
.fld input:focus, .fld select:focus, .fld textarea:focus { outline: none; border-color: var(--caoba, #6b2d14); background: #fff; box-shadow: 0 0 0 3px rgba(107,45,20,.1); }

.bulk-actions-bar { display: none; align-items: center; justify-content: space-between; background: var(--lino2, #f3f0ea); border: 1.5px solid var(--caoba, #6b2d14); border-radius: 12px; padding: .8rem 1.3rem; margin-bottom: 1.1rem; }
.bulk-actions-bar.active { display: flex; }

.kanban-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; align-items: start; margin-bottom: 1.3rem; }
@media (max-width: 1100px) { .kanban-row { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 560px)  { .kanban-row { grid-template-columns: 1fr; } }

.kanban-col { background: var(--papel, #fff); border: 1.5px solid var(--borde, #e2d9ce); border-radius: 14px; overflow: hidden; min-width: 0; transition: background .15s, border-color .15s; box-shadow: 0 2px 10px rgba(0,0,0,.04); }
.kanban-col.drag-over { background: #fdf3e7; border-color: var(--amb, #b8722a); }
.kanban-col-hd { display: flex; justify-content: space-between; align-items: center; padding: .85rem 1.1rem; color: #fff; }
.kanban-col[data-estado="1"] .kanban-col-hd { background: linear-gradient(135deg, #d99a1f, #b78103); }
.kanban-col[data-estado="2"] .kanban-col-hd { background: linear-gradient(135deg, #7e8fa8, #566384); }
.kanban-col[data-estado="3"] .kanban-col-hd { background: linear-gradient(135deg, #9b6fd6, #6a3fb5); }
.kanban-col[data-estado="4"] .kanban-col-hd { background: linear-gradient(135deg, #4a97e8, #1565c0); }
.kanban-col-hd-title { font-weight: 700; font-size: .8rem; letter-spacing: .02em; }
.kanban-col-hd-count { background: rgba(255,255,255,.28); border-radius: 20px; padding: 2px 10px; font-size: .74rem; font-weight: 700; }
.kanban-col-cards { padding: .9rem; }
.kanban-col-nota { font-size: .7rem; color: var(--g1, #8a7560); text-align: center; padding: 0 .9rem .7rem; font-style: italic; }

.seccion-listo { background: var(--papel, #fff); border: 1.5px solid #a5d6a7; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,.04); }
.seccion-listo-hd { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.2rem; background: linear-gradient(135deg, #52ab63, #2e7d32); color: #fff; }
.seccion-listo-hd-title { font-weight: 700; font-size: .92rem; letter-spacing: .02em; }
.seccion-listo-hd small { display: block; font-weight: 400; color: rgba(255,255,255,.85); font-size: .74rem; margin-top: 2px; }
.seccion-listo-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: .8rem; padding: 1.1rem; }

.ent-card { background: var(--papel, #fff); border: 1.5px solid var(--borde, #e2d9ce); border-radius: 12px; padding: .9rem 1rem; margin-bottom: .7rem; cursor: pointer; transition: box-shadow .15s, transform .1s, opacity .15s; position: relative; }
.ent-card[draggable="true"] { cursor: grab; }
.kanban-col-cards .ent-card:last-child { margin-bottom: 0; }
.ent-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,.1); transform: translateY(-1px); border-color: var(--caoba, #6b2d14); }
.ent-card.dragging { opacity: .4; }
.ent-card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: .55rem; gap: .5rem; }
.ent-card-cliente { font-weight: 700; color: var(--caoba, #6b2d14); font-size: .92rem; line-height: 1.25; }
.ent-pill { flex-shrink: 0; padding: 2px 10px; border-radius: 20px; font-size: .68rem; font-weight: 700; white-space: nowrap; background: var(--lino2, #f3f0ea); color: var(--tinta2, #554438); }
.ent-card-rows { display: flex; flex-direction: column; gap: .3rem; }
.ent-card-row { display: flex; justify-content: space-between; align-items: baseline; font-size: .78rem; gap: .5rem; }
.ent-card-row .k { color: var(--g1, #8a7560); flex-shrink: 0; }
.ent-card-row .v { color: var(--tinta2, #554438); text-align: right; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 65%; }
.ent-card-row .v.monto { font-weight: 700; color: #2e7d32; font-family: var(--font-display, inherit); font-size: .9rem; }
.ent-card-check { position: absolute; top: .7rem; right: .7rem; width: 16px; height: 16px; cursor: pointer; accent-color: var(--caoba, #6b2d14); display: none; }
.selection-mode .ent-card-check { display: block; }
.selection-mode .ent-card-top { padding-right: 1.6rem; }

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
$totalGen = array_sum($conteos);
$nombresEstado = [1 => 'Pendiente', 2 => 'En preparación', 3 => 'Listo para retirar', 4 => 'En camino', 5 => 'Entregado'];
$iconosEstado  = [1 => '⏳', 2 => '📦', 3 => '🏷️', 4 => '🚚', 5 => '✓'];

$ordenesDisponibles = [
    'fecha_desc'  => 'Fecha: más nueva primero',
    'fecha_asc'   => 'Fecha: más antigua primero',
    'monto_desc'  => 'Costo envío: mayor a menor',
    'monto_asc'   => 'Costo envío: menor a mayor',
    'tiempo_desc' => 'Tiempo de fabricación: mayor a menor',
    'tiempo_asc'  => 'Tiempo de fabricación: menor a mayor',
];

$urlFiltro = function(int $idEst = 0) use ($buscar, $orden): string {
    $qs = [];
    if ($idEst > 0) $qs['estado'] = $idEst;
    if ($buscar !== '') $qs['buscar'] = $buscar;
    if ($orden !== 'fecha_desc') $qs['orden'] = $orden;
    return URL . 'entrega' . (!empty($qs) ? '?' . http_build_query($qs) : '');
};

$urlPagCol = function(int $idEstCol, int $pagina) use ($buscar, $orden, $estadosColumnas, $idEstadoAncho): string {
    $qs = [];
    if ($buscar !== '') $qs['buscar'] = $buscar;
    if ($orden !== 'fecha_desc') $qs['orden'] = $orden;
    $todos = array_keys($estadosColumnas);
    $todos[] = $idEstadoAncho;
    foreach ($todos as $idE) {
        $key = 'p_' . $idE;
        if ($idE === $idEstCol) {
            $qs[$key] = $pagina;
        } elseif (!empty($_GET[$key])) {
            $qs[$key] = $_GET[$key];
        }
    }
    return URL . 'entrega?' . http_build_query($qs);
};

$urlPagGeneral = function(int $pagina) use ($buscar, $idEstadoFiltro, $orden): string {
    $qs = ['estado' => $idEstadoFiltro, 'pagina' => $pagina];
    if ($buscar !== '') $qs['buscar'] = $buscar;
    if ($orden !== 'fecha_desc') $qs['orden'] = $orden;
    return URL . 'entrega?' . http_build_query($qs);
};

$renderCard = function(array $e) use ($estadosDrag) {
    $tiempoFab = (float)($e['TiempoFabricacionTotal'] ?? 0);
    $puedeArrastrar = in_array((int)$e['IdEstadosdeEntrega'], $estadosDrag, true);
    ?>
    <div class="ent-card" <?= $puedeArrastrar ? 'draggable="true"' : '' ?>
         data-id="<?= $e['Id'] ?>" data-estado="<?= (int)$e['IdEstadosdeEntrega'] ?>"
         onclick="abrirModal(<?= htmlspecialchars(json_encode($e)) ?>)">
        <input type="checkbox" class="row-checkbox ent-card-check" value="<?= $e['Id'] ?>"
               onclick="event.stopPropagation()" onchange="actualizarBarraMasiva()">
        <div class="ent-card-top">
            <div class="ent-card-cliente"><?= htmlspecialchars($e['ClienteNombre'] . ' ' . $e['ClienteApellido']) ?></div>
            <?php if (!empty($e['CodigoEntrega'])): ?>
            <span class="ent-pill"><?= htmlspecialchars($e['CodigoEntrega']) ?></span>
            <?php endif; ?>
        </div>
        <div class="ent-card-rows">
            <div class="ent-card-row"><span class="k">Venta</span><span class="v">#<?= htmlspecialchars($e['NumerodeVenta'] ?? '—') ?></span></div>
            <div class="ent-card-row"><span class="k">Productos</span><span class="v" title="<?= htmlspecialchars($e['Productos'] ?? '—') ?>"><?= htmlspecialchars($e['Productos'] ?? '—') ?></span></div>
            <div class="ent-card-row"><span class="k">Tipo</span><span class="v"><?= htmlspecialchars($e['TipoEntregaNombre'] ?? '—') ?></span></div>
            <?php if ($tiempoFab > 0): ?>
            <div class="ent-card-row"><span class="k">Fabricación</span><span class="v"><?= number_format($tiempoFab, 0) ?>h</span></div>
            <?php endif; ?>
            <div class="ent-card-row"><span class="k">Envío</span>
                <span class="v monto"><?= $e['CostoEnvio'] > 0 ? '$' . number_format($e['CostoEnvio'], 0, ',', '.') : 'Gratis' ?></span>
            </div>
        </div>
    </div>
    <?php
};
?>

<div class="eg-page">

    <div class="eg-hd">
        <h1>Entregas</h1>
        <div class="eg-hd-links">
            <?php if (function_exists('puedeIniciarEntrega') && puedeIniciarEntrega()): ?>
            <a href="<?= URL ?>entrega/iniciarentrega" class="btn-eg btn-eg-secondary">🚚 Iniciar entrega</a>
            <?php endif; ?>
            <?php if (function_exists('esRepartidor') && esRepartidor()): ?>
            <a href="<?= URL ?>entrega/miruta" class="btn-eg btn-eg-secondary">📍 Mi ruta</a>
            <?php endif; ?>
        </div>
    </div>

    <?= Toast::flash() ?>

    <div class="stats-row">
        <a href="<?= $urlFiltro() ?>" class="stat-card c-todos <?= $idEstadoFiltro === 0 ? 'activo' : '' ?>">
            <div class="stat-card-icon">📋</div>
            <div><div class="stat-card-num"><?= $totalGen ?></div><div class="stat-card-label">Todos</div></div>
        </a>
        <?php foreach ($nombresEstado as $idEst => $nombre): ?>
        <a href="<?= $urlFiltro($idEst) ?>" class="stat-card c-<?= $idEst ?> <?= $idEstadoFiltro === $idEst ? 'activo' : '' ?>">
            <div class="stat-card-icon"><?= $iconosEstado[$idEst] ?></div>
            <div><div class="stat-card-num"><?= $conteos[$nombre] ?? 0 ?></div><div class="stat-card-label"><?= htmlspecialchars($nombre) ?></div></div>
        </a>
        <?php endforeach; ?>
    </div>

    <form class="eg-filters" method="GET" action="<?= URL ?>entrega">
        <?php if ($idEstadoFiltro > 0): ?><input type="hidden" name="estado" value="<?= $idEstadoFiltro ?>"><?php endif; ?>
        <input type="text" name="buscar" value="<?= htmlspecialchars($buscar) ?>" placeholder="Buscar por cliente, producto o código de envío...">
        <label for="selOrden">Ordenar por</label>
        <select name="orden" id="selOrden" onchange="this.form.submit()">
            <?php foreach ($ordenesDisponibles as $val => $label): ?>
            <option value="<?= $val ?>" <?= $orden === $val ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-eg btn-eg-primary">Buscar</button>
        <?php if (!empty($buscar) || $idEstadoFiltro > 0 || $orden !== 'fecha_desc'): ?>
        <a href="<?= URL ?>entrega" class="btn-eg btn-eg-secondary">Limpiar</a>
        <?php endif; ?>
    </form>

    <div style="margin-bottom: 0.8rem; display: flex; justify-content: flex-end;">
        <button type="button" class="btn-eg btn-eg-secondary btn-eg-sm" id="btnToggleSelection" onclick="toggleSelectionMode()">Selección múltiple</button>
    </div>

    <div class="bulk-actions-bar" id="bulkActionsBar">
        <div style="font-size: 0.86rem; font-weight: 700; color: var(--caoba, #6b2d14);">
            <span id="selectedCount">0</span> elemento(s) seleccionado(s)
        </div>
        <div style="display: flex; gap: 0.5rem; align-items:center;">
            <select id="bulkEstadoSelect" style="padding:.4rem .7rem;border-radius:8px;border:1.5px solid var(--borde,#e2d9ce);background:var(--papel,#fff);">
                <?php foreach ($estadosTodos as $est): ?>
                <option value="<?= $est['Id'] ?>"><?= htmlspecialchars($est['Nombre']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="button" class="btn-eg btn-eg-primary btn-eg-sm" onclick="ejecutarAccionMasiva()">Cambiar estado</button>
        </div>
    </div>

    <div id="zonaSeleccion">
    <?php if ($idEstadoFiltro > 0): ?>

        <?php if (empty($items)): ?>
            <div class="eg-vacio"><span>—</span><p>No hay entregas en "<?= htmlspecialchars($nombresEstado[$idEstadoFiltro] ?? '') ?>"<?= !empty($buscar) ? ' para "'.htmlspecialchars($buscar).'"' : '' ?>.</p></div>
        <?php else: ?>
            <div class="seccion-unica-grid"><?php foreach ($items as $e) $renderCard($e); ?></div>
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

        <p class="drag-hint">💡 Arrastrá una tarjeta entre Pendiente, En preparación y Listo para retirar para cambiarle el estado. "En camino" se maneja desde "Iniciar entrega".</p>

        <div class="kanban-row">
            <?php foreach ($estadosColumnas as $idEst => $nombreEst): $col = $columnas[$idEst]; $esDrag = in_array($idEst, $estadosDrag, true); ?>
            <div class="kanban-col" data-estado="<?= $idEst ?>" <?= $esDrag ? '' : 'data-nodrop="1"' ?>>
                <div class="kanban-col-hd">
                    <span class="kanban-col-hd-title"><?= htmlspecialchars($nombreEst) ?></span>
                    <span class="kanban-col-hd-count"><?= $col['total'] ?></span>
                </div>
                <div class="kanban-col-cards">
                    <?php if (empty($col['items'])): ?>
                        <div class="kanban-col-vacio">Sin entregas acá</div>
                    <?php else: ?>
                        <?php foreach ($col['items'] as $e) $renderCard($e); ?>
                    <?php endif; ?>
                </div>
                <?php if (!$esDrag): ?><div class="kanban-col-nota">Se arma desde "Iniciar entrega"</div><?php endif; ?>
                <?php if ($col['totalPaginas'] > 1): ?>
                <nav class="kanban-col-pag">
                    <?php if ($col['pagina'] > 1): ?><a href="<?= $urlPagCol($idEst, $col['pagina'] - 1) ?>">‹</a><?php endif; ?>
                    <span class="actual"><?= $col['pagina'] ?>/<?= $col['totalPaginas'] ?></span>
                    <?php if ($col['pagina'] < $col['totalPaginas']): ?><a href="<?= $urlPagCol($idEst, $col['pagina'] + 1) ?>">›</a><?php endif; ?>
                </nav>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="seccion-listo" data-estado="<?= $idEstadoAncho ?>" data-nodrop="1">
            <div class="seccion-listo-hd">
                <div><span class="seccion-listo-hd-title">Entregado</span><small>Ya llegó a destino — historial de entregas completadas</small></div>
                <span class="kanban-col-hd-count"><?= $seccionAncha['total'] ?></span>
            </div>
            <div class="seccion-listo-grid">
                <?php if (empty($seccionAncha['items'])): ?>
                    <div class="seccion-listo-vacio">Todavía no hay entregas completadas</div>
                <?php else: ?>
                    <?php foreach ($seccionAncha['items'] as $e) $renderCard($e); ?>
                <?php endif; ?>
            </div>
            <?php if ($seccionAncha['totalPaginas'] > 1): ?>
            <nav class="pag-general">
                <?php if ($seccionAncha['pagina'] > 1): ?><a href="<?= $urlPagCol($idEstadoAncho, $seccionAncha['pagina'] - 1) ?>">‹</a><?php endif; ?>
                <?php for ($i = 1; $i <= $seccionAncha['totalPaginas']; $i++): ?>
                    <?php if ($i === $seccionAncha['pagina']): ?><span class="actual"><?= $i ?></span>
                    <?php else: ?><a href="<?= $urlPagCol($idEstadoAncho, $i) ?>"><?= $i ?></a><?php endif; ?>
                <?php endfor; ?>
                <?php if ($seccionAncha['pagina'] < $seccionAncha['totalPaginas']): ?><a href="<?= $urlPagCol($idEstadoAncho, $seccionAncha['pagina'] + 1) ?>">›</a><?php endif; ?>
            </nav>
            <?php endif; ?>
        </div>

    <?php endif; ?>
    </div>

</div>

<div class="modal-bg" id="modalBg">
    <div class="modal">
        <div class="modal-hd"><h3 id="modalTitulo">Editar entrega</h3><button class="modal-close" onclick="cerrarModal()">✕</button></div>
        <form method="POST" action="<?= URL ?>entrega/guardar">
            <input type="hidden" name="Id" id="fId" value="">
            <div class="modal-body">
                <div class="modal-info" id="infoCliente">
                    <strong>Cliente:</strong> <span id="infoNombre">—</span>
                    &nbsp;|&nbsp;
                    <strong>Venta:</strong> <span id="infoVenta">—</span>
                </div>
                <div class="modal-divider">Datos de la entrega</div>
                <div class="fld-row">
                    <div class="fld">
                        <label>Estado</label>
                        <select name="IdEstadosdeEntrega" id="fEstado" required>
                            <?php foreach ($estadosTodos as $est): ?>
                            <option value="<?= $est['Id'] ?>"><?= htmlspecialchars($est['Nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="fld">
                        <label>Tipo de entrega</label>
                        <select name="IdTipodeEntrega" id="fTipo" required>
                            <option value="">Seleccionar</option>
                            <?php foreach ($tipos as $t): ?>
                            <option value="<?= $t['Id'] ?>"><?= htmlspecialchars($t['Nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="fld"><label>Código de envío</label><input type="text" name="CodigoEntrega" id="fCodigo" placeholder="SP-000123" maxlength="20"></div>
                <div class="fld"><label>Dirección de entrega</label><input type="text" name="Direccion" id="fDireccion" placeholder="Calle 123, Ciudad" maxlength="200"></div>
                <div class="fld-row">
                    <div class="fld"><label>Fecha de entrega</label><input type="date" name="FechadeEntrega" id="fFecha"></div>
                    <div class="fld"><label>Costo de envío ($)</label><input type="number" name="CostoEnvio" id="fCosto" min="0" step="0.01" placeholder="0.00"></div>
                </div>
            </div>
            <div class="modal-ft">
                <button type="button" class="btn-eg btn-eg-secondary" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn-eg btn-eg-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModal(e) {
    document.getElementById('fId').value          = e.Id;
    document.getElementById('fEstado').value      = e.IdEstadosdeEntrega ?? '';
    document.getElementById('fTipo').value        = e.IdTipodeEntrega    ?? '';
    document.getElementById('fCodigo').value      = e.CodigoEntrega      ?? '';
    document.getElementById('fDireccion').value   = e.Direccion          ?? '';
    document.getElementById('fCosto').value       = e.CostoEnvio         ?? '0';
    document.getElementById('fFecha').value       = (e.FechadeEntrega ?? '').substring(0, 10);
    document.getElementById('infoNombre').textContent = (e.ClienteNombre ?? '') + ' ' + (e.ClienteApellido ?? '');
    document.getElementById('infoVenta').textContent  = e.NumerodeVenta ?? '—';
    document.getElementById('modalTitulo').textContent = 'Editar entrega ' + e.Id;
    document.getElementById('modalBg').classList.add('open');
}
function cerrarModal() { document.getElementById('modalBg').classList.remove('open'); }
document.getElementById('modalBg').addEventListener('click', function (ev) { if (ev.target === this) cerrarModal(); });

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
function ejecutarAccionMasiva() {
    const nuevoEstado = document.getElementById('bulkEstadoSelect').value;
    const seleccionados = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
    if (seleccionados.length === 0) { alert('No hay elementos seleccionados.'); return; }
    if (confirm('¿Cambiar el estado de ' + seleccionados.length + ' entrega(s)?')) {
        enviarAccionPorPost('<?= URL ?>entrega/estadoMasivo', seleccionados, nuevoEstado);
    }
}
function enviarAccionPorPost(url, ids, estado) {
    const form = document.createElement('form');
    form.method = 'POST'; form.action = url;
    const inputIds = document.createElement('input');
    inputIds.type = 'hidden'; inputIds.name = 'ids'; inputIds.value = JSON.stringify(ids);
    form.appendChild(inputIds);
    const inputEstado = document.createElement('input');
    inputEstado.type = 'hidden'; inputEstado.name = 'estado'; inputEstado.value = estado;
    form.appendChild(inputEstado);
    document.body.appendChild(form);
    form.submit();
}

let cardArrastrada = null;

document.querySelectorAll('.ent-card[draggable="true"]').forEach(function (card) {
    card.addEventListener('dragstart', function (e) {
        if (document.getElementById('zonaSeleccion').classList.contains('selection-mode')) { e.preventDefault(); return; }
        cardArrastrada = card;
        card.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
    });
    card.addEventListener('dragend', function () {
        card.classList.remove('dragging');
        cardArrastrada = null;
    });
});

document.querySelectorAll('.kanban-col:not([data-nodrop])').forEach(function (zona) {
    zona.addEventListener('dragover', function (e) { e.preventDefault(); zona.classList.add('drag-over'); });
    zona.addEventListener('dragleave', function () { zona.classList.remove('drag-over'); });
    zona.addEventListener('drop', function (e) {
        e.preventDefault();
        zona.classList.remove('drag-over');
        if (!cardArrastrada) return;

        const idEntrega    = cardArrastrada.dataset.id;
        const estadoActual = cardArrastrada.dataset.estado;
        const estadoNuevo  = zona.dataset.estado;
        if (estadoActual === estadoNuevo) return;

        fetch('<?= URL ?>entrega/cambiarEstadoRapido', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(idEntrega) + '&estado=' + encodeURIComponent(estadoNuevo)
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) { location.reload(); }
            else { alert(data.error || 'No se pudo cambiar el estado.'); }
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
