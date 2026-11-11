<?php
$evolucionValorJson = json_encode(array_values($evolucion_valor ?? []));
$evolucionSaludJson = json_encode(array_values($evolucion_salud ?? []));
$gValor             = $graf['sto_valor'];
$gItems             = $graf['sto_items'];
$evolucionItemsJson = json_encode(array_values($evolucion_items ?? []));
$valorCmpJson       = json_encode(array_values($evolucion_valor_cmp ?? []));
$itemsCmpJson       = json_encode(array_values($evolucion_items_cmp ?? []));
$inflacion          = $inflacion ?? ['maderas' => ['promedio' => 0, 'cambios' => 0], 'insumos' => ['promedio' => 0, 'cambios' => 0]];
$comp               = $comparativa['actual'];
$ultimo             = $comparativa['ultimo'];
$variaciones        = $comparativa['variaciones'];

function variacionStock($val, $sufijo = ''): string {
    if ($val === null) return 'Sin comparativa';
    $signo = $val >= 0 ? '+' : '';
    return $signo . $val . $sufijo;
}
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
            <h1 class="est-title">Stock <span>análisis</span></h1>
            <div class="est-subtitle">Datos en vivo + histórico de diagnósticos IA — <?= date('d/m/Y H:i') ?></div>
        </div>
        <div class="header-actions">
            <a href="<?= URL ?>stock/historialDiagnosticos" class="export-btn">Ver diagnósticos</a>
            <a href="<?= URL ?>estadisticas/exportar/stock" class="export-btn">Exportar CSV</a>
        </div>
    </div>

    <?php include INCLUDES . 'admin/filtro_fechas.php'; ?>

    <?= Toast::flash() ?>

    <?php if ($ultimo_ia): ?>
    <div class="ia-box">
        <div class="ia-head">
            <div class="ia-meta">
                <span class="ia-tag">Diagnóstico IA #<?= $ultimo_ia['id'] ?></span>
                <span class="ia-fecha">Generado: <?= date('d/m/Y H:i', strtotime($ultimo_ia['fecha'])) ?></span>
                <?php if ($ultimo_ia['puntaje'] !== null): ?>
                    <?php
                    $pClase = $ultimo_ia['puntaje'] >= 75 ? 'salud-ok'
                            : ($ultimo_ia['puntaje'] >= 50 ? 'salud-media' : 'salud-baja');
                    ?>
                    <span class="ia-tag <?= $pClase ?>">
                        Salud: <?= $ultimo_ia['puntaje'] ?>/100
                    </span>
                <?php endif; ?>
            </div>
            <a href="<?= URL ?>stock/verDiagnostico/<?= $ultimo_ia['id'] ?>" class="ia-link">Ver completo →</a>
        </div>
        <?php if ($ultimo_ia['prioridad']): ?>
            <p class="ia-prioridad">
                <strong>Prioridad inmediata:</strong> <?= htmlspecialchars($ultimo_ia['prioridad']) ?>
            </p>
        <?php endif; ?>
        <?php if (!empty($ultimo_ia['alertas'])): ?>
            <div class="ia-alertas">
                <?php foreach (array_slice($ultimo_ia['alertas'], 0, 3) as $a): ?>
                    <div class="ia-alerta-item"><?= htmlspecialchars($a) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="ia-box sin-diag">
        <div class="ia-head">
            <span class="ia-tag neutro">Sin diagnósticos IA</span>
        </div>
        <p class="ia-prioridad sin-margen">
            Todavía no generaste ningún diagnóstico con IA.
            <a href="<?= URL ?>stock" class="ia-link">Ir a Stock →</a>
        </p>
    </div>
    <?php endif; ?>

    <p class="nota-seccion">Estado actual del stock: estos indicadores son una foto de hoy y no dependen del período elegido.</p>
    <div class="kpi-grid">
        <?php
        $deltaValor = $variaciones['valor_total'] ?? null;
        $deltaBajo  = $variaciones['items_bajo'] ?? null;
        $deltaSin   = $variaciones['items_sin']  ?? null;
        ?>
        <div class="kpi-card <?= $deltaValor === null ? '' : ($deltaValor >= 0 ? 'pos' : 'neg') ?>">
            <div class="kpi-label">Valor total stock</div>
            <div class="kpi-value">$<?= number_format($comp['valor_total'], 0, ',', '.') ?>
                <span class="kpi-unit">ARS</span>
            </div>
            <?php if ($deltaValor !== null): ?>
                <div class="kpi-delta <?= $deltaValor >= 0 ? 'pos' : 'neg' ?>">
                    <?= variacionStock($deltaValor, '%') ?> vs último diag.
                </div>
                <div class="kpi-vs">Diag #<?= $ultimo['Id'] ?? '—' ?>: $<?= number_format($ultimo['ValorTotalStock'] ?? 0, 0, ',', '.') ?></div>
            <?php else: ?>
                <div class="kpi-delta neu">Sin diagnóstico previo</div>
            <?php endif; ?>
            <div class="kpi-periodo"><?= ffIcono() ?>Hoy, <?= date('d/m/Y') ?> · no depende del período</div>
        </div>

        <div class="kpi-card warn">
            <div class="kpi-label">Ítems con bajo stock</div>
            <div class="kpi-value"><?= $comp['items_bajo_stock'] ?>
                <span class="kpi-unit">ítems</span>
            </div>
            <?php if ($deltaBajo !== null): ?>
                <?php $cls = $deltaBajo > 0 ? 'neg' : ($deltaBajo < 0 ? 'pos' : 'neu'); ?>
                <div class="kpi-delta <?= $cls ?>">
                    <?= variacionStock($deltaBajo, ' ítems') ?> vs último
                </div>
            <?php endif; ?>
            <div class="kpi-vs">Calculado en vivo desde stock</div>
            <div class="kpi-periodo"><?= ffIcono() ?>Hoy, <?= date('d/m/Y') ?> · no depende del período</div>
        </div>

        <div class="kpi-card <?= ($comp['items_sin_stock'] > 0) ? 'neg' : 'pos' ?>">
            <div class="kpi-label">Ítems sin stock</div>
            <div class="kpi-value"><?= $comp['items_sin_stock'] ?>
                <span class="kpi-unit">ítems</span>
            </div>
            <?php if ($deltaSin !== null): ?>
                <?php $cls = $deltaSin > 0 ? 'neg' : ($deltaSin < 0 ? 'pos' : 'neu'); ?>
                <div class="kpi-delta <?= $cls ?>">
                    <?= variacionStock($deltaSin, ' ítems') ?> vs último
                </div>
            <?php endif; ?>
            <div class="kpi-vs">Requieren reposición urgente</div>
            <div class="kpi-periodo"><?= ffIcono() ?>Hoy, <?= date('d/m/Y') ?> · no depende del período</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Ítems totales en stock</div>
            <div class="kpi-value"><?= $comp['items_total'] ?>
                <span class="kpi-unit">ítems</span>
            </div>
            <div class="kpi-delta neu">
                <?= $comp['items_maderas'] ?> maderas · <?= $comp['items_insumos'] ?> insumos
            </div>
            <div class="kpi-vs">Madera: $<?= number_format($comp['valor_maderas'], 0, ',', '.') ?> · Insumos: $<?= number_format($comp['valor_insumos'], 0, ',', '.') ?></div>
            <div class="kpi-periodo"><?= ffIcono() ?>Hoy, <?= date('d/m/Y') ?> · no depende del período</div>
        </div>
    </div>

    <div class="charts-main">
        <div class="chart-card" id="g-sto_valor">
            <div class="chart-header">
                <div class="chart-title">Evolución del valor del stock</div>
                <div class="chart-acciones">
                    <span class="chart-badge"><?= count($evolucion_valor ?? []) ?> diagnósticos en el período</span>
                    <?php ffFiltroGrafico($gValor); ?>
                </div>
            </div>
            <?= ffChip($gValor, true) ?>
            <?php if (!empty($evolucion_valor)): ?>
            <div class="chart-box"><canvas id="chartValor"></canvas></div>
            <?php else: ?>
            <div class="empty-state">Sin diagnósticos en el período. Generá diagnósticos desde /stock para ver la evolución.</div>
            <?php endif; ?>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Distribución del stock actual</div>
                <span class="chart-badge">Hoy</span>
            </div>
            <?= ffChipFijo('Hoy, ' . date('d/m/Y'), 'Stock actual: no depende del período elegido') ?>
            <?php if ($comp['valor_total'] > 0): ?>
            <div class="chart-box dona"><canvas id="chartDistrib"></canvas></div>
            <?php else: ?>
            <div class="empty-state">Sin stock cargado</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="chart-card" id="g-sto_items">
        <div class="chart-header">
            <div class="chart-title">Ítems bajo/sin stock — histórico</div>
            <div class="chart-acciones">
                <span class="chart-badge">Por diagnóstico</span>
                <?php ffFiltroGrafico($gItems); ?>
            </div>
        </div>
        <?= ffChip($gItems, true) ?>
        <?php if (!empty($evolucion_items)): ?>
        <div class="chart-box"><canvas id="chartItems"></canvas></div>
        <?php else: ?>
        <div class="empty-state">Sin diagnósticos en el período para comparar</div>
        <?php endif; ?>
    </div>

    <div class="charts-main">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Puntaje de salud — evolución</div>
                <?php if (!empty($evolucion_salud)): ?>
                <span class="chart-badge">Último del período: <?= end($evolucion_salud)['puntaje'] ?>/100</span>
                <?php endif; ?>
            </div>
            <?= ffChip($rango) ?>
            <?php if (!empty($evolucion_salud)): ?>
            <div class="chart-box"><canvas id="chartSalud"></canvas></div>
            <?php else: ?>
            <div class="empty-state">Sin puntajes de salud en el período</div>
            <?php endif; ?>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Inflación de materiales</div>
                <span class="chart-badge">En el período</span>
            </div>
            <?= ffChip($rango) ?>
            <div class="inf-grid inf-grid-2">
                <?php foreach (['maderas' => '🪵 Maderas', 'insumos' => '🔩 Insumos'] as $tipo => $etiqueta):
                    $prom = $inflacion[$tipo]['promedio'];
                ?>
                <div class="inf-cell">
                    <div class="inf-label"><?= $etiqueta ?></div>
                    <div class="inf-tipo-row">
                        <span class="val <?= $prom > 0 ? 'danger' : ($prom < 0 ? 'success' : '') ?>">
                            <?= $prom >= 0 ? '+' : '' ?><?= $prom ?>%
                        </span><br>
                        <span class="inf-cambios"><?= $inflacion[$tipo]['cambios'] ?> cambios de precio</span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Materiales más volátiles en el período</div>
            </div>
            <?= ffChip($rango) ?>
            <div class="tabla-scroll">
            <table class="rank-table" id="tablaVolatiles">
                <thead>
                    <tr>
                        <th class="col-pos">#</th>
                        <th>Material</th>
                        <th class="num">Cambios (u.)</th>
                        <th class="num">Var. promedio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($volatiles ?? []) as $i => $v): ?>
                    <tr>
                        <td class="td-rank"><?= $i + 1 ?></td>
                        <td>
                            <?= htmlspecialchars($v['nombre'] ?? '—') ?>
                            <span class="tipo-tag <?= $v['tipo'] == 1 ? 'madera' : 'insumo' ?>">
                                <?= $v['tipo'] == 1 ? 'MADERA' : 'INSUMO' ?>
                            </span>
                        </td>
                        <td class="num accent"><?= (int)$v['cambios'] ?></td>
                        <td class="num <?= $v['variacion_promedio'] > 0 ? 'danger' : 'success' ?>">
                            <?= $v['variacion_promedio'] >= 0 ? '+' : '' ?><?= round((float)$v['variacion_promedio'], 2) ?>%
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($volatiles)): ?>
                    <tr><td colspan="4" class="empty-state">Sin cambios de precio registrados</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Histórico de diagnósticos</div>
                <a href="<?= URL ?>stock/historialDiagnosticos" class="card-link">Ver todos</a>
            </div>
            <?= ffChip($rango) ?>
            <div class="tabla-scroll">
            <table class="rank-table" id="tablaHistorico">
                <thead>
                    <tr>
                        <th class="col-pos">#</th>
                        <th>Fecha</th>
                        <th class="num">Valor ($)</th>
                        <th class="num">Var. %</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($historico ?? []) as $h): ?>
                    <tr>
                        <td class="td-rank">#<?= $h['Id'] ?></td>
                        <td class="muted"><?= date('d/m/Y H:i', strtotime($h['FechaGenerado'])) ?></td>
                        <td class="num accent">$<?= number_format((float)$h['ValorTotalStock'], 0, ',', '.') ?></td>
                        <td class="num <?= $h['VariacionPromedioPct'] > 0 ? 'danger' : ($h['VariacionPromedioPct'] < 0 ? 'success' : 'muted') ?>">
                            <?= $h['VariacionPromedioPct'] !== null ? ($h['VariacionPromedioPct'] >= 0 ? '+' : '') . $h['VariacionPromedioPct'] . '%' : '—' ?>
                        </td>
                        <td>
                            <a href="<?= URL ?>stock/verDiagnostico/<?= $h['Id'] ?>" class="btn-action">Ver</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($historico)): ?>
                    <tr><td colspan="5" class="empty-state">Aún sin diagnósticos generados</td></tr>
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
const C_VERDE = SP_CHART.verde;
const C_ROJO  = SP_CHART.rojo;

