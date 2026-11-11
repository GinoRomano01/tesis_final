<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<main class="content">

<style>
:root{--caoba:#5c2d0a;--caoba2:#7a3e14;--amb:#b8722a;--lino:#f7f0e6;--lino2:#ede4d4;--papel:#fdfaf6;--tinta:#2c1a0e;--tinta2:#4a3020;--g1:#8a7560;--borde:#d4c4aa;--borde2:#e8dcc8;--sombra:rgba(92,45,10,.08);--rojo:#c0392b;--verde:#2e7d32;--amar:#c89200;--info:#2c5f7c;}
*{box-sizing:border-box;}
.dx-page{font-family:'Source Sans 3',Georgia,sans-serif;color:var(--tinta);}
.dx-hd{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1.4rem;padding-bottom:1rem;border-bottom:2px solid var(--borde);}
.dx-hd h1{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--caoba);margin:0 0 .2rem;}
.dx-hd small{color:var(--g1);font-size:.82rem;}
.dx-hd-actions{display:flex;gap:.5rem;flex-wrap:wrap;}
.btn-dx{display:inline-flex;align-items:center;gap:.4rem;padding:.48rem 1rem;border-radius:7px;font-size:.86rem;font-weight:600;font-family:'Source Sans 3',sans-serif;text-decoration:none;border:none;cursor:pointer;transition:background .15s,transform .12s;}
.btn-dx:hover{transform:translateY(-1px);}
.btn-dx-primary{background:var(--caoba);color:#fff;}
.btn-dx-primary:hover{background:var(--caoba2);color:#fff;}
.btn-dx-secondary{background:var(--lino2);color:var(--caoba);border:1.5px solid var(--borde);}
.btn-dx-secondary:hover{background:var(--borde);}
.dx-grid{display:grid;gap:1rem;margin-bottom:1.2rem;}
.dx-grid-2-1{grid-template-columns:2fr 1fr;}
.dx-grid-4{grid-template-columns:repeat(4,1fr);}
.dx-grid-2{grid-template-columns:1fr 1fr;}
@media(max-width:900px){.dx-grid-2-1,.dx-grid-4,.dx-grid-2{grid-template-columns:1fr;}}
.dx-card{background:var(--papel);border:1.5px solid var(--borde);border-radius:10px;box-shadow:0 2px 12px var(--sombra);overflow:hidden;}
.dx-card-hd{padding:.75rem 1.1rem;background:linear-gradient(to right,var(--caoba),var(--caoba2));color:#fff;font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;display:flex;align-items:center;gap:.5rem;}
.dx-card-bd{padding:1.1rem;}
.dx-card-border-danger{border-color:var(--rojo);border-width:2px;}
.dx-card-border-warning{border-color:var(--amar);border-width:2px;}
.dx-card-border-info{border-color:var(--info);border-width:2px;}
.dx-card-border-success{border-color:var(--verde);border-width:2px;}
.dx-card-hd.danger{background:linear-gradient(to right,#a52821,var(--rojo));}
.dx-card-hd.warning{background:linear-gradient(to right,#a07400,var(--amar));}
.dx-card-hd.info{background:linear-gradient(to right,#1d4459,var(--info));}
.dx-card-hd.success{background:linear-gradient(to right,#1f5a23,var(--verde));}
.dx-stat{text-align:center;padding:1rem .5rem;}
.dx-stat-label{font-size:.74rem;font-weight:700;color:var(--g1);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.4rem;}
.dx-stat-value{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--caoba);line-height:1;}
.dx-stat-sub{font-size:.78rem;color:var(--g1);margin-top:.35rem;line-height:1.45;}
.dx-stat-value.warning{color:var(--amar);}
.dx-stat-value.danger{color:var(--rojo);}
.dx-stat-value.success{color:var(--verde);}
.dx-score{text-align:center;padding:1.2rem .8rem;}
.dx-score-num{font-family:'Playfair Display',serif;font-size:4.5rem;font-weight:700;line-height:1;}
.dx-score-num.success{color:var(--verde);}
.dx-score-num.warning{color:var(--amar);}
.dx-score-num.danger{color:var(--rojo);}
.dx-score-of{font-size:.85rem;color:var(--g1);margin-top:.2rem;}
.dx-score-bar{height:8px;background:var(--lino2);border-radius:4px;margin-top:1rem;overflow:hidden;}
.dx-score-bar-fill{height:100%;border-radius:4px;transition:width .6s ease;}
.dx-score-bar-fill.success{background:var(--verde);}
.dx-score-bar-fill.warning{background:var(--amar);}
.dx-score-bar-fill.danger{background:var(--rojo);}
.dx-resumen{font-size:.96rem;line-height:1.65;color:var(--tinta2);margin:0 0 1rem;}
.dx-prioridad{background:var(--lino);border-left:4px solid var(--amb);border-radius:6px;padding:.85rem 1rem;font-size:.92rem;line-height:1.5;}
.dx-prioridad strong{display:block;color:var(--caoba);font-family:'Playfair Display',serif;margin-bottom:.3rem;font-size:.9rem;}
.dx-alerta-list{list-style:none;margin:0;padding:0;}
.dx-alerta-list li{padding:.7rem 1.1rem;border-bottom:1px solid var(--borde2);font-size:.92rem;color:var(--tinta2);display:flex;gap:.6rem;align-items:flex-start;}
.dx-alerta-list li:last-child{border-bottom:none;}
.dx-alerta-list li::before{content:"⚠";color:var(--rojo);font-weight:700;flex-shrink:0;}
.dx-reco-list{list-style:none;counter-reset:reco;margin:0;padding:0;}
.dx-reco-list li{padding:.7rem 1.1rem;border-bottom:1px solid var(--borde2);font-size:.92rem;color:var(--tinta2);display:flex;gap:.7rem;align-items:flex-start;counter-increment:reco;}
.dx-reco-list li:last-child{border-bottom:none;}
.dx-reco-list li::before{content:counter(reco);background:var(--caoba);color:#fff;width:24px;height:24px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.78rem;flex-shrink:0;font-family:'Playfair Display',serif;}
.dx-text{font-size:.92rem;line-height:1.6;color:var(--tinta2);margin:0 0 1rem;}
.dx-mini-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:.8rem;margin-bottom:1rem;}
.dx-mini-stat{text-align:center;padding:.75rem .5rem;background:var(--lino);border-radius:7px;}
.dx-mini-stat-label{font-size:.72rem;font-weight:700;color:var(--g1);text-transform:uppercase;letter-spacing:.04em;}
.dx-mini-stat-value{font-family:'Playfair Display',serif;font-size:1.35rem;font-weight:700;color:var(--caoba);margin-top:.2rem;}
.dx-table{width:100%;border-collapse:collapse;font-size:.85rem;}
.dx-table thead tr{background:var(--lino2);}
.dx-table thead th{padding:.55rem .85rem;text-align:left;font-size:.72rem;font-weight:700;letter-spacing:.05em;color:var(--caoba);text-transform:uppercase;border-bottom:2px solid var(--borde);}
.dx-table tbody tr{border-bottom:1px solid var(--borde2);transition:background .12s;}
.dx-table tbody tr:hover{background:var(--lino);}
.dx-table tbody tr:last-child{border-bottom:none;}
.dx-table tbody td{padding:.55rem .85rem;color:var(--tinta2);vertical-align:middle;}
.dx-table .num{font-family:'Playfair Display',serif;font-size:.92rem;}
.dx-table .monto{font-family:'Playfair Display',serif;color:var(--verde);font-weight:700;}
.dx-table .main{font-weight:700;color:var(--caoba);}
.dx-table small{font-size:.76rem;color:var(--g1);}
.dx-badge{display:inline-block;padding:2px 8px;border-radius:5px;font-size:.74rem;font-weight:700;font-family:'Source Sans 3',sans-serif;}
.dx-badge-danger{background:#fdf0f0;color:var(--rojo);border:1px solid #f5c6c6;}
.dx-badge-warning{background:#fdf6e3;color:#7a5800;border:1px solid #e8d699;}
.dx-badge-info{background:#e8f0f8;color:var(--info);border:1px solid #c8d8e8;}
.dx-capital{text-align:center;padding:1rem;background:var(--lino);border-radius:8px;margin-bottom:1rem;}
.dx-capital-label{font-size:.78rem;font-weight:700;color:var(--g1);text-transform:uppercase;letter-spacing:.04em;}
.dx-capital-num{font-family:'Playfair Display',serif;font-size:1.9rem;font-weight:700;color:var(--rojo);margin-top:.2rem;}
.dx-empty{padding:2rem 1rem;text-align:center;color:var(--g1);font-size:.9rem;font-style:italic;}
</style>

<div class="dx-page">

    <?= Toast::flash() ?>

    <?php
    $data = $diagnostico['DiagnosticoData'];
    $metricas = $data['metricas'];
    $analisis = $data['analisis'];
    $resumen = $metricas['resumen_general'];
    $puntaje = (int)($analisis['puntaje_salud_stock'] ?? 0);
    $colorPuntaje = $puntaje >= 75 ? 'success' : ($puntaje >= 50 ? 'warning' : 'danger');
    ?>

    <div class="dx-hd">
        <div>
            <h1> Diagnóstico de Stock  <?= $diagnostico['Id'] ?></h1>
            <small>
                Generado el <?= date('d/m/Y H:i', strtotime($diagnostico['FechaGenerado'])) ?>
                por <?= htmlspecialchars($diagnostico['NombreUsuario'] ?? 'Sistema') ?>
            </small>
        </div>
        <div class="dx-hd-actions">
            <a href="<?= URL ?>stock/historialDiagnosticos" class="btn-dx btn-dx-secondary"> Historial</a>
            <a href="<?= URL ?>stock" class="btn-dx btn-dx-primary"> Volver a Stock</a>
        </div>
    </div>

    <div class="dx-grid dx-grid-2-1">
        <div class="dx-card">
            <div class="dx-card-hd"> Resumen ejecutivo</div>
            <div class="dx-card-bd">
                <p class="dx-resumen"><?= htmlspecialchars($analisis['resumen_ejecutivo'] ?? '—') ?></p>
                <div class="dx-prioridad">
                    <strong>Acción prioritaria esta semana</strong>
                    <?= htmlspecialchars($analisis['prioridad_inmediata'] ?? '—') ?>
                </div>
            </div>
        </div>
        <div class="dx-card dx-card-border-<?= $colorPuntaje ?>">
            <div class="dx-card-hd <?= $colorPuntaje ?>">Salud del stock</div>
            <div class="dx-card-bd">
                <div class="dx-score">
                    <div class="dx-score-num <?= $colorPuntaje ?>"><?= $puntaje ?></div>
                    <div class="dx-score-of">de 100 puntos</div>
                    <div class="dx-score-bar">
                        <div class="dx-score-bar-fill <?= $colorPuntaje ?>" style="width:<?= $puntaje ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dx-grid dx-grid-4">
        <div class="dx-card">
            <div class="dx-card-bd">
                <div class="dx-stat">
                    <div class="dx-stat-label">Valor total stock</div>
                    <div class="dx-stat-value success">$<?= number_format($resumen['valor_total'], 0, ',', '.') ?></div>
                    <div class="dx-stat-sub">
                        Madera: $<?= number_format($resumen['valor_maderas'], 0, ',', '.') ?><br>
                        Insumos: $<?= number_format($resumen['valor_insumos'], 0, ',', '.') ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="dx-card">
            <div class="dx-card-bd">
                <div class="dx-stat">
                    <div class="dx-stat-label">Items totales</div>
                    <div class="dx-stat-value"><?= $resumen['total_maderas'] + $resumen['total_insumos'] ?></div>
                    <div class="dx-stat-sub">
                        <?= $resumen['total_maderas'] ?> maderas<br>
                        <?= $resumen['total_insumos'] ?> insumos
                    </div>
                </div>
            </div>
        </div>
        <div class="dx-card dx-card-border-warning">
            <div class="dx-card-bd">
                <div class="dx-stat">
                    <div class="dx-stat-label">Bajo stock</div>
                    <div class="dx-stat-value warning"><?= $resumen['items_bajo'] ?></div>
                    <div class="dx-stat-sub">≤ <?= $resumen['umbral_bajo'] ?> unidades</div>
                </div>
            </div>
        </div>
        <div class="dx-card dx-card-border-danger">
            <div class="dx-card-bd">
                <div class="dx-stat">
                    <div class="dx-stat-label">Sin stock</div>
                    <div class="dx-stat-value danger"><?= $resumen['items_sin'] ?></div>
                    <div class="dx-stat-sub">requieren reposición</div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($analisis['alertas_criticas'])): ?>
    <div class="dx-card dx-card-border-danger" style="margin-bottom:1.2rem;">
        <div class="dx-card-hd danger"> Alertas críticas</div>
        <ul class="dx-alerta-list">
            <?php foreach ($analisis['alertas_criticas'] as $alerta): ?>
                <li><?= htmlspecialchars($alerta) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="dx-card" style="margin-bottom:1.2rem;">
        <div class="dx-card-hd"> Inflación de materiales</div>
        <div class="dx-card-bd">
            <p class="dx-text"><?= htmlspecialchars($analisis['analisis_inflacion'] ?? '—') ?></p>
            <div class="dx-mini-stats">
                <div class="dx-mini-stat">
                    <div class="dx-mini-stat-label">Últimos 30 días</div>
                    <div class="dx-mini-stat-value"><?= $metricas['inflacion_precios']['promedio_30d'] ?>%</div>
                </div>
                <div class="dx-mini-stat">
                    <div class="dx-mini-stat-label">Últimos 60 días</div>
                    <div class="dx-mini-stat-value"><?= $metricas['inflacion_precios']['promedio_60d'] ?>%</div>
                </div>
                <div class="dx-mini-stat">
                    <div class="dx-mini-stat-label">Últimos 90 días</div>
                    <div class="dx-mini-stat-value"><?= $metricas['inflacion_precios']['promedio_90d'] ?>%</div>
                </div>
            </div>
            <?php if (!empty($metricas['inflacion_precios']['top_subas'])): ?>
            <h6 style="font-family:'Playfair Display',serif;color:var(--caoba);margin:1rem 0 .5rem;">Top 5 mayores subas</h6>
            <table class="dx-table">
                <thead>
                    <tr><th>Material</th><th>Anterior</th><th>Nuevo</th><th>Variación</th><th>Fecha</th></tr>
                </thead>
                <tbody>
                <?php foreach ($metricas['inflacion_precios']['top_subas'] as $s): ?>
                    <tr>
                        <td class="main"><?= htmlspecialchars($s['nombre']) ?></td>
                        <td class="num">$<?= number_format($s['precio_anterior'], 2, ',', '.') ?></td>
                        <td class="num">$<?= number_format($s['precio_nuevo'], 2, ',', '.') ?></td>
                        <td><span class="dx-badge dx-badge-danger">+<?= $s['variacion_pct'] ?>%</span></td>
                        <td><small><?= date('d/m/Y', strtotime($s['fecha'])) ?></small></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <div class="dx-empty">Sin cambios de precio registrados en el período.</div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($analisis['recomendaciones_reposicion'])): ?>
    <div class="dx-card" style="margin-bottom:1.2rem;">
        <div class="dx-card-hd success"> Recomendaciones de reposición</div>
        <ol class="dx-reco-list">
            <?php foreach ($analisis['recomendaciones_reposicion'] as $rec): ?>
                <li><?= htmlspecialchars($rec) ?></li>
            <?php endforeach; ?>
        </ol>
    </div>
    <?php endif; ?>

    <div class="dx-grid dx-grid-2">
        <div class="dx-card">
            <div class="dx-card-hd"> Maderas más usadas</div>
            <?php if (!empty($metricas['top_consumidos']['top_maderas'])): ?>
            <table class="dx-table">
                <thead><tr><th>Madera</th><th>Consumida</th><th>Costo total</th></tr></thead>
                <tbody>
                <?php foreach ($metricas['top_consumidos']['top_maderas'] as $m): ?>
                    <tr>
                        <td class="main">
                            <?= htmlspecialchars($m['TipoMadera']) ?><br>
                            <small><?= $m['Dimensiones'] ?> cm</small>
                        </td>
                        <td class="num"><?= number_format($m['CantidadConsumida'], 2, ',', '.') ?></td>
                        <td class="monto">$<?= number_format($m['CostoTotalConsumido'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <div class="dx-empty">Sin ventas concretadas con maderas registradas.</div>
            <?php endif; ?>
        </div>
        <div class="dx-card">
            <div class="dx-card-hd"> Insumos más usados</div>
            <?php if (!empty($metricas['top_consumidos']['top_insumos'])): ?>
            <table class="dx-table">
                <thead><tr><th>Insumo</th><th>Consumido</th><th>Costo total</th></tr></thead>
                <tbody>
                <?php foreach ($metricas['top_consumidos']['top_insumos'] as $i): ?>
                    <tr>
                        <td class="main"><?= htmlspecialchars($i['Nombre']) ?></td>
                        <td class="num"><?= number_format($i['CantidadConsumida'], 2, ',', '.') ?></td>
                        <td class="monto">$<?= number_format($i['CostoTotalConsumido'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <div class="dx-empty">Sin ventas concretadas con insumos registradas.</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="dx-card dx-card-border-warning" style="margin:1.2rem 0;">
        <div class="dx-card-hd warning">
                Capital inmovilizado en materiales muertos
            <small style="font-weight:400;opacity:.85;margin-left:.4rem;">
                (sin uso en <?= $metricas['materiales_muertos']['dias_umbral'] ?> días)
            </small>
        </div>
        <div class="dx-card-bd">
            <div class="dx-capital">
                <div class="dx-capital-label">Capital total inmovilizado</div>
                <div class="dx-capital-num">$<?= number_format($metricas['materiales_muertos']['capital_inmovilizado_total'], 2, ',', '.') ?></div>
            </div>
            <p class="dx-text"><?= htmlspecialchars($analisis['materiales_muertos_recomendacion'] ?? '—') ?></p>
            <?php if (!empty($metricas['materiales_muertos']['maderas_muertas'])): ?>
            <h6 style="font-family:'Playfair Display',serif;color:var(--caoba);margin:1rem 0 .5rem;">Top maderas sin rotación</h6>
            <table class="dx-table">
                <thead><tr><th>Material</th><th>Stock</th><th>Precio U.</th><th>Capital inmovilizado</th></tr></thead>
                <tbody>
                <?php foreach (array_slice($metricas['materiales_muertos']['maderas_muertas'], 0, 5) as $m): ?>
                    <tr>
                        <td class="main"><?= htmlspecialchars($m['Nombre']) ?></td>
                        <td class="num"><?= number_format($m['StockActual'], 2, ',', '.') ?></td>
                        <td class="num">$<?= number_format($m['PrecioUnitario'], 2, ',', '.') ?></td>
                        <td class="monto" style="color:var(--rojo);">$<?= number_format($m['CapitalInmovilizado'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
            <?php if (!empty($metricas['materiales_muertos']['insumos_muertos'])): ?>
            <h6 style="font-family:'Playfair Display',serif;color:var(--caoba);margin:1.2rem 0 .5rem;">Top insumos sin rotación</h6>
            <table class="dx-table">
                <thead><tr><th>Insumo</th><th>Stock</th><th>Precio U.</th><th>Capital inmovilizado</th></tr></thead>
                <tbody>
                <?php foreach (array_slice($metricas['materiales_muertos']['insumos_muertos'], 0, 5) as $i): ?>
                    <tr>
                        <td class="main"><?= htmlspecialchars($i['Nombre']) ?></td>
                        <td class="num"><?= number_format($i['StockActual'], 2, ',', '.') ?></td>
                        <td class="num">$<?= number_format($i['PrecioUnitario'], 2, ',', '.') ?></td>
                        <td class="monto" style="color:var(--rojo);">$<?= number_format($i['CapitalInmovilizado'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($metricas['impacto_margen']['productos_afectados'])): ?>
    <div class="dx-card dx-card-border-info" style="margin-bottom:1.2rem;">
        <div class="dx-card-hd info"> Productos con margen comprimido</div>
        <div class="dx-card-bd">
            <p class="dx-text"><?= htmlspecialchars($analisis['impacto_pricing'] ?? '—') ?></p>
            <table class="dx-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Costo original</th>
                        <th>Costo actual</th>
                        <th>Subió</th>
                        <th>Margen antes</th>
                        <th>Margen hoy</th>
                        <th>Pérdida margen</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($metricas['impacto_margen']['productos_afectados'] as $p): ?>
                    <tr>
                        <td class="main"><?= htmlspecialchars($p['nombre']) ?></td>
                        <td class="num">$<?= number_format($p['costo_original'], 0, ',', '.') ?></td>
                        <td class="num">$<?= number_format($p['costo_actual'], 0, ',', '.') ?></td>
                        <td><span class="dx-badge dx-badge-danger">+<?= $p['variacion_costo'] ?>%</span></td>
                        <td class="num"><?= $p['margen_original'] ?>%</td>
                        <td class="num"><?= $p['margen_actual'] ?>%</td>
                        <td><span class="dx-badge dx-badge-warning">-<?= $p['perdida_margen'] ?>%</span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

</div>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
<?php include INCLUDES . 'admin' . DS . 'modals_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'scripts_admin.php'; ?>