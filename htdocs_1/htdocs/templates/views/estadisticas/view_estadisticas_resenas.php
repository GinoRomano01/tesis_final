<?php
$totalSent = max(1, (int)($metricas['total'] ?? 0));
$pct = function($n) use ($totalSent) { return round(($n / $totalSent) * 100, 1); };
$sent = $metricas['sentimiento'] ?? ['positivo'=>0,'neutro'=>0,'negativo'=>0];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reseñas — Estadísticas</title>
<?php require_once INCLUDES . 'admin/head_admin.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Syne:wght@400;600;700&display=swap" rel="stylesheet">
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
.est-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem; border-bottom:2px solid var(--sp-border); padding-bottom:1rem; }
.est-title { font-size:1.75rem; font-weight:600; letter-spacing:-.01em; margin:0; color:#f8fafc; line-height:1.2; }
.est-title span { color:var(--sp-accent); }
.est-subtitle { font-size:.78rem; color:var(--sp-muted); margin-top:.25rem; font-family:var(--sp-mono); }

.filtros { display:flex; gap:.7rem; align-items:end; flex-wrap:wrap; margin-bottom:1.5rem; background:var(--sp-surface); border:1px solid var(--sp-border); border-radius:var(--sp-radius); padding:1rem 1.2rem; }
.filtros label { font-family:var(--sp-mono); font-size:.68rem; text-transform:uppercase; letter-spacing:.08em; color:var(--sp-muted); display:block; margin-bottom:.3rem; }
.filtros input[type=date] { background:var(--sp-bg); color:var(--sp-text); border:1px solid var(--sp-border); border-radius:6px; padding:.4rem .6rem; font-family:var(--sp-mono); font-size:.82rem; }
.btn-filtrar { font-family:var(--sp-mono); font-size:.72rem; padding:.45rem .9rem; background:var(--sp-accent); border:0; border-radius:6px; color:#0f1419; cursor:pointer; font-weight:500; }
.btn-limpiar { font-family:var(--sp-mono); font-size:.72rem; padding:.4rem .8rem; color:var(--sp-muted); text-decoration:none; }
.btn-limpiar:hover { color:var(--sp-accent); }

.kpi-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:1rem; margin-bottom:1.5rem; }
.kpi-card { background:var(--sp-surface); border:1px solid var(--sp-border); border-radius:var(--sp-radius); padding:1.4rem 1.6rem; position:relative; overflow:hidden; }
.kpi-card::before { content:''; position:absolute; top:0; left:0; right:0; height:2px; background:var(--sp-accent); opacity:.35; }
.kpi-card.pos::before { background:var(--sp-success); opacity:.6; }
.kpi-card.neg::before { background:var(--sp-danger); opacity:.6; }
.kpi-card.warn::before { background:var(--sp-warning); opacity:.6; }
.kpi-card.neu::before { background:var(--sp-muted); opacity:.5; }
.kpi-label { font-family:var(--sp-mono); font-size:.68rem; text-transform:uppercase; letter-spacing:.1em; color:var(--sp-muted); margin-bottom:.5rem; }
.kpi-value { font-family:var(--sp-mono); font-size:1.6rem; font-weight:500; color:var(--sp-text); line-height:1; }
.kpi-sub { font-family:var(--sp-mono); font-size:.7rem; color:var(--sp-muted); margin-top:.4rem; }

.chart-card { background:var(--sp-surface); border:1px solid var(--sp-border); border-radius:var(--sp-radius); padding:1.4rem; margin-bottom:1rem; }
.chart-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; flex-wrap:wrap; gap:.5rem; }
.chart-title { font-family:var(--sp-mono); font-size:.72rem; text-transform:uppercase; letter-spacing:.1em; color:var(--sp-muted); }
.chart-badge { font-family:var(--sp-mono); font-size:.68rem; padding:.15rem .5rem; border-radius:4px; background:rgba(56,189,248,.1); color:var(--sp-accent); }

.bar-row { margin:.7rem 0; }
.bar-row-head { display:flex; justify-content:space-between; font-family:var(--sp-mono); font-size:.78rem; margin-bottom:.3rem; }
.bar-row-head .name { color:var(--sp-text); text-transform:capitalize; }
.bar-row-head .val { color:var(--sp-muted); }
.bar-bg { background:rgba(255,255,255,.04); border-radius:4px; height:8px; overflow:hidden; }
.bar-fill { height:100%; border-radius:4px; transition:width .4s; }

