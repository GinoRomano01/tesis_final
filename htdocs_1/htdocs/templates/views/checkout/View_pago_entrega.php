<?php include INCLUDES . 'header_cliente.php'; ?>

<style>
:root {
    --lino: #f7f0e6; --lino2: #ede4d4; --papel: #fdfaf6;
    --caoba: #5c2d0a; --caoba2: #7a3e14; --amb: #b8722a; --dorado: #c9a84c;
    --tinta: #2c1a0e; --tinta2: #4a3020; --g1: #8a7560; --borde: #d4c4aa;
    --verde: #2e6b3a; --rojo: #c0392b;
}
.entrega-page {
    background: var(--lino);
    min-height: 100vh;
    padding: 2rem 0 4rem;
    font-family: 'Source Sans 3', Georgia, sans-serif;
}
.entrega-inner { max-width: 820px; margin: 0 auto; padding: 0 1.5rem; }
.entrega-titulo {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem; font-weight: 700; color: var(--caoba);
    margin-bottom: .5rem;
    display: flex; align-items: center; gap: .6rem;
}
.entrega-steps {
    display: flex; align-items: center; gap: .5rem;
    margin-bottom: 2rem; font-size: .82rem; color: var(--g1);
}
.step { display: flex; align-items: center; gap: .3rem; }
.step.active { color: var(--caoba); font-weight: 700; }
.step-sep { color: var(--borde); }

