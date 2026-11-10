<?php
$visitasJson     = json_encode(array_values($visitas_diarias ?? []));
$dispositivoJson = json_encode(array_values($dispositivos   ?? []));
$eventosJson     = json_encode(array_values($eventos        ?? []));
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Visitas — Estadisticas</title>
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

.filtros-bar { display:flex; align-items:center; gap:.5rem; }
.dias-pills  { display:flex; gap:.3rem; }
.dias-pills a { font-family:var(--sp-mono); font-size:.72rem; padding:.28rem .65rem;
                border:1px solid var(--sp-border); border-radius:6px; color:var(--sp-muted);
                text-decoration:none; transition:.15s; }
.dias-pills a:hover, .dias-pills a.active { border-color:var(--sp-accent); color:var(--sp-accent); }
.btn-limpiar { font-family:var(--sp-mono); font-size:.72rem; padding:.28rem .65rem;
               border:1px solid var(--sp-border); border-radius:6px; color:var(--sp-muted);
               background:none; cursor:pointer; transition:.15s; text-decoration:none; }
.btn-limpiar:hover { border-color:var(--sp-danger); color:var(--sp-danger); }

.chip-activo { font-family:var(--sp-mono); font-size:.7rem; padding:.2rem .7rem;
               background:rgba(200,169,110,.1); border:1px solid rgba(200,169,110,.25);
               border-radius:20px; color:var(--sp-accent); display:inline-block; margin-bottom:1rem; }

.export-btn { font-family:var(--sp-mono); font-size:.72rem; padding:.28rem .7rem;
              border:1px solid var(--sp-accent); border-radius:6px; color:var(--sp-accent);
              text-decoration:none; transition:.15s; }
.export-btn:hover { background:var(--sp-accent); color:#18181c; }

.chart-card  { background:var(--sp-surface); border:1px solid var(--sp-border); border-radius:var(--sp-radius); padding:1.4rem; margin-bottom:1rem; }
.chart-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; }
.chart-title { font-family:var(--sp-mono); font-size:.72rem; text-transform:uppercase; letter-spacing:.1em; color:var(--sp-muted); }
.chart-badge { font-family:var(--sp-mono); font-size:.68rem; padding:.15rem .5rem; border-radius:4px; background:rgba(200,169,110,.1); color:var(--sp-accent); }

.row2 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem; }

.rank-table    { width:100%; border-collapse:collapse; font-size:.82rem; }
.rank-table thead tr { background:rgba(255,255,255,.04); }
.rank-table th { font-family:var(--sp-mono); font-size:.67rem; text-transform:uppercase;
                 letter-spacing:.08em; color:var(--sp-muted); padding:.5rem .4rem;
                 border-bottom:1px solid var(--sp-border); cursor:pointer;
                 white-space:nowrap; user-select:none; }
