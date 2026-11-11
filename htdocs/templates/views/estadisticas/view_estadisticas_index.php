<?php
$ventasJson  = json_encode(array_values($ventas_diarias  ?? []));
$visitasJson = json_encode(array_values($visitas_diarias ?? []));
$pagosJson   = json_encode(array_values($por_tipo_pago   ?? []));
$ventasCmpJson  = json_encode(array_values($ventas_diarias_cmp  ?? []));
$visitasCmpJson = json_encode(array_values($visitas_diarias_cmp ?? []));
$hayVentas  = array_sum(array_column($ventas_diarias  ?? [], 'monto_total'))   > 0;
$hayVisitas = array_sum(array_column($visitas_diarias ?? [], 'total_visitas')) > 0;
$act = $kpis['actual']   ?? ['ventas' => 0, 'ordenes' => 0, 'visitas' => 0];
$ant = $kpis['anterior'] ?? ['ventas' => 0, 'ordenes' => 0, 'visitas' => 0];
$ticketAct = $act['ordenes'] ? $act['ventas'] / $act['ordenes'] : 0;
$ticketAnt = $ant['ordenes'] ? $ant['ventas'] / $ant['ordenes'] : 0;
$pesos = fn($v) => '$' . number_format((float)$v, 0, ',', '.');
$kr = $kpi_rango;
$kc = $kr['cmp'];
$gEmbudo = $graf['ini_embudo'];
$gProd   = $graf['ini_productos'];
$gBusq   = $graf['ini_busquedas'];

