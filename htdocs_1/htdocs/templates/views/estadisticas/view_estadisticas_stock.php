<?php
$evolucionValorJson = json_encode(array_values($evolucion_valor ?? []));
$evolucionSaludJson = json_encode(array_values($evolucion_salud ?? []));
$inflacion          = $inflacion_categoria ?? [];
$comp               = $comparativa['actual'];
$ultimo             = $comparativa['ultimo'];
$variaciones        = $comparativa['variaciones'];

function variacionStock($val, $sufijo = ''): string {
    if ($val === null) return 'Sin comparativa';
    $signo = $val >= 0 ? '+' : '';
    return $signo . $val . $sufijo;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Stock — Estadísticas</title>
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
.est-header  { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem; border-bottom:2px solid var(--sp-border); padding-bottom:1rem; }
.est-title { font-size:1.75rem; font-weight:600; letter-spacing:-.01em; margin:0; color:#f8fafc; line-height:1.2; }
.est-title span { color:var(--sp-accent); }
.est-subtitle { font-size:.78rem; color:var(--sp-muted); margin-top:.25rem; font-family:var(--sp-mono); }

.export-btn { font-family:var(--sp-mono); font-size:.72rem; padding:.28rem .7rem;
              border:1px solid var(--sp-accent); border-radius:6px; color:var(--sp-accent);
              text-decoration:none; transition:.15s; }
.export-btn:hover { background:var(--sp-accent); color:#18181c; }

.kpi-grid  { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; margin-bottom:1.5rem; }
.kpi-card  { background:var(--sp-surface); border:1px solid var(--sp-border);
             border-radius:var(--sp-radius); padding:1.4rem 1.6rem;
             position:relative; overflow:hidden; }
.kpi-card::before { content:''; position:absolute; top:0; left:0; right:0;
                    height:2px; background:var(--sp-accent); opacity:.35; }
.kpi-card.pos::before { background:var(--sp-success); opacity:.6; }
.kpi-card.neg::before { background:var(--sp-danger);  opacity:.6; }
.kpi-card.warn::before { background:var(--sp-warning); opacity:.6; }
.kpi-label { font-family:var(--sp-mono); font-size:.68rem; text-transform:uppercase;
             letter-spacing:.1em; color:var(--sp-muted); margin-bottom:.5rem; }
.kpi-value { font-family:var(--sp-mono); font-size:1.6rem; font-weight:500; color:var(--sp-text); line-height:1; }
.kpi-unit  { font-size:.7rem; color:var(--sp-muted); margin-left:.25rem; font-family:var(--sp-mono); }
.kpi-delta { font-family:var(--sp-mono); font-size:.72rem; margin-top:.5rem;
             padding:.2rem .5rem; border-radius:4px; display:inline-block; }
.kpi-delta.pos { color:var(--sp-success); background:rgba(92,184,122,.1); }
.kpi-delta.neg { color:var(--sp-danger);  background:rgba(200,92,92,.1); }
.kpi-delta.neu { color:var(--sp-muted);   background:rgba(122,120,112,.1); }
.kpi-vs    { font-size:.68rem; color:var(--sp-muted); margin-top:.25rem; font-family:var(--sp-mono); }

.ia-box { background:linear-gradient(135deg, rgba(200,169,110,.06), rgba(110,157,200,.04));
          border:1px solid rgba(200,169,110,.25);
          border-left:4px solid var(--sp-accent);
          border-radius:var(--sp-radius); padding:1.2rem 1.4rem; margin-bottom:1.5rem; }
.ia-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:.7rem; flex-wrap:wrap; gap:.5rem; }
.ia-tag  { font-family:var(--sp-mono); font-size:.7rem; padding:.2rem .6rem;
           background:rgba(200,169,110,.15); border:1px solid rgba(200,169,110,.3);
           border-radius:20px; color:var(--sp-accent); }
.ia-fecha { font-family:var(--sp-mono); font-size:.7rem; color:var(--sp-muted); }
.ia-prioridad { font-size:.95rem; line-height:1.5; margin:0 0 .8rem; color:var(--sp-text); }
.ia-prioridad strong { color:var(--sp-accent); }
.ia-alertas { display:flex; flex-direction:column; gap:.4rem; margin-top:.6rem; }
.ia-alerta-item { font-family:var(--sp-mono); font-size:.78rem; color:var(--sp-muted); padding-left:1.2rem; position:relative; }
.ia-alerta-item::before { content:'!'; position:absolute; left:0; color:var(--sp-danger); font-weight:700; }
.ia-link { font-family:var(--sp-mono); font-size:.72rem; color:var(--sp-accent); text-decoration:none; }
.ia-link:hover { text-decoration:underline; }

.row2 { display:grid; grid-template-columns:2fr 1fr; gap:1rem; margin-bottom:1rem; }
.row2-eq { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem; }
.chart-card { background:var(--sp-surface); border:1px solid var(--sp-border); border-radius:var(--sp-radius); padding:1.4rem; }
.chart-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; }
.chart-title { font-family:var(--sp-mono); font-size:.72rem; text-transform:uppercase; letter-spacing:.1em; color:var(--sp-muted); }
.chart-badge { font-family:var(--sp-mono); font-size:.68rem; padding:.15rem .5rem; border-radius:4px; background:rgba(200,169,110,.1); color:var(--sp-accent); }

.inf-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:.6rem; margin-bottom:.8rem; }
.inf-cell { background:rgba(255,255,255,.025); border:1px solid var(--sp-border); border-radius:8px; padding:.7rem .9rem; }
.inf-label { font-family:var(--sp-mono); font-size:.66rem; text-transform:uppercase; letter-spacing:.08em; color:var(--sp-muted); }
.inf-tipo { display:flex; justify-content:space-between; margin-top:.4rem; }
.inf-tipo-row { font-family:var(--sp-mono); font-size:.78rem; }
.inf-tipo-row .tipo { color:var(--sp-muted); }
.inf-tipo-row .val { color:var(--sp-text); font-weight:500; }
.inf-tipo-row .val.danger { color:var(--sp-danger); }
.inf-tipo-row .val.success { color:var(--sp-success); }

