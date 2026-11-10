<?php
$mensualJson = json_encode(array_values($ventas_mensuales ?? []));
$estadoJson  = json_encode(array_values($estado_mes      ?? []));
$pagosJson   = json_encode(array_values($por_tipo_pago   ?? []));
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ventas — Estadisticas</title>
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

.charts-row2 { display:grid; grid-template-columns:2fr 1fr; gap:1rem; margin-bottom:1rem; }
.charts-row3 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem; }
.chart-card  { background:var(--sp-surface); border:1px solid var(--sp-border); border-radius:var(--sp-radius); padding:1.4rem; }
.chart-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; }
.chart-title { font-family:var(--sp-mono); font-size:.72rem; text-transform:uppercase; letter-spacing:.1em; color:var(--sp-muted); }

.rank-table    { width:100%; border-collapse:collapse; font-size:.84rem; }
.rank-table thead tr { background:rgba(255,255,255,.04); }
.rank-table th { font-family:var(--sp-mono); font-size:.68rem; text-transform:uppercase;
                 letter-spacing:.08em; color:var(--sp-muted); padding:.5rem .4rem;
                 border-bottom:1px solid var(--sp-border); cursor:pointer;
                 white-space:nowrap; user-select:none; }
.rank-table th:hover { color:var(--sp-accent); }
.rank-table th.num, .rank-table td.num { text-align:right; }
.rank-table tbody tr:nth-child(even) { background:rgba(255,255,255,.025); }
.rank-table tbody tr:hover           { background:rgba(200,169,110,.06); }
.rank-table td { padding:.52rem .4rem; border-bottom:1px solid rgba(42,42,50,.5); color:var(--sp-text); }
.rank-table tr:last-child td { border-bottom:none; }
.accent { color:var(--sp-accent); }
.muted  { color:var(--sp-muted); font-size:.8rem; }

.estado-badges { display:flex; flex-direction:column; gap:.8rem; margin-top:.5rem; }
.estado-row    { display:flex; align-items:center; gap:.8rem; }
.estado-label  { font-size:.82rem; min-width:90px; color:var(--sp-text); }
.estado-bar-w  { flex:1; background:rgba(255,255,255,.06); border-radius:4px; height:10px; overflow:hidden; }
.estado-bar    { height:100%; border-radius:4px; }
.estado-info   { display:flex; flex-direction:column; align-items:flex-end; min-width:90px; }
.estado-num    { font-family:var(--sp-mono); font-size:.78rem; color:var(--sp-text); }
.estado-monto  { font-family:var(--sp-mono); font-size:.68rem; color:var(--sp-muted); }

