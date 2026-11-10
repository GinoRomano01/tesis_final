<?php include INCLUDES . 'header_cliente.php'; ?>

<style>
:root {
    --lino: #f7f0e6; --lino2: #ede4d4; --papel: #fdfaf6;
    --caoba: #5c2d0a; --caoba2: #7a3e14; --amb: #b8722a; --dorado: #c9a84c;
    --tinta: #2c1a0e; --tinta2: #4a3020; --g1: #8a7560; --borde: #d4c4aa;
    --verde: #2e6b3a;
}
.metodo-page {
    background: var(--lino);
    min-height: 100vh;
    padding: 2rem 0 4rem;
    font-family: 'Source Sans 3', Georgia, sans-serif;
}
.metodo-inner { max-width: 820px; margin: 0 auto; padding: 0 1.5rem; }
.metodo-titulo {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem; font-weight: 700; color: var(--caoba);
    margin-bottom: .5rem;
    display: flex; align-items: center; gap: .6rem;
}
.metodo-steps {
    display: flex; align-items: center; gap: .5rem;
    margin-bottom: 2rem; font-size: .82rem; color: var(--g1);
}
.step-done { color: var(--verde); }
.step-active { color: var(--caoba); font-weight: 700; }
.step-sep { color: var(--borde); }

.metodo-panel {
    background: var(--papel);
    border: 1.5px solid var(--borde);
    border-radius: 12px;
    padding: 1.8rem;
    margin-bottom: 1.5rem;
}
.metodo-subtitulo {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem; font-weight: 700; color: var(--caoba);
    margin-bottom: 1.4rem;
    padding-bottom: .8rem;
    border-bottom: 1.5px solid var(--borde);
}


.metodos-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.metodo-card {
    border: 2px solid var(--borde);
    border-radius: 12px;
    padding: 1.3rem;
    cursor: pointer;
    transition: all .2s;
    background: #fff;
    position: relative;
    text-align: center;
}
.metodo-card:hover { border-color: var(--amb); transform: translateY(-2px); }
.metodo-card.selected {
    border-color: var(--caoba);
    background: var(--lino);
    box-shadow: 0 4px 16px rgba(92,45,10,.12);
}
.metodo-icono { font-size: 2.2rem; margin-bottom: .6rem; display: block; }
.metodo-nombre {
    font-family: 'Playfair Display', serif;
    font-size: .95rem; font-weight: 700; color: var(--caoba);
    margin-bottom: .2rem;
}
.metodo-desc { font-size: .78rem; color: var(--g1); line-height: 1.4; }
.metodo-check {
    position: absolute; top: .7rem; right: .7rem;
    width: 20px; height: 20px;
    border: 2px solid var(--borde); border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .7rem; color: #fff;
    transition: all .2s;
}
.metodo-card.selected .metodo-check {
    background: var(--caoba); border-color: var(--caoba);
}


.marca-section {
    display: none;
    margin-top: 1.2rem;
    padding-top: 1.2rem;
    border-top: 1.5px solid var(--borde);
}
.marca-section.show { display: block; }
.marca-titulo {
    font-size: .88rem; font-weight: 700; color: var(--tinta2);
    margin-bottom: .8rem;
}
.marcas-grid { display: flex; gap: .8rem; flex-wrap: wrap; }
.marca-card {
    border: 2px solid var(--borde);
    border-radius: 10px;
    padding: .8rem 1.4rem;
    cursor: pointer;
    transition: all .2s;
    background: #fff;
    display: flex; align-items: center; gap: .6rem;
    font-weight: 700; font-size: .9rem; color: var(--tinta2);
}
.marca-card:hover { border-color: var(--amb); }
.marca-card.selected { border-color: var(--caoba); background: var(--lino); color: var(--caoba); }
.marca-logo { font-size: 1.4rem; }


.resumen-metodo {
    background: var(--papel);
    border: 1.5px solid var(--borde);
    border-radius: 12px;
    padding: 1.3rem;
}
.re-fila {
    display: flex; justify-content: space-between;
    font-size: .9rem; color: var(--tinta2); margin-bottom: .5rem;
}
.re-fila.total {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem; font-weight: 700; color: var(--caoba);
    padding-top: .7rem; margin-top: .5rem; border-top: 1.5px solid var(--borde);
}
.btn-continuar {
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    width: 100%; margin-top: 1.2rem; padding: .85rem;
    background: var(--caoba); color: #fff; border: none; border-radius: 9px;
    font-size: 1rem; font-weight: 700; cursor: pointer; transition: background .15s;
    box-shadow: 0 2px 10px rgba(92,45,10,.3);
}
.btn-continuar:hover { background: var(--caoba2); }
.btn-continuar:disabled { opacity: .6; cursor: not-allowed; }
.btn-volver {
    display: flex; align-items: center; gap: .35rem;
    margin-top: .85rem; font-size: .84rem; color: var(--amb);
    text-decoration: none; font-weight: 600;
}
.btn-volver:hover { color: var(--caoba); }

@media (max-width: 600px) {
    .metodos-grid { grid-template-columns: 1fr; }
}
</style>