.rank-table { width:100%; border-collapse:collapse; font-size:.84rem; }
.rank-table thead tr { background:rgba(255,255,255,.04); }
.rank-table th { font-family:var(--sp-mono); font-size:.66rem; text-transform:uppercase; letter-spacing:.08em; color:var(--sp-muted); padding:.55rem .5rem; border-bottom:1px solid var(--sp-border); white-space:nowrap; text-align:left; }
.rank-table th.num, .rank-table td.num { text-align:right; }
.rank-table tbody tr:nth-child(even) { background:rgba(255,255,255,.025); }
.rank-table tbody tr:hover { background:rgba(56,189,248,.06); }
.rank-table td { padding:.6rem .5rem; border-bottom:1px solid rgba(42,42,50,.5); color:var(--sp-text); vertical-align:top; }
.rank-table tr:last-child td { border-bottom:none; }
.muted { color:var(--sp-muted); }

.flag-tag { display:inline-block; font-family:var(--sp-mono); font-size:.64rem; padding:.1rem .4rem; border-radius:3px; margin-right:.2rem; background:rgba(248,113,113,.12); color:var(--sp-danger); border:1px solid rgba(248,113,113,.25); }

.resena-detalle summary { cursor:pointer; font-size:.78rem; color:var(--sp-accent); font-family:var(--sp-mono); }
.resena-detalle .contenido { background:var(--sp-bg); border:1px solid var(--sp-border); padding:.7rem; margin-top:.4rem; border-radius:6px; font-size:.82rem; line-height:1.5; }

