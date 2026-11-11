<?php

?>

<style>
.lobby-titulo {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--caoba);
    margin-bottom: 1.5rem;
}

.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.metric-card {
    background: var(--papel);
    border: 1.5px solid var(--borde);
    border-radius: var(--radius-lg);
    padding: 1.2rem 1.4rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: var(--shadow-sm);
    transition: box-shadow var(--trans-slow), transform var(--trans-slow);
}
.metric-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.metric-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.metric-icon.clientes  { background: #e3f2fd; color: var(--azul); }
.metric-icon.pedidos   { background: #fff8e1; color: var(--amarillo); }
.metric-icon.productos { background: #f3ebe0; color: var(--caoba); }
.metric-icon.ventas    { background: #e8f5e9; color: var(--verde2); }

.metric-label {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--g1);
    margin-bottom: .2rem;
    font-family: var(--font-body);
}
.metric-value {
    font-family: var(--font-display);
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--caoba);
    line-height: 1;
}
.metric-sub {
    font-size: .74rem;
    color: var(--g1);
    margin-top: .2rem;
    font-family: var(--font-body);
}

.activity-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.2rem;
}
@media (max-width: 860px) {
    .activity-grid { grid-template-columns: 1fr; }
}

.activity-panel {
    background: var(--papel);
    border: 1.5px solid var(--borde);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.activity-hd {
    background: linear-gradient(to right, var(--caoba), var(--caoba2));
    padding: .8rem 1.2rem;
}
.activity-hd h3 {
    font-family: var(--font-display);
    font-size: .95rem;
    font-weight: 700;
    color: #fff;
    margin: 0;
}

.activity-list {
    list-style: none;
    margin: 0;
    padding: 0;
}
.activity-list li {
    padding: .75rem 1.2rem;
    border-bottom: 1px solid var(--borde2);
    font-size: .84rem;
    color: var(--tinta2);
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: .75rem;
    transition: background var(--trans-fast);
}
.activity-list li:last-child { border-bottom: none; }
.activity-list li:hover { background: var(--lino); }

.act-main {
    font-weight: 600;
    color: var(--tinta);
    font-family: var(--font-body);
}
.act-sub {
    font-size: .76rem;
    color: var(--g1);
    margin-top: .1rem;
    font-family: var(--font-body);
}
.act-right { text-align: right; flex-shrink: 0; }
.act-monto {
    font-family: var(--font-display);
    font-size: .9rem;
    font-weight: 700;
    color: var(--verde2);
}
.act-fecha {
    font-size: .72rem;
    color: var(--g1);
    margin-top: .1rem;
    font-family: var(--font-body);
}

.act-vacio {
    padding: 2rem 1.2rem;
    text-align: center;
    color: var(--g1);
    font-size: .84rem;
    font-family: var(--font-body);
}

.pill {
    display: inline-flex;
    align-items: center;
    padding: 2px 9px;
    border-radius: var(--radius-pill);
    font-size: .7rem;
    font-weight: 700;
    font-family: var(--font-body);
    margin-top: .3rem;
}
.pill-pendiente  { background: #fff8e1; color: var(--amarillo); border: 1px solid #ffe082; }
.pill-produccion { background: #e3f2fd; color: var(--azul);     border: 1px solid #90caf9; }
.pill-listo      { background: #f3e5f5; color: var(--purpura);  border: 1px solid #ce93d8; }
.pill-entregado  { background: #e8f5e9; color: var(--verde2);   border: 1px solid #a5d6a7; }
.pill-cancelado  { background: #fdf0f0; color: var(--rojo2);    border: 1px solid #f5c6c6; }
.pill-aprobado   { background: #e8f5e9; color: var(--verde2);   border: 1px solid #a5d6a7; }
</style>

<h1 class="lobby-titulo">Panel Administrativo</h1>

<div class="dashboard-cards">

    <div class="metric-card">
        <div class="metric-icon clientes">
            <i class="fas fa-users"></i>
        </div>
        <div class="metric-info">
            <div class="metric-label">Clientes</div>
            <div class="metric-value"><?= number_format($totalClientes ?? 0) ?></div>
            <div class="metric-sub">Total registrados</div>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon pedidos">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div class="metric-info">
            <div class="metric-label">Pedidos</div>
            <div class="metric-value"><?= number_format($pedidosPendientes ?? 0) ?></div>
            <div class="metric-sub">Pendientes</div>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon productos">
            <i class="fas fa-couch"></i>
        </div>
        <div class="metric-info">
            <div class="metric-label">Productos</div>
            <div class="metric-value"><?= number_format($totalProductos ?? 0) ?></div>
            <div class="metric-sub">En catalogo</div>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon ventas">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="metric-info">
            <div class="metric-label">Ventas del mes</div>
            <div class="metric-value">$<?= number_format($ventasMes ?? 0, 0, ',', '.') ?></div>
            <div class="metric-sub">Monto total (ARS)</div>
        </div>
    </div>

</div>

<div class="activity-grid">

    <div class="activity-panel">
        <div class="activity-hd">
            <h3>Ultimas ventas</h3>
        </div>
        <?php if (empty($ultimasVentas)): ?>
            <div class="act-vacio">No hay ventas registradas aun.</div>
        <?php else: ?>
        <ul class="activity-list">
            <?php foreach ($ultimasVentas as $v):
                $epNom = mb_strtolower($v['EstadoPago'] ?? '');
                if (str_contains($epNom, 'aprob'))      $pillV = 'aprobado';
                elseif (str_contains($epNom, 'pend'))   $pillV = 'pendiente';
                elseif (str_contains($epNom, 'rechaz')) $pillV = 'cancelado';
                else                                     $pillV = 'pendiente';
                $fecha = !empty($v['FechadeEmision'])
                    ? date('d/m/Y', strtotime($v['FechadeEmision']))
                    : '—';
            ?>
            <li>
                <div>
                    <div class="act-main">
                        <?= htmlspecialchars($v['ClienteNombre'] . ' ' . $v['ClienteApellido']) ?>
                    </div>
                    <div class="act-sub">
                        Venta <?= $v['NumerodeVenta'] ?> &mdash;
                        <?= htmlspecialchars(mb_strimwidth($v['Productos'] ?? '—', 0, 40, '…')) ?>
                    </div>
                    <span class="pill pill-<?= $pillV ?>">
                        <?= htmlspecialchars($v['EstadoPago']) ?>
                    </span>
                </div>
                <div class="act-right">
                    <div class="act-monto">$<?= number_format($v['MontoTotal'], 0, ',', '.') ?></div>
                    <div class="act-fecha"><?= $fecha ?></div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>

    <div class="activity-panel">
        <div class="activity-hd">
            <h3>Ultimos pedidos</h3>
        </div>
        <?php if (empty($ultimosPedidos)): ?>
            <div class="act-vacio">No hay pedidos registrados aun.</div>
        <?php else: ?>
        <ul class="activity-list">
            <?php foreach ($ultimosPedidos as $p):
                $peNom = mb_strtolower($p['Estado'] ?? '');
                if (str_contains($peNom, 'producci'))   $pillP = 'produccion';
                elseif (str_contains($peNom, 'listo'))      $pillP = 'listo';
                elseif (str_contains($peNom, 'entregado'))  $pillP = 'entregado';
                elseif (str_contains($peNom, 'cancelado'))  $pillP = 'cancelado';
                else                                         $pillP = 'pendiente';
            ?>
            <li>
                <div>
                    <div class="act-main">
                        <?= htmlspecialchars($p['ClienteNombre'] . ' ' . $p['ClienteApellido']) ?>
                    </div>
                    <div class="act-sub">
                        Venta <?= $p['NumerodeVenta'] ?> &mdash;
                        <?= htmlspecialchars(mb_strimwidth($p['Productos'] ?? '—', 0, 40, '…')) ?>
                    </div>
                    <?php if (!empty($p['Responsable'])): ?>
                    <div class="act-sub">Responsable: <?= htmlspecialchars($p['Responsable']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="act-right">
                    <span class="pill pill-<?= $pillP ?>">
                        <?= htmlspecialchars($p['Estado']) ?>
                    </span>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>

</div>