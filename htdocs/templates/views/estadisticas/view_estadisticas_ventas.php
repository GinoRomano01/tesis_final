<?php
$evolFilas   = $evolucion['filas']    ?? [];
$porMes      = ($evolucion['agrupado'] ?? 'dia') === 'mes';
$evolJson    = json_encode(array_values($evolFilas));
$pagosJson   = json_encode(array_values($por_tipo_pago ?? []));
$hayEvol     = array_sum(array_column($evolFilas, 'ordenes')) > 0;
$evolCmpJson = json_encode(array_values($evolucion_cmp['filas'] ?? []));
$gEvol = $graf['ven_evolucion'];
$gProd = $graf['ven_productos'];
$gCli  = $graf['ven_clientes'];
?>
<?php require_once INCLUDES . 'admin/head_admin.php'; ?>
<link rel="stylesheet" href="<?= URL ?>templates/assets/css/estadisticas.css?v=6">
<?php require_once INCLUDES . 'admin/sidebar_admin.php'; ?>

<div class="main-area">
<?php require_once INCLUDES . 'admin/topbar_admin.php'; ?>

<main class="content">
<div class="est-wrap">

    <div class="est-header">
        <div>
            <h1 class="est-title">Ventas <span>detalle</span></h1>
            <div class="est-subtitle">Actualizado <?= date('d/m/Y H:i') ?></div>
        </div>
        <a href="<?= URL ?>estadisticas/exportar/ventas" class="export-btn">Exportar CSV</a>
    </div>

    <?php include INCLUDES . 'admin/filtro_fechas.php'; ?>

    <?= Toast::flash() ?>

    <div class="chart-card" id="g-ven_evolucion">
        <div class="chart-header">
            <div class="chart-title">Evolución de ventas <?= $porMes ? 'por mes' : 'por día' ?></div>
            <div class="chart-acciones">
                <span class="chart-badge">Monto + cantidad de órdenes</span>
                <?php ffFiltroGrafico($gEvol); ?>
            </div>
        </div>
        <?= ffChip($gEvol, true) ?>
        <?php if ($hayEvol): ?>
        <div class="chart-box grande"><canvas id="chartEvolucion"></canvas></div>
        <?php else: ?>
        <div class="empty-state">Sin ventas aprobadas en el período</div>
        <?php endif; ?>
    </div>

    <div class="charts-row">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Estado de las ventas en el período</div>
            </div>
            <?= ffChip($rango) ?>
            <div class="estado-badges">
                <?php
                $colores = [
                    'Aprobado'  => 'var(--verde)',
                    'Pendiente' => 'var(--amb)',
                    'Rechazado' => 'var(--rojo)',
                ];
                $maxEst = max(1, ...array_column($estado ?: [['cantidad'=>1]], 'cantidad'));
                foreach (($estado ?? []) as $e):
                    $pct = round($e['cantidad'] / $maxEst * 100);
                    $col = $colores[$e['estado']] ?? 'var(--g1)';
                ?>
                <div class="estado-row">
                    <span class="estado-label"><?= htmlspecialchars($e['estado']) ?></span>
                    <div class="estado-bar-w">
                        <div class="estado-bar" style="width:<?= $pct ?>%;background:<?= $col ?>"></div>
                    </div>
                    <div class="estado-info">
                        <span class="estado-num"><?= (int)$e['cantidad'] ?> órdenes</span>
                        <span class="estado-monto">$<?= number_format((float)($e['monto_total'] ?? 0), 0, ',', '.') ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($estado)): ?>
                <div class="empty-state">Sin ventas en el período</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Distribución por tipo de pago</div>
                <span class="chart-badge">Monto aprobado</span>
            </div>
            <?= ffChip($rango) ?>
            <?php if (!empty($por_tipo_pago)): ?>
            <div class="chart-box dona"><canvas id="chartPagos"></canvas></div>
            <?php else: ?>
            <div class="empty-state">Sin pagos aprobados en el período</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-card" id="g-ven_productos">
            <div class="chart-header">
                <div class="chart-title">Top productos</div>
                <div class="chart-acciones">
                    <a href="<?= URL ?>estadisticas/exportar/productos" class="card-link">Exportar CSV</a>
                    <?php ffFiltroGrafico($gProd); ?>
                </div>
            </div>
            <?= ffChip($gProd, true, 'tabla') ?>
            <?php $prodCmp = $gProd['cmp']['activo']; ?>
            <div class="tabla-scroll">
            <table class="rank-table" id="tablaProductos">
                <thead>
                    <tr>
                        <th class="col-pos" onclick="sortTable('tablaProductos',0)">Pos.</th>
                        <th onclick="sortTable('tablaProductos',1)">Producto</th>
                        <th class="num" onclick="sortTable('tablaProductos',2)">Uds. (u.)</th>
                        <th class="num" onclick="sortTable('tablaProductos',3)">Ingreso ($)</th>
                        <?php if ($prodCmp): ?>
                        <th class="num th-cmp" title="Unidades en el período comparado">Comparado (u.)</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($top_productos ?? []) as $i => $p): ?>
                    <tr>
                        <td class="td-rank"><?= $i + 1 ?></td>
                        <td>
                            <div class="td-name"><?= htmlspecialchars($p['nombre']) ?></div>
                            <div class="td-cat"><?= htmlspecialchars($p['categoria']) ?></div>
                        </td>
                        <td class="num accent"><?= number_format((int)$p['unidades_vendidas']) ?></td>
                        <td class="num td-money">$<?= number_format((float)$p['ingreso_total'], 0, ',', '.') ?></td>
                        <?php if ($prodCmp): ?><?= ffCeldaCmp($p) ?><?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($top_productos)): ?>
                    <tr><td colspan="<?= $prodCmp ? 5 : 4 ?>" class="empty-state">Sin ventas en el período</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>

        <div class="chart-card" id="g-ven_clientes">
            <div class="chart-header">
                <div class="chart-title">Top clientes</div>
                <div class="chart-acciones">
                    <a href="<?= URL ?>estadisticas/exportar/clientes" class="card-link">Exportar CSV</a>
                    <?php ffFiltroGrafico($gCli); ?>
                </div>
            </div>
            <?= ffChip($gCli, true, 'tabla') ?>
            <?php $cliCmp = $gCli['cmp']['activo']; ?>
            <div class="tabla-scroll">
            <table class="rank-table" id="tablaClientes">
                <thead>
                    <tr>
                        <th class="col-pos" onclick="sortTable('tablaClientes',0)">Pos.</th>
                        <th onclick="sortTable('tablaClientes',1)">Cliente</th>
                        <th class="num" onclick="sortTable('tablaClientes',2)">Órdenes (u.)</th>
                        <th class="num" onclick="sortTable('tablaClientes',3)">Total ($)</th>
                        <?php if ($cliCmp): ?>
                        <th class="num th-cmp" title="Total gastado en el período comparado">Comparado ($)</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($top_clientes ?? []) as $i => $c): ?>
                    <tr>
                        <td class="td-rank"><?= $i + 1 ?></td>
                        <td class="td-name"><?= htmlspecialchars($c['cliente']) ?></td>
                        <td class="num accent"><?= number_format((int)$c['ordenes']) ?></td>
                        <td class="num td-money">$<?= number_format((float)$c['total_gastado'], 0, ',', '.') ?></td>
                        <?php if ($cliCmp): ?><?= ffCeldaCmp($c, 'pesos') ?><?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($top_clientes)): ?>
                    <tr><td colspan="<?= $cliCmp ? 5 : 4 ?>" class="empty-state">Sin compras en el período</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>

