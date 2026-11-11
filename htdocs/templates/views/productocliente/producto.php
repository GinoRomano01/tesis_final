<?php include INCLUDES . 'header_cliente.php'; ?>

<style>
:root {
    --lino: #f7f0e6; --lino2: #ede4d4; --lino3: #e4d8c4;
    --papel: #fdfaf6;
    --caoba: #5c2d0a; --caoba2: #7a3e14;
    --amb: #b8722a; --dorado: #c9a84c;
    --tinta: #2c1a0e; --tinta2: #4a3020;
    --g1: #8a7560; --g2: #b5a08a;
    --borde: #d4c4aa; --borde2: #e8dcc8;
    --verde: #2e6b3a;
    --sombra: rgba(92,45,10,.09);
}

.prod-page {
    background: var(--lino);
    min-height: 100vh;
    font-family: 'Source Sans 3', Georgia, sans-serif;
    color: var(--tinta);
}

.prod-breadcrumb {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem 1.5rem .5rem;
    font-size: .82rem;
    color: var(--g1);
    display: flex;
    align-items: center;
    gap: .4rem;
    flex-wrap: wrap;
}
.prod-breadcrumb a { color: var(--amb); text-decoration: none; font-weight: 600; }
.prod-breadcrumb a:hover { color: var(--caoba); }
.prod-breadcrumb i { font-size: .6rem; }
.prod-main {
    max-width: 1200px;
    margin: 0 auto;
    padding: .5rem 1.5rem 3rem;
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 2.5rem;
    align-items: start;
}
.prod-galeria {
    position: sticky;
    top: 115px;
}

.carrusel-principal {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    background: var(--lino2);
    aspect-ratio: 4/3;
    box-shadow: 0 4px 24px var(--sombra);
}
.carrusel-principal img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: none;
    transition: opacity .3s;
}
.carrusel-principal img.activa { display: block; }
.carrusel-principal .car-ph {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    font-size: 5rem; opacity: .25;
}

.car-btn {
    position: absolute;
    top: 50%; transform: translateY(-50%);
    width: 40px; height: 40px;
    background: rgba(253,250,246,.88);
    border: 1.5px solid var(--borde);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    color: var(--caoba);
    font-size: .85rem;
    transition: background .15s, transform .15s;
    z-index: 5;
    box-shadow: 0 2px 8px rgba(0,0,0,.12);
}
.car-btn:hover { background: var(--papel); transform: translateY(-50%) scale(1.08); }
.car-prev { left: 10px; }
.car-next { right: 10px; }

.car-counter {
    position: absolute;
    bottom: 10px; right: 12px;
    background: rgba(44,26,14,.55);
    color: #fff;
    font-size: .72rem; font-weight: 600;
    padding: 3px 9px;
    border-radius: 20px;
    letter-spacing: .04em;
}

.carrusel-thumbs {
    display: flex;
    gap: .5rem;
    margin-top: .7rem;
    overflow-x: auto;
    padding-bottom: 4px;
}
.carrusel-thumbs::-webkit-scrollbar { height: 4px; }
.carrusel-thumbs::-webkit-scrollbar-thumb { background: var(--borde); border-radius: 2px; }

.thumb {
    width: 68px; height: 58px;
    border-radius: 7px;
    object-fit: cover;
    border: 2px solid transparent;
    cursor: pointer;
    flex-shrink: 0;
    transition: border-color .15s, transform .15s;
    background: var(--lino2);
}
.thumb:hover { transform: translateY(-2px); border-color: var(--amb); }
.thumb.activa { border-color: var(--caoba); box-shadow: 0 0 0 1px var(--caoba); }
.prod-cat-badge {
    display: inline-block;
    background: rgba(92,45,10,.1);
    color: var(--caoba2);
    font-size: .76rem; font-weight: 700;
    padding: 3px 11px;
    border-radius: 20px;
    letter-spacing: .05em;
    margin-bottom: .65rem;
    font-family: 'Source Sans 3', sans-serif;
}
.prod-nombre {
    font-family: 'Playfair Display', serif;
    font-size: 1.9rem;
    font-weight: 700;
    color: var(--tinta);
    line-height: 1.25;
    margin-bottom: .5rem;
}
.prod-tipo {
    font-size: .88rem;
    color: var(--g1);
    font-style: italic;
    margin-bottom: 1rem;
}
.prod-precio-wrap {
    display: flex;
    align-items: baseline;
    gap: .6rem;
    margin-bottom: 1.4rem;
}
.prod-precio {
    font-family: 'Playfair Display', serif;
    font-size: 2.1rem;
    font-weight: 700;
    color: var(--caoba);
}
.prod-precio-label { font-size: .8rem; color: var(--g1); }
.prod-desc {
    font-size: .94rem;
    color: var(--tinta2);
    line-height: 1.7;
    margin-bottom: 1.4rem;
    padding-bottom: 1.4rem;
    border-bottom: 1.5px solid var(--borde2);
}
.prod-compra {
    display: flex;
    align-items: center;
    gap: .75rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}
