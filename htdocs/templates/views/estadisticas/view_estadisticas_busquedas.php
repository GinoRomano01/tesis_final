<?php
$terminosJson = json_encode(array_values($terminos_top   ?? []));
$sinResJson   = json_encode(array_values($sin_resultados ?? []));
$gTop  = $graf['bus_top'];
$gNube = $graf['bus_nube'];
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
            <h1 class="est-title">Búsquedas <span>y demanda</span></h1>
            <div class="est-subtitle">Actualizado <?= date('d/m/Y H:i') ?></div>
        </div>
        <a href="<?= URL ?>estadisticas/exportar/busquedas" class="export-btn">Exportar CSV</a>
    </div>

    <?php include INCLUDES . 'admin/filtro_fechas.php'; ?>

    <?= Toast::flash() ?>

    <?php if (!empty($sin_resultados)): ?>
    <div class="alerta-box">
        <div class="alerta-titulo">
            Atención: <?= count($sin_resultados) ?> término(s) sin resultados en el catálogo
        </div>
        <p class="alerta-desc">
            Estas búsquedas no encontraron productos.
            Considerar agregar productos nuevos o revisar los nombres del catálogo.
        </p>
    </div>
    <?php endif; ?>

    <div class="chart-card" id="g-bus_top">
        <div class="chart-header">
            <div class="chart-title">Top 10 términos</div>
            <div class="chart-acciones">
                <span class="chart-badge">Búsquedas (u.)</span>
                <?php ffFiltroGrafico($gTop); ?>
            </div>
        </div>
        <?= ffChip($gTop, true, 'barra') ?>
        <?php if (!empty($top10)): ?>
        <div class="chart-box<?= $gTop['cmp']['activo'] ? ' alto' : '' ?>"><canvas id="chartBusq"></canvas></div>
        <?php else: ?>
        <div class="empty-state">Sin búsquedas en el período</div>
        <?php endif; ?>
    </div>

    <div class="charts-row">
        <div class="chart-card" id="g-bus_nube">
            <div class="chart-header">
                <div class="chart-title">Nube de términos buscados</div>
                <div class="chart-acciones">
                    <?php ffFiltroGrafico($gNube); ?>
                </div>
            </div>
            <?= ffChip($gNube, true, 'nube') ?>
            <?php $nubeCmp = $gNube['cmp']['activo']; ?>
            <div class="nube">
                <?php
                $max  = max(1, ...array_column($nube ?: [['veces'=>1]], 'veces'));
                foreach (($nube ?? []) as $t):
                    $ratio = $t['veces'] / $max;
                    $cls   = $ratio > .75 ? 't1' : ($ratio > .5 ? 't2' : ($ratio > .25 ? 't3' : 't4'));
                    $title = (int)$t['veces'] . ' búsquedas — ' . round($t['resultados_promedio'] ?? 0) . ' resultados promedio';
                    if ($nubeCmp) {
                        $title .= ' · período comparado: ' . (int)$t['cmp'] . ' búsquedas';
                    }
                ?>
                <span class="termino <?= $cls ?>" title="<?= htmlspecialchars($title) ?>">
                    <?= htmlspecialchars($t['termino']) ?><?= $nubeCmp ? ffVariacion($t) : '' ?>
                </span>
                <?php endforeach; ?>
                <?php if (empty($nube)): ?>
                <div class="empty-state">Sin búsquedas en el período. El tracker.js las registra automáticamente.</div>
                <?php endif; ?>
            </div>
            <?php if ($nubeCmp && !empty($nube)): ?>
            <div class="leyenda-cmp">
                <span><span class="var-cmp sube">▲</span> se buscó más</span>
                <span><span class="var-cmp baja">▼</span> se buscó menos</span>
                <span><span class="var-cmp nuevo">nuevo</span> no se buscó en el período comparado</span>
            </div>
            <?php endif; ?>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title chart-title-danger">Sin resultados — oportunidades de catálogo</div>
            </div>
            <?= ffChip($rango) ?>
            <div class="sin-res">
                <?php foreach (($sin_resultados ?? []) as $s): ?>
                <div class="sin-row">
                    <span class="sin-label"><?= htmlspecialchars($s['termino']) ?></span>
                    <span class="sin-count"><?= (int)$s['veces'] ?> búsquedas</span>
                </div>
                <?php endforeach; ?>
                <?php if (empty($sin_resultados)): ?>
                <div class="ok-msg">Todas las búsquedas del período tienen resultados en el catálogo</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title">Todos los términos</div>
            <a href="<?= URL ?>estadisticas/exportar/busquedas" class="card-link">Exportar CSV</a>
        </div>
        <?= ffChip($rango) ?>
        <div class="tabla-scroll">
        <table class="rank-table" id="tablaBusquedas">
            <thead>
                <tr>
                    <th class="col-pos" onclick="sortTable('tablaBusquedas',0)">Pos.</th>
                    <th onclick="sortTable('tablaBusquedas',1)">Término</th>
                    <th class="num" onclick="sortTable('tablaBusquedas',2)">Búsquedas (u.)</th>
                    <th class="num" onclick="sortTable('tablaBusquedas',3)">Resultados prom. (u.)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $maxT = max(1, ...array_column($terminos_top ?: [['veces'=>1]], 'veces'));
                foreach (($terminos_top ?? []) as $i => $t):
                    $pct = round($t['veces'] / $maxT * 100);
                ?>
                <tr>
                    <td class="td-rank"><?= $i + 1 ?></td>
                    <td>
                        <?= htmlspecialchars($t['termino']) ?>
                        <span class="bar-inline-w">
                            <span class="bar-inline" style="width:<?= $pct ?>%"></span>
                        </span>
                    </td>
                    <td class="num accent"><?= number_format((int)$t['veces']) ?></td>
                    <td class="num">
                        <?= $t['resultados_promedio'] !== null ? number_format(round($t['resultados_promedio'])) : '—' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($terminos_top)): ?>
                <tr><td colspan="4" class="empty-state">Sin datos</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>

</div>
</main>
</div>

<script src="<?= URL ?>templates/assets/js/estadisticas-charts.js?v=4"></script>
<script>
const [C_CAOBA] = SP_CHART.serie;

const top10 = <?= json_encode(array_values($top10 ?? [])) ?>;

if (document.getElementById('chartBusq')) {

    const dsBusq = [{ label: 'Búsquedas — <?= ffPeriodo($gTop['desde'], $gTop['hasta']) ?>',
        data: top10.map(r => parseInt(r.veces)),
        backgroundColor: SP_CHART.alpha(C_CAOBA, .8), borderRadius: 4 }];
    if (top10.length && 'cmp' in top10[0]) {
        dsBusq.push(SP_CHART.cmpBarra('Período comparado — <?= ffPeriodo($gTop['cmp']['desde'], $gTop['cmp']['hasta']) ?>', top10));
    }
    new Chart(document.getElementById('chartBusq'), {
        type: 'bar',
        data: {
            labels: top10.map(r => r.termino),
            datasets: dsBusq
        },
        options: {
            indexAxis: 'y',
            plugins: {
                legend: { display: dsBusq.length > 1 },
                tooltip: { callbacks: { label: ctx => ' ' + ctx.dataset.label + ': ' + ctx.parsed.x + ' búsquedas' } }
            },
            scales: {
                x: { grid: { color: SP_CHART.grid }, border: { display: false },
                     beginAtZero: true, ticks: { precision: 0 } },
                y: { grid: { display: false }, ticks: { color: SP_CHART.tinta } }
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