.rank-table th:hover { color:var(--sp-accent); }
.rank-table th.num, .rank-table td.num { text-align:right; }
.rank-table tbody tr:nth-child(even) { background:rgba(255,255,255,.025); }
.rank-table tbody tr:hover           { background:rgba(200,169,110,.06); }
.rank-table td { padding:.52rem .4rem; border-bottom:1px solid rgba(42,42,50,.5);
                 color:var(--sp-text); vertical-align:middle;
                 max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.rank-table tr:last-child td { border-bottom:none; }
.muted { color:var(--sp-muted); font-size:.78rem; }

.eventos-list { display:flex; flex-direction:column; gap:.6rem; }
.evento-row   { display:flex; align-items:center; gap:.8rem; }
.evento-tag   { font-family:var(--sp-mono); font-size:.7rem; padding:.2rem .5rem;
                border-radius:4px; background:rgba(255,255,255,.05); color:var(--sp-muted);
                min-width:110px; text-align:center; border:1px solid var(--sp-border); }
.evento-bar-w { flex:1; background:rgba(255,255,255,.06); border-radius:3px; height:6px; overflow:hidden; }
.evento-bar   { height:100%; border-radius:3px; background:var(--sp-accent2); }
.evento-num   { font-family:var(--sp-mono); font-size:.75rem; color:var(--sp-muted); min-width:40px; text-align:right; }

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
            <h1 class="est-title">Visitas <span>y trafico</span></h1>
            <div class="est-subtitle">Periodo: ultimos <?= $dias ?> dias — <?= date('d/m/Y H:i') ?></div>
        </div>
        <div class="filtros-bar">
            <div class="dias-pills">
                <?php foreach ([7, 30, 90] as $d): ?>
                <a href="?dias=<?= $d ?>" class="<?= ($dias == $d) ? 'active' : '' ?>"><?= $d ?> dias</a>
                <?php endforeach; ?>
            </div>
            <?php if ($dias != 30): ?>
            <a href="?dias=30" class="btn-limpiar">Limpiar filtro</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="chip-activo">Periodo activo: ultimos <?= $dias ?> dias</div>

    <?= Toast::flash() ?>

    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title">Visitas por dia — ultimos <?= $dias ?> dias</div>
            <span class="chart-badge"><?= count($visitas_diarias ?? []) ?> registros</span>
        </div>
        <?php if (!empty($visitas_diarias)): ?>
        <canvas id="chartVisitas" height="160"></canvas>
        <?php else: ?>
        <div class="empty-state">Sin visitas registradas en el periodo. Verificar que tracker.js este activo.</div>
        <?php endif; ?>
    </div>

    <div class="row2">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Dispositivos (30 dias)</div>
            </div>
            <?php if (!empty($dispositivos)): ?>
            <canvas id="chartDisp" height="200"></canvas>
            <?php else: ?>
            <div class="empty-state">Sin datos de dispositivos</div>
            <?php endif; ?>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Eventos por tipo — cantidad (u.)</div>
            </div>
            <?php if (!empty($eventos)): ?>
            <canvas id="chartEventos" height="200"></canvas>
            <?php else: ?>
            <div class="empty-state">Sin eventos registrados. Agregar atributo data-track en los elementos del sitio.</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title">Paginas mas visitadas (30 dias)</div>
            <a href="<?= URL ?>estadisticas/exportar/visitas" class="export-btn">Exportar CSV</a>
        </div>
        <table class="rank-table" id="tablaVisitas">
            <thead>
                <tr>
                    <th onclick="sortTable('tablaVisitas',0)" style="width:32px">Pos.</th>
                    <th onclick="sortTable('tablaVisitas',1)">URL</th>
                    <th onclick="sortTable('tablaVisitas',2)">Titulo</th>
                    <th class="num" onclick="sortTable('tablaVisitas',3)">Visitas (u.)</th>
                    <th class="num" onclick="sortTable('tablaVisitas',4)">Sesiones unicas (u.)</th>
                    <th class="num" onclick="sortTable('tablaVisitas',5)">Tiempo prom. (seg.)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($paginas_top ?? []) as $i => $pg): ?>
                <tr>
                    <td class="muted"><?= $i + 1 ?></td>
                    <td title="<?= htmlspecialchars($pg['url']) ?>">
                        <?= htmlspecialchars(parse_url($pg['url'], PHP_URL_PATH) ?: $pg['url']) ?>
                    </td>
                    <td class="muted"><?= htmlspecialchars($pg['titulo'] ?? '—') ?></td>
                    <td class="num" style="color:var(--sp-accent)"><?= number_format($pg['total_visitas']) ?></td>
                    <td class="num muted"><?= number_format($pg['sesiones_unicas']) ?></td>
                    <td class="num muted"><?= $pg['tiempo_promedio_seg'] ? round($pg['tiempo_promedio_seg']) . 's' : '—' ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($paginas_top)): ?>
                <tr><td colspan="6" class="empty-state" style="padding:.8rem">Sin paginas registradas aun</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
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

const visitasRaw     = <?= $visitasJson ?>;
const dispositivoRaw = <?= $dispositivoJson ?>;
const eventosRaw     = <?= $eventosJson ?>;

if (visitasRaw.length) {
    new Chart(document.getElementById('chartVisitas'), {
        type: 'line',
        data: {
            labels: visitasRaw.map(r => r.fecha),
            datasets: [
                { label: 'Total visitas', data: visitasRaw.map(r => parseInt(r.total_visitas)),
                  borderColor: ACCENT2, backgroundColor: ACCENT2 + '18',
                  borderWidth: 2, fill: true, tension: .35, pointRadius: 2, pointHoverRadius: 5 },
                { label: 'Sesiones unicas', data: visitasRaw.map(r => parseInt(r.sesiones_unicas)),
                  borderColor: ACCENT, borderWidth: 1.5, fill: false,
                  tension: .35, pointRadius: 2, borderDash: [4, 4] }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { labels: { padding: 16, boxWidth: 10 } } },
            scales: {
                x: { grid: { color: BORDER }, ticks: { maxTicksLimit: 10 } },
                y: { grid: { color: BORDER } }
            }
        }
    });
}

if (dispositivoRaw.length) {
    new Chart(document.getElementById('chartDisp'), {
        type: 'doughnut',
        data: {
            labels: dispositivoRaw.map(r => r.dispositivo),
            datasets: [{ data: dispositivoRaw.map(r => parseInt(r.total)),
                backgroundColor: [ACCENT, ACCENT2, SUCCESS, MUTED],
                borderColor: '#18181c', borderWidth: 3 }]
        },
        options: {
            cutout: '65%',
            plugins: {
                legend: { position: 'bottom', labels: { padding: 14, boxWidth: 10 } },
                tooltip: { callbacks: { label: ctx => ' ' + ctx.parsed + ' sesiones' } }
            }
        }
    });
}

if (eventosRaw.length) {
    new Chart(document.getElementById('chartEventos'), {
        type: 'bar',
        data: {
            labels: eventosRaw.map(r => r.tipo),
            datasets: [{ label: 'Cantidad (u.)',
                data: eventosRaw.map(r => parseInt(r.total)),
                backgroundColor: ACCENT2 + '70', borderColor: ACCENT2,
                borderWidth: 1, borderRadius: 4 }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false },
                       tooltip: { callbacks: { label: ctx => ' ' + ctx.parsed.x + ' eventos' } } },
            scales: {
                x: { grid: { color: BORDER } },
                y: { grid: { display: false } }
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
</body>
</html>