.qty-ctrl {
    display: flex;
    align-items: center;
    border: 1.5px solid var(--borde);
    border-radius: 9px;
    overflow: hidden;
    background: #fff;
}
.qty-ctrl button {
    width: 36px; height: 42px;
    border: none; background: none;
    cursor: pointer; font-size: 1.1rem;
    color: var(--caoba);
    transition: background .12s;
}
.qty-ctrl button:hover { background: var(--lino2); }
.qty-ctrl input {
    width: 44px; height: 42px;
    border: none;
    border-left: 1px solid var(--borde2);
    border-right: 1px solid var(--borde2);
    text-align: center;
    font-size: .95rem; font-weight: 700;
    color: var(--tinta);
    background: none;
    -moz-appearance: textfield;
}
.qty-ctrl input::-webkit-outer-spin-button,
.qty-ctrl input::-webkit-inner-spin-button { -webkit-appearance: none; }
.btn-agregar {
    flex: 1;
    display: flex; align-items: center; justify-content: center; gap: .45rem;
    padding: .7rem 1.5rem;
    background: var(--caoba);
    color: #fff;
    border: none;
    border-radius: 9px;
    font-family: 'Source Sans 3', sans-serif;
    font-size: .95rem; font-weight: 700;
    cursor: pointer;
    transition: background .15s, transform .12s;
    box-shadow: 0 2px 10px rgba(92,45,10,.28);
}
.btn-agregar:hover { background: var(--caoba2); transform: translateY(-1px); }
.btn-agregar.exito { background: var(--verde); }
.btn-carrito-ir {
    display: flex; align-items: center; justify-content: center; gap: .4rem;
    padding: .68rem 1.2rem;
    background: none;
    color: var(--caoba);
    border: 1.5px solid var(--caoba);
    border-radius: 9px;
    font-family: 'Source Sans 3', sans-serif;
    font-size: .92rem; font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: background .15s;
}
.btn-carrito-ir:hover { background: rgba(92,45,10,.07); color: var(--caoba); }
.prod-chars { margin-top: 1.6rem; }
.pc-titulo {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem; font-weight: 700;
    color: var(--caoba);
    margin-bottom: .8rem;
    display: flex; align-items: center; gap: .5rem;
}
.pc-titulo::after {
    content: ''; flex: 1;
    height: 1.5px; background: var(--borde); margin-left: .3rem;
}
.chars-tabla {
    width: 100%;
    border-collapse: collapse;
    border-radius: 10px;
    overflow: hidden;
    border: 1.5px solid var(--borde);
    font-size: .88rem;
}
.chars-tabla thead th {
    background: linear-gradient(to right, var(--caoba), var(--caoba2));
    color: #fff;
    padding: .6rem 1rem;
    text-align: left;
    font-family: 'Source Sans 3', sans-serif;
    font-weight: 700; font-size: .82rem;
    letter-spacing: .04em;
    text-transform: uppercase;
}
.chars-tabla tbody tr:nth-child(odd)  { background: var(--papel); }
.chars-tabla tbody tr:nth-child(even) { background: var(--lino); }
.chars-tabla tbody tr:hover { background: var(--lino2); }
.chars-tabla td {
    padding: .55rem 1rem;
    color: var(--tinta2);
    border-bottom: 1px solid var(--borde2);
    vertical-align: middle;
}
.chars-tabla td:first-child { font-weight: 700; color: var(--caoba2); width: 45%; }
.chars-tabla tbody tr:last-child td { border-bottom: none; }

