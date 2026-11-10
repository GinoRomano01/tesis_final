<?php include INCLUDES . 'header_cliente.php'; ?>

<head>
    <link rel="stylesheet" href="<?= URL ?>templates/assets/css/catalogo.css">
</head>

<div class="cat-page">

    <div class="cat-search-wrap">
        <form class="cat-search-inner" method="GET" action="<?= URL ?>catalogo">
            
            <?php foreach (['orden','categoria','madera','diseño','acabado','herraje','almacen','tipo'] as $k): ?>
                <?php if (!empty($filtros[$k])): ?>
                    <input type="hidden" name="<?= $k ?>" value="<?= htmlspecialchars($filtros[$k]) ?>">
                <?php endif; ?>
            <?php endforeach; ?>
            <div class="cat-search-box">
                <i class="fas fa-search cat-search-icon"></i>
                <input type="text" name="buscar"
                       value="<?= htmlspecialchars($buscar) ?>"
                       placeholder="Buscar por nombre o descripción…">
            </div>
            <button type="submit" class="btn-buscar">Buscar</button>
            <span class="cat-total">
                <b><?= $total ?></b> resultado<?= $total !== 1 ? 's' : '' ?>
            </span>
        </form>
    </div>

    
    <div class="cat-layout">

        
        <aside class="cat-sidebar">
            <div class="sf-hd">
                <span class="sf-hd-title"> Filtros</span>
                <a href="<?= URL ?>catalogo<?= !empty($buscar) ? '?buscar='.urlencode($buscar) : '' ?>" class="btn-limpiar">
                    Limpiar todo
                </a>
            </div>

            
            <div class="sf-sec open" id="sf-orden">
                <div class="sf-sec-hd" onclick="toggleSf('sf-orden')">
                    <span>Ordenar por</span>
                    <i class="fas fa-chevron-down sf-chev"></i>
                </div>
                <div class="sf-orden sf-body" style="display:flex;">
                    <?php
                    $ordenes = [
                        'nuevo'       => ' Más nuevos',
                        'precio_asc'  => '↑ Precio: menor a mayor',
                        'precio_desc' => '↓ Precio: mayor a menor',
                    ];
                    foreach ($ordenes as $val => $label): ?>
                        <label class="<?= $orden === $val ? 'active' : '' ?>">
                            <input type="radio" name="orden" value="<?= $val ?>"
                                   <?= $orden === $val ? 'checked' : '' ?>
                                   onchange="aplicarFiltro('orden', '<?= $val ?>')">
                            <?= $label ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            
            <?php if (!empty($categorias)): ?>
            <div class="sf-sec <?= !empty($filtros['categoria']) ? 'open' : '' ?>" id="sf-cat">
                <div class="sf-sec-hd" onclick="toggleSf('sf-cat')">
                    <span>Categoría</span>
                    <i class="fas fa-chevron-down sf-chev"></i>
                </div>
                <div class="sf-body">
                    <div class="sf-list">
                        <?php foreach ($categorias as $c): ?>
                        <label class="sf-item <?= $filtros['categoria'] == $c['Id'] ? 'checked' : '' ?>">
                            <input type="radio" name="categoria" value="<?= $c['Id'] ?>"
                                   <?= $filtros['categoria'] == $c['Id'] ? 'checked' : '' ?>
                                   onchange="aplicarFiltro('categoria', <?= $c['Id'] ?>)">
                            <?= htmlspecialchars($c['Nombre']) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if (!empty($tipos)): ?>
            <div class="sf-sec <?= !empty($filtros['tipo']) ? 'open' : '' ?>" id="sf-tipo">
                <div class="sf-sec-hd" onclick="toggleSf('sf-tipo')">
                    <span>Tipo de producto</span>
                    <i class="fas fa-chevron-down sf-chev"></i>
                </div>
                <div class="sf-body">
                    <div class="sf-list">
                        <?php foreach ($tipos as $t): ?>
                        <label class="sf-item <?= $filtros['tipo'] == $t['Id'] ? 'checked' : '' ?>">
                            <input type="radio" name="tipo" value="<?= $t['Id'] ?>"
                                   <?= $filtros['tipo'] == $t['Id'] ? 'checked' : '' ?>
                                   onchange="aplicarFiltro('tipo', <?= $t['Id'] ?>)">
                            <?= htmlspecialchars($t['Nombre']) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if (!empty($diseños)): ?>
            <div class="sf-sec <?= !empty($filtros['diseño']) ? 'open' : '' ?>" id="sf-dis">
                <div class="sf-sec-hd" onclick="toggleSf('sf-dis')">
                    <span>Diseño</span>
                    <i class="fas fa-chevron-down sf-chev"></i>
                </div>
                <div class="sf-body">
                    <div class="sf-list">
                        <?php foreach ($diseños as $d): ?>
                        <label class="sf-item <?= $filtros['diseño'] == $d['Id'] ? 'checked' : '' ?>">
                            <input type="radio" name="diseño" value="<?= $d['Id'] ?>"
                                   <?= $filtros['diseño'] == $d['Id'] ? 'checked' : '' ?>
                                   onchange="aplicarFiltro('diseño', <?= $d['Id'] ?>)">
                            <?= htmlspecialchars($d['Nombre']) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if (!empty($acabados)): ?>
            <div class="sf-sec <?= !empty($filtros['acabado']) ? 'open' : '' ?>" id="sf-aca">
                <div class="sf-sec-hd" onclick="toggleSf('sf-aca')">
                    <span>Acabado</span>
                    <i class="fas fa-chevron-down sf-chev"></i>
                </div>
                <div class="sf-body">
                    <div class="sf-list">
                        <?php foreach ($acabados as $a): ?>
                        <label class="sf-item <?= $filtros['acabado'] == $a['Id'] ? 'checked' : '' ?>">
                            <input type="radio" name="acabado" value="<?= $a['Id'] ?>"
                                   <?= $filtros['acabado'] == $a['Id'] ? 'checked' : '' ?>
                                   onchange="aplicarFiltro('acabado', <?= $a['Id'] ?>)">
                            <?= htmlspecialchars($a['Nombre']) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if (!empty($maderas)): ?>
            <div class="sf-sec <?= !empty($filtros['madera']) ? 'open' : '' ?>" id="sf-mad">
                <div class="sf-sec-hd" onclick="toggleSf('sf-mad')">
                    <span>Madera</span>
                    <i class="fas fa-chevron-down sf-chev"></i>
                </div>
                <div class="sf-body">
                    <div class="sf-list">
                        <?php foreach ($maderas as $m): ?>
                        <label class="sf-item <?= $filtros['madera'] == $m['Id'] ? 'checked' : '' ?>">
                            <input type="radio" name="madera" value="<?= $m['Id'] ?>"
                                   <?= $filtros['madera'] == $m['Id'] ? 'checked' : '' ?>
                                   onchange="aplicarFiltro('madera', <?= $m['Id'] ?>)">
                            <?= htmlspecialchars($m['Nombre']) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if (!empty($herrajes)): ?>
            <div class="sf-sec <?= !empty($filtros['herraje']) ? 'open' : '' ?>" id="sf-her">
                <div class="sf-sec-hd" onclick="toggleSf('sf-her')">
                    <span>Herraje</span>
                    <i class="fas fa-chevron-down sf-chev"></i>
                </div>
                <div class="sf-body">
                    <div class="sf-list">
                        <?php foreach ($herrajes as $h): ?>
                        <label class="sf-item <?= $filtros['herraje'] == $h['Id'] ? 'checked' : '' ?>">
                            <input type="radio" name="herraje" value="<?= $h['Id'] ?>"
                                   <?= $filtros['herraje'] == $h['Id'] ? 'checked' : '' ?>
                                   onchange="aplicarFiltro('herraje', <?= $h['Id'] ?>)">
                            <?= htmlspecialchars($h['Nombre']) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if (!empty($almacenes)): ?>
            <div class="sf-sec <?= !empty($filtros['almacen']) ? 'open' : '' ?>" id="sf-alm">
                <div class="sf-sec-hd" onclick="toggleSf('sf-alm')">
                    <span>Almacenamiento</span>
                    <i class="fas fa-chevron-down sf-chev"></i>
                </div>
                <div class="sf-body">
                    <div class="sf-list">
                        <?php foreach ($almacenes as $al): ?>
                        <label class="sf-item <?= $filtros['almacen'] == $al['Id'] ? 'checked' : '' ?>">
                            <input type="radio" name="almacen" value="<?= $al['Id'] ?>"
                                   <?= $filtros['almacen'] == $al['Id'] ? 'checked' : '' ?>
                                   onchange="aplicarFiltro('almacen', <?= $al['Id'] ?>)">
                            <?= htmlspecialchars($al['Nombre']) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </aside>

        
        <main class="cat-main">

            
            <div class="cat-main-hd">
                <h2>
                    <?php if (!empty($buscar)): ?>
                        Resultados para «<?= htmlspecialchars($buscar) ?>»
                    <?php elseif (!empty($filtros['categoria'])): ?>
                        <?php $nc = array_column($categorias,'Nombre','Id'); ?>
                        <?= htmlspecialchars($nc[$filtros['categoria']] ?? 'Categoría') ?>
                    <?php else: ?>
                        Todos los productos
                    <?php endif; ?>
                </h2>
                <span style="font-size:.82rem;color:var(--g1);">
                    Página <?= $pagina ?> de <?= $totalPags ?>
                </span>
            </div>

            
            <?php
            $pillLabels = [
                'categoria' => ['label'=>'Categoría','items'=>array_column($categorias,'Nombre','Id')],
                'tipo'      => ['label'=>'Tipo','items'=>array_column($tipos,'Nombre','Id')],
                'diseño'    => ['label'=>'Diseño','items'=>array_column($diseños,'Nombre','Id')],
                'acabado'   => ['label'=>'Acabado','items'=>array_column($acabados,'Nombre','Id')],
                'madera'    => ['label'=>'Madera','items'=>array_column($maderas,'Nombre','Id')],
                'herraje'   => ['label'=>'Herraje','items'=>array_column($herrajes,'Nombre','Id')],
                'almacen'   => ['label'=>'Almacen.','items'=>array_column($almacenes,'Nombre','Id')],
            ];
            $hayFiltros = false;
            foreach ($pillLabels as $k => $meta) {
                if (!empty($filtros[$k])) { $hayFiltros = true; break; }
            }
            if ($hayFiltros):
            ?>
            <div class="filtros-activos">
                <?php foreach ($pillLabels as $k => $meta):
                    if (empty($filtros[$k])) continue;
                    $nombre = $meta['items'][$filtros[$k]] ?? $filtros[$k];
                    
                    $qp = array_merge($filtros, ['pagina'=>1]);
                    unset($qp[$k]);
                    if (!empty($buscar)) $qp['buscar'] = $buscar;
                    $urlSin = '?' . http_build_query(array_filter($qp));
                ?>
                <span class="fa-tag-pill">
                    <?= $meta['label'] ?>: <?= htmlspecialchars($nombre) ?>
                    <a href="<?= URL ?>catalogo<?= $urlSin ?>" title="Quitar filtro">✕</a>
                </span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            
            <div class="prod-grid">
            <?php if (empty($productos)): ?>
                <div class="cat-vacio">
                    <span class="cv-icon">🔍</span>
                    <p>No encontramos productos con los filtros seleccionados.</p>
                    <a href="<?= URL ?>catalogo">Ver todos los productos</a>
                </div>
            <?php else: ?>
                <?php foreach ($productos as $p): ?>
                <div class="prod-card">
                    <div class="pc-img">
                        <?php if (!empty($p['URLImagen'])): ?>
                            <img src="<?= htmlspecialchars($p['URLImagen']) ?>"
                                 alt="<?= htmlspecialchars($p['NombredelProducto']) ?>"
                                 loading="lazy">
                        <?php else: ?>
                            <div class="pc-ph">🪑</div>
                        <?php endif; ?>
                        <?php if (!empty($p['NombreCategoria'])): ?>
                            <span class="pc-cat"><?= htmlspecialchars($p['NombreCategoria']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="pc-body">
                        <div class="pc-nombre"><?= htmlspecialchars($p['NombredelProducto']) ?></div>
                        <?php if (!empty($p['NombreTipo'])): ?>
                            <div class="pc-tipo"><?= htmlspecialchars($p['NombreTipo']) ?></div>
                        <?php endif; ?>
                        <div class="pc-precio">
                            $<?= number_format($p['PrecioVenta'] ?? 0, 2, ',', '.') ?>
                        </div>
                        <div class="pc-btns">
                            <a href="<?= URL ?>productocliente/index/<?= $p['Id'] ?>" class="btn-ver">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <button type="button" class="btn-cart"
                                    onclick="agregarCarrito(<?= $p['Id'] ?>, this)">
                                <i class="fas fa-cart-plus"></i> Agregar
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
            </div>

            
            <?php if ($totalPags > 1): ?>
            <nav class="cat-pag" aria-label="Paginación">
                <?php
                
                $qBase = array_filter(array_merge($filtros, ['buscar'=>$buscar,'orden'=>$orden]));
                $urlPag = function(int $p) use ($qBase): string {
                    global $URL; 
                    return '?' . http_build_query(array_merge($qBase, ['pagina'=>$p]));
                };
                ?>
                
                <?php if ($pagina > 1): ?>
                    <a href="<?= URL ?>catalogo<?= $urlPag($pagina-1) ?>" class="pag-btn">
                        <i class="fas fa-chevron-left" style="font-size:.7rem;"></i>
                    </a>
                <?php else: ?>
                    <span class="pag-btn disabled"><i class="fas fa-chevron-left" style="font-size:.7rem;"></i></span>
                <?php endif; ?>

                <?php
                
                $rango = 3;
                $desde = max(1, $pagina - $rango);
                $hasta = min($totalPags, $pagina + $rango);
                if ($desde > 1): ?>
                    <a href="<?= URL ?>catalogo<?= $urlPag(1) ?>" class="pag-btn">1</a>
                    <?php if ($desde > 2): ?><span class="pag-sep">…</span><?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $desde; $i <= $hasta; $i++): ?>
                    <a href="<?= URL ?>catalogo<?= $urlPag($i) ?>"
                       class="pag-btn <?= $i === $pagina ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($hasta < $totalPags): ?>
                    <?php if ($hasta < $totalPags - 1): ?><span class="pag-sep">…</span><?php endif; ?>
                    <a href="<?= URL ?>catalogo<?= $urlPag($totalPags) ?>" class="pag-btn"><?= $totalPags ?></a>
                <?php endif; ?>

                
                <?php if ($pagina < $totalPags): ?>
                    <a href="<?= URL ?>catalogo<?= $urlPag($pagina+1) ?>" class="pag-btn">
                        <i class="fas fa-chevron-right" style="font-size:.7rem;"></i>
                    </a>
                <?php else: ?>
                    <span class="pag-btn disabled"><i class="fas fa-chevron-right" style="font-size:.7rem;"></i></span>
                <?php endif; ?>
            </nav>
            <?php endif; ?>

        </main>
    </div>
</div>

<script>

function aplicarFiltro(nombre, valor) {
    const url = new URL(window.location.href);
    url.searchParams.set(nombre, valor);
    url.searchParams.set('pagina', '1');
    window.location.href = url.toString();
}

function toggleSf(id) {
    const sec = document.getElementById(id);
    sec.classList.toggle('open');
    const body = sec.querySelector('.sf-body');
    body.style.display = sec.classList.contains('open') ? 'block' : 'none';
}

document.querySelectorAll('.sf-sec.open .sf-body').forEach(b => {
    b.style.display = 'block';
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