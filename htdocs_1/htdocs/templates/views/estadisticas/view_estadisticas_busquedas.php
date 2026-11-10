<?php
$terminosJson = json_encode(array_values($terminos_top   ?? []));
$sinResJson   = json_encode(array_values($sin_resultados ?? []));
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Busquedas — Estadisticas</title>
<?php require_once INCLUDES . 'admin/head_admin.php'; ?>

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
.est-subtitle { font-size:.82rem; color:var(--sp-muted); margin-top:.35rem; font-family:var(--sp-mono); letter-spacing:.02em; }

.export-btn { font-family:var(--sp-mono); font-size:.72rem; padding:.28rem .7rem;
              border:1px solid var(--sp-accent); border-radius:6px; color:var(--sp-accent);
              text-decoration:none; transition:.15s; }
.export-btn:hover { background:var(--sp-accent); color:#18181c; }

.alerta-box { background:rgba(200,92,92,.06); border:1px solid rgba(200,92,92,.25);
              border-left:4px solid var(--sp-danger); border-radius:var(--sp-radius);
              padding:1rem 1.2rem; margin-bottom:1.2rem; }
.alerta-titulo { font-size:.88rem; font-weight:700; color:var(--sp-danger); margin-bottom:.25rem; }
.alerta-desc   { font-size:.8rem; color:var(--sp-muted); margin:0; font-family:var(--sp-mono); }

.row2       { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem; }
.chart-card { background:var(--sp-surface); border:1px solid var(--sp-border); border-radius:var(--sp-radius); padding:1.4rem; margin-bottom:1rem; }
.chart-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; }
.chart-title { font-family:var(--sp-mono); font-size:.72rem; text-transform:uppercase; letter-spacing:.1em; color:var(--sp-muted); }
.chart-title-danger { color:var(--sp-danger); }

.nube    { display:flex; flex-wrap:wrap; gap:.5rem; align-items:center; }
.termino { font-family:var(--sp-mono); padding:.3rem .75rem; border-radius:20px;
           border:1px solid var(--sp-border); color:var(--sp-muted);
           cursor:default; transition:.15s; }
.termino:hover { border-color:var(--sp-accent); color:var(--sp-accent); }
.termino.t1 { font-size:.95rem; border-color:var(--sp-accent); color:var(--sp-accent); background:rgba(200,169,110,.07); }
.termino.t2 { font-size:.85rem; color:var(--sp-text); }
.termino.t3 { font-size:.78rem; }
.termino.t4 { font-size:.72rem; }

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
.muted { color:var(--sp-muted); font-size:.8rem; }

.bar-inline-w { display:inline-block; width:70px; height:5px; vertical-align:middle;
                margin:0 .5rem; background:rgba(255,255,255,.06); border-radius:3px; overflow:hidden; }
.bar-inline   { display:block; height:100%; border-radius:3px; background:var(--sp-accent2); }

.sin-res   { display:flex; flex-direction:column; gap:.6rem; }
.sin-row   { display:flex; align-items:center; gap:.8rem; }
.sin-label { font-family:var(--sp-mono); font-size:.82rem; flex:1; color:var(--sp-text); }
.sin-count { font-family:var(--sp-mono); font-size:.75rem; padding:.15rem .55rem;
             border-radius:4px; background:rgba(200,92,92,.12);
             color:var(--sp-danger); border:1px solid rgba(200,92,92,.2); }
.ok-msg    { font-family:var(--sp-mono); font-size:.82rem; color:var(--sp-success); padding:.5rem; }

