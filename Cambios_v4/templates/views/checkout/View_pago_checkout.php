<?php include INCLUDES . 'header_cliente.php'; ?>

<style>
:root {
    --lino: #f7f0e6; --lino2: #ede4d4; --papel: #fdfaf6;
    --caoba: #5c2d0a; --caoba2: #7a3e14; --amb: #b8722a; --dorado: #c9a84c;
    --tinta: #2c1a0e; --tinta2: #4a3020; --g1: #8a7560; --borde: #d4c4aa;
    --verde: #2e6b3a;
}
.checkout-page {
    background: var(--lino);
    min-height: 100vh;
    padding: 2rem 0 4rem;
    font-family: 'Source Sans 3', Georgia, sans-serif;
}
.checkout-inner {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 1.5rem;
}
.checkout-titulo {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--caoba);
    margin-bottom: .5rem;
    display: flex;
    align-items: center;
    gap: .6rem;
}
.checkout-steps {
    display: flex;
    align-items: center;
    gap: .5rem;
    margin-bottom: 1.8rem;
    font-size: .82rem;
    color: var(--g1);
}
.checkout-steps .step-done { color: var(--verde); }
.checkout-steps .step-active { color: var(--caoba); font-weight: 700; }
.checkout-steps .step-sep { color: var(--borde); }
.checkout-layout {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 1.5rem;
    align-items: start;
}
.checkout-form-panel {
    background: var(--papel);
    border: 1.5px solid var(--borde);
    border-radius: 12px;
    overflow: visible;
}
.cfp-seccion {
    padding: 1.3rem 1.4rem;
    border-bottom: 1px solid var(--borde);
}
.cfp-seccion:last-child { border-bottom: none; }
.cfp-titulo {
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--caoba);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.cfp-titulo i { font-size: .9rem; color: var(--amb); }
.mp-field-wrap { display: grid; gap: .85rem; }
.mp-field { display: flex; flex-direction: column; gap: .3rem; }
.mp-field label {
    font-size: .82rem;
    font-weight: 600;
    color: var(--tinta2);
    font-family: 'Source Sans 3', sans-serif;
}
.mp-iframe-wrap {
    background: #fff;
    border: 1.5px solid var(--borde);
    border-radius: 8px;
    height: 42px;
    padding: 0;
    transition: border-color .15s;
    overflow: hidden;
}
.mp-iframe-wrap iframe {
    width: 100%;
    height: 100%;
    border: none;
    display: block;
    pointer-events: auto;
}
.mp-field-wrap, .mp-field, .mp-row { pointer-events: auto; }
.mp-input {
    background: #fff;
    border: 1.5px solid var(--borde);
    border-radius: 8px;
    height: 42px;
    padding: 0 .9rem;
    font-family: 'Source Sans 3', sans-serif;
    font-size: .9rem;
    color: var(--tinta);
    width: 100%;
    box-sizing: border-box;
    transition: border-color .15s;
}
.mp-input:focus { outline: none; border-color: var(--amb); box-shadow: 0 0 0 3px rgba(184,114,42,.1); }
.mp-select {
    background: #fff;
    border: 1.5px solid var(--borde);
    border-radius: 8px;
    height: 42px;
    padding: 0 .9rem;
    font-family: 'Source Sans 3', sans-serif;
    font-size: .9rem;
    color: var(--tinta);
    width: 100%;
    cursor: pointer;
    transition: border-color .15s;
}
.mp-select:focus { outline: none; border-color: var(--amb); }
.mp-row { display: grid; grid-template-columns: 1fr 1fr; gap: .85rem; }
.checkout-resumen {
    background: var(--papel);
    border: 1.5px solid var(--borde);
    border-radius: 12px;
    padding: 1.3rem;
    position: sticky;
    top: 120px;
}
.cr-titulo {
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--caoba);
    margin-bottom: 1rem;
    padding-bottom: .8rem;
    border-bottom: 1.5px solid var(--borde);
}
.cr-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: .5rem;
    margin-bottom: .7rem;
    font-size: .88rem;
}
.cr-item-nombre { color: var(--tinta2); flex: 1; line-height: 1.35; }
.cr-item-nombre small { display: block; color: var(--g1); font-size: .76rem; }
.cr-item-precio {
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    color: var(--caoba);
    white-space: nowrap;
}
.cr-sep { height: 1.5px; background: var(--borde); margin: 1rem 0; }
.cr-subtotal, .cr-envio {
    display: flex;
    justify-content: space-between;
    font-size: .88rem;
    color: var(--tinta2);
    margin-bottom: .4rem;
}
.cr-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--caoba);
    padding-top: .6rem;
    border-top: 1.5px solid var(--borde);
    margin-top: .4rem;
}
.btn-pagar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    width: 100%;
    margin-top: 1.2rem;
    padding: .85rem;
    background: var(--caoba);
    color: #fff;
    border: none;
    border-radius: 9px;
    font-family: 'Source Sans 3', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: background .15s, transform .12s;
    box-shadow: 0 2px 10px rgba(92,45,10,.3);
}
.btn-pagar:hover:not(:disabled) { background: var(--caoba2); transform: translateY(-1px); }
.btn-pagar:disabled { opacity: .6; cursor: not-allowed; transform: none; }
.checkout-seguro {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .35rem;
    margin-top: .6rem;
    font-size: .78rem;
    color: var(--g1);
}
.checkout-seguro i { color: var(--verde); }
.btn-volver {
    display: flex;
    align-items: center;
    gap: .35rem;
    margin-top: .85rem;
    font-size: .84rem;
    color: var(--amb);
    text-decoration: none;
    font-weight: 600;
    font-family: 'Source Sans 3', sans-serif;
}
.btn-volver:hover { color: var(--caoba); }
.metodo-badge {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    background: var(--lino2);
    border: 1.5px solid var(--borde);
    border-radius: 20px;
    padding: .3rem .9rem;
    font-size: .82rem;
    color: var(--tinta2);
    font-weight: 600;
    margin-bottom: 1rem;
}
@media (max-width: 780px) {
    .checkout-layout { grid-template-columns: 1fr; }
    .checkout-resumen { position: static; }
    .mp-row { grid-template-columns: 1fr; }
}
</style>