const evolValor = <?= $evolucionValorJson ?>;
const evolItems = <?= $evolucionItemsJson ?>;
const evolSalud = <?= $evolucionSaludJson ?>;
const valorCmp  = <?= $valorCmpJson ?>;
const itemsCmp  = <?= $itemsCmpJson ?>;

if (document.getElementById('chartValor')) {
    const dsValor = [{
        label: 'Valor total ($)', formato: 'pesos',
        data: evolValor.map(r => parseFloat(r.valor)),
        borderColor: C_CAOBA,
        backgroundColor: SP_CHART.alpha(C_CAOBA, .1),
        borderWidth: 2.5, fill: true, tension: .35,
        pointRadius: 4, pointHoverRadius: 7,
        pointBackgroundColor: SP_CHART.surface, pointBorderColor: C_CAOBA, pointBorderWidth: 2,
    }];
    if (valorCmp.length) {
        dsValor.push(SP_CHART.cmp('Período comparado ($)',
            valorCmp.map(r => parseFloat(r.valor)), valorCmp.map(r => r.fecha), C_AMB, 'pesos'));
    }
    new Chart(document.getElementById('chartValor'), {
        type: 'line',
        data: {
            labels: evolValor.map(r => SP_CHART.fechaCorta(r.fecha)),
            datasets: dsValor
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: dsValor.length > 1 },
                tooltip: { callbacks: { label: SP_CHART.tooltipLabel } }
            },
            scales: {
                x: { grid: { display: false }, ticks: { maxTicksLimit: 8 } },
                y: { grid: { color: SP_CHART.grid }, border: { display: false },
                     ticks: { callback: v => SP_CHART.pesosCorto(v) } }
            }
        }
    });
}