.empty-state { text-align:center; padding:1.5rem; color:var(--sp-muted); font-size:.82rem; font-family:var(--sp-mono); }
@media(max-width:900px) { .row2 { grid-template-columns:1fr; } }
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
            <h1 class="est-title">Busquedas <span>y demanda</span></h1>
            <div class="est-subtitle">Ultimos 30 dias — <?= date('d/m/Y H:i') ?></div>
        </div>
        <a href="<?= URL ?>estadisticas/exportar/busquedas" class="export-btn">Exportar CSV</a>
    </div>

    <?= Toast::flash() ?>

    <?php if (!empty($sin_resultados)): ?>
    <div class="alerta-box">
        <div class="alerta-titulo">
            Atencion: <?= count($sin_resultados) ?> termino(s) sin resultados en el catalogo
        </div>
        <p class="alerta-desc">
            Estas busquedas no encontraron productos.
            Considerar agregar productos nuevos o revisar los nombres del catalogo.
        </p>
    </div>
    <?php endif; ?>

    <div class="row2">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Nube de terminos buscados</div>
            </div>
            <div class="nube">
                <?php
                $max  = max(1, ...array_column($terminos_top ?: [['veces'=>1]], 'veces'));
                foreach (($terminos_top ?? []) as $t):
                    $ratio = $t['veces'] / $max;
                    $cls   = $ratio > .75 ? 't1' : ($ratio > .5 ? 't2' : ($ratio > .25 ? 't3' : 't4'));
                ?>
                <span class="termino <?= $cls ?>"
                      title="<?= (int)$t['veces'] ?> busquedas — <?= round($t['resultados_promedio'] ?? 0) ?> resultados promedio">
                    <?= htmlspecialchars($t['termino']) ?>
                </span>
                <?php endforeach; ?>
                <?php if (empty($terminos_top)): ?>
                <div class="empty-state">Sin busquedas registradas aun. El tracker.js las registra automaticamente.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Top 10 terminos — busquedas (u.)</div>
            </div>
            <?php if (!empty($terminos_top)): ?>
            <canvas id="chartBusq" height="240"></canvas>
            <?php else: ?>
            <div class="empty-state">Sin datos</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row2">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Todos los terminos (30 dias)</div>
            </div>
            <table class="rank-table" id="tablaBusquedas">
                <thead>
                    <tr>
                        <th onclick="sortTable('tablaBusquedas',0)">Pos.</th>
                        <th onclick="sortTable('tablaBusquedas',1)">Termino</th>
                        <th class="num" onclick="sortTable('tablaBusquedas',2)">Busquedas (u.)</th>
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
                        <td class="muted"><?= $i + 1 ?></td>
                        <td>
                            <?= htmlspecialchars($t['termino']) ?>
                            <span class="bar-inline-w">
                                <span class="bar-inline" style="width:<?= $pct ?>%"></span>
                            </span>
                        </td>
                        <td class="num" style="color:var(--sp-accent2)"><?= number_format((int)$t['veces']) ?></td>
                        <td class="num muted">
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

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title chart-title-danger">Sin resultados — oportunidades de catalogo</div>
            </div>
            <div class="sin-res">
                <?php foreach (($sin_resultados ?? []) as $s): ?>
                <div class="sin-row">
                    <span class="sin-label"><?= htmlspecialchars($s['termino']) ?></span>
                    <span class="sin-count"><?= (int)$s['veces'] ?> busquedas</span>
                </div>
                <?php endforeach; ?>
                <?php if (empty($sin_resultados)): ?>
                <div class="ok-msg">Todas las busquedas tienen resultados en el catalogo</div>
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

const top10 = <?= json_encode(array_slice($terminos_top ?? [], 0, 10)) ?>;

if (top10.length) {
    new Chart(document.getElementById('chartBusq'), {
        type: 'bar',
        data: {
            labels: top10.map(r => r.termino),
            datasets: [{ label: 'Busquedas (u.)',
                data: top10.map(r => parseInt(r.veces)),
                backgroundColor: ACCENT2 + '70', borderColor: ACCENT2,
                borderWidth: 1, borderRadius: 4 }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ' ' + ctx.parsed.x + ' busquedas' } }
            },
            scales: {
                x: { grid: { color: BORDER } },
                y: { grid: { display: false }, ticks: { font: { size: 10 } } }
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