function variacion($actual, $anterior): string {
    if (!$anterior) return 'Sin datos del período comparado';
    $pct = round(($actual - $anterior) / $anterior * 100, 1);
    return ($pct >= 0 ? '+' : '') . $pct . '% vs período comparado';
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
            <h1 class="est-title">Pantalla de <span>Estadísticas</span></h1>
            <div class="est-subtitle">Actualizado: <?= date('d/m/Y H:i') ?></div>
        </div>
    </div>

    <?php include INCLUDES . 'admin/filtro_fechas.php'; ?>

    <?= Toast::flash() ?>

    <form class="kpi-filtro" method="GET" action="<?= URL ?>estadisticas">
        <div class="kpi-filtro-titulo">
            Resumen de las tarjetas
            <small>
                <?= $kr['propio'] ? 'Período propio' : 'Sigue al filtro general' ?>
                · <?= ffDelAl($kr['desde'], $kr['hasta']) ?>
                <?php if ($kc['activo']): ?>
                · comparando con <?= ffPeriodo($kc['desde'], $kc['hasta']) ?><?= ffAvisoDuracion($kr['dias'], $kc) ?>
                <?php endif; ?>
            </small>
        </div>
        <label class="ff-campo">
            <span>Desde</span>
            <input type="date" name="kpi_desde" value="<?= htmlspecialchars($kr['desde']) ?>" max="<?= date('Y-m-d') ?>" required>
        </label>
        <label class="ff-campo">
            <span>Hasta</span>
            <input type="date" name="kpi_hasta" value="<?= htmlspecialchars($kr['hasta']) ?>" max="<?= date('Y-m-d') ?>" required>
        </label>
        <button type="submit" class="btn-filtrar">Aplicar</button>
        <a href="<?= URL ?>estadisticas?kpi_igualar=1" class="btn-limpiar"
           title="Las tarjetas vuelven a usar el período del filtro general, comparando con el período anterior">Igualar al general</a>
        <?php ffBloqueComparar('kpi_', $kc); ?>
    </form>

    <div class="kpi-grid">
        <?php
        $kpiItems = [
            [
                'label' => 'Ventas del período',
                'value' => $pesos($act['ventas']),
                'unit'  => 'ARS',
                'delta' => variacion($act['ventas'], $ant['ventas']),
                'ant'   => $pesos($ant['ventas']),
            ],
            [
                'label' => 'Órdenes aprobadas',
                'value' => $act['ordenes'],
                'unit'  => 'órdenes',
                'delta' => variacion($act['ordenes'], $ant['ordenes']),
                'ant'   => $ant['ordenes'],
            ],
            [
                'label' => 'Visitas',
                'value' => number_format($act['visitas'], 0, ',', '.'),
                'unit'  => 'sesiones',
                'delta' => variacion($act['visitas'], $ant['visitas']),
                'ant'   => number_format($ant['visitas'], 0, ',', '.'),
            ],
            [
                'label' => 'Ticket promedio',
                'value' => $pesos($ticketAct),
                'unit'  => 'por orden',
                'delta' => variacion($ticketAct, $ticketAnt),
                'ant'   => $pesos($ticketAnt),
            ],
        ];

        foreach ($kpiItems as $kpi):
            $isPos = $kc['activo'] && str_starts_with($kpi['delta'], '+');
            $isNeg = $kc['activo'] && str_starts_with($kpi['delta'], '-');
            $dcls  = $isPos ? 'pos' : ($isNeg ? 'neg' : 'neu');
            $ccls  = $isPos ? 'pos' : ($isNeg ? 'neg' : '');
        ?>
        <div class="kpi-card <?= $ccls ?>">
            <div class="kpi-label"><?= $kpi['label'] ?></div>
            <div class="kpi-value">
                <?= $kpi['value'] ?>
                <span class="kpi-unit"><?= $kpi['unit'] ?></span>
            </div>
            <?php if ($kc['activo']): ?>
            <div class="kpi-delta <?= $dcls ?>"><?= $kpi['delta'] ?></div>
            <div class="kpi-cmp">
                <div class="kpi-cmp-col">
                    <div class="kpi-cmp-fecha"><?= ffPeriodo($kr['desde'], $kr['hasta']) ?></div>
                    <div class="kpi-cmp-val"><?= $kpi['value'] ?></div>
                </div>
                <div class="kpi-cmp-vs">vs</div>
                <div class="kpi-cmp-col ant">
                    <div class="kpi-cmp-fecha"><?= ffPeriodo($kc['desde'], $kc['hasta']) ?></div>
                    <div class="kpi-cmp-val"><?= $kpi['ant'] ?></div>
                </div>
            </div>
            <?php else: ?>
            <div class="kpi-periodo"><?= ffIcono() ?><?= ffDelAl($kr['desde'], $kr['hasta']) ?></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="chart-card" id="g-ini_ventas">
        <div class="chart-header">
            <div class="chart-title">Ventas diarias en pesos (ARS)</div>
            <div class="chart-acciones">
                <span class="chart-badge">Ventas aprobadas</span>
                <?php ffFiltroGrafico($graf['ini_ventas']); ?>
            </div>
        </div>
        <?= ffChip($graf['ini_ventas'], true) ?>
        <?php if ($hayVentas): ?>
        <div class="chart-box grande"><canvas id="chartVentas"></canvas></div>
        <?php else: ?>
        <div class="empty-state">Sin ventas en el período seleccionado</div>
        <?php endif; ?>
    </div>

    <div class="chart-card" id="g-ini_visitas">
        <div class="chart-header">
            <div class="chart-title">Visitas por día</div>
            <div class="chart-acciones">
                <span class="chart-badge">Sin personal</span>
                <?php ffFiltroGrafico($graf['ini_visitas']); ?>
            </div>
        </div>
        <?= ffChip($graf['ini_visitas'], true) ?>
        <?php if ($hayVisitas): ?>
        <div class="chart-box grande"><canvas id="chartVisitas"></canvas></div>
        <?php else: ?>
        <div class="empty-state">Sin visitas registradas en el período</div>
        <?php endif; ?>
    </div>

    <div class="charts-row">
        <div class="chart-card" id="g-ini_embudo">
            <div class="chart-header">
                <div class="chart-title">Embudo de conversión</div>
                <div class="chart-acciones">
                    <span class="chart-badge" title="Sesiones únicas de clientes; compras web aprobadas">Sesiones únicas</span>
                    <?php ffFiltroGrafico($gEmbudo); ?>
                </div>
            </div>
            <?= ffChip($gEmbudo, true, 'barra') ?>
            <?php
            $embudoData = $embudo ?? [];
            $embudoCmp  = $embudo_cmp ?? [];
            $maxEmbudo  = max(1, ...array_column($embudoData ?: [['cantidad'=>1]], 'cantidad'),
                                 ...array_column($embudoCmp ?: [['cantidad'=>1]], 'cantidad'));
            $convDe = fn(array $filas, int $i) => $i === 0 ? null : ($filas[$i-1]['cantidad'] > 0
                ? round($filas[$i]['cantidad'] / $filas[$i-1]['cantidad'] * 100, 1)
                : 0);
            foreach ($embudoData as $i => $e):
                $pct  = round($e['cantidad'] / $maxEmbudo * 100);
                $conv = $convDe($embudoData, $i);
                $c    = $embudoCmp[$i] ?? null;
            ?>
            <div class="embudo-item">
                <span class="embudo-label"><?= htmlspecialchars($e['etapa']) ?></span>
                <div class="embudo-barras">
                    <div class="embudo-bar-wrap">
                        <div class="embudo-bar" style="width:<?= $pct ?>%"></div>
                    </div>
                    <?php if ($c): ?>
                    <div class="embudo-bar-wrap cmp" title="Período comparado: <?= number_format($c['cantidad']) ?>">
                        <div class="embudo-bar cmp" style="width:<?= round($c['cantidad'] / $maxEmbudo * 100) ?>%"></div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="embudo-right">
                    <span class="embudo-num"><?= number_format($e['cantidad']) ?></span>
                    <span class="embudo-conv"><?= $conv !== null ? $conv . '% conv.' : 'entrada' ?></span>
                    <?php if ($c):
                        $convC = $convDe($embudoCmp, $i); ?>
                    <span class="embudo-cmp">vs <?= number_format($c['cantidad']) ?><?= $convC !== null ? ' · ' . $convC . '%' : '' ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($embudoData)): ?>
            <div class="empty-state">Sin datos de conversión aún</div>
            <?php elseif ($embudoCmp): ?>
            <div class="leyenda-cmp">
                <span><i class="sw amb"></i><?= ffPeriodo($gEmbudo['desde'], $gEmbudo['hasta']) ?></span>
                <span><i class="sw cmp"></i><?= ffPeriodo($gEmbudo['cmp']['desde'], $gEmbudo['cmp']['hasta']) ?></span>
            </div>
            <?php endif; ?>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Tipo de pago — monto total (ARS)</div>
            </div>
            <?= ffChip($rango) ?>
            <?php if (!empty($por_tipo_pago)): ?>
            <div class="chart-box dona"><canvas id="chartPagos"></canvas></div>
            <?php else: ?>
            <div class="empty-state">Sin pagos aprobados en el período</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="charts-main">
        <div class="chart-card" id="g-ini_productos">
            <div class="chart-header">
                <div class="chart-title">Productos más vendidos</div>
                <div class="chart-acciones">
                    <a href="<?= URL ?>estadisticas/exportar/productos" class="card-link">Exportar CSV</a>
                    <?php ffFiltroGrafico($gProd); ?>
                </div>
            </div>
            <?= ffChip($gProd, true, 'tabla') ?>
            <?php $prodCmp = $gProd['cmp']['activo']; ?>
            <div class="tabla-scroll">
            <table class="top-table" id="tablaProductos">
                <thead>
                    <tr>
                        <th onclick="sortTable('tablaProductos',0)">Pos.</th>
                        <th onclick="sortTable('tablaProductos',1)">Producto</th>
                        <th class="num" onclick="sortTable('tablaProductos',2)">Uds. (u.)</th>
                        <th class="num" onclick="sortTable('tablaProductos',3)">Ingreso ($)</th>
                        <?php if ($prodCmp): ?>
                        <th class="num th-cmp" title="Unidades en el período comparado">Comparado (u.)</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($productos_top ?? []) as $i => $p): ?>
                    <tr>
                        <td class="td-rank"><?= $i + 1 ?></td>
                        <td>
                            <div class="td-name"><?= htmlspecialchars($p['nombre']) ?></div>
                            <div class="td-cat"><?= htmlspecialchars($p['categoria']) ?></div>
                        </td>
                        <td class="num td-units"><?= number_format((int)$p['unidades_vendidas']) ?></td>
                        <td class="num td-money">$<?= number_format((float)$p['ingreso_total'], 0, ',', '.') ?></td>
                        <?php if ($prodCmp): ?><?= ffCeldaCmp($p) ?><?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($productos_top)): ?>
                    <tr><td colspan="<?= $prodCmp ? 5 : 4 ?>" class="empty-state">Sin ventas en el período</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>

        <div class="chart-card" id="g-ini_busquedas">
            <div class="chart-header">
                <div class="chart-title">Búsquedas frecuentes</div>
                <div class="chart-acciones">
                    <a href="<?= URL ?>estadisticas/busquedas" class="card-link">Ver detalle</a>
                    <?php ffFiltroGrafico($gBusq); ?>
                </div>
            </div>
            <?= ffChip($gBusq, true, 'barra') ?>
            <?php if ($gBusq['cmp']['activo'] && !empty($terminos_top)):
                $maxB = max(1, ...array_column($terminos_top, 'veces'), ...array_column($terminos_top, 'cmp'));
            ?>
            <div>
                <?php foreach ($terminos_top as $t): ?>
                <div class="bar-row">
                    <div class="bar-row-head">
                        <span class="name"><?= htmlspecialchars($t['termino']) ?></span>
                        <span class="val"><?= (int)$t['veces'] ?> <span class="val-cmp">vs <?= (int)$t['cmp'] ?></span></span>
                    </div>
                    <div class="bar-bg"><div class="bar-fill" style="width:<?= round($t['veces'] / $maxB * 100) ?>%;background:var(--caoba2);"></div></div>
                    <div class="bar-bg cmp"><div class="bar-fill cmp" style="width:<?= round($t['cmp'] / $maxB * 100) ?>%"></div></div>
                </div>
                <?php endforeach; ?>
                <div class="leyenda-cmp">
                    <span><i class="sw"></i><?= ffPeriodo($gBusq['desde'], $gBusq['hasta']) ?></span>
                    <span><i class="sw cmp"></i><?= ffPeriodo($gBusq['cmp']['desde'], $gBusq['cmp']['hasta']) ?></span>
                </div>
            </div>
            <?php else: ?>
            <div class="terminos-list">
                <?php
                $maxVeces = max(1, ...array_column($terminos_top ?: [['veces'=>1]], 'veces'));
                foreach (($terminos_top ?? []) as $t):
                    $hot = ($t['veces'] / $maxVeces) > 0.4;
                ?>
                <span class="termino-pill <?= $hot ? 'hot' : '' ?>"
                      title="<?= (int)$t['veces'] ?> búsquedas — <?= round($t['resultados_promedio'] ?? 0) ?> resultados promedio">
                    <?= htmlspecialchars($t['termino']) ?>
                    <small>(<?= $t['veces'] ?>)</small>
                </span>
                <?php endforeach; ?>
                <?php if (empty($terminos_top)): ?>
                <div class="empty-state">Sin búsquedas en el período</div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>
