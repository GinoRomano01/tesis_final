<?php
$ventasJson      = json_encode(array_values($ventas_diarias   ?? []));
$visitasJson     = json_encode(array_values($visitas_diarias  ?? []));
$embudoJson      = json_encode(array_values($embudo           ?? []));
$dispositivoJson = json_encode(array_values($por_dispositivo  ?? []));
$pagosJson       = json_encode(array_values($por_tipo_pago    ?? []));

$ventasHoy  = number_format((float)($kpis['ventas_hoy']  ?? 0), 0, ',', '.');
$ventasAyer = number_format((float)($kpis['ventas_ayer'] ?? 0), 0, ',', '.');
$ordenesHoy = (int)($kpis['ordenes_hoy']  ?? 0);
$visitasHoy = (int)($kpis['visitas_hoy']  ?? 0);
$visitasAyer= (int)($kpis['visitas_ayer'] ?? 0);

function variacion($hoy, $ayer): string {
    if (!$ayer) return 'Sin comparativa';
    $pct = round(($hoy - $ayer) / $ayer * 100, 1);
    return ($pct >= 0 ? '+' : '') . $pct . '% vs ayer';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $title ?? 'Estadísticas' ?></title>
<?php require_once INCLUDES . 'admin/head_admin.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Syne:wght@400;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
.est-wrap {
    --sp-bg:       #0f1419;
    --sp-surface:  #1a1f26;
    --sp-border:   #2d3540;
    --sp-accent:   #38bdf8;
    --sp-accent2:  #a78bfa;
    --sp-text:     #f1f5f9;
    --sp-muted:    #94a3b8;
    --sp-success:  #4ade80;
    --sp-danger:   #f87171;
    --sp-warning:  #fbbf24;
    --sp-radius:   12px;
    --sp-mono:     'DM Mono', monospace;
    --sp-sans:     'Inter', system-ui, sans-serif;
    font-family:   var(--sp-sans);
    color:         var(--sp-text);
    background:    var(--sp-bg);
    padding:       2rem;
    min-height:    100vh;
}
.est-header   { display:flex; align-items:center; justify-content:space-between; margin-bottom:2rem; flex-wrap:wrap; gap:1rem; border-bottom:2px solid var(--sp-border); padding-bottom:1rem; }
.est-title { font-size:1.75rem; font-weight:600; letter-spacing:-.01em; margin:0; color:#f8fafc; line-height:1.2; }
.est-title span { color:var(--sp-accent); }
.est-subtitle { font-size:.78rem; color:var(--sp-muted); margin-top:.25rem; font-family:var(--sp-mono); }

.filtros-bar { display:flex; align-items:center; gap:.5rem; flex-wrap:wrap; }
.dias-pills  { display:flex; gap:.3rem; }
.dias-pills a { font-family:var(--sp-mono); font-size:.72rem; padding:.28rem .65rem;
                border:1px solid var(--sp-border); border-radius:6px; color:var(--sp-muted);
                text-decoration:none; transition:.15s; }
.dias-pills a:hover,
.dias-pills a.active { border-color:var(--sp-accent); color:var(--sp-accent); background:rgba(200,169,110,.07); }
.btn-limpiar { font-family:var(--sp-mono); font-size:.72rem; padding:.28rem .65rem;
               border:1px solid var(--sp-border); border-radius:6px; color:var(--sp-muted);
               background:none; cursor:pointer; transition:.15s; text-decoration:none; }
.btn-limpiar:hover { border-color:var(--sp-danger); color:var(--sp-danger); }

.chip-activo { font-family:var(--sp-mono); font-size:.7rem; padding:.2rem .7rem;
               background:rgba(200,169,110,.1); border:1px solid rgba(200,169,110,.25);
               border-radius:20px; color:var(--sp-accent); display:inline-block; margin-bottom:1rem; }

.kpi-grid  { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; margin-bottom:2rem; }
.kpi-card  { background:var(--sp-surface); border:1px solid var(--sp-border);
             border-radius:var(--sp-radius); padding:1.4rem 1.6rem;
             position:relative; overflow:hidden; }
.kpi-card::before { content:''; position:absolute; top:0; left:0; right:0;
                    height:2px; background:var(--sp-accent); opacity:.35; }
.kpi-card.pos::before { background:var(--sp-success); opacity:.6; }
.kpi-card.neg::before { background:var(--sp-danger);  opacity:.6; }
.kpi-label { font-family:var(--sp-mono); font-size:.68rem; text-transform:uppercase;
             letter-spacing:.1em; color:var(--sp-muted); margin-bottom:.5rem; }
.kpi-value { font-family:var(--sp-mono); font-size:2rem; font-weight:500; color:var(--sp-text); line-height:1; }
.kpi-unit  { font-size:.72rem; color:var(--sp-muted); margin-left:.25rem; font-family:var(--sp-mono); }
.kpi-delta { font-family:var(--sp-mono); font-size:.72rem; margin-top:.5rem;
             padding:.2rem .5rem; border-radius:4px; display:inline-block; }
.kpi-delta.pos { color:var(--sp-success); background:rgba(92,184,122,.1); }
.kpi-delta.neg { color:var(--sp-danger);  background:rgba(200,92,92,.1); }
.kpi-delta.neu { color:var(--sp-muted);   background:rgba(122,120,112,.1); }
.kpi-vs    { font-size:.68rem; color:var(--sp-muted); margin-top:.25rem; font-family:var(--sp-mono); }

.charts-main { display:grid; grid-template-columns:2fr 1fr; gap:1rem; margin-bottom:1rem; }
.charts-row  { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem; }
.chart-card  { background:var(--sp-surface); border:1px solid var(--sp-border);
               border-radius:var(--sp-radius); padding:1.4rem; }
.chart-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; }
.chart-title  { font-family:var(--sp-mono); font-size:.72rem; text-transform:uppercase;
                letter-spacing:.1em; color:var(--sp-muted); }
.chart-badge  { font-family:var(--sp-mono); font-size:.68rem; padding:.15rem .5rem;
                border-radius:4px; background:rgba(200,169,110,.1); color:var(--sp-accent); }

.top-table    { width:100%; border-collapse:collapse; font-size:.84rem; }
.top-table thead tr { background:rgba(255,255,255,.04); }
.top-table th { font-family:var(--sp-mono); font-size:.66rem; text-transform:uppercase;
                letter-spacing:.08em; color:var(--sp-muted); padding:.55rem .5rem;
                border-bottom:1px solid var(--sp-border); cursor:pointer;
                white-space:nowrap; user-select:none; }
.top-table th:hover { color:var(--sp-accent); }
.top-table th.num, .top-table td.num { text-align:right; }
.top-table tbody tr:nth-child(even) { background:rgba(255,255,255,.025); }
.top-table tbody tr:hover           { background:rgba(200,169,110,.06); }
.top-table td { padding:.55rem .5rem; border-bottom:1px solid rgba(42,42,50,.5);
                color:var(--sp-text); vertical-align:middle; }
.top-table tr:last-child td { border-bottom:none; }
.td-name  { font-weight:600; }
.td-cat   { font-size:.72rem; color:var(--sp-muted); margin-top:2px; }
.td-rank  { color:var(--sp-muted); font-size:.78rem; }
.td-units { color:var(--sp-accent); font-family:var(--sp-mono); }
.td-money { color:var(--sp-muted); font-family:var(--sp-mono); font-size:.8rem; }

.embudo-item     { display:flex; align-items:center; gap:.8rem; margin-bottom:.9rem; }
.embudo-label    { font-size:.8rem; min-width:72px; color:var(--sp-text); }
.embudo-bar-wrap { flex:1; background:rgba(255,255,255,.06); border-radius:4px; height:10px; overflow:hidden; }
.embudo-bar      { height:100%; border-radius:4px; background:var(--sp-accent); }
.embudo-right    { display:flex; flex-direction:column; align-items:flex-end; min-width:72px; }
.embudo-num      { font-family:var(--sp-mono); font-size:.78rem; color:var(--sp-text); }
.embudo-conv     { font-family:var(--sp-mono); font-size:.67rem; color:var(--sp-muted); }

.terminos-list { display:flex; flex-wrap:wrap; gap:.4rem; }
.termino-pill  { font-family:var(--sp-mono); font-size:.72rem; padding:.25rem .6rem;
                 border:1px solid var(--sp-border); border-radius:20px; color:var(--sp-muted);
                 cursor:default; transition:.15s; }
.termino-pill:hover { border-color:var(--sp-accent); color:var(--sp-accent); }
.termino-pill.hot { border-color:var(--sp-accent); color:var(--sp-accent); background:rgba(200,169,110,.07); }

.empty-state { text-align:center; padding:2rem; color:var(--sp-muted);
               font-size:.82rem; font-family:var(--sp-mono); }

@media(max-width:900px) {
    .charts-main { grid-template-columns:1fr; }
    .charts-row  { grid-template-columns:1fr; }
    .kpi-grid    { grid-template-columns:1fr 1fr; }
}
</style>
</head>
<body class="admin-body">
<?php require_once INCLUDES . 'admin/sidebar_admin.php'; ?>

<div class="main-area">
<?php require_once INCLUDES . 'admin/topbar_admin.php'; ?>

<main class="content">
<div class="est-wrap">

    <div class="est-header">
        <div>
            <h1 class="est-title">Pantalla de <span>Estadisticas</span></h1>
            <div class="est-subtitle">
                Ultimos <?= $dias ?> dias — Actualizado: <?= date('d/m/Y H:i') ?>
            </div>
        </div>
        <div class="filtros-bar">
            <div class="dias-pills">
                <?php foreach ([7, 30, 90, 365] as $d): ?>
                <a href="?dias=<?= $d ?>"
                   class="<?= ($dias == $d) ? 'active' : '' ?>"
                   title="Ultimos <?= $d ?> dias">
                    <?= $d === 365 ? '1 año' : $d . 'd' ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php if ($dias != 30): ?>
            <a href="?dias=30" class="btn-limpiar">Limpiar filtro</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="chip-activo">Periodo activo: ultimos <?= $dias ?> dias</div>

    <?= Toast::flash() ?>

    <div class="kpi-grid">
        <?php
        $deltaVentas  = variacion((float)($kpis['ventas_hoy']  ?? 0), (float)($kpis['ventas_ayer'] ?? 0));
        $deltaOrdenes = variacion((int)($kpis['ordenes_hoy']   ?? 0), (int)($kpis['ordenes_ayer']  ?? 0));
        $deltaVisitas = variacion($visitasHoy, $visitasAyer);

        $kpiItems = [
            [
                'label' => 'Ventas hoy',
                'value' => '$' . $ventasHoy,
                'unit'  => 'ARS',
                'delta' => $deltaVentas,
                'vs'    => 'Ayer: $' . $ventasAyer,
            ],
            [
                'label' => 'Ordenes hoy',
                'value' => $ordenesHoy,
                'unit'  => 'pedidos',
                'delta' => $deltaOrdenes,
                'vs'    => 'Ayer: ' . (int)($kpis['ordenes_ayer'] ?? 0) . ' pedidos',
            ],
            [
                'label' => 'Visitas hoy',
                'value' => $visitasHoy,
                'unit'  => 'sesiones',
                'delta' => $deltaVisitas,
                'vs'    => 'Ayer: ' . $visitasAyer . ' sesiones',
            ],
            [
                'label' => 'Periodo',
                'value' => $dias,
                'unit'  => 'dias',
                'delta' => 'Datos analizados',
                'vs'    => date('d/m/Y', strtotime("-{$dias} days")) . ' al ' . date('d/m/Y'),
            ],
        ];

        foreach ($kpiItems as $kpi):
            $isPos = str_contains($kpi['delta'], '+');
            $isNeg = str_starts_with($kpi['delta'], '-');
            $dcls  = $isPos ? 'pos' : ($isNeg ? 'neg' : 'neu');
            $ccls  = $isPos ? 'pos' : ($isNeg ? 'neg' : '');
        ?>
        <div class="kpi-card <?= $ccls ?>">
            <div class="kpi-label"><?= $kpi['label'] ?></div>
            <div class="kpi-value">
                <?= $kpi['value'] ?>
                <span class="kpi-unit"><?= $kpi['unit'] ?></span>
            </div>
            <div class="kpi-delta <?= $dcls ?>"><?= $kpi['delta'] ?></div>
            <div class="kpi-vs"><?= $kpi['vs'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="charts-main">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Ventas diarias en pesos (ARS)</div>
                <span class="chart-badge">Ultimos <?= $dias ?> dias</span>
            </div>
            <?php if (!empty($ventas_diarias)): ?>
            <canvas id="chartVentas" height="220"></canvas>
            <?php else: ?>
            <div class="empty-state">Sin ventas en el periodo seleccionado</div>
            <?php endif; ?>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Embudo de conversion (30 dias)</div>
            </div>
            <?php
            $embudoData   = $embudo ?? [];
            $maxEmbudo    = max(1, ...array_column($embudoData ?: [['cantidad'=>1]], 'cantidad'));
            foreach ($embudoData as $i => $e):
                $pct  = round($e['cantidad'] / $maxEmbudo * 100);
                $conv = $i === 0 ? null : ($embudoData[$i-1]['cantidad'] > 0
                    ? round($e['cantidad'] / $embudoData[$i-1]['cantidad'] * 100, 1)
                    : 0);
            ?>
            <div class="embudo-item">
                <span class="embudo-label"><?= htmlspecialchars($e['etapa']) ?></span>
                <div class="embudo-bar-wrap">
                    <div class="embudo-bar" style="width:<?= $pct ?>%"></div>
                </div>
                <div class="embudo-right">
                    <span class="embudo-num"><?= number_format($e['cantidad']) ?></span>
                    <span class="embudo-conv"><?= $conv !== null ? $conv . '% conv.' : 'entrada' ?></span>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($embudoData)): ?>
            <div class="empty-state">Sin datos de conversion aun</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Visitas por dia (sesiones)</div>
                <span class="chart-badge">Ultimos <?= $dias ?> dias</span>
            </div>
            <?php if (!empty($visitas_diarias)): ?>
            <canvas id="chartVisitas" height="200"></canvas>
            <?php else: ?>
            <div class="empty-state">Sin visitas registradas en el periodo</div>
            <?php endif; ?>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Tipo de pago — monto total (ARS)</div>
                <span class="chart-badge">30 dias</span>
            </div>
            <?php if (!empty($por_tipo_pago)): ?>
            <canvas id="chartPagos" height="200"></canvas>
            <?php else: ?>
            <div class="empty-state">Sin pagos registrados en el periodo</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="charts-main">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Productos mas vendidos</div>
                <a href="<?= URL ?>estadisticas/exportar/productos"
                   style="font-family:var(--sp-mono);font-size:.7rem;color:var(--sp-accent);text-decoration:none">
                    Exportar CSV
                </a>
            </div>
            <table class="top-table" id="tablaProductos">
                <thead>
                    <tr>
                        <th onclick="sortTable('tablaProductos',0)">Pos.</th>
                        <th onclick="sortTable('tablaProductos',1)">Producto</th>
                        <th class="num" onclick="sortTable('tablaProductos',2)">Uds. (u.)</th>
                        <th class="num" onclick="sortTable('tablaProductos',3)">Ingreso ($)</th>
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
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($productos_top)): ?>
                    <tr><td colspan="4" class="empty-state">Sin ventas registradas aun</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Busquedas frecuentes (30 dias)</div>
                <a href="<?= URL ?>estadisticas/busquedas"
                   style="font-family:var(--sp-mono);font-size:.7rem;color:var(--sp-accent);text-decoration:none">
                    Ver detalle
                </a>
            </div>
            <div class="terminos-list">
                <?php
                $maxVeces = max(1, ...array_column($terminos_top ?: [['veces'=>1]], 'veces'));
                foreach (($terminos_top ?? []) as $t):
                    $hot = ($t['veces'] / $maxVeces) > 0.4;
                ?>
                <span class="termino-pill <?= $hot ? 'hot' : '' ?>"
                      title="<?= (int)$t['veces'] ?> busquedas — <?= round($t['resultados_promedio'] ?? 0) ?> resultados promedio">
                    <?= htmlspecialchars($t['termino']) ?>
                    <small style="opacity:.55">(<?= $t['veces'] ?>)</small>
                </span>
                <?php endforeach; ?>
                <?php if (empty($terminos_top)): ?>
                <div class="empty-state">Sin busquedas registradas</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