.entrega-panel {
    background: var(--papel);
    border: 1.5px solid var(--borde);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.ep-seccion { padding: 1.4rem; border-bottom: 1px solid var(--borde); }
.ep-seccion:last-child { border-bottom: none; }
.ep-titulo {
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem; font-weight: 700; color: var(--caoba);
    margin-bottom: 1.2rem;
    display: flex; align-items: center; gap: .5rem;
}
.ep-titulo i { color: var(--amb); font-size: .9rem; }

.opciones-entrega { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }


.opcion-card {
    border: 2px solid var(--borde); border-radius: 10px;
    padding: 1.2rem; cursor: pointer;
    transition: all .2s; background: #fff; position: relative;
}
.opcion-card:hover { border-color: var(--amb); }
.opcion-card.selected { border-color: var(--caoba); background: var(--lino); }


.opcion-card.bloqueada {
    opacity: .55;
    cursor: not-allowed;
    background: #f5f5f5;
    border-color: var(--borde) !important;
}
.opcion-card.bloqueada:hover { border-color: var(--borde) !important; }

.opcion-card input[type="radio"] {
    position: absolute; top: 1rem; right: 1rem;
    width: 18px; height: 18px; accent-color: var(--caoba);
}
.opcion-icono { font-size: 2rem; margin-bottom: .6rem; display: block; }
.opcion-titulo {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; font-weight: 700; color: var(--caoba); margin-bottom: .3rem;
}
.opcion-desc { font-size: .82rem; color: var(--g1); line-height: 1.4; }
.opcion-precio { margin-top: .6rem; font-size: .9rem; font-weight: 700; }
.opcion-precio.gratis { color: var(--verde); }
.opcion-precio.pago   { color: var(--caoba); }


.aviso-sin-cobertura {
    display: flex; align-items: flex-start; gap: .6rem;
    background: #fff8e1; border: 1.5px solid #ffe082;
    border-radius: 9px; padding: .85rem 1rem;
    font-size: .84rem; color: #7b5800; margin-top: .9rem;
    line-height: 1.45;
}
.aviso-sin-cobertura i { color: #f9a825; margin-top: .1rem; flex-shrink: 0; }

.dir-actual {
    background: var(--lino2); border: 1.5px solid var(--borde);
    border-radius: 8px; padding: 1rem 1.2rem; margin-bottom: 1rem;
    display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem;
}
.dir-datos { flex: 1; }
.dir-nombre { font-weight: 700; color: var(--tinta); font-size: .95rem; }
.dir-calle  { color: var(--tinta2); font-size: .88rem; margin-top: .2rem; }
.dir-localidad { color: var(--g1); font-size: .82rem; margin-top: .1rem; }
.btn-cambiar-dir {
    background: none; border: 1.5px solid var(--borde);
    border-radius: 7px; padding: .4rem .9rem;
    font-size: .82rem; color: var(--amb); font-weight: 600;
    cursor: pointer; white-space: nowrap; transition: border-color .15s;
}
.btn-cambiar-dir:hover { border-color: var(--amb); }

.nueva-dir { display: none; }
.nueva-dir.show { display: block; }

.nd-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .8rem; }
.nd-full  { grid-column: 1 / -1; }
.form-label-sp {
    display: block; font-size: .82rem; font-weight: 600;
    color: var(--tinta2); margin-bottom: .3rem;
}
.form-input-sp {
    width: 100%; background: #fff;
    border: 1.5px solid var(--borde); border-radius: 8px;
    height: 40px; padding: 0 .9rem; font-size: .9rem; color: var(--tinta);
    box-sizing: border-box; transition: border-color .15s;
}
.form-input-sp:focus { outline: none; border-color: var(--amb); }
.form-select-sp {
    width: 100%; background: #fff;
    border: 1.5px solid var(--borde); border-radius: 8px;
    height: 40px; padding: 0 .9rem; font-size: .9rem; color: var(--tinta);
    cursor: pointer; box-sizing: border-box;
}

.resumen-entrega {
    background: var(--papel); border: 1.5px solid var(--borde);
    border-radius: 12px; padding: 1.3rem;
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
.btn-siguiente {
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    width: 100%; margin-top: 1.2rem; padding: .85rem;
    background: var(--caoba); color: #fff; border: none; border-radius: 9px;
    font-size: 1rem; font-weight: 700; cursor: pointer; transition: background .15s;
    box-shadow: 0 2px 10px rgba(92,45,10,.3);
}
.btn-siguiente:hover { background: var(--caoba2); }
.btn-volver {
    display: flex; align-items: center; gap: .35rem;
    margin-top: .85rem; font-size: .84rem; color: var(--amb);
    text-decoration: none; font-weight: 600;
}
.btn-volver:hover { color: var(--caoba); }

@media (max-width: 600px) {
    .opciones-entrega { grid-template-columns: 1fr; }
    .nd-grid { grid-template-columns: 1fr; }
    .nd-full  { grid-column: 1; }
}
</style>

<div class="entrega-page">
    <div class="entrega-inner">

        <h1 class="entrega-titulo">
            <i class="fas fa-truck"></i> Forma de entrega
        </h1>

        <div class="entrega-steps">
            <div class="step active"><i class="fas fa-truck"></i> Entrega</div>
            <div class="step-sep">›</div>
            <div class="step"><i class="fas fa-credit-card"></i> Pago</div>
            <div class="step-sep">›</div>
            <div class="step"><i class="fas fa-check"></i> Confirmación</div>
        </div>

        <?= Toast::flash() ?>

        <form method="POST" action="<?= URL ?>checkout/guardarEntrega" id="form-entrega">

        <div class="entrega-panel">

            
            <div class="ep-seccion">
                <div class="ep-titulo"><i class="fas fa-box"></i> ¿Cómo querés recibir tu pedido?</div>

                <div class="opciones-entrega">

                    
                    <label class="opcion-card selected" id="card-retiro" onclick="seleccionar('retiro')">
                        <input type="radio" name="tipo_entrega" value="1" checked>
                        <span class="opcion-icono"></span>
                        <div class="opcion-titulo">Retiro en sucursal</div>
                        <div class="opcion-desc">Retirá tu pedido en nuestro local. Te avisamos por mail cuando esté listo.</div>
                        <div class="opcion-precio gratis">✓ Sin costo adicional</div>
                    </label>

                    
                    <?php if ($puedeEnvio): ?>
                    <label class="opcion-card" id="card-envio" onclick="seleccionar('envio')">
                        <input type="radio" name="tipo_entrega" value="2">
                        <span class="opcion-icono"></span>
                        <div class="opcion-titulo">Envío a domicilio</div>
                        <div class="opcion-desc">Recibí tu pedido directamente en tu domicilio dentro de Córdoba capital.</div>
                        <div class="opcion-precio pago">+ $20.000</div>
                    </label>
                    <?php else: ?>
                    
                    <div class="opcion-card bloqueada" id="card-envio">
                        <input type="radio" name="tipo_entrega" value="2" disabled style="display:none;">
                        <span class="opcion-icono"></span>
                        <div class="opcion-titulo">Envío a domicilio</div>
                        <div class="opcion-desc">Recibí tu pedido directamente en tu domicilio.</div>
                        <div class="opcion-precio pago">+ $20.000</div>
                    </div>
                    <?php endif; ?>

                </div>

                <?php if (!$puedeEnvio): ?>
                <div class="aviso-sin-cobertura">
                    <i class="fas fa-info-circle"></i>
                    <span>
                        El envío a domicilio está disponible únicamente para <strong>Córdoba capital</strong>
                        (código postal 5000–5009). Tu localidad registrada no tiene cobertura de envío.
                        Podés actualizar tu domicilio en
                        <a href="<?= URL ?>cliente/editar" style="color:var(--caoba);font-weight:700;">Mi perfil</a>
                        si vivís en Córdoba capital.
                    </span>
                </div>
                <?php endif; ?>

            </div>

            
            <?php if ($puedeEnvio): ?>
            <div class="ep-seccion" id="seccion-dir" style="display:none;">
                <div class="ep-titulo"><i class="fas fa-map-marker-alt"></i> Dirección de entrega</div>

                <input type="hidden" name="usar_nueva_dir" id="usar_nueva_dir" value="0">

                <?php if ($domicilio && $domicilio['Calle']): ?>
                <div class="dir-actual" id="dir-actual">
                    <div class="dir-datos">
                        <div class="dir-nombre">
                            <?= htmlspecialchars(
                                mb_convert_case($domicilio['Nombre']   ?? '', MB_CASE_TITLE, 'UTF-8') . ' ' .
                                mb_convert_case($domicilio['Apellido'] ?? '', MB_CASE_TITLE, 'UTF-8')
                            ) ?>
                        </div>
                        <div class="dir-calle">
                            <?= htmlspecialchars($domicilio['Calle']) ?>
                            <?= htmlspecialchars($domicilio['Numero']) ?>
                            <?php if (!empty($domicilio['Barrio'])): ?>
                                — <?= htmlspecialchars($domicilio['Barrio']) ?>
                            <?php endif; ?>
                        </div>
                        <div class="dir-localidad">
                            <?= htmlspecialchars($domicilio['Localidad'] ?? '') ?>
                            <?php if (!empty($domicilio['CodigoPostal'])): ?>
                                (CP <?= htmlspecialchars($domicilio['CodigoPostal']) ?>)
                            <?php endif; ?>
                        </div>
                    </div>
                    <button type="button" class="btn-cambiar-dir" onclick="mostrarNuevaDireccion()">
                        <i class="fas fa-edit"></i> Cambiar
                    </button>
                </div>
                <?php else: ?>
                    <script>document.getElementById('usar_nueva_dir').value = '1';</script>
                <?php endif; ?>

                
                <div class="nueva-dir <?= (!$domicilio || !$domicilio['Calle']) ? 'show' : '' ?>" id="nueva-dir">
                    <div style="margin-bottom:.8rem;">
                        <label class="form-label-sp">Tipo de domicilio</label>
                        <select name="nd_tipo_domicilio" id="nd_tipo_domicilio" class="form-select-sp"
                                onchange="mostrarCamposDomicilio(this.value)">
                            <option value="">Seleccioná...</option>
                            <?php foreach ($tiposDomicilio as $td): ?>
                                <option value="<?= $td['Id'] ?>"><?= htmlspecialchars($td['Nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div id="campos-domicilio"></div>
                </div>
            </div>
            <?php endif; ?>

        </div>

        
        <div class="resumen-entrega">
            <div class="re-fila">
                <span>Subtotal productos</span>
                <span>$<?= number_format($subtotal, 2, ',', '.') ?></span>
            </div>
            <div class="re-fila" id="fila-envio" style="display:none;">
                <span>Costo de envío</span>
                <span style="color:var(--caoba);font-weight:700;">+ $20.000</span>
            </div>
            <div class="re-fila total">
                <span>Total</span>
                <span id="total-final">$<?= number_format($subtotal, 2, ',', '.') ?></span>
            </div>

            <button type="submit" class="btn-siguiente">
                Continuar al pago <i class="fas fa-arrow-right"></i>
            </button>
            <a href="<?= URL ?>carrito" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver al carrito
            </a>
        </div>

        </form>
    </div>
</div>

<script>
const SUBTOTAL    = <?= $subtotal ?>;
const COSTO_ENVIO = 20000;
const PUEDE_ENVIO = <?= $puedeEnvio ? 'true' : 'false' ?>;

function seleccionar(tipo) {
    if (tipo === 'envio' && !PUEDE_ENVIO) return;

    document.getElementById('card-retiro').classList.toggle('selected', tipo === 'retiro');

    const cardEnvio = document.getElementById('card-envio');
    if (cardEnvio && PUEDE_ENVIO) {
        cardEnvio.classList.toggle('selected', tipo === 'envio');
    }

    const radioRetiro = document.querySelector('input[name="tipo_entrega"][value="1"]');
    const radioEnvio  = document.querySelector('input[name="tipo_entrega"][value="2"]');
    if (radioRetiro) radioRetiro.checked = (tipo === 'retiro');
    if (radioEnvio && PUEDE_ENVIO)  radioEnvio.checked  = (tipo === 'envio');

    const secDir = document.getElementById('seccion-dir');
    if (secDir) secDir.style.display = (tipo === 'envio' && PUEDE_ENVIO) ? 'block' : 'none';

    const filaEnvio = document.getElementById('fila-envio');
    if (filaEnvio) filaEnvio.style.display = (tipo === 'envio' && PUEDE_ENVIO) ? 'flex' : 'none';

    const total = (tipo === 'envio' && PUEDE_ENVIO) ? SUBTOTAL + COSTO_ENVIO : SUBTOTAL;
    document.getElementById('total-final').textContent =
        '$' + total.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function mostrarNuevaDireccion() {
    document.getElementById('nueva-dir').classList.add('show');
    document.getElementById('dir-actual').style.display = 'none';
    document.getElementById('usar_nueva_dir').value = '1';
}

function mostrarCamposDomicilio(tipo) {
    const cont = document.getElementById('campos-domicilio');
    let html = '';

    if (tipo === '1') {
        html = `
        <div class="nd-grid">
            <div>
                <label class="form-label-sp">Calle *</label>
                <input type="text" name="nd_calle" class="form-input-sp" required>
            </div>
            <div>
                <label class="form-label-sp">Número *</label>
                <input type="number" name="nd_numero" class="form-input-sp" required>
            </div>
        </div>`;
    } else if (tipo === '2') {
        html = `
        <div class="nd-grid">
            <div>
                <label class="form-label-sp">Calle *</label>
                <input type="text" name="nd_calle" class="form-input-sp" required>
            </div>
            <div>
                <label class="form-label-sp">Número *</label>
                <input type="number" name="nd_numero" class="form-input-sp" required>
            </div>
            <div>
                <label class="form-label-sp">Piso</label>
                <input type="number" name="nd_piso" class="form-input-sp">
            </div>
            <div>
                <label class="form-label-sp">N° Departamento</label>
                <input type="text" name="nd_numero_piso" class="form-input-sp">
            </div>
        </div>`;
    } else if (tipo === '3') {
        html = `
        <div class="nd-grid">
            <div class="nd-full">
                <label class="form-label-sp">Nombre del barrio / country *</label>
                <input type="text" name="nd_country" class="form-input-sp" required>
            </div>
            <div class="nd-full">
                <label class="form-label-sp">Manzana / Barrio</label>
                <input type="text" name="nd_barrio" class="form-input-sp">
            </div>
            <div>
                <label class="form-label-sp">Calle *</label>
                <input type="text" name="nd_calle" class="form-input-sp" required>
            </div>
            <div>
                <label class="form-label-sp">Número *</label>
                <input type="number" name="nd_numero" class="form-input-sp" required>
            </div>
        </div>`;
    }

    cont.innerHTML = html;
}

document.getElementById('form-entrega').addEventListener('submit', function (e) {
    const tipo      = document.querySelector('input[name="tipo_entrega"]:checked')?.value;
    const usarNueva = document.getElementById('usar_nueva_dir')?.value;

    if (tipo === '2' && !PUEDE_ENVIO) {
        e.preventDefault();
        alert('El envío a domicilio no está disponible para tu localidad.');
        return;
    }

    if (tipo === '2' && usarNueva === '1') {
        const tipoDom = document.getElementById('nd_tipo_domicilio')?.value;
        if (!tipoDom) {
            e.preventDefault();
            alert('Por favor seleccioná el tipo de domicilio.');
            return;
        }
        const calle = document.querySelector('input[name="nd_calle"]');
        if (calle && !calle.value.trim()) {
            e.preventDefault();
            alert('Por favor ingresá la calle.');
            return;
        }
    }
});
</script>

<?php include INCLUDES . 'footer_cliente.php'; ?>