<div class="metodo-page">
    <div class="metodo-inner">

        <h1 class="metodo-titulo">
            <i class="fas fa-credit-card"></i> Medio de pago
        </h1>

        <div class="metodo-steps">
            <span class="step-done"><i class="fas fa-check-circle"></i> Entrega</span>
            <span class="step-sep">›</span>
            <span class="step-active"><i class="fas fa-credit-card"></i> Pago</span>
            <span class="step-sep">›</span>
            <span>Confirmación</span>
        </div>

        <?= Toast::flash() ?>

        <div class="metodo-panel">
            <div class="metodo-subtitulo">Elegí cómo querés pagar</div>

            <div class="metodos-grid">

                <div class="metodo-card" id="card-credito" onclick="seleccionarMetodo('credito')">
                    <div class="metodo-check"><i class="fas fa-check"></i></div>
                    <span class="metodo-icono"></span>
                    <div class="metodo-nombre">Tarjeta de crédito</div>
                    <div class="metodo-desc">Visa o Mastercard. Hasta 12 cuotas.</div>
                </div>

                <div class="metodo-card" id="card-debito" onclick="seleccionarMetodo('debito')">
                    <div class="metodo-check"><i class="fas fa-check"></i></div>
                    <span class="metodo-icono"></span>
                    <div class="metodo-nombre">Tarjeta de débito</div>
                    <div class="metodo-desc">Visa o Mastercard débito. Pago inmediato.</div>
                </div>

                <div class="metodo-card" id="card-efectivo" onclick="seleccionarMetodo('efectivo')">
                    <div class="metodo-check"><i class="fas fa-check"></i></div>
                    <span class="metodo-icono"></span>
                    <div class="metodo-nombre">Efectivo</div>
                    <div class="metodo-desc">Generamos un cupón para pagar en Pago Fácil o Rapipago.</div>
                </div>

                <div class="metodo-card" id="card-mp" onclick="seleccionarMetodo('mp')">
                    <div class="metodo-check"><i class="fas fa-check"></i></div>
                    <span class="metodo-icono"></span>
                    <div class="metodo-nombre">Mercado Pago</div>
                    <div class="metodo-desc">Pagá con tu cuenta MP, QR o cualquier método disponible.</div>
                </div>

            </div>

            
            <div class="marca-section" id="marca-section">
                <div class="marca-titulo">Seleccioná la red de tu tarjeta:</div>
                <div class="marcas-grid">
                    <div class="marca-card" id="marca-visa" onclick="seleccionarMarca('visa')">
                        <span class="marca-logo"></span> Visa
                    </div>
                    <div class="marca-card" id="marca-master" onclick="seleccionarMarca('mastercard')">
                        <span class="marca-logo"></span> Mastercard
                    </div>
                </div>
            </div>

        </div>

        
        <div class="resumen-metodo">
            <div class="re-fila">
                <span>Subtotal</span>
                <span>$<?= number_format($subtotal, 2, ',', '.') ?></span>
            </div>
            <?php if (($entrega['costo'] ?? 0) > 0): ?>
            <div class="re-fila">
                <span>Envío</span>
                <span>+ $<?= number_format($entrega['costo'], 2, ',', '.') ?></span>
            </div>
            <?php endif; ?>
            <div class="re-fila total">
                <span>Total</span>
                <span>$<?= number_format($total, 2, ',', '.') ?></span>
            </div>

            <button class="btn-continuar" id="btn-continuar" onclick="continuar()" disabled>
                Continuar <i class="fas fa-arrow-right"></i>
            </button>

            <a href="<?= URL ?>checkout/entrega" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver a entrega
            </a>
        </div>

    </div>
</div>

<script>
let metodoSeleccionado = null;
let marcaSeleccionada  = null;

function seleccionarMetodo(metodo) {
    metodoSeleccionado = metodo;
    marcaSeleccionada  = null;

    document.querySelectorAll('.metodo-card').forEach(c => c.classList.remove('selected'));
    document.getElementById('card-' + metodo).classList.add('selected');

    const marcaSection = document.getElementById('marca-section');
    if (metodo === 'credito' || metodo === 'debito') {
        marcaSection.classList.add('show');
        document.querySelectorAll('.marca-card').forEach(c => c.classList.remove('selected'));
        actualizarBoton();
    } else {
        marcaSection.classList.remove('show');
        actualizarBoton();
    }
}

function seleccionarMarca(marca) {
    marcaSeleccionada = marca;
    document.querySelectorAll('.marca-card').forEach(c => c.classList.remove('selected'));
    const cards = document.querySelectorAll('.marca-card');
    cards.forEach(card => {
        if (card.getAttribute('onclick') && card.getAttribute('onclick').includes(marca)) {
            card.classList.add('selected');
        }
    });
    actualizarBoton();
}

function actualizarBoton() {
    const btn = document.getElementById('btn-continuar');
    const necesitaMarca = metodoSeleccionado === 'credito' || metodoSeleccionado === 'debito';
    const listo = metodoSeleccionado && (!necesitaMarca || marcaSeleccionada);
    btn.disabled = !listo;
}

function continuar() {
    if (!metodoSeleccionado) return;
    const necesitaMarca = metodoSeleccionado === 'credito' || metodoSeleccionado === 'debito';
    if (necesitaMarca && !marcaSeleccionada) return;

    sessionStorage.setItem('metodo_pago', metodoSeleccionado);
    sessionStorage.setItem('marca_tarjeta', marcaSeleccionada ?? '');

    const rutas = {
        credito:    '<?= URL ?>checkout/index',
        debito:     '<?= URL ?>checkout/index',
        efectivo:   '<?= URL ?>checkout/efectivo',
        mp:         '<?= URL ?>checkout/mercadopago',
    };
    window.location.href = rutas[metodoSeleccionado];
}
</script>

<?php include INCLUDES . 'footer_cliente.php'; ?>