</main>
</div>

<script>
const ACCENT  = '#38bdf8';
const ACCENT2 = '#a78bfa';
const SUCCESS = '#4ade80';
const DANGER  = '#f87171';
const WARNING = '#fbbf24';
const MUTED   = '#94a3b8';
const BORDER  = '#2d3540';

Chart.defaults.color       = MUTED;
Chart.defaults.borderColor = BORDER;
Chart.defaults.font.family = "'DM Mono', monospace";
Chart.defaults.font.size   = 11;

const ventasRaw  = <?= $ventasJson ?>;
const visitasRaw = <?= $visitasJson ?>;
const pagosRaw   = <?= $pagosJson ?>;

if (ventasRaw.length) {
    new Chart(document.getElementById('chartVentas'), {
        type: 'line',
        data: {
            labels: ventasRaw.map(r => r.fecha),
            datasets: [{
                label: 'Ventas ($)',
                data: ventasRaw.map(r => parseFloat(r.monto_total)),
                borderColor: ACCENT, backgroundColor: ACCENT + '18',
                borderWidth: 2, fill: true, tension: .35,
                pointRadius: 3, pointHoverRadius: 6, pointBackgroundColor: ACCENT,
            }]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ' $' + ctx.parsed.y.toLocaleString('es-AR') } }
            },
            scales: {
                x: { grid: { color: BORDER }, ticks: { maxTicksLimit: 8 } },
                y: { grid: { color: BORDER }, ticks: { callback: v => '$' + (v / 1000).toFixed(0) + 'k' } }
            }
        }
    });
}