</div>
</main>
</div>

<script src="<?= URL ?>templates/assets/js/estadisticas-charts.js?v=4"></script>
<script>
const [C_CAOBA, C_AMB] = SP_CHART.serie;

const evolRaw  = <?= $evolJson ?>;
const pagosRaw = <?= $pagosJson ?>;
const evolCmp  = <?= $evolCmpJson ?>;   

if (document.getElementById('chartEvolucion')) {
    const dsEvol = [
        { label: 'Monto ($)', formato: 'pesos',
          data: evolRaw.map(r => parseFloat(r.monto_total)),
          backgroundColor: SP_CHART.alpha(C_AMB, .7), borderRadius: 4, yAxisID: 'y', order: 2 },
        { label: 'Órdenes (u.)',
          data: evolRaw.map(r => parseInt(r.ordenes)),
          type: 'line', borderColor: C_CAOBA, borderWidth: 2.5,
          pointRadius: evolRaw.length > 60 ? 0 : 3, pointHoverRadius: 6,
          pointBackgroundColor: SP_CHART.surface, pointBorderColor: C_CAOBA, pointBorderWidth: 2,
          fill: false, tension: .3, yAxisID: 'y2', order: 1 }
    ];
    if (evolCmp.length) {
        dsEvol.push(SP_CHART.cmp('Monto — período comparado ($)',
            evolCmp.map(r => parseFloat(r.monto_total)), evolCmp.map(r => r.periodo),
            SP_CHART.serie[2], 'pesos', { yAxisID: 'y' }));
    }
    new Chart(document.getElementById('chartEvolucion'), {
        type: 'bar',
        data: {
            labels: evolRaw.map(r => SP_CHART.fechaCorta(r.periodo)),
            datasets: dsEvol
        },
        options: {
            interaction: { mode: 'index', intersect: false },
            plugins: {
                tooltip: { callbacks: { label: SP_CHART.tooltipLabel } }
            },
            scales: {
                x:  { grid: { display: false }, ticks: { maxTicksLimit: 15 } },
                y:  { grid: { color: SP_CHART.grid }, border: { display: false }, beginAtZero: true,
                      ticks: { callback: v => SP_CHART.pesosCorto(v) } },
                y2: { position: 'right', grid: { display: false }, border: { display: false },
                      beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });
}

if (document.getElementById('chartPagos')) {
    new Chart(document.getElementById('chartPagos'), {
        type: 'doughnut',
        data: {
            labels: pagosRaw.map(r => r.tipo_pago),
            datasets: [{ data: pagosRaw.map(r => parseFloat(r.monto_total)),
                backgroundColor: SP_CHART.serie,
                borderColor: SP_CHART.surface, borderWidth: 3 }]
        },
        options: {
            cutout: '62%',
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ': ' + SP_CHART.pesos(ctx.parsed) } }
            }
        }
    });
}

function sortTable(tableId, col) {
    const table = document.getElementById(tableId);
    const tbody = table.querySelector('tbody');
    const rows  = Array.from(tbody.querySelectorAll('tr'));
    const asc   = table.dataset.sortCol == col && table.dataset.sortDir == 'asc';
    table.dataset.sortCol = col;
    table.dataset.sortDir = asc ? 'desc' : 'asc';
    rows.sort((a, b) => {
        const va = a.cells[col]?.innerText.replace(/[$.,\s]/g, '') ?? '';
        const vb = b.cells[col]?.innerText.replace(/[$.,\s]/g, '') ?? '';
        const na = parseFloat(va), nb = parseFloat(vb);
        const cmp = isNaN(na) || isNaN(nb) ? va.localeCompare(vb, 'es') : na - nb;
        return asc ? -cmp : cmp;
    });
    rows.forEach(r => tbody.appendChild(r));
}
</script>
<?php require_once INCLUDES . 'admin/scripts_admin.php'; ?>
