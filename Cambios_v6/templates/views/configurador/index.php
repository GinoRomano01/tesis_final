<?php include INCLUDES . 'header_cliente.php'; ?>

<head>
    <link rel="stylesheet" href="<?= URL ?>templates/assets/css/catalogo.css">
</head>

<style>
.cfg-page { max-width: 1300px; margin: 0 auto; padding: 1.5rem 1rem 3rem; }
.cfg-hero { text-align: center; margin-bottom: 1.8rem; }
.cfg-hero h1 { font-family: var(--font-display, inherit); font-size: 1.9rem; color: var(--caoba, #6b2d14); margin: 0 0 .4rem; }
.cfg-hero p { color: var(--g1, #8a7560); font-size: .95rem; margin: 0; }

.cfg-layout { display: grid; grid-template-columns: 1fr 300px; gap: 1.3rem; align-items: start; }
@media (max-width: 900px) { .cfg-layout { grid-template-columns: 1fr; } }

.cfg-resultados { background: var(--lino, #faf8f5); border: 1.5px solid var(--borde, #e2d9ce); border-radius: 14px; padding: 1.2rem; position: relative; min-width: 0; }
.cfg-resultados-hd { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
.cfg-resultados-hd h2 { font-size: 1.05rem; color: var(--caoba, #6b2d14); margin: 0; }
.cfg-resultados-count { font-size: .82rem; color: var(--g1, #8a7560); }

.cfg-carrusel-wrap { position: relative; }
.cfg-carrusel { display: flex; gap: 1rem; overflow-x: auto; scroll-snap-type: x mandatory; scroll-behavior: smooth; padding-bottom: .5rem; }
.cfg-carrusel::-webkit-scrollbar { height: 8px; }
.cfg-carrusel::-webkit-scrollbar-thumb { background: var(--borde, #e2d9ce); border-radius: 10px; }
.cfg-carrusel .prod-card { flex: 0 0 230px; scroll-snap-align: start; }

.cfg-flecha {
    position: absolute; top: 50%; transform: translateY(-50%);
    width: 40px; height: 40px; border-radius: 50%; border: 1.5px solid var(--borde, #e2d9ce);
    background: var(--papel, #fff); color: var(--caoba, #6b2d14); font-size: 1.1rem;
    display: flex; align-items: center; justify-content: center; cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,.1); z-index: 5; transition: background .15s;
}
.cfg-flecha:hover { background: var(--lino2, #f3f0ea); }
.cfg-flecha.izq { left: -18px; }
.cfg-flecha.der { right: -18px; }
@media (max-width: 600px) { .cfg-flecha { display: none; } }

.cfg-vacio { text-align: center; padding: 2.5rem 1rem; color: var(--g1, #8a7560); width: 100%; }
.cfg-vacio span { font-size: 2rem; display: block; margin-bottom: .5rem; opacity: .4; }
.cfg-loading { text-align: center; padding: 2rem; color: var(--g1, #8a7560); width: 100%; }
.cfg-selectores {
    background: var(--papel, #fff); border: 1.5px solid var(--borde, #e2d9ce); border-radius: 14px;
    padding: 1.1rem;
}
.cfg-selectores h2 { font-size: 1rem; color: var(--caoba, #6b2d14); margin: 0 0 .9rem; }
.cfg-fld { margin-bottom: .8rem; }
.cfg-fld label { display: block; font-size: .74rem; font-weight: 700; color: var(--tinta2, #554438); text-transform: uppercase; margin-bottom: .3rem; }
.cfg-fld select {
    width: 100%; background: var(--lino, #faf8f5); border: 1.5px solid var(--borde, #e2d9ce);
    border-radius: 8px; padding: .48rem .7rem; font-size: .85rem; color: var(--tinta, #2c1a0e);
}
.cfg-fld select:focus { outline: none; border-color: var(--caoba, #6b2d14); }
.cfg-limpiar { width: 100%; padding: .5rem; border-radius: 8px; border: 1.5px solid var(--borde, #e2d9ce); background: var(--lino2, #f3f0ea); color: var(--caoba, #6b2d14); font-weight: 600; cursor: pointer; font-size: .82rem; margin-top: .3rem; }
.cfg-limpiar:hover { background: var(--borde, #e2d9ce); }
.cfg-recordado { font-size: .72rem; color: var(--g1, #8a7560); text-align: center; margin-top: .6rem; font-style: italic; padding-bottom: .2rem; }
.cfg-colores-container { margin-bottom: 1rem; }
.cfg-colores-hd { font-size: .74rem; font-weight: 700; color: var(--tinta2, #554438); text-transform: uppercase; margin-bottom: .5rem; }
.cfg-colores-carrusel-wrap { position: relative; }
.cfg-colores-carrusel { display: flex; gap: .55rem; overflow-x: auto; padding: 2px 22px; scroll-behavior: smooth; }
.cfg-colores-carrusel::-webkit-scrollbar { height: 6px; }
.cfg-colores-carrusel::-webkit-scrollbar-thumb { background: var(--borde, #e2d9ce); border-radius: 10px; }
.cfg-color-item { flex: 0 0 auto; display: flex; flex-direction: column; align-items: center; gap: .3rem; cursor: pointer; background: none; border: none; padding: 0; }
.cfg-color-swatch { width: 32px; height: 32px; border-radius: 50%; border: 2px solid var(--papel, #fff); box-shadow: 0 0 0 1.5px var(--borde, #e2d9ce); display: block; transition: box-shadow .15s; }
.cfg-color-item.activo .cfg-color-swatch { box-shadow: 0 0 0 2.5px var(--caoba, #6b2d14); }
.cfg-color-item:hover .cfg-color-swatch { box-shadow: 0 0 0 2px var(--amb, #b8722a); }
.cfg-color-nombre { font-size: .66rem; color: var(--g1, #8a7560); white-space: nowrap; }
.cfg-color-item.activo .cfg-color-nombre { color: var(--caoba, #6b2d14); font-weight: 700; }
.cfg-flecha-sm { width: 26px; height: 26px; font-size: .85rem; }
.cfg-flecha-sm.izq { left: -6px; }
.cfg-flecha-sm.der { right: -6px; }
</style>

<div class="cfg-page">

    <div class="cfg-hero">
        <h1>Creá tu mueble</h1>
        <p>Elegí las características que buscás y te mostramos, al instante, los muebles que coinciden.</p>
    </div>

    <div class="cfg-layout">

        <div class="cfg-resultados">
            <div class="cfg-resultados-hd">
                <h2>Muebles que coinciden</h2>
                <span class="cfg-resultados-count" id="cfgCount"></span>
            </div>
            <div class="cfg-carrusel-wrap">
                <button type="button" class="cfg-flecha izq" onclick="cfgScroll(-1)" aria-label="Anterior">‹</button>
                <div class="cfg-carrusel prod-grid" id="cfgResultados">
                    <div class="cfg-loading">Cargando muebles...</div>
                </div>
                <button type="button" class="cfg-flecha der" onclick="cfgScroll(1)" aria-label="Siguiente">›</button>
            </div>
        </div>

        <div class="cfg-selectores">
            <h2>Elegí las specs</h2>

            <div class="cfg-fld">
                <label>Tipo de producto</label>
                <select id="cfgTipo">
                    <option value="">Cualquiera</option>
                    <?php foreach ($tipos as $t): ?>
                    <option value="<?= $t['Id'] ?>"><?= htmlspecialchars($t['Nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="cfg-fld">
                <label>Madera</label>
                <select id="cfgMadera">
                    <option value="">Cualquiera</option>
                    <?php foreach ($maderas as $m): ?>
                    <option value="<?= $m['Id'] ?>"><?= htmlspecialchars($m['Nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="cfg-fld">
                <label>Diseño</label>
                <select id="cfgDiseno">
                    <option value="">Cualquiera</option>
                    <?php foreach ($disenos as $d): ?>
                    <option value="<?= $d['Id'] ?>"><?= htmlspecialchars($d['Nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="cfg-fld">
                <label>Acabado</label>
                <select id="cfgAcabado">
                    <option value="">Cualquiera</option>
                    <?php foreach ($acabados as $a): ?>
                    <option value="<?= $a['Id'] ?>"><?= htmlspecialchars($a['Nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="cfg-colores-container" id="cfgColoresContainer" style="display:none;">
                <div class="cfg-colores-hd" id="cfgColoresHd"></div>
                <div class="cfg-colores-carrusel-wrap">
                    <button type="button" class="cfg-flecha cfg-flecha-sm izq" onclick="cfgScrollColores(-1)" aria-label="Anterior">‹</button>
                    <div class="cfg-colores-carrusel" id="cfgColoresLista"></div>
                    <button type="button" class="cfg-flecha cfg-flecha-sm der" onclick="cfgScrollColores(1)" aria-label="Siguiente">›</button>
                </div>
            </div>
            <div class="cfg-fld">
                <label>Herraje</label>
                <select id="cfgHerraje">
                    <option value="">Cualquiera</option>
                    <?php foreach ($herrajes as $h): ?>
                    <option value="<?= $h['Id'] ?>"><?= htmlspecialchars($h['Nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="cfg-fld">
                <label>Almacenamiento</label>
                <select id="cfgAlmacen">
                    <option value="">Cualquiera</option>
                    <?php foreach ($almacenes as $al): ?>
                    <option value="<?= $al['Id'] ?>"><?= htmlspecialchars($al['Nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="button" class="cfg-limpiar" onclick="cfgLimpiar()">Limpiar selección</button>
            <div class="cfg-recordado" id="cfgRecordado" style="display:none;">✓ Recuperamos tu selección anterior</div>
        </div>

    </div>
</div>

<script>
const CFG_STORAGE_KEY = 'sanplacido_configurador';
const CFG_HORAS_VIGENCIA = 6; 

const cfgSelects = {
    tipo:    document.getElementById('cfgTipo'),
    diseno:  document.getElementById('cfgDiseno'),
    acabado: document.getElementById('cfgAcabado'),
    herraje: document.getElementById('cfgHerraje'),
    almacen: document.getElementById('cfgAlmacen'),
    madera:  document.getElementById('cfgMadera'),
};

let cfgColorSeleccionado = '';

function cfgLeerSeleccion() {
    const val = {};
    for (const k in cfgSelects) val[k] = cfgSelects[k].value;
    val.color = cfgColorSeleccionado;
    return val;
}

function cfgGuardarSeleccion() {
    const data = { valores: cfgLeerSeleccion(), guardado: Date.now() };
    try { localStorage.setItem(CFG_STORAGE_KEY, JSON.stringify(data)); } catch (e) {}
}

function cfgRecuperarSeleccion() {
    try {
        const raw = localStorage.getItem(CFG_STORAGE_KEY);
        if (!raw) return false;
        const data = JSON.parse(raw);
        const horasPasadas = (Date.now() - data.guardado) / 1000 / 60 / 60;
        if (horasPasadas > CFG_HORAS_VIGENCIA) {
            localStorage.removeItem(CFG_STORAGE_KEY);
            return false;
        }
        let algoRestaurado = false;
        for (const k in cfgSelects) {
            if (data.valores[k]) {
                cfgSelects[k].value = data.valores[k];
                algoRestaurado = true;
            }
        }
        if (data.valores.color) {
            cfgColorSeleccionado = data.valores.color;
            algoRestaurado = true;
        }
        return algoRestaurado;
    } catch (e) { return false; }
}

function cfgLimpiar() {
    for (const k in cfgSelects) cfgSelects[k].value = '';
    cfgColorSeleccionado = '';
    localStorage.removeItem(CFG_STORAGE_KEY);
    document.getElementById('cfgRecordado').style.display = 'none';
    document.getElementById('cfgColoresContainer').style.display = 'none';
    cfgBuscar();
}

function cfgBuscar() {
    const v = cfgLeerSeleccion();
    cfgGuardarSeleccion();

    const qs = new URLSearchParams({
        tipo: v.tipo, diseno: v.diseno, acabado: v.acabado,
        herraje: v.herraje, almacen: v.almacen, madera: v.madera,
        color: v.color,
    }).toString();

    const cont = document.getElementById('cfgResultados');
    cont.innerHTML = '<div class="cfg-loading">Buscando...</div>';

    fetch('<?= URL ?>configurador/buscar?' + qs)
        .then(r => r.text())
        .then(html => {
            cont.innerHTML = html;
            const n = cont.querySelectorAll('.prod-card').length;
            document.getElementById('cfgCount').textContent = n > 0 ? (n + ' resultado' + (n !== 1 ? 's' : '')) : '';
        })
        .catch(() => {
            cont.innerHTML = '<div class="cfg-vacio"><span>⚠️</span><p>No pudimos cargar los resultados. Probá de nuevo.</p></div>';
        });
}

function cfgScroll(dir) {
    const cont = document.getElementById('cfgResultados');
    cont.scrollBy({ left: dir * 260, behavior: 'smooth' });
}

function cfgScrollColores(dir) {
    const cont = document.getElementById('cfgColoresLista');
    cont.scrollBy({ left: dir * 120, behavior: 'smooth' });
}

for (const k in cfgSelects) {
    if (k === 'acabado') continue; 
    cfgSelects[k].addEventListener('change', cfgBuscar);
}

function cfgActualizarColores() {
    const idAcabado = cfgSelects.acabado.value;
    const cont      = document.getElementById('cfgColoresContainer');
    const hd        = document.getElementById('cfgColoresHd');
    const lista     = document.getElementById('cfgColoresLista');

    if (!idAcabado) { cont.style.display = 'none'; lista.innerHTML = ''; return; }

    fetch('<?= URL ?>configurador/colores?idAcabado=' + idAcabado)
        .then(r => r.json())
        .then(data => {
            if (!data.colores || data.colores.length === 0) {
                cont.style.display = 'none';
                lista.innerHTML = '';
                return;
            }
            hd.textContent = 'Color de ' + data.nombreAcabado;
            lista.innerHTML = '';
            data.colores.forEach(c => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'cfg-color-item' + (String(c.Id) === String(cfgColorSeleccionado) ? ' activo' : '');
                btn.title = c.Nombre;
                btn.innerHTML =
                    '<span class="cfg-color-swatch" style="background:' + c.ColorHex + ';"></span>' +
                    '<span class="cfg-color-nombre">' + c.Nombre + '</span>';
                btn.addEventListener('click', () => cfgElegirColor(c.Id, btn));
                lista.appendChild(btn);
            });
            cont.style.display = 'block';
        })
        .catch(() => { cont.style.display = 'none'; });
}

function cfgElegirColor(idColor, btnClickeado) {
    const yaEstaba = String(cfgColorSeleccionado) === String(idColor);
    cfgColorSeleccionado = yaEstaba ? '' : idColor;

    document.querySelectorAll('#cfgColoresLista .cfg-color-item').forEach(el => el.classList.remove('activo'));
    if (!yaEstaba) btnClickeado.classList.add('activo');

    cfgBuscar();
}

cfgSelects.acabado.addEventListener('change', function () {
    cfgColorSeleccionado = '';
    cfgActualizarColores();
    cfgBuscar();
});

document.addEventListener('DOMContentLoaded', function () {
    const restaurado = cfgRecuperarSeleccion();
    if (restaurado) document.getElementById('cfgRecordado').style.display = 'block';
    cfgActualizarColores();
    cfgBuscar();
});

function agregarCarrito(idProducto, btn) {
    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;

    fetch('<?= URL ?>carrito/agregar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_producto=' + idProducto + '&cantidad=1'
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            btn.innerHTML = '<i class="fas fa-check"></i> Listo';
            btn.style.background = 'var(--verde)';
            const badge = document.querySelector('.badge-cart');
            if (badge && data.total_items) {
                badge.textContent = data.total_items;
                badge.style.display = 'flex';
            }
            setTimeout(() => {
                btn.innerHTML = orig;
                btn.style.background = '';
                btn.disabled = false;
            }, 1800);
        } else {
            btn.innerHTML = orig;
            btn.disabled = false;
            alert(data.mensaje ?? 'Error al agregar al carrito');
        }
    })
    .catch(() => {
        btn.innerHTML = orig;
        btn.disabled = false;
    });
}
</script>

<?php include INCLUDES . 'footer_cliente.php'; ?>