const valMaderas = <?= (float)$comp['valor_maderas'] ?>;
const valInsumos = <?= (float)$comp['valor_insumos'] ?>;
if (document.getElementById('chartDistrib')) {
    new Chart(document.getElementById('chartDistrib'), {
        type: 'doughnut',
        data: {
            labels: ['Maderas', 'Insumos'],
            datasets: [{
                data: [valMaderas, valInsumos],
                backgroundColor: [C_CAOBA, SP_CHART.serie[3]],
                borderColor: SP_CHART.surface, borderWidth: 3
            }]
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

if (document.getElementById('chartItems')) {
    const dsItems = [
        { label: 'Bajo stock', data: evolItems.map(r => parseInt(r.bajo)),
          backgroundColor: SP_CHART.alpha(C_AMB, .8), borderRadius: 4, order: 1 },
        { label: 'Sin stock',  data: evolItems.map(r => parseInt(r.sin)),
          backgroundColor: SP_CHART.alpha(C_ROJO, .8), borderRadius: 4, order: 1 }
    ];
    if (itemsCmp.length) {
        const fechas = itemsCmp.map(r => r.fecha);
        dsItems.push(SP_CHART.cmp('Bajo stock — período comparado', itemsCmp.map(r => parseInt(r.bajo)), fechas, C_AMB));
        dsItems.push(SP_CHART.cmp('Sin stock — período comparado',  itemsCmp.map(r => parseInt(r.sin)),  fechas, C_ROJO));
    }
    new Chart(document.getElementById('chartItems'), {
        type: 'bar',
        data: {
            labels: evolItems.map(r => SP_CHART.fechaCorta(r.fecha)),
            datasets: dsItems
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: { tooltip: { callbacks: { label: SP_CHART.tooltipLabel } } },
            scales: {
                x: { grid: { display: false }, ticks: { maxTicksLimit: 7 } },
                y: { grid: { color: SP_CHART.grid }, border: { display: false },
                     beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });
}

if (document.getElementById('chartSalud')) {
    new Chart(document.getElementById('chartSalud'), {
        type: 'line',
        data: {
            labels: evolSalud.map(r => SP_CHART.fechaCorta(r.fecha)),
            datasets: [{
                label: 'Salud',
                data: evolSalud.map(r => parseInt(r.puntaje)),
                borderColor: C_VERDE,
                backgroundColor: SP_CHART.alpha(C_VERDE, .1),
                borderWidth: 2.5, fill: true, tension: .35,
                pointRadius: 5, pointHoverRadius: 8,
                pointBorderColor: SP_CHART.surface, pointBorderWidth: 2,
                pointBackgroundColor: evolSalud.map(r =>
                    r.puntaje >= 75 ? C_VERDE : (r.puntaje >= 50 ? C_AMB : C_ROJO)
                ),
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ' ' + ctx.parsed.y + ' / 100' } }
            },
            scales: {
                x: { grid: { display: false }, ticks: { maxTicksLimit: 8 } },
                y: { grid: { color: SP_CHART.grid }, border: { display: false }, min: 0, max: 100,
                     ticks: { stepSize: 25, callback: v => v + ' pts' } }
            }
        }
    });
}
</script>
<?php require_once INCLUDES . 'admin/scripts_admin.php'; ?>