.rank-table  { width:100%; border-collapse:collapse; font-size:.84rem; }
.rank-table thead tr { background:rgba(255,255,255,.04); }
.rank-table th { font-family:var(--sp-mono); font-size:.66rem; text-transform:uppercase;
                 letter-spacing:.08em; color:var(--sp-muted); padding:.55rem .5rem;
                 border-bottom:1px solid var(--sp-border); cursor:pointer;
                 white-space:nowrap; user-select:none; text-align:left; }
.rank-table th:hover { color:var(--sp-accent); }
.rank-table th.num, .rank-table td.num { text-align:right; }
.rank-table tbody tr:nth-child(even) { background:rgba(255,255,255,.025); }
.rank-table tbody tr:hover { background:rgba(200,169,110,.06); }
.rank-table td { padding:.55rem .5rem; border-bottom:1px solid rgba(42,42,50,.5);
                 color:var(--sp-text); vertical-align:middle; }
.rank-table tr:last-child td { border-bottom:none; }
.muted { color:var(--sp-muted); font-size:.78rem; }
.accent { color:var(--sp-accent); font-family:var(--sp-mono); }
.danger { color:var(--sp-danger); font-family:var(--sp-mono); }
.success { color:var(--sp-success); font-family:var(--sp-mono); }

.tipo-tag { display:inline-block; font-family:var(--sp-mono); font-size:.66rem;
            padding:.12rem .45rem; border-radius:4px; }
.tipo-tag.madera { background:rgba(200,169,110,.12); color:var(--sp-accent); border:1px solid rgba(200,169,110,.25); }
.tipo-tag.insumo { background:rgba(110,157,200,.12); color:var(--sp-accent2); border:1px solid rgba(110,157,200,.25); }