<div class="checkout-page">
    <div class="checkout-inner">

        <h1 class="checkout-titulo">
            <i class="fas fa-lock"></i>
            Finalizar compra
        </h1>

        <div class="checkout-steps">
            <span class="step-done"><i class="fas fa-check-circle"></i> Entrega</span>
            <span class="step-sep">›</span>
            <span class="step-done"><i class="fas fa-check-circle"></i> Método</span>
            <span class="step-sep">›</span>
            <span class="step-active"><i class="fas fa-credit-card"></i> Pago</span>
            <span class="step-sep">›</span>
            <span>Confirmación</span>
        </div>

        <?= Toast::flash() ?>

        <div class="checkout-layout">

            <div class="checkout-form-panel">

                <div class="cfp-seccion">
                    <div class="cfp-titulo"><i class="fas fa-user"></i> Datos de contacto</div>
                    <div class="mp-field">
                        <label>Correo electrónico</label>
                        <input type="email" id="cardholderEmail" class="mp-input"
                               value="<?= htmlspecialchars($email) ?>"
                               placeholder="tu@email.com">
                    </div>
                </div>

                <div class="cfp-seccion">
                    <div class="cfp-titulo"><i class="fas fa-credit-card"></i> Datos de la tarjeta</div>

                    <div id="metodo-badge" class="metodo-badge" style="display:none;"></div>

                    <div class="mp-field-wrap">

                        <div class="mp-field">
                            <label>Número de tarjeta</label>
                            <div class="mp-iframe-wrap" id="cardNumber"></div>
                        </div>

                        <div class="mp-row">
                            <div class="mp-field">
                                <label>Vencimiento</label>
                                <div class="mp-iframe-wrap" id="expirationDate"></div>
                            </div>
                            <div class="mp-field">
                                <label>Código de seguridad</label>
                                <div class="mp-iframe-wrap" id="securityCode"></div>
                            </div>
                        </div>

                        <div class="mp-field">
                            <label>Nombre en la tarjeta</label>
                            <input type="text" id="cardholderName" class="mp-input"
                                   placeholder="Como figura en la tarjeta">
                        </div>

                        <div class="mp-row">
                            <div class="mp-field">
                                <label>Tipo de documento</label>
                                <select id="docType" class="mp-select"></select>
                            </div>
                            <div class="mp-field">
                                <label>Número de documento</label>
                                <input type="text" id="docNumber" class="mp-input" placeholder="DNI">
                            </div>
                        </div>

                        <div class="mp-field">
                            <label>Cuotas</label>
                            <select id="installmentsSelect" class="mp-select">
                                <option value="1">1 cuota sin interés</option>
                                <option value="3">3 cuotas</option>
                                <option value="6">6 cuotas</option>
                                <option value="12">12 cuotas</option>
                            </select>
                        </div>

                    </div>
                </div>

                <select id="issuer" name="issuer" style="display:none;"></select>

                <form id="form-pago" method="POST"
                    action="<?= URL ?>checkout/procesar"
                    style="position:absolute;visibility:hidden;width:0;height:0;overflow:hidden;">
                    <input type="hidden" id="token"              name="token">
                    <input type="hidden" id="payment_method_id"  name="payment_method_id">
                    <input type="hidden" id="installments"       name="installments">
                    <input type="hidden" id="identificationType" name="identificationType">
                    <input type="hidden" id="issuer_hidden"      name="issuer">
                    <input type="hidden" id="email_hidden"       name="email">
                    <input type="hidden" id="docNumber_hidden"   name="identificationNumber">
                    <input type="hidden" id="metodo_pago"        name="metodo_pago"   value="">
                    <input type="hidden" id="marca_tarjeta"      name="marca_tarjeta" value="">
                    <input type="hidden" id="device_session_id"  name="device_session_id" value="">
                </form>

            </div>

            <div class="checkout-resumen">
                <div class="cr-titulo">Resumen del pedido</div>

                <?php foreach ($items as $item): ?>
                <div class="cr-item">
                    <div class="cr-item-nombre">
                        <?= htmlspecialchars($item['NombredelProducto']) ?>
                        <small>x<?= $item['Cantidad'] ?></small>
                    </div>
                    <div class="cr-item-precio">
                        $<?= number_format($item['PrecioVenta'] * $item['Cantidad'], 2, ',', '.') ?>
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="cr-sep"></div>

                <div class="cr-subtotal">
                    <span>Subtotal</span>
                    <span>$<?= number_format($subtotal, 2, ',', '.') ?></span>
                </div>

                <?php if (($entrega['costo'] ?? 0) > 0): ?>
                <div class="cr-envio">
                    <span>Envío</span>
                    <span>+ $<?= number_format($entrega['costo'], 2, ',', '.') ?></span>
                </div>
                <?php endif; ?>

                <div class="cr-total">
                    <span>Total</span>
                    <span>$<?= number_format($total, 2, ',', '.') ?></span>
                </div>

                <button type="button" id="btn-pagar" class="btn-pagar" onclick="iniciarPago()">
                    <i class="fas fa-lock"></i>
                    Pagar $<?= number_format($total, 2, ',', '.') ?>
                </button>

                <div class="checkout-seguro">
                    <i class="fas fa-shield-alt"></i>
                    Pago seguro con MercadoPago
                </div>

                <a href="<?= URL ?>checkout/metodo" class="btn-volver">
                    <i class="fas fa-arrow-left"></i> Volver a método de pago
                </a>
            </div>

        </div>
    </div>