.btn-action { font-family:var(--sp-mono); font-size:.66rem; padding:.25rem .55rem; border-radius:5px; text-decoration:none; transition:.15s; display:inline-block; margin-right:.2rem; }
.btn-action.aprobar  { border:1px solid var(--sp-success); color:var(--sp-success); }
.btn-action.aprobar:hover  { background:var(--sp-success); color:#0f1419; }
.btn-action.rechazar { border:1px solid var(--sp-danger); color:var(--sp-danger); }
.btn-action.rechazar:hover { background:var(--sp-danger); color:#0f1419; }
.btn-action.ocultar  { border:1px solid var(--sp-muted); color:var(--sp-muted); }
.btn-action.ocultar:hover  { background:var(--sp-muted); color:#0f1419; }

.empty-state { text-align:center; padding:2rem; color:var(--sp-muted); font-size:.85rem; font-family:var(--sp-mono); }
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
            <h1 class="est-title">Reseñas <span>análisis IA</span></h1>
            <div class="est-subtitle">Modelo: <?= GROQ_MODEL ?> — <?= date('d/m/Y H:i') ?></div>
        </div>
    </div>

    <?= Toast::flash() ?>

    
    <form method="GET" action="<?= URL ?>estadisticas/resenas" class="filtros">
        <div>
            <label>Desde</label>
            <input type="date" name="desde" value="<?= htmlspecialchars($desde ?? '') ?>">
        </div>
        <div>
            <label>Hasta</label>
            <input type="date" name="hasta" value="<?= htmlspecialchars($hasta ?? '') ?>">
        </div>
        <button type="submit" class="btn-filtrar">Filtrar</button>
        <a href="<?= URL ?>estadisticas/resenas" class="btn-limpiar">Limpiar</a>
    </form>

    
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-label">Total analizadas</div>
            <div class="kpi-value"><?= number_format((int)$metricas['total'], 0, ',', '.') ?></div>
            <div class="kpi-sub">Reseñas procesadas con IA</div>
        </div>
        <div class="kpi-card pos">
            <div class="kpi-label">Positivas</div>
            <div class="kpi-value"><?= $sent['positivo'] ?></div>
            <div class="kpi-sub"><?= $pct($sent['positivo']) ?>% del total</div>
        </div>
        <div class="kpi-card neu">
            <div class="kpi-label">Neutras</div>
            <div class="kpi-value"><?= $sent['neutro'] ?></div>
            <div class="kpi-sub"><?= $pct($sent['neutro']) ?>% del total</div>
        </div>
        <div class="kpi-card neg">
            <div class="kpi-label">Negativas</div>
            <div class="kpi-value"><?= $sent['negativo'] ?></div>
            <div class="kpi-sub"><?= $pct($sent['negativo']) ?>% del total</div>
        </div>
        <div class="kpi-card warn">
            <div class="kpi-label">Toxicidad prom.</div>
            <div class="kpi-value"><?= number_format((float)$metricas['promedio_toxicidad'], 3, ',', '.') ?></div>
            <div class="kpi-sub">Escala 0–1 (0 = limpio)</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Tokens consumidos</div>
            <div class="kpi-value"><?= number_format((int)$metricas['tokens_total'], 0, ',', '.') ?></div>
            <div class="kpi-sub">Acumulado en Groq <br> 14400 por dia </div>
        </div>
    </div>

    
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title">Distribución de sentimiento</div>
            <span class="chart-badge"><?= (int)$metricas['total'] ?> reseñas</span>
        </div>
        <?php foreach (['positivo'=>'var(--sp-success)','neutro'=>'var(--sp-muted)','negativo'=>'var(--sp-danger)'] as $k=>$color):
            $n = $sent[$k]; $p = $pct($n); ?>
            <div class="bar-row">
                <div class="bar-row-head">
                    <span class="name"><?= $k ?></span>
                    <span class="val"><?= $n ?> · <?= $p ?>%</span>
                </div>
                <div class="bar-bg"><div class="bar-fill" style="width:<?= $p ?>%;background:<?= $color ?>;"></div></div>
            </div>
        <?php endforeach; ?>
    </div>

    
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title">Categorías más mencionadas</div>
            <span class="chart-badge">Detección IA</span>
        </div>
        <?php if (empty($metricas['categorias'])): ?>
            <div class="empty-state">Aún no hay categorías detectadas.</div>
        <?php else:
            $maxCat = max($metricas['categorias']);
            foreach ($metricas['categorias'] as $cat=>$n):
                $p = round(($n/$maxCat)*100); ?>
            <div class="bar-row">
                <div class="bar-row-head">
                    <span class="name"><?= htmlspecialchars($cat) ?></span>
                    <span class="val"><?= $n ?> menciones</span>
                </div>
                <div class="bar-bg"><div class="bar-fill" style="width:<?= $p ?>%;background:var(--sp-accent);"></div></div>
            </div>
        <?php endforeach; endif; ?>
    </div>

    
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title">Cola de revisión</div>
            <span class="chart-badge"><?= count($cola) ?> pendientes</span>
        </div>

        <?php if (empty($cola)): ?>
            <div class="empty-state">No hay reseñas pendientes de moderación. </div>
        <?php else: ?>
        <div style="overflow-x:auto;">
        <table class="rank-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cliente</th>
                    <th class="num">★</th>
                    <th>Resumen IA</th>
                    <th class="num">Tox.</th>
                    <th>Flags</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cola as $r):
                $flags = json_decode($r['Flags'] ?? '[]', true) ?: []; ?>
                <tr>
                    <td><?= htmlspecialchars($r['NombredelProducto'] ?? '—') ?></td>
                    <td class="muted"><?= htmlspecialchars($r['AutorNombre'] ?? '—') ?></td>
                    <td class="num"><?= (int)$r['Puntuacion'] ?></td>
                    <td style="max-width:280px;">
                        <em class="muted"><?= htmlspecialchars($r['ResumenCorto'] ?? '—') ?></em>
                        <details class="resena-detalle">
                            <summary>Ver reseña</summary>
                            <div class="contenido">
                                <?= nl2br(htmlspecialchars($r['ContenidoPublicado'] ?? $r['ContenidoOriginal'])) ?>
                            </div>
                        </details>
                    </td>
                    <td class="num">
                        <?php
                        $tox = (float)$r['ScoreToxicidad'];
                        $tcolor = $tox >= 0.5 ? 'var(--sp-danger)' : ($tox >= 0.25 ? 'var(--sp-warning)' : 'var(--sp-success)');
                        ?>
                        <span style="color:<?= $tcolor ?>;font-family:var(--sp-mono);">
                            <?= number_format($tox, 2, ',', '.') ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($flags): ?>
                            <?php foreach ($flags as $f): ?>
                                <span class="flag-tag"><?= htmlspecialchars($f) ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td style="white-space:nowrap;">
                        <a href="<?= URL ?>estadisticas/moderar-resena/<?= $r['Id'] ?>/aprobada"  class="btn-action aprobar">Aprobar</a>
                        <a href="<?= URL ?>estadisticas/moderar-resena/<?= $r['Id'] ?>/rechazada" class="btn-action rechazar">Rechazar</a>
                        <a href="<?= URL ?>estadisticas/moderar-resena/<?= $r['Id'] ?>/oculta"    class="btn-action ocultar">Ocultar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>

</div>
</main>
</div>

<?php require_once INCLUDES . 'admin/scripts_admin.php'; ?>
</body>
</html>