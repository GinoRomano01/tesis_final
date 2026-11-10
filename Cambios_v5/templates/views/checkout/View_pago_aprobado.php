<?php include INCLUDES . 'header_cliente.php'; ?>

<style>
:root {
    --lino: #f7f0e6; --lino2: #ede4d4; --papel: #fdfaf6;
    --caoba: #5c2d0a; --caoba2: #7a3e14; --amb: #b8722a;
    --tinta: #2c1a0e; --tinta2: #4a3020; --g1: #8a7560; --borde: #d4c4aa;
    --verde: #2e6b3a; --verde2: #d1fae5;
}

.aprobado-page {
    background: var(--lino);
    min-height: 100vh;
    padding: 2.5rem 0 5rem;
    font-family: 'Source Sans 3', Georgia, sans-serif;
}
.aprobado-inner { max-width: 820px; margin: 0 auto; padding: 0 1.5rem; }

.banner-exito {
    background: linear-gradient(135deg, var(--verde) 0%, #3a8a4a 100%);
    border-radius: 14px;
    padding: 2rem 2.5rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 1.8rem;
    box-shadow: 0 8px 32px rgba(46,107,58,.2);
}
.banner-check {
    width: 64px; height: 64px;
    background: rgba(255,255,255,.2);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem;
    color: #fff;
    flex-shrink: 0;
    border: 2px solid rgba(255,255,255,.4);
}
.banner-texto h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    color: #fff;
    margin-bottom: .2rem;
}
.banner-texto p { color: rgba(255,255,255,.85); font-size: .9rem; }

.codigo-wrap {
    background: var(--papel);
    border: 1.5px solid var(--borde);
    border-radius: 14px;
    padding: 1.5rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    margin-bottom: 1.8rem;
    box-shadow: 0 2px 12px rgba(92,45,10,.06);
}
.codigo-izq { flex: 1; }
.codigo-tag {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .12em;
    color: var(--g1);
    margin-bottom: .4rem;
}
.codigo-valor {
    font-family: 'Playfair Display', serif;
    font-size: 2.6rem;
    font-weight: 700;
    color: var(--caoba);
    letter-spacing: .2em;
    line-height: 1;
}
.codigo-desc {
    font-size: .82rem;
    color: var(--g1);
    margin-top: .4rem;
}
.codigo-der {
    text-align: center;
    flex-shrink: 0;
}
.tipo-entrega-badge {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    gap: .3rem;
    background: var(--lino2);
    border: 1.5px solid var(--borde);
    border-radius: 10px;
    padding: .9rem 1.2rem;
    font-size: .8rem;
    font-weight: 700;
    color: var(--tinta2);
}
.tipo-entrega-badge span { font-size: 1.8rem; }

.factura-documento {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 24px rgba(92,45,10,.1), 0 1px 4px rgba(92,45,10,.05);
    overflow: hidden;
    margin-bottom: 1.5rem;
    border: 1px solid var(--borde);
}

.factura-membrete {
    background: var(--caoba);
    padding: 1.8rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
}
.membrete-empresa {}
.membrete-logo {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: .04em;
}
.membrete-rubro {
    font-size: .78rem;
    color: rgba(255,255,255,.7);
    margin-top: .1rem;
    text-transform: uppercase;
    letter-spacing: .1em;
}
.membrete-doc {
    text-align: right;
}
.membrete-doc-tipo {
    font-size: .72rem;
    text-transform: uppercase;
    letter-spacing: .12em;
    color: rgba(255,255,255,.6);
    margin-bottom: .2rem;
}
.membrete-doc-nro {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: .05em;
}
.membrete-doc-fecha {
    font-size: .8rem;
    color: rgba(255,255,255,.7);
    margin-top: .2rem;
}


.banda-estado {
    background: var(--verde2);
    border-bottom: 1px solid #6ee7b7;
    padding: .6rem 2rem;
    display: flex;
    align-items: center;
    gap: .5rem;
    font-size: .82rem;
    font-weight: 700;
    color: #065f46;
}
.banda-estado i { color: var(--verde); }

.factura-cuerpo { padding: 1.8rem 2rem; }


