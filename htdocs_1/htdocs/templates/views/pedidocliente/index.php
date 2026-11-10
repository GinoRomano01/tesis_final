<?php include INCLUDES . 'header_cliente.php'; ?>

<style>
:root{
  --lino:#f7f0e6;--lino2:#ede4d4;--papel:#fdfaf6;
  --caoba:#5c2d0a;--caoba2:#7a3e14;--amb:#b8722a;
  --tinta:#2c1a0e;--tinta2:#4a3020;--g1:#8a7560;
  --borde:#d4c4aa;--borde2:#e8dcc8;--verde:#2e6b3a;
  --rojo:#c0392b;--azul:#1565c0;--amarillo:#f57f17;
  --sombra:rgba(92,45,10,.08);
}

.pedidos-page {
    background: var(--lino);
    min-height: 100vh;
    padding: 2.5rem 0 4rem;
    font-family: 'Source Sans 3', Georgia, sans-serif;
}
.pedidos-inner {
    max-width: 960px;
    margin: 0 auto;
    padding: 0 1.5rem;
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: 1.5rem;
    align-items: start;
}


.perfil-sidebar {
    background: var(--papel);
    border: 1.5px solid var(--borde);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px var(--sombra);
    position: sticky;
    top: 1rem;
}
.perfil-sidebar-top {
    background: linear-gradient(to bottom right, var(--caoba), var(--caoba2));
    padding: 1.4rem 1rem;
    text-align: center;
}
.perfil-avatar {
    width: 60px; height: 60px;
    background: rgba(255,255,255,.15);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .7rem;
    font-size: 1.6rem;
    color: rgba(255,255,255,.85);
}
.perfil-nombre {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: .15rem;
}
.perfil-dni { font-size: .78rem; color: rgba(255,255,255,.65); }
.perfil-nav { padding: .5rem 0; }
.perfil-nav a {
    display: flex; align-items: center; gap: .6rem;
    padding: .65rem 1.1rem; font-size: .88rem;
    color: var(--tinta2); text-decoration: none;
    transition: background .12s, color .12s;
    border-left: 3px solid transparent;
}
.perfil-nav a:hover { background: var(--lino); color: var(--caoba); }
.perfil-nav a.active {
    background: var(--lino2); color: var(--caoba);
    font-weight: 700; border-left-color: var(--caoba);
}
.perfil-nav a.danger { color: #c0392b; }
.perfil-nav a.danger:hover { background: #fdf0f0; }
.perfil-nav-sep { height: 1px; background: var(--borde2); margin: .4rem .8rem; }


.pedidos-panel {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.pedidos-hd {
    background: var(--papel);
    border: 1.5px solid var(--borde);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px var(--sombra);
}
.pedidos-hd-top {
    background: linear-gradient(to right, var(--caoba), var(--caoba2));
    padding: 1rem 1.4rem;
}
.pedidos-hd-top h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0;
}


.pedidos-vacio {
    text-align: center;
    padding: 3rem 1.5rem;
    color: var(--g1);
}
.pedidos-vacio i {
    font-size: 3rem; opacity: .2;
    display: block; margin-bottom: .8rem;
}
.pedidos-vacio p { font-size: .95rem; margin-bottom: 1.2rem; }
.btn-ir-catalogo {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .6rem 1.2rem;
    background: var(--caoba); color: #fff;
    border-radius: 8px; text-decoration: none;
    font-weight: 700; font-size: .88rem;
    transition: background .15s;
}
.btn-ir-catalogo:hover { background: var(--caoba2); color: #fff; }


.pedido-card {
    background: var(--papel);
    border: 1.5px solid var(--borde);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px var(--sombra);
    transition: box-shadow .15s;
}
.pedido-card:hover { box-shadow: 0 4px 16px rgba(92,45,10,.13); }
.pedido-card-hd {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: .5rem;
    padding: .9rem 1.2rem;
    background: var(--lino2);
    border-bottom: 1.5px solid var(--borde);
}
.pedido-num {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; font-weight: 700; color: var(--caoba);
}
.pedido-fecha { font-size: .8rem; color: var(--g1); }
.pedido-card-bd {
    padding: 1rem 1.2rem;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: .8rem 1.5rem;
    align-items: start;
}
.pedido-productos {
    font-size: .88rem;
    color: var(--tinta2);
    font-weight: 600;
    grid-column: 1 / -1;
    padding-bottom: .5rem;
    border-bottom: 1px solid var(--borde2);
    margin-bottom: .2rem;
}
.pedido-dato-grupo {
    display: flex;
    flex-direction: column;
    gap: .5rem;
}
.pedido-dato {
    display: flex;
    align-items: center;
    gap: .5rem;
    font-size: .84rem;
    color: var(--tinta2);
}
.pedido-dato-label {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: var(--g1);
    min-width: 90px;
}
.pedido-monto {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--verde);
    text-align: right;
    white-space: nowrap;
}


.pill {
    display: inline-flex; align-items: center; gap: .25rem;
    padding: 2px 10px; border-radius: 20px;
    font-size: .73rem; font-weight: 700;
}
.pill-aprobado  { background: #e8f5e9; color: var(--verde);   border: 1px solid #a5d6a7; }
.pill-pendiente { background: #fff8e1; color: var(--amarillo); border: 1px solid #ffe082; }
.pill-rechazado { background: #fdf0f0; color: var(--rojo);    border: 1px solid #f5c6c6; }
.pill-otro      { background: var(--lino2); color: var(--tinta2); border: 1px solid var(--borde); }
.pill-produccion{ background: #e3f2fd; color: var(--azul);    border: 1px solid #90caf9; }
.pill-listo     { background: #f3e5f5; color: #6a1b9a;        border: 1px solid #ce93d8; }
.pill-entregado { background: #e8f5e9; color: var(--verde);   border: 1px solid #a5d6a7; }


.envio-codigo {
    font-family: monospace;
    font-size: .82rem;
    background: var(--lino2);
    padding: 2px 8px;
    border-radius: 5px;
    border: 1px solid var(--borde);
}

@media (max-width: 680px) {
    .pedidos-inner         { grid-template-columns: 1fr; }
    .perfil-sidebar        { position: static; }
    .pedido-card-bd        { grid-template-columns: 1fr; }
    .pedido-monto          { text-align: left; }
}
</style>

<div class="pedidos-page">
    <div class="pedidos-inner">

        
        <aside class="perfil-sidebar">
            <div class="perfil-sidebar-top">
                <div class="perfil-avatar"><i class="fas fa-user"></i></div>
                <div class="perfil-nombre">
                    <?= htmlspecialchars(($cliente['Nombre'] ?? '') . ' ' . ($cliente['Apellido'] ?? '')) ?>
                </div>
                <div class="perfil-dni">DNI <?= htmlspecialchars($cliente['DNI'] ?? '') ?></div>
            </div>
            <nav class="perfil-nav">
                <a href="<?= URL ?>cliente/perfil">
                    <i class="fas fa-user" style="width:16px;text-align:center;"></i> Mi Perfil
                </a>
                <a href="<?= URL ?>pedidocliente" class="active">
                    <i class="fas fa-shopping-bag" style="width:16px;text-align:center;"></i> Mis Pedidos
                </a>
                <a href="<?= URL ?>cliente/editar">
                    <i class="fas fa-edit" style="width:16px;text-align:center;"></i> Editar Perfil
                </a>
                <div class="perfil-nav-sep"></div>
                <a href="<?= URL ?>login/logout" class="danger">
                    <i class="fas fa-sign-out-alt" style="width:16px;text-align:center;"></i> Cerrar Sesión
                </a>
            </nav>
        </aside>

        
        <div class="pedidos-panel">

            
            <div class="pedidos-hd">
                <div class="pedidos-hd-top">
                    <h2>
                        <i class="fas fa-shopping-bag" style="margin-right:.5rem;opacity:.8;"></i>
                        Mis Pedidos
                    </h2>
                </div>

                <?php if (empty($pedidos)): ?>
                <div class="pedidos-vacio">
                    <i class="fas fa-box-open"></i>
                    <p>Todavía no tenés pedidos registrados.</p>
                    <a href="<?= URL ?>catalogo" class="btn-ir-catalogo">
                        <i class="fas fa-store"></i> Ver catálogo
                    </a>
                </div>
                <?php endif; ?>
            </div>

            
            <?php if (!empty($pedidos)): ?>
            <?php foreach ($pedidos as $p):
                
                $epNom = mb_strtolower($p['EstadoPago'] ?? '');
                if (str_contains($epNom, 'aprob'))       $pillPago = 'aprobado';
                elseif (str_contains($epNom, 'pend'))    $pillPago = 'pendiente';
                elseif (str_contains($epNom, 'rechaz'))  $pillPago = 'rechazado';
                else                                      $pillPago = 'otro';

                
                $peNom = mb_strtolower($p['EstadoPedido'] ?? '');
                if (str_contains($peNom, 'producción') || str_contains($peNom, 'produccion')) $pillPed = 'produccion';
                elseif (str_contains($peNom, 'listo'))   $pillPed = 'listo';
                elseif (str_contains($peNom, 'entregado')) $pillPed = 'entregado';
                elseif (str_contains($peNom, 'cancelado')) $pillPed = 'rechazado';
                elseif (str_contains($peNom, 'pendiente')) $pillPed = 'pendiente';
                else                                        $pillPed = 'otro';

                $fecha = !empty($p['Fecha'])
                    ? date('d/m/Y', strtotime($p['Fecha']))
                    : '—';

                $fechaEntrega = !empty($p['FechaDeEntrega'])
                    ? date('d/m/Y', strtotime($p['FechaDeEntrega']))
                    : null;
            ?>
            <div class="pedido-card">
                
                <div class="pedido-card-hd">
                    <div>
                        <div class="pedido-num">N° de Venta <?= $p['NumerodeVenta'] ?></div>
                        <div class="pedido-fecha"><?= $fecha ?></div>
                    </div>
                    <span class="pill pill-<?= $pillPago ?>">
                        <?= htmlspecialchars($p['EstadoPago'] ?? '—') ?>
                    </span>
                </div>

                
                <div class="pedido-card-bd">

                    
                    <div class="pedido-productos">
                        <i class="fas fa-box" style="color:var(--amb);margin-right:.3rem;font-size:.8rem;"></i>
                        <?= htmlspecialchars($p['Productos'] ?? '—') ?>
                    </div>

                    
                    <div class="pedido-dato-grupo">

                        
                        <div class="pedido-dato">
                            <span class="pedido-dato-label">Pago</span>
                            <?= htmlspecialchars($p['TipoPago'] ?? '—') ?>
                            <?php if (!empty($p['MarcaTarjeta'])): ?>
                                — <?= htmlspecialchars($p['MarcaTarjeta']) ?>
                                <?php if (($p['Cuotas'] ?? 1) > 1): ?>
                                    (<?= $p['Cuotas'] ?> cuotas)
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        
                        <?php if (!empty($p['EstadoPedido'])): ?>
                        <div class="pedido-dato">
                            <span class="pedido-dato-label">Producción</span>
                            <span class="pill pill-<?= $pillPed ?>">
                                <?= htmlspecialchars($p['EstadoPedido']) ?>
                            </span>
                        </div>
                        <?php endif; ?>

                        
                        <?php if (!empty($p['TipoEntrega'])): ?>
                        <div class="pedido-dato">
                            <span class="pedido-dato-label">Entrega</span>
                            <?= htmlspecialchars($p['TipoEntrega']) ?>
                            <?php if (!empty($p['EstadoEntrega'])): ?>
                                —
                                <span class="pill pill-<?= str_contains(mb_strtolower($p['EstadoEntrega']), 'finaliz') ? 'entregado' : 'pendiente' ?>">
                                    <?= htmlspecialchars($p['EstadoEntrega']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        
                        <?php if ($fechaEntrega): ?>
                        <div class="pedido-dato">
                            <span class="pedido-dato-label">Fecha entrega</span>
                            <?= $fechaEntrega ?>
                        </div>
                        <?php endif; ?>

                        
                        <?php if (!empty($p['CodigoEntrega'])): ?>
                        <div class="pedido-dato">
                            <span class="pedido-dato-label">Código envío</span>
                            <span class="envio-codigo"><?= htmlspecialchars($p['CodigoEntrega']) ?></span>
                        </div>
                        <?php endif; ?>

                    </div>

                    
                    <div class="pedido-monto">
                        $<?= number_format($p['MontoTotal'] ?? 0, 2, ',', '.') ?>
                        <?php if (($p['CostoEnvio'] ?? 0) > 0): ?>
                        <div style="font-size:.72rem;font-weight:400;color:var(--g1);margin-top:.2rem;">
                            + $<?= number_format($p['CostoEnvio'], 2, ',', '.') ?> envío
                        </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include INCLUDES . 'footer_cliente.php'; ?>