.madera-badge {
    display: inline-block;
    background: rgba(184,114,42,.12);
    color: var(--caoba);
    border-radius: 5px;
    padding: 1px 8px;
    font-size: .78rem; font-weight: 700;
    margin-right: .3rem;
}
.prod-relacionados {
    max-width: 1200px;
    margin: 0 auto 4rem;
    padding: 0 1.5rem;
}
.rel-titulo {
    font-family: 'Playfair Display', serif;
    font-size: 1.35rem; font-weight: 700;
    color: var(--caoba);
    margin-bottom: 1.1rem;
    padding-bottom: .7rem;
    border-bottom: 1.5px solid var(--borde);
}
.rel-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.prod-card-min {
    background: var(--papel);
    border: 1.5px solid var(--borde);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px var(--sombra);
    display: flex; flex-direction: column;
    transition: transform .2s, box-shadow .2s;
}
.prod-card-min:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(92,45,10,.14); }
.prod-card-min .pc-img { width: 100%; aspect-ratio: 4/3; background: var(--lino2); overflow: hidden; }
.prod-card-min .pc-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .35s; }
.prod-card-min:hover .pc-img img { transform: scale(1.04); }
.prod-card-min .pc-body { padding: .8rem; flex: 1; display: flex; flex-direction: column; }
.prod-card-min .pc-nombre {
    font-family: 'Playfair Display', serif;
    font-size: .92rem; font-weight: 600; color: var(--tinta);
    margin-bottom: .35rem; line-height: 1.3;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.prod-card-min .pc-precio {
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem; font-weight: 700; color: var(--caoba);
    margin: auto 0 .65rem;
}
.prod-card-min .pc-btns { display: flex; gap: .35rem; }
.btn-ver-min {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: .25rem;
    padding: .38rem .4rem;
    background: var(--papel); color: var(--caoba);
    border: 1.5px solid var(--borde); border-radius: 6px;
    font-size: .78rem; font-weight: 600; text-decoration: none;
    transition: border-color .15s, background .15s;
}
.btn-ver-min:hover { border-color: var(--amb); background: var(--lino2); color: var(--caoba); }
.btn-cart-min {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: .25rem;
    padding: .38rem .4rem;
    background: var(--caoba); color: #fff;
    border: none; border-radius: 6px;
    font-size: .78rem; font-weight: 600;
    cursor: pointer;
    transition: background .15s;
}
.btn-cart-min:hover { background: var(--caoba2); }
@media (max-width: 960px) {
    .prod-main { grid-template-columns: 1fr; gap: 1.5rem; }
    .prod-galeria { position: static; }
    .carrusel-principal { aspect-ratio: 16/10; }
}
@media (max-width: 600px) {
    .prod-nombre { font-size: 1.5rem; }
    .prod-precio { font-size: 1.75rem; }
    .prod-compra { gap: .5rem; }
    .btn-agregar, .btn-carrito-ir { font-size: .85rem; }
}
</style>

<div class="prod-page">

    <div class="prod-breadcrumb">
        <a href="<?= URL ?>"><i class="fas fa-home"></i> Inicio</a>
        <i class="fas fa-chevron-right"></i>
        <a href="<?= URL ?>catalogo">Catálogo</a>
        <?php if (!empty($producto['NombreCategoria'])): ?>
            <i class="fas fa-chevron-right"></i>
            <a href="<?= URL ?>catalogo?categoria=<?= $producto['IdCategoria'] ?>">
                <?= htmlspecialchars($producto['NombreCategoria']) ?>
            </a>
        <?php endif; ?>
        <i class="fas fa-chevron-right"></i>
        <span><?= htmlspecialchars($producto['NombredelProducto']) ?></span>
    </div>

    <div class="prod-main">
        <div class="prod-galeria">
            <div class="carrusel-principal" id="carrusel">

                <?php
                $todasImagenes = [];
                if (!empty($producto['URLImagen'])) {
                    $todasImagenes[] = $producto['URLImagen'];
                }
                foreach ($imagenes as $img) {
                    if ($img['URLImagen'] !== ($producto['URLImagen'] ?? '')) {
                        $todasImagenes[] = $img['URLImagen'];
                    }
                }
                ?>

                <?php if (!empty($todasImagenes)): ?>
                    <?php foreach ($todasImagenes as $idx => $urlImg): ?>
                        <img src="<?= htmlspecialchars($urlImg) ?>"
                             alt="<?= htmlspecialchars($producto['NombredelProducto']) ?> — imagen <?= $idx + 1 ?>"
                             class="<?= $idx === 0 ? 'activa' : '' ?>"
                             loading="<?= $idx === 0 ? 'eager' : 'lazy' ?>">
                    <?php endforeach; ?>

                    <?php if (count($todasImagenes) > 1): ?>
                        <button class="car-btn car-prev" onclick="carMove(-1)">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="car-btn car-next" onclick="carMove(1)">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <span class="car-counter" id="carCounter">1 / <?= count($todasImagenes) ?></span>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="car-ph">🪑</div>
                <?php endif; ?>
            </div>

            <?php if (count($todasImagenes) > 1): ?>
            <div class="carrusel-thumbs">
                <?php foreach ($todasImagenes as $idx => $urlImg): ?>
                    <img class="thumb <?= $idx === 0 ? 'activa' : '' ?>"
                         src="<?= htmlspecialchars($urlImg) ?>"
                         alt="Miniatura <?= $idx + 1 ?>"
                         onclick="carGoTo(<?= $idx ?>)"
                         loading="lazy">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="prod-info">

            <div style="display:flex;gap:.4rem;flex-wrap:wrap;margin-bottom:.6rem;">
                <?php if (!empty($producto['NombreCategoria'])): ?>
                    <span class="prod-cat-badge"><?= htmlspecialchars($producto['NombreCategoria']) ?></span>
                <?php endif; ?>
                <?php if (!empty($producto['NombreTipo'])): ?>
                    <span class="prod-cat-badge" style="background:rgba(184,114,42,.1);color:var(--amb);">
                        <?= htmlspecialchars($producto['NombreTipo']) ?>
                    </span>
                <?php endif; ?>
            </div>

            <h1 class="prod-nombre"><?= htmlspecialchars($producto['NombredelProducto']) ?></h1>

            <?php if (!empty($producto['NombreDiseño'])): ?>
                <div class="prod-tipo">Diseño: <?= htmlspecialchars($producto['NombreDiseño']) ?></div>
            <?php endif; ?>

            <div class="prod-precio-wrap">
                <div class="prod-precio">
                    $<?= number_format($producto['PrecioVenta'] ?? 0, 2, ',', '.') ?>
                </div>
                <span class="prod-precio-label">ARS / unidad</span>
            </div>

            <?php if (!empty($producto['Descripcion'])): ?>
                <div class="prod-desc"><?= nl2br(htmlspecialchars($producto['Descripcion'])) ?></div>
            <?php endif; ?>

            <div class="prod-compra">
                <div class="qty-ctrl">
                    <button onclick="qtyChange(-1)">−</button>
                    <input type="number" id="qtyInput" value="1" min="1" max="99">
                    <button onclick="qtyChange(1)">+</button>
                </div>
                <button class="btn-agregar" id="btnAgregar"
                        onclick="agregarCarrito(<?= $producto['Id'] ?>)">
                    <i class="fas fa-cart-plus"></i> Agregar al carrito
                </button>
            </div>
            <a href="<?= URL ?>carrito" class="btn-carrito-ir">
                <i class="fas fa-shopping-cart"></i> Ver mi carrito
            </a>

            <?php if (!empty($caracteristicas) || !empty($maderas)): ?>
            <div class="prod-chars">
                <div class="pc-titulo"><i class="fas fa-list-ul"></i> Características</div>
                <table class="chars-tabla">
                    <thead>
                        <tr>
                            <th>Característica</th>
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($caracteristicas as $titulo => $valor): ?>
                        <tr>
                            <td><?= htmlspecialchars($titulo) ?></td>
                            <td><?= htmlspecialchars($valor) ?></td>
                        </tr>
                        <?php endforeach; ?>

                        <?php if (!empty($maderas)): ?>
                        <tr>
                            <td>Madera<?= count($maderas) > 1 ? 's' : '' ?></td>
                            <td>
                                <?php foreach ($maderas as $m): ?>
                                    <span class="madera-badge"><?= htmlspecialchars($m['NombreMadera']) ?></span>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                        <?php if ($maderas[0]['Observaciones'] ?? false): ?>
                        <tr>
                            <td>Observaciones</td>
                            <td><?= htmlspecialchars($maderas[0]['Observaciones']) ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <?php if (!empty($relacionados)): ?>
    <div class="prod-relacionados">
        <div class="rel-titulo">También te puede interesar</div>
        <div class="rel-grid">
            <?php foreach ($relacionados as $r): ?>
            <div class="prod-card-min">
                <div class="pc-img">
                    <?php if (!empty($r['URLImagen'])): ?>
                        <img src="<?= htmlspecialchars($r['URLImagen']) ?>"
                             alt="<?= htmlspecialchars($r['NombredelProducto']) ?>"
                             loading="lazy">
                    <?php else: ?>
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:2.5rem;opacity:.3;">🪑</div>
                    <?php endif; ?>
                </div>
                <div class="pc-body">
                    <div class="pc-nombre"><?= htmlspecialchars($r['NombredelProducto']) ?></div>
                    <div class="pc-precio">$<?= number_format($r['PrecioVenta'] ?? 0, 2, ',', '.') ?></div>
                    <div class="pc-btns">
                        <a href="<?= URL ?>productocliente/index/<?= $r['Id'] ?>" class="btn-ver-min">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        <button type="button" class="btn-cart-min"
                                onclick="agregarCarritoRel(<?= $r['Id'] ?>, this)">
                            <i class="fas fa-cart-plus"></i> Agregar
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
const URL_BASE = '<?= URL ?>';
const TOTAL_SLIDES = <?= count($todasImagenes) ?>;
let carActual = 0;

function carGoTo(idx) {
    document.querySelectorAll('#carrusel img').forEach((el, i) => el.classList.toggle('activa', i === idx));
    document.querySelectorAll('.thumb').forEach((el, i) => el.classList.toggle('activa', i === idx));
    carActual = idx;
    const counter = document.getElementById('carCounter');
    if (counter) counter.textContent = (idx + 1) + ' / ' + TOTAL_SLIDES;
}

function carMove(dir) {
    let next = carActual + dir;
    if (next < 0) next = TOTAL_SLIDES - 1;
    if (next >= TOTAL_SLIDES) next = 0;
    carGoTo(next);
}

let touchX = 0;
const car = document.getElementById('carrusel');
if (car) {
    car.addEventListener('touchstart', e => touchX = e.touches[0].clientX, { passive: true });
    car.addEventListener('touchend', e => {
        const dx = e.changedTouches[0].clientX - touchX;
        if (Math.abs(dx) > 40) carMove(dx < 0 ? 1 : -1);
    }, { passive: true });
}

function qtyChange(delta) {
    const inp = document.getElementById('qtyInput');
    let val = parseInt(inp.value) + delta;
    if (val < 1) val = 1;
    if (val > 99) val = 99;
    inp.value = val;
}

function agregarCarrito(idProducto) {
    const btn      = document.getElementById('btnAgregar');
    const cantidad = parseInt(document.getElementById('qtyInput').value) || 1;
    const orig     = btn.innerHTML;
    btn.innerHTML  = '<i class="fas fa-spinner fa-spin"></i> Agregando…';
    btn.disabled   = true;

    fetch(URL_BASE + 'carrito/agregar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: 'id_producto=' + idProducto + '&cantidad=' + cantidad
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            btn.innerHTML = '<i class="fas fa-check"></i> ¡Listo!';
            btn.classList.add('exito');
            actualizarBadge(data.total_items);
            setTimeout(() => {
                btn.innerHTML = orig;
                btn.classList.remove('exito');
                btn.disabled = false;
            }, 2000);
        } else {
            btn.innerHTML = orig;
            btn.disabled  = false;
            if (data.redirect) window.location.href = data.redirect;
            else alert(data.mensaje ?? 'Error al agregar');
        }
    })
    .catch(() => { btn.innerHTML = orig; btn.disabled = false; });
}

function agregarCarritoRel(idProducto, btn) {
    const orig    = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled  = true;

    fetch(URL_BASE + 'carrito/agregar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: 'id_producto=' + idProducto + '&cantidad=1'
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            btn.innerHTML      = '<i class="fas fa-check"></i>';
            btn.style.background = 'var(--verde)';
            actualizarBadge(data.total_items);
            setTimeout(() => {
                btn.innerHTML      = orig;
                btn.style.background = '';
                btn.disabled       = false;
            }, 1800);
        } else {
            btn.innerHTML = orig;
            btn.disabled  = false;
            if (data.redirect) window.location.href = data.redirect;
        }
    })
    .catch(() => { btn.innerHTML = orig; btn.disabled = false; });
}

function actualizarBadge(total) {
    const badge = document.querySelector('.badge-cart');
    if (!badge) return;
    if (total > 0) { badge.textContent = total; badge.style.display = 'flex'; }
    else badge.style.display = 'none';
}
</script>
<?php include VIEWS . 'productocliente' . DS . '_resenas.php'; ?>
<?php include INCLUDES . 'footer_cliente.php'; ?>