.factura-seccion-titulo {
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .12em;
    color: var(--g1);
    padding-bottom: .4rem;
    border-bottom: 1px solid var(--borde);
    margin-bottom: .9rem;
}

.datos-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: .8rem 1.5rem;
    margin-bottom: 1.6rem;
}
.dato-item {}
.dato-label {
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .09em;
    color: var(--g1);
    margin-bottom: .2rem;
}
.dato-valor {
    font-size: .9rem;
    font-weight: 600;
    color: var(--tinta);
}
.dato-valor.destacado {
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    color: var(--caoba);
}

.factura-tabla {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1.4rem;
    font-size: .85rem;
}
.factura-tabla thead tr {
    background: var(--lino2);
}
.factura-tabla thead th {
    padding: .6rem .9rem;
    text-align: left;
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .09em;
    color: var(--g1);
    border-bottom: 1.5px solid var(--borde);
}
.factura-tabla thead th:last-child { text-align: right; }
.factura-tabla tbody td {
    padding: .65rem .9rem;
    border-bottom: 1px solid #f0e8d8;
    color: var(--tinta2);
    vertical-align: middle;
}
.factura-tabla tbody td:last-child { text-align: right; font-weight: 700; color: var(--tinta); }
.factura-tabla tbody tr:last-child td { border-bottom: none; }

.factura-totales {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 1.6rem;
}
.totales-box {
    width: 280px;
    background: var(--lino);
    border: 1.5px solid var(--borde);
    border-radius: 10px;
    overflow: hidden;
}
.total-fila {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .5rem 1rem;
    font-size: .85rem;
    color: var(--tinta2);
    border-bottom: 1px solid var(--borde);
}
.total-fila:last-child { border-bottom: none; }
.total-fila.total-final {
    background: var(--caoba);
    color: #fff;
    padding: .75rem 1rem;
}
.total-fila.total-final span:first-child { font-weight: 700; font-size: .9rem; }
.total-fila.total-final span:last-child {
    font-family: 'Playfair Display', serif;
    font-size: 1.2rem;
    font-weight: 700;
}


.pago-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .8rem 1.5rem;
    padding: 1rem 1.2rem;
    background: var(--lino);
    border: 1.5px solid var(--borde);
    border-radius: 10px;
    margin-bottom: 1.4rem;
}

.factura-pie {
    background: var(--lino2);
    border-top: 1.5px solid var(--borde);
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: .75rem;
    color: var(--g1);
}
.factura-pie-codigo {
    font-family: monospace;
    background: var(--papel);
    border: 1px solid var(--borde);
    border-radius: 5px;
    padding: .2rem .6rem;
    font-size: .72rem;
    color: var(--tinta2);
}