if (visitasRaw.length) {
    new Chart(document.getElementById('chartVisitas'), {
        type: 'bar',
        data: {
            labels: visitasRaw.map(r => r.fecha),
            datasets: [
                { label: 'Total sesiones', data: visitasRaw.map(r => parseInt(r.total_visitas)),
                  backgroundColor: ACCENT2 + '70', borderColor: ACCENT2, borderWidth: 1, borderRadius: 3 },
                { label: 'Sesiones unicas', data: visitasRaw.map(r => parseInt(r.sesiones_unicas)),
                  backgroundColor: SUCCESS + '50', borderColor: SUCCESS, borderWidth: 1, borderRadius: 3 }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { labels: { padding: 16, boxWidth: 10 } } },
            scales: {
                x: { grid: { display: false }, ticks: { maxTicksLimit: 7 } },
                y: { grid: { color: BORDER } }
            }
        }
    });
}

if (pagosRaw.length) {
    new Chart(document.getElementById('chartPagos'), {
        type: 'doughnut',
        data: {
            labels: pagosRaw.map(r => r.tipo_pago),
            datasets: [{
                data: pagosRaw.map(r => parseFloat(r.monto_total)),
                backgroundColor: [ACCENT, ACCENT2, SUCCESS, '#c85c5c', MUTED],
                borderColor: '#18181c', borderWidth: 3
            }]
        },
        options: {
            cutout: '65%',
            plugins: {
                legend: { position: 'bottom', labels: { padding: 14, boxWidth: 10 } },
                tooltip: { callbacks: { label: ctx => ' $' + ctx.parsed.toLocaleString('es-AR') } }
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
</body>
</html>