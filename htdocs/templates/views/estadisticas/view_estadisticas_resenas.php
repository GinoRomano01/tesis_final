<?php
$totalSent = max(1, (int)($metricas['total'] ?? 0));
$pct = function($n) use ($totalSent) { return round(($n / $totalSent) * 100, 1); };
$sent = $metricas['sentimiento'] ?? ['positivo'=>0,'neutro'=>0,'negativo'=>0];
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
            <h1 class="est-title">Reseñas <span>análisis IA</span></h1>
            <div class="est-subtitle">Modelo: <?= GROQ_MODEL ?> — <?= date('d/m/Y H:i') ?></div>
        </div>
    </div>

    <?php include INCLUDES . 'admin/filtro_fechas.php'; ?>

    <?= Toast::flash() ?>

    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-label">Total analizadas</div>
            <div class="kpi-value"><?= number_format((int)$metricas['total'], 0, ',', '.') ?></div>
            <div class="kpi-sub">Reseñas procesadas con IA</div>
            <div class="kpi-periodo"><?= ffIcono() ?><?= ffDelAl($rango['desde'], $rango['hasta']) ?></div>
        </div>
        <div class="kpi-card pos">
            <div class="kpi-label">Positivas</div>
            <div class="kpi-value"><?= $sent['positivo'] ?></div>
            <div class="kpi-sub"><?= $pct($sent['positivo']) ?>% del total</div>
            <div class="kpi-periodo"><?= ffIcono() ?><?= ffDelAl($rango['desde'], $rango['hasta']) ?></div>
        </div>
        <div class="kpi-card neu">
            <div class="kpi-label">Neutras</div>
            <div class="kpi-value"><?= $sent['neutro'] ?></div>
            <div class="kpi-sub"><?= $pct($sent['neutro']) ?>% del total</div>
            <div class="kpi-periodo"><?= ffIcono() ?><?= ffDelAl($rango['desde'], $rango['hasta']) ?></div>
        </div>
        <div class="kpi-card neg">
            <div class="kpi-label">Negativas</div>
            <div class="kpi-value"><?= $sent['negativo'] ?></div>
            <div class="kpi-sub"><?= $pct($sent['negativo']) ?>% del total</div>
            <div class="kpi-periodo"><?= ffIcono() ?><?= ffDelAl($rango['desde'], $rango['hasta']) ?></div>
        </div>
        <div class="kpi-card warn">
            <div class="kpi-label">Toxicidad prom.</div>
            <div class="kpi-value"><?= number_format((float)$metricas['promedio_toxicidad'], 3, ',', '.') ?></div>
            <div class="kpi-sub">Escala 0–1 (0 = limpio)</div>
            <div class="kpi-periodo"><?= ffIcono() ?><?= ffDelAl($rango['desde'], $rango['hasta']) ?></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Tokens consumidos</div>
            <div class="kpi-value"><?= number_format((int)$metricas['tokens_total'], 0, ',', '.') ?></div>
            <div class="kpi-sub">Consumidos en el período<br>Límite: 14.400 por día</div>
            <div class="kpi-periodo"><?= ffIcono() ?><?= ffDelAl($rango['desde'], $rango['hasta']) ?></div>
        </div>
    </div>

    <?php
    $gSent    = $graf['res_sentimiento'];
    $sentG    = $sent_graf['sentimiento'] ?? ['positivo'=>0,'neutro'=>0,'negativo'=>0];
    $totalG   = (int)($sent_graf['total'] ?? 0);
    $sentC    = isset($sent_cmp) ? $sent_cmp['sentimiento'] : null;
    $totalC   = (int)($sent_cmp['total'] ?? 0);
    $pctDe    = fn($n, $total) => round($n / max(1, $total) * 100, 1);
    ?>
    <div class="chart-card" id="g-res_sentimiento">
        <div class="chart-header">
            <div class="chart-title">Distribución de sentimiento</div>
            <div class="chart-acciones">
                <span class="chart-badge"><?= $totalG ?> reseñas<?= $sentC !== null ? ' vs ' . $totalC : '' ?></span>
                <?php ffFiltroGrafico($gSent); ?>
            </div>
        </div>
        <?= ffChip($gSent, true, 'barra') ?>
        <?php foreach (['positivo'=>'var(--verde)','neutro'=>'var(--g1)','negativo'=>'var(--rojo)'] as $k=>$color):
            $n = $sentG[$k]; $p = $pctDe($n, $totalG); ?>
            <div class="bar-row">
                <div class="bar-row-head">
                    <span class="name"><?= $k ?></span>
                    <span class="val">
                        <?= $n ?> · <?= $p ?>%
                        <?php if ($sentC !== null): ?>
                        <span class="val-cmp">vs <?= $sentC[$k] ?> · <?= $pctDe($sentC[$k], $totalC) ?>%</span>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="bar-bg"><div class="bar-fill" style="width:<?= $p ?>%;background:<?= $color ?>;"></div></div>
                <?php if ($sentC !== null): ?>
                <div class="bar-bg cmp"><div class="bar-fill cmp" style="width:<?= $pctDe($sentC[$k], $totalC) ?>%"></div></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <?php if ($sentC !== null): ?>
        <div class="leyenda-cmp">
            <span><?= ffPeriodo($gSent['desde'], $gSent['hasta']) ?>: barra del color de cada sentimiento</span>
            <span><i class="sw cmp"></i><?= ffPeriodo($gSent['cmp']['desde'], $gSent['cmp']['hasta']) ?></span>
        </div>
        <?php endif; ?>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title">Categorías más mencionadas</div>
            <span class="chart-badge">Detección IA</span>
        </div>
        <?= ffChip($rango) ?>
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
                <div class="bar-bg"><div class="bar-fill" style="width:<?= $p ?>%;background:var(--amb);"></div></div>
            </div>
        <?php endforeach; endif; ?>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title">Cola de revisión</div>
            <span class="chart-badge" title="La cola muestra todas las reseñas pendientes, sin importar el período">
                <?= count($cola) ?> pendientes · todas las fechas
            </span>
        </div>
        <?= ffChipFijo('Todas las pendientes', 'La cola no se filtra por fecha') ?>

        <?php if (empty($cola)): ?>
            <div class="empty-state">No hay reseñas pendientes de moderación.</div>
        <?php else: ?>
        <div class="tabla-scroll">
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
                    <td class="td-name"><?= htmlspecialchars($r['NombredelProducto'] ?? '—') ?></td>
                    <td class="muted"><?= htmlspecialchars($r['AutorNombre'] ?? '—') ?></td>
                    <td class="num"><?= (int)$r['Puntuacion'] ?></td>
                    <td class="col-resumen">
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
                        $tclase = $tox >= 0.5 ? 'danger' : ($tox >= 0.25 ? 'warn-text' : 'success');
                        ?>
                        <span class="<?= $tclase ?>">
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
                    <td class="nowrap">
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
