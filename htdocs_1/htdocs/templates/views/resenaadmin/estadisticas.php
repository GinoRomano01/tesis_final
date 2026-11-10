<?php include INCLUDES . 'header_admin.php'; ?>

<?php
$m = $metricas;
$total = max(1, $m['total']);
$pct = fn($n) => round(($n / $total) * 100, 1);
?>

<div style="max-width:1200px;margin:30px auto;padding:0 16px;">

    <h2 style="margin-bottom:6px;">Estadísticas de reseñas</h2>
    <p style="color:#666;">Análisis automático con IA · Modelo: <code><?= GROQ_MODEL ?></code></p>

    
    <form method="GET" action="<?= URL ?>resenaadmin/estadisticas"
          style="display:flex;gap:10px;align-items:end;margin:18px 0;flex-wrap:wrap;">
        <div>
            <label style="display:block;font-size:.85rem;color:#555;">Desde</label>
            <input type="date" name="desde" value="<?= htmlspecialchars($desde ?? '') ?>"
                   style="padding:7px;border:1px solid #ccc;border-radius:6px;">
        </div>
        <div>
            <label style="display:block;font-size:.85rem;color:#555;">Hasta</label>
            <input type="date" name="hasta" value="<?= htmlspecialchars($hasta ?? '') ?>"
                   style="padding:7px;border:1px solid #ccc;border-radius:6px;">
        </div>
        <button type="submit" style="background:#222;color:#fff;border:0;padding:8px 18px;border-radius:6px;cursor:pointer;">
            Filtrar
        </button>
        <a href="<?= URL ?>resenaadmin/estadisticas" style="padding:8px 14px;color:#555;">Limpiar</a>
    </form>

    
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin-bottom:24px;">
        <?php
        $tiles = [
            ['Total analizadas', $m['total'], '#222'],
            ['Positivas',        $m['sentimiento']['positivo'].' ('.$pct($m['sentimiento']['positivo']).'%)', '#2e7d32'],
            ['Neutras',          $m['sentimiento']['neutro'].' ('.$pct($m['sentimiento']['neutro']).'%)',     '#757575'],
            ['Negativas',        $m['sentimiento']['negativo'].' ('.$pct($m['sentimiento']['negativo']).'%)', '#c62828'],
            ['Toxicidad prom.',  $m['promedio_toxicidad'], '#f57c00'],
            ['Tokens gastados',  number_format($m['tokens_total'], 0, ',', '.'), '#1565c0'],
        ];
        foreach ($tiles as [$l,$v,$c]): ?>
            <div style="background:#fff;border:1px solid #eee;border-radius:10px;padding:14px;">
                <div style="font-size:.78rem;color:#888;text-transform:uppercase;letter-spacing:.5px;"><?= $l ?></div>
                <div style="font-size:1.5rem;font-weight:700;color:<?= $c ?>;margin-top:4px;"><?= $v ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    
    <div style="background:#fff;border:1px solid #eee;border-radius:10px;padding:18px;margin-bottom:24px;">
        <h4 style="margin-top:0;">Distribución de sentimiento</h4>
        <?php foreach (['positivo'=>'#2e7d32','neutro'=>'#9e9e9e','negativo'=>'#c62828'] as $k=>$color):
            $n = $m['sentimiento'][$k]; $p = $pct($n); ?>
            <div style="margin:8px 0;">
                <div style="display:flex;justify-content:space-between;font-size:.9rem;">
                    <span style="text-transform:capitalize;"><?= $k ?></span>
                    <span><?= $n ?> · <?= $p ?>%</span>
                </div>
                <div style="background:#f0f0f0;border-radius:4px;height:10px;overflow:hidden;">
                    <div style="width:<?= $p ?>%;background:<?= $color ?>;height:100%;"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    
    <div style="background:#fff;border:1px solid #eee;border-radius:10px;padding:18px;margin-bottom:24px;">
        <h4 style="margin-top:0;">Categorías más mencionadas</h4>
        <?php if (empty($m['categorias'])): ?>
            <p style="color:#888;">Sin datos.</p>
        <?php else:
            $maxCat = max($m['categorias']);
            foreach ($m['categorias'] as $cat=>$n):
                $p = round(($n/$maxCat)*100); ?>
            <div style="margin:6px 0;">
                <div style="display:flex;justify-content:space-between;font-size:.9rem;">
                    <span style="text-transform:capitalize;"><?= htmlspecialchars($cat) ?></span>
                    <span><?= $n ?></span>
                </div>
                <div style="background:#f0f0f0;border-radius:4px;height:8px;overflow:hidden;">
                    <div style="width:<?= $p ?>%;background:#1565c0;height:100%;"></div>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>

    
    <div style="background:#fff;border:1px solid #eee;border-radius:10px;padding:18px;">
        <h4 style="margin-top:0;">Cola de revisión (<?= count($cola) ?>)</h4>
        <?php if (empty($cola)): ?>
            <p style="color:#888;">No hay reseñas pendientes de moderación. </p>
        <?php else: ?>
            <table style="width:100%;border-collapse:collapse;font-size:.92rem;">
                <thead>
                    <tr style="background:#f7f7f7;text-align:left;">
                        <th style="padding:8px;">Producto</th>
                        <th style="padding:8px;">Cliente</th>
                        <th style="padding:8px;">★</th>
                        <th style="padding:8px;">Resumen IA</th>
                        <th style="padding:8px;">Tox.</th>
                        <th style="padding:8px;">Flags</th>
                        <th style="padding:8px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($cola as $r):
                    $flags = json_decode($r['Flags'] ?? '[]', true) ?: []; ?>
                    <tr style="border-top:1px solid #eee;vertical-align:top;">
                        <td style="padding:8px;"><?= htmlspecialchars($r['NombredelProducto']) ?></td>
                        <td style="padding:8px;"><?= htmlspecialchars($r['AutorNombre']) ?></td>
                        <td style="padding:8px;"><?= (int)$r['Puntuacion'] ?></td>
                        <td style="padding:8px;max-width:280px;">
                            <em style="color:#555;"><?= htmlspecialchars($r['ResumenCorto'] ?? '—') ?></em>
                            <details style="margin-top:4px;">
                                <summary style="cursor:pointer;font-size:.85rem;">Ver reseña</summary>
                                <div style="background:#fafafa;padding:8px;margin-top:4px;border-radius:4px;">
                                    <?= nl2br(htmlspecialchars($r['ContenidoPublicado'] ?? $r['ContenidoOriginal'])) ?>
                                </div>
                            </details>
                        </td>
                        <td style="padding:8px;"><?= number_format((float)$r['ScoreToxicidad'], 2) ?></td>
                        <td style="padding:8px;font-size:.82rem;">
                            <?= $flags ? implode(', ', array_map('htmlspecialchars', $flags)) : '—' ?>
                        </td>
                        <td style="padding:8px;white-space:nowrap;">
                            <a href="<?= URL ?>resenaadmin/moderar/<?= $r['Id'] ?>/aprobada"
                               style="background:#2e7d32;color:#fff;padding:4px 10px;border-radius:4px;text-decoration:none;font-size:.82rem;">Aprobar</a>
                            <a href="<?= URL ?>resenaadmin/moderar/<?= $r['Id'] ?>/rechazada"
                               style="background:#c62828;color:#fff;padding:4px 10px;border-radius:4px;text-decoration:none;font-size:.82rem;">Rechazar</a>
                            <a href="<?= URL ?>resenaadmin/moderar/<?= $r['Id'] ?>/oculta"
                               style="background:#757575;color:#fff;padding:4px 10px;border-radius:4px;text-decoration:none;font-size:.82rem;">Ocultar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</div>

<?php include INCLUDES . 'footer_admin.php'; ?>