</div>

<script src="https://sdk.mercadopago.com/js/v2"></script>
<script src="https://www.mercadopago.com/v2/security.js" view="checkout"></script>
<script>
const mp = new MercadoPago('<?= $public_key ?>', { locale: 'es-AR' });

const metodoPago   = sessionStorage.getItem('metodo_pago')   ?? 'credito';
const marcaTarjeta = sessionStorage.getItem('marca_tarjeta') ?? '';
const INTERESES = { 1: 0.00, 3: 0.12, 6: 0.25, 12: 0.55, 18: 0.85, 24: 1.20 };
const BASE_TOTAL = <?= $total ?>;

function actualizarResumenCuotas() {
    const cuotas    = metodoPago === 'debito' ? 1 : parseInt(document.getElementById('installmentsSelect').value || 1);
    const porc      = INTERESES[cuotas] ?? 0;
    const interes   = Math.round(BASE_TOTAL * porc * 100) / 100;
    const total     = Math.round((BASE_TOTAL + interes) * 100) / 100;
    const cuotaVal  = Math.round((total / cuotas) * 100) / 100;

    let filaInt = document.getElementById('cr-interes');
    if (!filaInt) {
        filaInt = document.createElement('div');
        filaInt.id = 'cr-interes';
        filaInt.className = 'cr-envio';
        document.querySelector('.cr-total').insertAdjacentElement('beforebegin', filaInt);
    }
    if (interes > 0) {
        filaInt.style.display = '';
        filaInt.innerHTML = `<span>Interés (${cuotas} cuotas)</span>
                             <span>+ $${interes.toLocaleString('es-AR',{minimumFractionDigits:2})}</span>`;
    } else {
        filaInt.style.display = 'none';
    }

    document.querySelector('.cr-total span:last-child').textContent =
        '$' + total.toLocaleString('es-AR', {minimumFractionDigits: 2});

    document.getElementById('btn-pagar').innerHTML =
        `<i class="fas fa-lock"></i> Pagar $${total.toLocaleString('es-AR', {minimumFractionDigits: 2})}` +
        (cuotas > 1 ? ` en ${cuotas} cuotas de $${cuotaVal.toLocaleString('es-AR',{minimumFractionDigits:2})}` : '');

    window._totalConInteres = total;
    window._cuotasActuales  = cuotas;
}

