<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<main class="content">

<style>
:root{--caoba:#5c2d0a;--caoba2:#7a3e14;--amb:#b8722a;--lino:#f7f0e6;--lino2:#ede4d4;--papel:#fdfaf6;--tinta:#2c1a0e;--tinta2:#4a3020;--g1:#8a7560;--borde:#d4c4aa;--borde2:#e8dcc8;--sombra:rgba(92,45,10,.08);--rojo:#c0392b;--verde:#2e7d32;--amar:#c89200;}
*{box-sizing:border-box;}
.hx-page{font-family:'Source Sans 3',Georgia,sans-serif;color:var(--tinta);}
.hx-hd{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1.2rem;}
.hx-hd h1{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--caoba);margin:0;}
.btn-hx{display:inline-flex;align-items:center;gap:.4rem;padding:.48rem 1rem;border-radius:7px;font-size:.86rem;font-weight:600;font-family:'Source Sans 3',sans-serif;text-decoration:none;border:none;cursor:pointer;transition:background .15s,transform .12s;}
.btn-hx:hover{transform:translateY(-1px);}
.btn-hx-primary{background:var(--caoba);color:#fff;}
.btn-hx-primary:hover{background:var(--caoba2);color:#fff;}
.btn-hx-secondary{background:var(--lino2);color:var(--caoba);border:1.5px solid var(--borde);}
.btn-hx-secondary:hover{background:var(--borde);}
.btn-hx-sm{padding:.28rem .7rem;font-size:.78rem;}

.hx-empty{background:var(--papel);border:1.5px dashed var(--borde);border-radius:10px;padding:3rem 1rem;text-align:center;color:var(--g1);}
.hx-empty span{font-size:3rem;display:block;margin-bottom:.5rem;opacity:.3;}
.hx-empty p{margin:0;font-size:.95rem;}

.hx-table-wrap{background:var(--papel);border:1.5px solid var(--borde);border-radius:10px;overflow:hidden;box-shadow:0 2px 12px var(--sombra);}
.hx-table{width:100%;border-collapse:collapse;}
.hx-table thead tr{background:linear-gradient(to right,var(--caoba),var(--caoba2));}
.hx-table thead th{padding:.68rem 1rem;text-align:left;font-size:.74rem;font-weight:700;letter-spacing:.06em;color:rgba(255,255,255,.9);text-transform:uppercase;border-right:1px solid rgba(255,255,255,.12);}
.hx-table thead th:last-child{border-right:none;}
.hx-table tbody tr{border-bottom:1px solid var(--borde2);transition:background .12s;}
.hx-table tbody tr:hover{background:var(--lino);}
.hx-table tbody td{padding:.65rem 1rem;font-size:.86rem;color:var(--tinta2);border-right:1px solid var(--borde2);vertical-align:middle;}
.hx-table tbody td:last-child{border-right:none;}

.hx-id{font-family:'Playfair Display',serif;font-weight:700;color:var(--caoba);font-size:.95rem;}
.hx-fecha{font-size:.82rem;color:var(--g1);}
.hx-monto{font-family:'Playfair Display',serif;color:var(--verde);font-weight:700;font-size:.95rem;}
.hx-resumen{color:var(--tinta2);font-size:.83rem;line-height:1.5;max-width:380px;display:block;}

.hx-badge{display:inline-block;padding:2px 9px;border-radius:5px;font-size:.78rem;font-weight:700;font-family:'Playfair Display',serif;min-width:30px;text-align:center;}
.hx-badge-warning{background:#fdf6e3;color:#7a5800;border:1px solid #e8d699;}
.hx-badge-danger{background:#fdf0f0;color:var(--rojo);border:1px solid #f5c6c6;}
.hx-badge-neutral{background:var(--lino2);color:var(--g1);border:1px solid var(--borde);}

.hx-var{font-family:'Playfair Display',serif;font-weight:700;font-size:.92rem;}
.hx-var.up{color:var(--rojo);}
.hx-var.down{color:var(--verde);}
.hx-var.zero{color:var(--g1);}
</style>

<div class="hx-page">

    <?= Toast::flash() ?>

    <div class="hx-hd">
        <h1> Historial de diagnósticos</h1>
        <a href="<?= URL ?>stock" class="btn-hx btn-hx-primary">← Volver a Stock</a>
    </div>

    <?php if (empty($historial)): ?>
        <div class="hx-empty">
            <span></span>
            <p>Todavía no hay diagnósticos generados.<br>Desde la vista de stock podés generar el primero.</p>
        </div>
    <?php else: ?>
        <div class="hx-table-wrap">
            <table class="hx-table">
                <thead>
                    <tr>
                        
                        <th>Fecha</th>
                        <th>Generado por</th>
                        <th>Valor stock</th>
                        <th>Bajo</th>
                        <th>Sin</th>
                        <th>Var. promedio</th>
                        <th>Resumen</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($historial as $h):
                    $var = $h['VariacionPromedioPct'];
                    $varClass = $var === null ? 'zero' : ($var > 0 ? 'up' : ($var < 0 ? 'down' : 'zero'));
                    $varTxt = $var === null ? '—' : ($var > 0 ? '+' : '') . $var . '%';
                ?>
                    <tr>
                        
                        <td class="hx-fecha"><?= date('d/m/Y H:i', strtotime($h['FechaGenerado'])) ?></td>
                        <td><?= htmlspecialchars($h['NombreUsuario'] ?? 'Sistema') ?></td>
                        <td><span class="hx-monto">$<?= number_format($h['ValorTotalStock'], 0, ',', '.') ?></span></td>
                        <td>
                            <?php if ($h['ItemsBajoStock'] > 0): ?>
                                <span class="hx-badge hx-badge-warning"><?= $h['ItemsBajoStock'] ?></span>
                            <?php else: ?>
                                <span class="hx-badge hx-badge-neutral">0</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($h['ItemsSinStock'] > 0): ?>
                                <span class="hx-badge hx-badge-danger"><?= $h['ItemsSinStock'] ?></span>
                            <?php else: ?>
                                <span class="hx-badge hx-badge-neutral">0</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="hx-var <?= $varClass ?>"><?= $varTxt ?></span></td>
                        <td>
                            <span class="hx-resumen">
                                <?= htmlspecialchars(mb_substr($h['ResumenTexto'] ?? '', 0, 120)) ?><?= mb_strlen($h['ResumenTexto'] ?? '') > 120 ? '…' : '' ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= URL ?>stock/verDiagnostico/<?= $h['Id'] ?>" class="btn-hx btn-hx-primary btn-hx-sm">
                                 Ver
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
<?php include INCLUDES . 'admin' . DS . 'modals_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'scripts_admin.php'; ?>