.btn-action { font-family:var(--sp-mono); font-size:.7rem; padding:.2rem .55rem;
              border:1px solid var(--sp-accent); border-radius:5px;
              color:var(--sp-accent); text-decoration:none; transition:.15s; }
.btn-action:hover { background:var(--sp-accent); color:#18181c; }

.empty-state { text-align:center; padding:1.8rem; color:var(--sp-muted); font-size:.82rem; font-family:var(--sp-mono); }

@media(max-width:900px) { .row2, .row2-eq, .inf-grid { grid-template-columns:1fr; } }
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
            <h1 class="est-title">Stock <span>análisis</span></h1>
            <div class="est-subtitle">Snapshot en vivo + histórico de diagnósticos IA — <?= date('d/m/Y H:i') ?></div>
        </div>
        <div style="display:flex;gap:.5rem;">
            <a href="<?= URL ?>stock/historialDiagnosticos" class="export-btn">Ver diagnósticos</a>
            <a href="<?= URL ?>estadisticas/exportar/stock" class="export-btn">Exportar CSV</a>
        </div>
    </div>

    <?= Toast::flash() ?>

    
    <?php if ($ultimo_ia): ?>
    <div class="ia-box">
        <div class="ia-head">
            <div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;">
                <span class="ia-tag">Diagnóstico IA #<?= $ultimo_ia['id'] ?></span>
                <span class="ia-fecha">Generado: <?= date('d/m/Y H:i', strtotime($ultimo_ia['fecha'])) ?></span>
                <?php if ($ultimo_ia['puntaje'] !== null): ?>
                    <?php
                    $pColor = $ultimo_ia['puntaje'] >= 75 ? 'var(--sp-success)' 
                            : ($ultimo_ia['puntaje'] >= 50 ? 'var(--sp-warning)' : 'var(--sp-danger)');
                    ?>
                    <span class="ia-tag" style="color:<?= $pColor ?>;border-color:<?= $pColor ?>;background:transparent;">
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
    <div class="ia-box" style="border-left-color:var(--sp-muted);">
        <div class="ia-head">
            <span class="ia-tag" style="background:rgba(122,120,112,.12);color:var(--sp-muted);border-color:var(--sp-border);">Sin diagnósticos IA</span>
        </div>
        <p class="ia-prioridad" style="margin:0;">
            Todavía no generaste ningún diagnóstico con IA. 
            <a href="<?= URL ?>stock" class="ia-link">Ir a Stock →</a>
        </p>
    </div>
    <?php endif; ?>

    
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
        </div>

        <div class="kpi-card warn">
            <div class="kpi-label">Items con bajo stock</div>
            <div class="kpi-value"><?= $comp['items_bajo_stock'] ?>
                <span class="kpi-unit">items</span>
            </div>
            <?php if ($deltaBajo !== null): ?>
                <?php $cls = $deltaBajo > 0 ? 'neg' : ($deltaBajo < 0 ? 'pos' : 'neu'); ?>
                <div class="kpi-delta <?= $cls ?>">
                    <?= variacionStock($deltaBajo, ' items') ?> vs último
                </div>
            <?php endif; ?>
            <div class="kpi-vs">Calculado en vivo desde stock</div>
        </div>

        <div class="kpi-card <?= ($comp['items_sin_stock'] > 0) ? 'neg' : 'pos' ?>">
            <div class="kpi-label">Items sin stock</div>
            <div class="kpi-value"><?= $comp['items_sin_stock'] ?>
                <span class="kpi-unit">items</span>
            </div>
            <?php if ($deltaSin !== null): ?>
                <?php $cls = $deltaSin > 0 ? 'neg' : ($deltaSin < 0 ? 'pos' : 'neu'); ?>
                <div class="kpi-delta <?= $cls ?>">
                    <?= variacionStock($deltaSin, ' items') ?> vs último
                </div>
            <?php endif; ?>
            <div class="kpi-vs">Requieren reposición urgente</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Items totales en stock</div>
            <div class="kpi-value"><?= $comp['items_total'] ?>
                <span class="kpi-unit">items</span>
            </div>
            <div class="kpi-delta neu">
                <?= $comp['items_maderas'] ?> maderas · <?= $comp['items_insumos'] ?> insumos
            </div>
            <div class="kpi-vs">Madera: $<?= number_format($comp['valor_maderas'], 0, ',', '.') ?> · Insumos: $<?= number_format($comp['valor_insumos'], 0, ',', '.') ?></div>
        </div>
    </div>

    
    <div class="row2">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Evolución del valor del stock</div>
                <span class="chart-badge"><?= count($evolucion_valor ?? []) ?> diagnósticos</span>
            </div>
            <?php if (!empty($evolucion_valor)): ?>
            <canvas id="chartValor" height="200"></canvas>
            <?php else: ?>
            <div class="empty-state">Generá diagnósticos desde /stock para ver evolución en el tiempo.</div>
            <?php endif; ?>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Distribución del stock actual</div>
            </div>
            <?php if ($comp['valor_total'] > 0): ?>
            <canvas id="chartDistrib" height="200"></canvas>
            <?php else: ?>
            <div class="empty-state">Sin stock cargado</div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="row2-eq">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Items bajo/sin stock — histórico</div>
            </div>
            <?php if (!empty($evolucion_valor)): ?>
            <canvas id="chartItems" height="200"></canvas>
            <?php else: ?>
            <div class="empty-state">Sin diagnósticos para comparar</div>
            <?php endif; ?>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Puntaje de salud — evolución</div>
                <?php if (!empty($evolucion_salud)): ?>
                <span class="chart-badge">Actual: <?= end($evolucion_salud)['puntaje'] ?>/100</span>
                <?php endif; ?>
            </div>
            <?php if (!empty($evolucion_salud)): ?>
            <canvas id="chartSalud" height="200"></canvas>
            <?php else: ?>
            <div class="empty-state">El puntaje aparece al generar diagnósticos</div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="chart-card" style="margin-bottom:1rem;">
        <div class="chart-header">
            <div class="chart-title">Inflación promedio por categoría de material</div>
            <span class="chart-badge">Desde stock_historial_precios</span>
        </div>
        <div class="inf-grid">
            <?php foreach ([30, 60, 90] as $rango):
                $d = $inflacion[$rango] ?? ['maderas' => ['promedio' => 0, 'cambios' => 0], 'insumos' => ['promedio' => 0, 'cambios' => 0]];
            ?>
            <div class="inf-cell">
                <div class="inf-label">Últimos <?= $rango ?> días</div>
                <div class="inf-tipo">
                    <div class="inf-tipo-row">
                        <span class="tipo">🪵 Maderas</span><br>
                        <span class="val <?= $d['maderas']['promedio'] > 0 ? 'danger' : ($d['maderas']['promedio'] < 0 ? 'success' : '') ?>">
                            <?= $d['maderas']['promedio'] >= 0 ? '+' : '' ?><?= $d['maderas']['promedio'] ?>%
                        </span><br>
                        <span class="tipo" style="font-size:.7rem;"><?= $d['maderas']['cambios'] ?> cambios</span>
                    </div>
                    <div class="inf-tipo-row" style="text-align:right;">
                        <span class="tipo">🔩 Insumos</span><br>
                        <span class="val <?= $d['insumos']['promedio'] > 0 ? 'danger' : ($d['insumos']['promedio'] < 0 ? 'success' : '') ?>">
                            <?= $d['insumos']['promedio'] >= 0 ? '+' : '' ?><?= $d['insumos']['promedio'] ?>%
                        </span><br>
                        <span class="tipo" style="font-size:.7rem;"><?= $d['insumos']['cambios'] ?> cambios</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    
    <div class="row2-eq">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Materiales más volátiles (90 días)</div>
            </div>
            <table class="rank-table" id="tablaVolatiles">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Material</th>
                        <th class="num">Cambios (u.)</th>
                        <th class="num">Var. promedio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($volatiles ?? []) as $i => $v): ?>
                    <tr>
                        <td class="muted"><?= $i + 1 ?></td>
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

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Histórico de diagnósticos</div>
                <a href="<?= URL ?>stock/historialDiagnosticos" class="export-btn">Ver todos</a>
            </div>
            <table class="rank-table" id="tablaHistorico">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th class="num">Valor ($)</th>
                        <th class="num">Var. %</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($historico ?? []) as $h): ?>
                    <tr>
                        <td class="muted">#<?= $h['Id'] ?></td>
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