</main>
</div>

<script src="<?= URL ?>templates/assets/js/estadisticas-charts.js?v=4"></script>
<script>
const [C_CAOBA, C_AMB] = SP_CHART.serie;

const ventasRaw  = <?= $ventasJson ?>;
const visitasRaw = <?= $visitasJson ?>;
const pagosRaw   = <?= $pagosJson ?>;
const ventasCmp  = <?= $ventasCmpJson ?>;
const visitasCmp = <?= $visitasCmpJson ?>;

if (document.getElementById('chartVentas')) {
    const dsVentas = [{
        label: 'Ventas ($)', formato: 'pesos',
        data: ventasRaw.map(r => parseFloat(r.monto_total)),
        borderColor: C_CAOBA, backgroundColor: SP_CHART.alpha(C_CAOBA, .1),
        borderWidth: 2.5, fill: true, tension: .35,
        pointRadius: ventasRaw.length > 60 ? 0 : 3, pointHoverRadius: 6,
        pointBackgroundColor: SP_CHART.surface, pointBorderColor: C_CAOBA, pointBorderWidth: 2,
    }];
    if (ventasCmp.length) {
        dsVentas.push(SP_CHART.cmp('Período comparado ($)',
            ventasCmp.map(r => parseFloat(r.monto_total)), ventasCmp.map(r => r.fecha), C_AMB, 'pesos'));
    }
    new Chart(document.getElementById('chartVentas'), {
        type: 'line',
        data: {
            labels: ventasRaw.map(r => SP_CHART.fechaCorta(r.fecha)),
            datasets: dsVentas
        },
        options: {
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: dsVentas.length > 1 },
                tooltip: { callbacks: { label: SP_CHART.tooltipLabel } }
            },
            scales: {
                x: { grid: { display: false }, ticks: { maxTicksLimit: 12 } },
                y: { grid: { color: SP_CHART.grid }, border: { display: false }, beginAtZero: true,
                     ticks: { callback: v => SP_CHART.pesosCorto(v) } }
            }
        }
    });
}

if (document.getElementById('chartVisitas')) {
    const dsVisitas = [
        { label: 'Páginas vistas', data: visitasRaw.map(r => parseInt(r.total_visitas)),
          backgroundColor: SP_CHART.alpha(C_AMB, .75), borderRadius: 4, order: 1 },
        { label: 'Sesiones únicas', data: visitasRaw.map(r => parseInt(r.sesiones_unicas)),
          backgroundColor: SP_CHART.alpha(C_CAOBA, .85), borderRadius: 4, order: 1 }
    ];
    if (visitasCmp.length) {
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
                y: { grid: { color: SP_CHART.grid }, border: { display: false }, beginAtZero: true,
                     ticks: { precision: 0 } }
            }
        }
    });
}

if (document.getElementById('chartPagos')) {
    new Chart(document.getElementById('chartPagos'), {
        type: 'doughnut',
        data: {
            labels: pagosRaw.map(r => r.tipo_pago),
            datasets: [{
                data: pagosRaw.map(r => parseFloat(r.monto_total)),
                backgroundColor: SP_CHART.serie,
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