.acciones {
    display: flex;
    gap: .8rem;
    flex-wrap: wrap;
}
.btn-descargar {
    flex: 1;
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    padding: .85rem;
    background: var(--caoba);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: .95rem;
    font-weight: 700;
    cursor: pointer;
    transition: background .15s, transform .1s;
    box-shadow: 0 3px 12px rgba(92,45,10,.3);
    text-decoration: none;
    font-family: 'Source Sans 3', sans-serif;
}
.btn-descargar:hover { background: var(--caoba2); transform: translateY(-1px); color: #fff; }
.btn-inicio {
    flex: 1;
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    padding: .85rem;
    background: none;
    color: var(--caoba);
    border: 1.5px solid var(--borde);
    border-radius: 10px;
    font-size: .95rem;
    font-weight: 700;
    cursor: pointer;
    transition: border-color .15s, background .15s;
    text-decoration: none;
    font-family: 'Source Sans 3', sans-serif;
}
.btn-inicio:hover { border-color: var(--caoba); background: var(--lino2); color: var(--caoba); }

@media (max-width: 640px) {
    .banner-exito { flex-direction: column; text-align: center; }
    .codigo-wrap  { flex-direction: column; text-align: center; }
    .datos-grid   { grid-template-columns: 1fr 1fr; }
    .pago-info-grid { grid-template-columns: 1fr; }
    .factura-membrete { flex-direction: column; gap: .8rem; }
    .membrete-doc { text-align: left; }
    .factura-pie  { flex-direction: column; gap: .4rem; text-align: center; }
}

@media print {
    .no-print { display: none !important; }
    body, .aprobado-page { background: #fff !important; padding: 0 !important; }
    .aprobado-inner { max-width: 100%; padding: 0; }
    .factura-documento { box-shadow: none; border: 1px solid #ccc; }
    .banner-exito, .codigo-wrap { display: none; }
}
</style>

<div class="aprobado-page">
    <div class="aprobado-inner">

        <?php
        $nroFactura  = str_pad($pago['NumeroFactura'] ?? '0', 8, '0', STR_PAD_LEFT);
        $nroVenta    = str_pad($pago['NumerodeVenta'] ?? '0', 6, '0', STR_PAD_LEFT);
        $fecha       = $pago['FechadeEmision'] ?? date('Y-m-d H:i:s');
        $cuotas      = (int)($pago['Cuotas'] ?? 1);
        $monto       = (float)($pago['MontoTotal'] ?? 0);
        $marca       = ucfirst($pago['MarcaTarjeta'] ?? '');
        $tipoPago    = $pago['TipoPago'] ?? '—';
        $cliente     = $pago['NombreCliente'] ?? '—';
        $dni         = $pago['DNI'] ?? '—';
        $correo      = $pago['CorreoElectronico'] ?? '—';
        $tipoEntrega = ($entrega['IdTipodeEntrega'] ?? 0) == 1;
        ?>

        <div class="banner-exito no-print">
            <div class="banner-check">✓</div>
            <div class="banner-texto">
                <h2>¡Pago aprobado!</h2>
                <p>Tu pedido fue confirmado. Guardá el código de entrega y la factura.</p>
            </div>
        </div>

        <div class="codigo-wrap no-print">
            <div class="codigo-izq">
                <div class="codigo-tag">Código de entrega</div>
                <div class="codigo-valor"><?= htmlspecialchars($codigoEntrega) ?></div>
                <div class="codigo-desc">
                    <?= $tipoEntrega
                        ? 'Presentá este código en nuestro local para retirar tu pedido.'
                        : 'Guardá este código para hacer seguimiento de tu envío.' ?>
                </div>
            </div>
            <div class="codigo-der">
                <div class="tipo-entrega-badge">
                    <span><?= $tipoEntrega ? '' : '' ?></span>
                    <?= $tipoEntrega ? 'Retiro en local' : 'Envío a domicilio' ?>
                </div>
            </div>
        </div>

        <div class="factura-documento" id="factura-pdf">

            <div class="factura-membrete">
                <div class="membrete-empresa">
                    <div class="membrete-logo">San Plácido</div>
                    <div class="membrete-rubro">Muebles artesanales</div>
                </div>
                <div class="membrete-doc">
                    <div class="membrete-doc-tipo">Comprobante de venta</div>
                    <div class="membrete-doc-nro">N° <?= $nroFactura ?></div>
                    <div class="membrete-doc-fecha">
                        <?= date('d \d\e F \d\e Y, H:i', strtotime($fecha)) ?>
                    </div>
                </div>
            </div>

            <div class="banda-estado">
                <i class="fas fa-check-circle"></i>
                Pago aprobado — Comprobante válido
            </div>

            <div class="factura-cuerpo">

                <div class="factura-seccion-titulo">Datos del comprador</div>
                <div class="datos-grid">
                    <div class="dato-item">
                        <div class="dato-label">Nombre completo</div>
                        <div class="dato-valor destacado"><?= htmlspecialchars($cliente) ?></div>
                    </div>
                    <div class="dato-item">
                        <div class="dato-label">DNI</div>
                        <div class="dato-valor"><?= htmlspecialchars($dni) ?></div>
                    </div>
                    <div class="dato-item">
                        <div class="dato-label">Correo electrónico</div>
                        <div class="dato-valor"><?= htmlspecialchars($correo) ?></div>
                    </div>
                </div>

                <div class="factura-seccion-titulo">Detalle del pedido</div>
                <table class="factura-tabla">
                    <thead>
                        <tr>
                            <th>Concepto</th>
                            <th>N° de venta</th>
                            <th>Entrega</th>
                            <th>Subtotal</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>Pedido San Plácido</strong><br>
                                <span style="font-size:.78rem;color:var(--g1);">
                                    <?= $cuotas == 1 ? 'Pago único' : $cuotas . ' cuotas' ?>
                                    <?= $marca ? '— ' . $marca : '' ?>
                                </span>
                            </td>
                            <td style="font-family:monospace;font-size:.85rem;">#<?= $nroVenta ?></td>
                            <td>
                                <?= $tipoEntrega
                                    ? '<span style="color:var(--verde);font-weight:700;">🏪 Retiro</span>'
                                    : '<span style="color:var(--amb);font-weight:700;">🚚 Envío</span>' ?>
                                <?php if (!$tipoEntrega && ($entrega['Direccion'] ?? '')): ?>
                                    <br><span style="font-size:.75rem;color:var(--g1);"><?= htmlspecialchars($entrega['Direccion']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>$<?= number_format($monto, 2, ',', '.') ?></td>
                            <td>$<?= number_format($monto, 2, ',', '.') ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="factura-totales">
                    <div class="totales-box">
                        <div class="total-fila">
                            <span>Subtotal</span>
                            <span>$<?= number_format($monto, 2, ',', '.') ?></span>
                        </div>
                        <div class="total-fila">
                            <span>Impuestos</span>
                            <span>$0,00</span>
                        </div>
                        <?php if (($pago['Interes'] ?? 0) > 0): ?>
                        <div class="total-fila">
                            <span>Interés</span>
                            <span>$<?= number_format($pago['Interes'], 2, ',', '.') ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="total-fila total-final">
                            <span>Total abonado</span>
                            <span>$<?= number_format($monto, 2, ',', '.') ?></span>
                        </div>
                    </div>
                </div>

                <div class="factura-seccion-titulo">Información del pago</div>
                <div class="pago-info-grid">
                    <div class="dato-item">
                        <div class="dato-label">Medio de pago</div>
                        <div class="dato-valor"><?= htmlspecialchars($tipoPago) ?><?= $marca ? ' — ' . $marca : '' ?></div>
                    </div>
                    <div class="dato-item">
                        <div class="dato-label">Cuotas</div>
                        <div class="dato-valor"><?= $cuotas == 1 ? '1 pago sin interés' : $cuotas . ' cuotas' ?></div>
                    </div>
                    <div class="dato-item">
                        <div class="dato-label">Estado</div>
                        <div class="dato-valor" style="color:var(--verde);font-weight:700;">
                            <i class="fas fa-check-circle"></i> Aprobado
                        </div>
                    </div>
                    <div class="dato-item">
                        <div class="dato-label">N° de factura</div>
                        <div class="dato-valor" style="font-family:monospace;"><?= $nroFactura ?></div>
                    </div>
                </div>

            </div>


            <div class="factura-pie">
                <span>San Plácido — Muebles artesanales de calidad</span>
                <span class="factura-pie-codigo">FC-<?= $nroFactura ?>-<?= date('Y', strtotime($fecha)) ?></span>
                <span>Gracias por su compra</span>
            </div>

        </div>


        <div class="acciones no-print">
            <button class="btn-descargar" onclick="window.print()">
                <i class="fas fa-print"></i> Imprimir / Guardar PDF
            </button>
            <a href="<?= URL ?>" class="btn-inicio">
                <i class="fas fa-home"></i> Volver al inicio
            </a>
        </div>

    </div>
</div>

<script>
console.log('%c✅ DEBUG PAGO APROBADO:', 'color:#2e7d32;font-weight:bold;', {
    paymentIdMP: <?= json_encode($pago['PaymentIdMP'] ?? null) ?>,
    status: <?= json_encode($pago['Status'] ?? null) ?>,
    statusDetail: <?= json_encode($pago['StatusDetail'] ?? null) ?>,
    idVenta: <?= json_encode($pago['IdVenta'] ?? null) ?>,
    codigoEntrega: <?= json_encode($codigoEntrega) ?>
});
</script>

<?php include INCLUDES . 'footer_cliente.php'; ?>