const evolValor = <?= $evolucionValorJson ?>;
const evolSalud = <?= $evolucionSaludJson ?>;

if (evolValor.length) {
    new Chart(document.getElementById('chartValor'), {
        type: 'line',
        data: {
            labels: evolValor.map(r => r.fecha),
            datasets: [{
                label: 'Valor total ($)',
                data: evolValor.map(r => parseFloat(r.valor)),
                borderColor: ACCENT,
                backgroundColor: ACCENT + '20',
                borderWidth: 2, fill: true, tension: .35,
                pointRadius: 4, pointHoverRadius: 7, pointBackgroundColor: ACCENT,
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

const valMaderas = <?= (float)$comp['valor_maderas'] ?>;
const valInsumos = <?= (float)$comp['valor_insumos'] ?>;
if (valMaderas + valInsumos > 0) {
    new Chart(document.getElementById('chartDistrib'), {
        type: 'doughnut',
        data: {
            labels: ['Maderas', 'Insumos'],
            datasets: [{
                data: [valMaderas, valInsumos],
                backgroundColor: [ACCENT, ACCENT2],
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

if (evolValor.length) {
    new Chart(document.getElementById('chartItems'), {
        type: 'bar',
        data: {
            labels: evolValor.map(r => r.fecha),
            datasets: [
                { label: 'Bajo stock', data: evolValor.map(r => parseInt(r.bajo)),
                  backgroundColor: WARNING + '70', borderColor: WARNING, borderWidth: 1, borderRadius: 3 },
                { label: 'Sin stock',  data: evolValor.map(r => parseInt(r.sin)),
                  backgroundColor: DANGER + '70', borderColor: DANGER, borderWidth: 1, borderRadius: 3 }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { labels: { padding: 16, boxWidth: 10 } } },
            scales: {
                x: { grid: { display: false }, ticks: { maxTicksLimit: 7 } },
                y: { grid: { color: BORDER }, beginAtZero: true }
            }
        }
    });
}

if (evolSalud.length) {
    new Chart(document.getElementById('chartSalud'), {
        type: 'line',
        data: {
            labels: evolSalud.map(r => r.fecha),
            datasets: [{
                label: 'Salud',
                data: evolSalud.map(r => parseInt(r.puntaje)),
                borderColor: SUCCESS,
                backgroundColor: SUCCESS + '20',
                borderWidth: 2, fill: true, tension: .35,
                pointRadius: 5, pointHoverRadius: 8,
                pointBackgroundColor: evolSalud.map(r =>
                    r.puntaje >= 75 ? SUCCESS : (r.puntaje >= 50 ? WARNING : DANGER)
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
                x: { grid: { color: BORDER }, ticks: { maxTicksLimit: 8 } },
                y: { grid: { color: BORDER }, min: 0, max: 100,
                     ticks: { stepSize: 25, callback: v => v + ' pts' } }
            }
        }
    });
}
</script>
<?php require_once INCLUDES . 'admin/scripts_admin.php'; ?>
</body>
</html>