document.getElementById('installmentsSelect')?.addEventListener('change', actualizarResumenCuotas);
actualizarResumenCuotas();
const badge = document.getElementById('metodo-badge');
const marcaLabels  = { visa: '💙 Visa', mastercard: '🔴 Mastercard' };
const metodoLabels = { credito: 'Tarjeta de crédito', debito: 'Tarjeta de débito' };
if (metodoPago && badge) {
    const marcaText = marcaTarjeta ? ' — ' + (marcaLabels[marcaTarjeta] ?? marcaTarjeta) : '';
    badge.textContent = (metodoLabels[metodoPago] ?? metodoPago) + marcaText;
    badge.style.display = 'inline-flex';
}

if (metodoPago === 'debito') {
    const cuotasField = document.getElementById('installmentsSelect')?.closest('.mp-field');
    if (cuotasField) cuotasField.style.display = 'none';
}

let camposListos    = { cardNumber: false, expirationDate: false, securityCode: false };
let paymentMethodId = '';
let lastBin         = '';

function todosCargados() {
    return Object.values(camposListos).every(v => v);
}

async function buscarPaymentMethodIdPorBin(bin) {
    if (!bin || bin.length < 6) return '';
    try {
        const r = await fetch(
            `https://api.mercadopago.com/v1/payment_methods/search?public_key=<?= $public_key ?>&bins=${bin.substring(0, 6)}&marketplace=NONE`
        );
        const d = await r.json();
        const results = d.results ?? d;
        if (Array.isArray(results) && results.length > 0) {
            return results[0].id ?? '';
        }
    } catch (err) {
        console.warn('BIN lookup falló:', err);
    }
    return '';
}

const cardNumberElement = mp.fields.create('cardNumber', {
    placeholder: '0000 0000 0000 0000'
});

cardNumberElement.on('ready', () => {
    camposListos.cardNumber = true;
});

cardNumberElement.on('binChange', async (data) => {
    console.log('binChange data completo:', JSON.stringify(data));

    const bin = data.bin ?? '';
    if (bin) lastBin = bin;

    if (data.paymentMethodId) {
        paymentMethodId = data.paymentMethodId;
        console.log('paymentMethodId desde binChange:', paymentMethodId);
        return;
    }

    if (bin.length >= 6) {
        const pmid = await buscarPaymentMethodIdPorBin(bin);
        if (pmid) {
            paymentMethodId = pmid;
            console.log('paymentMethodId desde BIN API lookup:', paymentMethodId);
        }
    }
});

cardNumberElement.mount('cardNumber');

const expirationDateElement = mp.fields.create('expirationDate', { placeholder: 'MM/AA' });
expirationDateElement.on('ready', () => { camposListos.expirationDate = true; });
expirationDateElement.mount('expirationDate');

const securityCodeElement = mp.fields.create('securityCode', { placeholder: 'CVV' });
securityCodeElement.on('ready', () => { camposListos.securityCode = true; });
securityCodeElement.mount('securityCode');

fetch('https://api.mercadopago.com/v1/identification_types?public_key=<?= $public_key ?>')
    .then(r => r.json())
    .then(types => {
        const select = document.getElementById('docType');
        const lista  = Array.isArray(types) ? types : [{ id:'DNI', name:'DNI' }, { id:'CI', name:'Cédula' }];
        lista.forEach(type => {
            const opt = document.createElement('option');
            opt.value = type.id;
            opt.textContent = type.name;
            select.appendChild(opt);
        });
    })
    .catch(() => {
        [{ id:'DNI', name:'DNI' }, { id:'CI', name:'Cédula' }].forEach(type => {
            const opt = document.createElement('option');
            opt.value = type.id;
            opt.textContent = type.name;
            document.getElementById('docType').appendChild(opt);
        });
    });