.empty-state { text-align:center; padding:1.5rem; color:var(--sp-muted); font-size:.82rem; font-family:var(--sp-mono); }
@media(max-width:900px) { .charts-row2,.charts-row3 { grid-template-columns:1fr; } }
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
            <h1 class="est-title">Ventas <span>detalle</span></h1>
            <div class="est-subtitle">Resumen mensual — actualizado <?= date('d/m/Y H:i') ?></div>
        </div>
        <a href="<?= URL ?>estadisticas/exportar/ventas" class="export-btn">Exportar CSV</a>
    </div>

    <?= Toast::flash() ?>

    <div class="charts-row2">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Evolucion mensual — ultimos 12 meses</div>
            </div>
            <?php if (!empty($ventas_mensuales)): ?>
            <canvas id="chartMensual" height="220"></canvas>
            <?php else: ?>
            <div class="empty-state">Sin ventas aprobadas en los ultimos 12 meses</div>
            <?php endif; ?>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Estado del mes actual</div>
            </div>
            <div class="estado-badges">
                <?php
                $colores = [
                    'Aprobado'  => '#5cb87a',
                    'Pendiente' => '#c8a96e',
                    'Rechazado' => '#c85c5c',
                ];
                $maxEst = max(1, ...array_column($estado_mes ?: [['cantidad'=>1]], 'cantidad'));
                foreach (($estado_mes ?? []) as $e):
                    $pct = round($e['cantidad'] / $maxEst * 100);
                    $col = $colores[$e['estado']] ?? '#7a7870';
                ?>
                <div class="estado-row">
                    <span class="estado-label"><?= htmlspecialchars($e['estado']) ?></span>
                    <div class="estado-bar-w">
                        <div class="estado-bar" style="width:<?= $pct ?>%;background:<?= $col ?>"></div>
                    </div>
                    <div class="estado-info">
                        <span class="estado-num"><?= (int)$e['cantidad'] ?> ordenes</span>
                        <span class="estado-monto">$<?= number_format((float)($e['monto_total'] ?? 0), 0, ',', '.') ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($estado_mes)): ?>
                <div class="empty-state">Sin ventas este mes</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="charts-row3">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Top productos</div>
                <a href="<?= URL ?>estadisticas/exportar/productos" class="export-btn">Exportar CSV</a>
            </div>
            <table class="rank-table" id="tablaProductos">
                <thead>
                    <tr>
                        <th onclick="sortTable('tablaProductos',0)">Pos.</th>
                        <th onclick="sortTable('tablaProductos',1)">Producto</th>
                        <th class="num" onclick="sortTable('tablaProductos',2)">Uds. (u.)</th>
                        <th class="num" onclick="sortTable('tablaProductos',3)">Ingreso ($)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($top_productos ?? []) as $i => $p): ?>
                    <tr>
                        <td class="muted"><?= $i + 1 ?></td>
                        <td>
                            <div><?= htmlspecialchars($p['nombre']) ?></div>
                            <div class="muted"><?= htmlspecialchars($p['categoria']) ?></div>
                        </td>
                        <td class="num accent"><?= number_format((int)$p['unidades_vendidas']) ?></td>
                        <td class="num muted">$<?= number_format((float)$p['ingreso_total'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($top_productos)): ?>
                    <tr><td colspan="4" class="empty-state">Sin datos</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Top clientes</div>
                <a href="<?= URL ?>estadisticas/exportar/clientes" class="export-btn">Exportar CSV</a>
            </div>
            <table class="rank-table" id="tablaClientes">
                <thead>
                    <tr>
                        <th onclick="sortTable('tablaClientes',0)">Pos.</th>
                        <th onclick="sortTable('tablaClientes',1)">Cliente</th>
                        <th class="num" onclick="sortTable('tablaClientes',2)">Ordenes (u.)</th>
                        <th class="num" onclick="sortTable('tablaClientes',3)">Total ($)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($top_clientes ?? []) as $i => $c): ?>
                    <tr>
                        <td class="muted"><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($c['cliente']) ?></td>
                        <td class="num accent"><?= number_format((int)$c['ordenes']) ?></td>
                        <td class="num muted">$<?= number_format((float)$c['total_gastado'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($top_clientes)): ?>
                    <tr><td colspan="4" class="empty-state">Sin datos</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div style="max-width:460px">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Distribucion por tipo de pago — monto ($, 30 dias)</div>
            </div>
            <?php if (!empty($por_tipo_pago)): ?>
            <canvas id="chartPagos" height="200"></canvas>
            <?php else: ?>
            <div class="empty-state">Sin pagos en los ultimos 30 dias</div>
            <?php endif; ?>
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

const mensualRaw = <?= $mensualJson ?>;
const pagosRaw   = <?= $pagosJson ?>;

if (mensualRaw.length) {
    new Chart(document.getElementById('chartMensual'), {
        type: 'bar',
        data: {
            labels: mensualRaw.map(r => r.mes),
            datasets: [
                { label: 'Monto ($)',
                  data: mensualRaw.map(r => parseFloat(r.monto_total)),
                  backgroundColor: ACCENT + '60', borderColor: ACCENT,
                  borderWidth: 1, borderRadius: 4, yAxisID: 'y' },
                { label: 'Ordenes (u.)',
                  data: mensualRaw.map(r => parseInt(r.ordenes)),
                  type: 'line', borderColor: ACCENT2, borderWidth: 2,
                  pointRadius: 3, pointHoverRadius: 6, fill: false, tension: .3, yAxisID: 'y2' }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { labels: { padding: 16, boxWidth: 10 } } },
            scales: {
                x:  { grid: { display: false } },
                y:  { grid: { color: BORDER }, ticks: { callback: v => '$' + (v / 1000).toFixed(0) + 'k' } },
                y2: { position: 'right', grid: { display: false } }
            }
        }
    });
}

if (pagosRaw.length) {
    new Chart(document.getElementById('chartPagos'), {
        type: 'doughnut',
        data: {
            labels: pagosRaw.map(r => r.tipo_pago),
            datasets: [{ data: pagosRaw.map(r => parseFloat(r.monto_total)),
                backgroundColor: [SUCCESS, ACCENT, ACCENT2, DANGER, MUTED],
                borderColor: '#18181c', borderWidth: 3 }]
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