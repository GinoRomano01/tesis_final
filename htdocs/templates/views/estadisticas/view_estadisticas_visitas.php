<?php
$visitasJson     = json_encode(array_values($visitas_diarias ?? []));
$dispositivoJson = json_encode(array_values($dispositivos   ?? []));
$eventosJson     = json_encode(array_values($eventos        ?? []));
$hayVisitas      = array_sum(array_column($visitas_diarias ?? [], 'total_visitas')) > 0;
$visitasCmpJson  = json_encode(array_values($visitas_diarias_cmp ?? []));
$gVis = $graf['vis_visitas'];
$gEve = $graf['vis_eventos'];
$gPag = $graf['vis_paginas'];
$pagCmp = $gPag['cmp']['activo'];
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
            <h1 class="est-title">Visitas <span>y tráfico</span></h1>
            <div class="est-subtitle">Solo clientes y visitantes (sin personal) — <?= date('d/m/Y H:i') ?></div>
        </div>
        <a href="<?= URL ?>estadisticas/exportar/visitas" class="export-btn">Exportar CSV</a>
    </div>

    <?php include INCLUDES . 'admin/filtro_fechas.php'; ?>

    <?= Toast::flash() ?>

    <div class="chart-card" id="g-vis_visitas">
        <div class="chart-header">
            <div class="chart-title">Visitas por día</div>
            <div class="chart-acciones">
                <span class="chart-badge"><?= $gVis['dias'] ?> días</span>
                <?php ffFiltroGrafico($gVis); ?>
            </div>
        </div>
        <?= ffChip($gVis, true) ?>
        <?php if ($hayVisitas): ?>
        <div class="chart-box grande"><canvas id="chartVisitas"></canvas></div>
        <?php else: ?>
        <div class="empty-state">Sin visitas registradas en el período. Verificar que tracker.js esté activo.</div>
        <?php endif; ?>
    </div>

    <div class="chart-card" id="g-vis_eventos">
        <div class="chart-header">
            <div class="chart-title">Eventos por tipo</div>
            <div class="chart-acciones">
                <span class="chart-badge">Cantidad (u.)</span>
                <?php ffFiltroGrafico($gEve); ?>
            </div>
        </div>
        <?= ffChip($gEve, true, 'barra') ?>
        <?php if (!empty($eventos)): ?>
        <div class="chart-box"><canvas id="chartEventos"></canvas></div>
        <?php else: ?>
        <div class="empty-state">Sin eventos registrados en el período.</div>
        <?php endif; ?>
    </div>

    <div class="charts-main">
        <div class="chart-card" id="g-vis_paginas">
            <div class="chart-header">
                <div class="chart-title">Páginas más visitadas</div>
                <div class="chart-acciones">
                    <a href="<?= URL ?>estadisticas/exportar/visitas" class="card-link">Exportar CSV</a>
                    <?php ffFiltroGrafico($gPag); ?>
                </div>
            </div>
            <?= ffChip($gPag, true, 'tabla') ?>
            <div class="tabla-scroll">
            <table class="rank-table tabla-truncada" id="tablaVisitas">
                <thead>
                    <tr>
                        <th class="col-pos" onclick="sortTable('tablaVisitas',0)">Pos.</th>
                        <th onclick="sortTable('tablaVisitas',1)">URL</th>
                        <th onclick="sortTable('tablaVisitas',2)">Título</th>
                        <th class="num" onclick="sortTable('tablaVisitas',3)">Visitas (u.)</th>
                        <th class="num" onclick="sortTable('tablaVisitas',4)">Sesiones únicas (u.)</th>
                        <th class="num" onclick="sortTable('tablaVisitas',5)">Tiempo prom. (seg.)</th>
                        <?php if ($pagCmp): ?>
                        <th class="num th-cmp" title="Visitas en el período comparado">Comparado (u.)</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($paginas_top ?? []) as $i => $pg): ?>
                    <tr>
                        <td class="td-rank"><?= $i + 1 ?></td>
                        <td class="td-name" title="<?= htmlspecialchars($pg['url']) ?>">
                            <?= htmlspecialchars(parse_url($pg['url'], PHP_URL_PATH) ?: $pg['url']) ?>
                        </td>
                        <td class="muted"><?= htmlspecialchars($pg['titulo'] ?? '—') ?></td>
                        <td class="num accent"><?= number_format($pg['total_visitas']) ?></td>
                        <td class="num"><?= number_format($pg['sesiones_unicas']) ?></td>
                        <td class="num"><?= $pg['tiempo_promedio_seg'] ? round($pg['tiempo_promedio_seg']) . 's' : '—' ?></td>
                        <?php if ($pagCmp): ?><?= ffCeldaCmp($pg) ?><?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($paginas_top)): ?>
                    <tr><td colspan="<?= $pagCmp ? 7 : 6 ?>" class="empty-state">Sin páginas registradas en el período</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Dispositivos</div>
                <span class="chart-badge">Páginas vistas</span>
            </div>
            <?= ffChip($rango) ?>
            <?php if (!empty($dispositivos)): ?>
            <div class="chart-box dona"><canvas id="chartDisp"></canvas></div>
            <?php else: ?>
            <div class="empty-state">Sin datos de dispositivos</div>
            <?php endif; ?>
        </div>
    </div>