function mostrarError(msg) {
    let div = document.getElementById('mp-error-msg');
    if (!div) {
        div = document.createElement('div');
        div.id = 'mp-error-msg';
        div.style.cssText = 'background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;border-radius:8px;padding:.8rem 1rem;margin-top:.8rem;font-size:.88rem;';
        document.getElementById('btn-pagar').insertAdjacentElement('afterend', div);
    }
    div.textContent = msg;
    div.style.display = 'block';
}

async function iniciarPago() {
    const btn = document.getElementById('btn-pagar');

    if (!todosCargados()) {
        mostrarError('Los campos de pago aún no están listos. Esperá un momento.');
        return;
    }

    const nombre = document.getElementById('cardholderName').value.trim();
    const docNum = document.getElementById('docNumber').value.trim();
    const email  = document.getElementById('cardholderEmail').value.trim();

    if (!nombre) { mostrarError('Ingresá el nombre como figura en la tarjeta.'); return; }
    if (!docNum)  { mostrarError('Ingresá tu número de documento.'); return; }
    if (!email)   { mostrarError('Ingresá tu correo electrónico.'); return; }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
    const errDiv = document.getElementById('mp-error-msg');
    if (errDiv) errDiv.style.display = 'none';

    try {
        const token = await mp.fields.createCardToken({
            cardholderName:       nombre,
            identificationType:   document.getElementById('docType').value,
            identificationNumber: docNum,
        });

        console.log('Token:', JSON.stringify(token));

        if (!paymentMethodId) {
            const bin = token.first_six_digits ?? lastBin ?? '';
            if (bin) {
                paymentMethodId = await buscarPaymentMethodIdPorBin(bin);
                console.log('paymentMethodId desde BIN token:', paymentMethodId);
            }
        }

        if (!paymentMethodId && token.payment_method_id) {
            paymentMethodId = token.payment_method_id;
            console.log('paymentMethodId desde token directo:', paymentMethodId);
        }

        if (!paymentMethodId) {
            mostrarError('No se pudo detectar el tipo de tarjeta. Reingresá el número completo.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-lock"></i> Pagar $<?= number_format($total, 2, ',', '.') ?>';
            return;
        }

        document.getElementById('token').value             = token.id;
        document.getElementById('payment_method_id').value = paymentMethodId;
        document.getElementById('installments').value       = metodoPago === 'debito'
            ? '1'
            : document.getElementById('installmentsSelect').value;
        document.getElementById('identificationType').value = document.getElementById('docType').value;
        document.getElementById('issuer_hidden').value      = token.issuer_id ?? '';
        document.getElementById('email_hidden').value       = email;
        document.getElementById('docNumber_hidden').value   = docNum;
        document.getElementById('metodo_pago').value        = metodoPago;
        document.getElementById('marca_tarjeta').value      = marcaTarjeta;
        document.getElementById('device_session_id').value  = window.MP_DEVICE_SESSION_ID || '';
        console.log('MP_DEVICE_SESSION_ID:', window.MP_DEVICE_SESSION_ID);
        document.getElementById('form-pago').submit();

    } catch(e) {
        console.error('MP Error:', e);
        const errores = {
            'cardNumber':           'Número de tarjeta inválido.',
            'expirationDate':       'Fecha de vencimiento inválida.',
            'securityCode':         'Código de seguridad inválido.',
            'cardholderName':       'El nombre es obligatorio.',
            'identificationNumber': 'Número de documento inválido.',
        };
        let msg = 'Error al procesar la tarjeta. Verificá los datos e intentá de nuevo.';
        if (Array.isArray(e)) {
            const traducidos = e.map(er => errores[er.field] ?? er.message ?? er.field).join(' ');
            if (traducidos) msg = traducidos;
        } else if (e?.message) {
            msg = e.message;
        }
        mostrarError(msg);
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-lock"></i> Pagar $<?= number_format($total, 2, ',', '.') ?>';
    }
}



</script>

<?php include INCLUDES . 'footer_cliente.php'; ?>