</div>
</main>
</div>

<script src="<?= URL ?>templates/assets/js/estadisticas-charts.js?v=4"></script>
<script>
const [C_CAOBA, C_AMB] = SP_CHART.serie;

const visitasRaw     = <?= $visitasJson ?>;
const dispositivoRaw = <?= $dispositivoJson ?>;
const eventosRaw     = <?= $eventosJson ?>;
const visitasCmp     = <?= $visitasCmpJson ?>;  

if (document.getElementById('chartVisitas')) {
    const dsVisitas = [
        { label: 'Páginas vistas', data: visitasRaw.map(r => parseInt(r.total_visitas)),
          backgroundColor: SP_CHART.alpha(C_AMB, .75), borderRadius: 4, order: 1 },
        { label: 'Sesiones únicas', data: visitasRaw.map(r => parseInt(r.sesiones_unicas)),
          backgroundColor: SP_CHART.alpha(C_CAOBA, .85), borderRadius: 4, order: 1 }
    ];
    if (visitasCmp.length) {
        dsVisitas.push(SP_CHART.cmp('Páginas vistas — período comparado',
            visitasCmp.map(r => parseInt(r.total_visitas)), visitasCmp.map(r => r.fecha), SP_CHART.serie[2]));
        dsVisitas.push(SP_CHART.cmp('Sesiones únicas — período comparado',
            visitasCmp.map(r => parseInt(r.sesiones_unicas)), visitasCmp.map(r => r.fecha), C_CAOBA));
    }
    new Chart(document.getElementById('chartVisitas'), {
        type: 'bar',
        data: {
            labels: visitasRaw.map(r => SP_CHART.fechaCorta(r.fecha)),
            datasets: dsVisitas
        },
        options: {
            interaction: { mode: 'index', intersect: false },
            plugins: { tooltip: { callbacks: { label: SP_CHART.tooltipLabel } } },
            scales: {
                x: { grid: { display: false }, ticks: { maxTicksLimit: 15 } },
                y: { grid: { color: SP_CHART.grid }, border: { display: false },
                     beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });
}

if (document.getElementById('chartDisp')) {
    new Chart(document.getElementById('chartDisp'), {
        type: 'doughnut',
        data: {
            labels: dispositivoRaw.map(r => r.dispositivo),
            datasets: [{ data: dispositivoRaw.map(r => parseInt(r.total)),
                backgroundColor: SP_CHART.serie,
                borderColor: SP_CHART.surface, borderWidth: 3 }]
        },
        options: {
            cutout: '62%',
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ': ' + ctx.parsed + ' páginas vistas' } }
            }
        }
    });
}

if (document.getElementById('chartEventos')) {
    const dsEventos = [{ label: 'Eventos — <?= ffPeriodo($gEve['desde'], $gEve['hasta']) ?>',
        data: eventosRaw.map(r => parseInt(r.total)),
        backgroundColor: SP_CHART.alpha(C_CAOBA, .8), borderRadius: 4 }];
    if (eventosRaw.length && 'cmp' in eventosRaw[0]) {
        dsEventos.push(SP_CHART.cmpBarra('Período comparado — <?= ffPeriodo($gEve['cmp']['desde'], $gEve['cmp']['hasta']) ?>', eventosRaw));
    }
    new Chart(document.getElementById('chartEventos'), {
        type: 'bar',
        data: {
            labels: eventosRaw.map(r => r.tipo),
            datasets: dsEventos
        },
        options: {
            indexAxis: 'y',
            plugins: { legend: { display: dsEventos.length > 1 },
                       tooltip: { callbacks: { label: ctx => ' ' + ctx.dataset.label + ': ' + ctx.parsed.x + ' eventos' } } },
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
        const va = a.cells[col]?.innerText.replace(/[$.,s\s]/g, '') ?? '';
        const vb = b.cells[col]?.innerText.replace(/[$.,s\s]/g, '') ?? '';
        const na = parseFloat(va), nb = parseFloat(vb);
        const cmp = isNaN(na) || isNaN(nb) ? va.localeCompare(vb, 'es') : na - nb;
        return asc ? -cmp : cmp;
    });
    rows.forEach(r => tbody.appendChild(r));
}
</script>
<?php require_once INCLUDES . 'admin/scripts_admin.php'; ?>
