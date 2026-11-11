<?php include INCLUDES . 'header_cliente.php'; ?>

<head>
    <link rel="stylesheet" href="<?= URL ?>templates/assets/css/checkout.css">
</head>

<div class="carrito-page">
    <div class="carrito-inner">
        <h1 class="carrito-titulo">
            <i class="fas fa-shopping-cart"></i>
            Mi Carrito
            <?php if (!empty($items)): ?>
                <span>(<?= count($items) ?> producto<?= count($items) !== 1 ? 's' : '' ?>)</span>
            <?php endif; ?>
        </h1>

        <?php if (empty($items)): ?>
        <div class="carrito-items">
            <div class="carrito-vacio">
                <span class="cv-icon">🛒</span>
                <p>Tu carrito está vacío.</p>
                <a href="<?= URL ?>catalogo">Ver el catálogo</a>
            </div>
        </div>

        <?php else: ?>
        <div class="carrito-layout">
 
            <div class="carrito-items" id="carritoItemsLista">
                <?php foreach ($items as $item): ?>
                <div class="carrito-item" id="item-<?= $item['IdProducto'] ?>">
                    <?php if (!empty($item['URLImagen'])): ?>
                        <img class="ci-img" src="<?= htmlspecialchars($item['URLImagen']) ?>"
                             alt="<?= htmlspecialchars($item['NombredelProducto']) ?>">
                    <?php else: ?>
                        <div class="ci-img-ph">🪑</div>
                    <?php endif; ?>

                    <div class="ci-info">
                        <div class="ci-nombre"><?= htmlspecialchars($item['NombredelProducto']) ?></div>
                        <?php if ($item['NombreTipo']): ?>
                            <div class="ci-tipo"><?= htmlspecialchars($item['NombreTipo']) ?></div>
                        <?php endif; ?>
                        <a href="<?= URL ?>producto/<?= $item['IdProducto'] ?>"
                           style="font-size:.78rem;color:var(--amb);margin-top:.2rem;display:inline-block;">
                            Ver detalle →
                        </a>
                    </div>

                    <div class="ci-precio-unit">
                        <b>$<?= number_format($item['PrecioVenta'], 2, ',', '.') ?></b>
                        c/u
                    </div>

                    <div class="ci-cantidad">
                        <button onclick="cambiarCantidad(<?= $item['IdProducto'] ?>, -1)" title="Restar">−</button>
                        <input type="number" min="1" max="99"
                               value="<?= $item['Cantidad'] ?>"
                               id="cant-<?= $item['IdProducto'] ?>"
                               onchange="setCantidad(<?= $item['IdProducto'] ?>, this.value)">
                        <button onclick="cambiarCantidad(<?= $item['IdProducto'] ?>, 1)" title="Sumar">+</button>
                    </div>

                    <div class="ci-subtotal" id="sub-<?= $item['IdProducto'] ?>">
                        $<?= number_format($item['PrecioVenta'] * $item['Cantidad'], 2, ',', '.') ?>
                    </div>

                    <button class="ci-eliminar" title="Eliminar"
                            onclick="eliminarItem(<?= $item['IdProducto'] ?>)">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="carrito-resumen" id="resumenCarrito">
                <div class="cr-titulo">Resumen del pedido</div>
                <div class="cr-fila">
                    <span>Subtotal</span>
                    <span id="resSubtotal">$<?= number_format($subtotal, 2, ',', '.') ?></span>
                </div>
                <div class="cr-fila">
                    <span>Envío</span>
                    <span style="color:var(--verde);font-weight:600;">A calcular</span>
                </div>
                <div class="cr-fila total">
                    <span>Total</span>
                    <span id="resTotal">$<?= number_format($total, 2, ',', '.') ?></span>
                </div>

                <a href="<?= URL ?>checkout/entrega" class="btn-comprar">
                    <i class="fas fa-lock"></i> Proceder al pago
                </a>
                <button class="btn-vaciar" onclick="vaciarCarrito()">
                    <i class="fas fa-trash"></i> Vaciar carrito
                </button>
                <a href="<?= URL ?>catalogo" class="btn-seguir">
                    <i class="fas fa-arrow-left"></i> Seguir comprando
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
const URL_BASE = '<?= URL ?>';

function fmtPrecio(n) {
    return '$' + parseFloat(n).toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function actualizarResumen(data) {
    const sub = document.getElementById('resSubtotal');
    const tot = document.getElementById('resTotal');
    if (sub) sub.textContent = fmtPrecio(data.subtotal);
    if (tot) tot.textContent = fmtPrecio(data.total ?? data.subtotal);

    const badge = document.querySelector('.badge-cart');
    if (badge) {
        if (data.total_items > 0) {
            badge.textContent = data.total_items;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }

    if (!data.items || data.items.length === 0) {
        location.reload();
    }
}

function cambiarCantidad(idProducto, delta) {
    const input = document.getElementById('cant-' + idProducto);
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    input.value = val;
    setCantidad(idProducto, val);
}

function setCantidad(idProducto, cantidad) {
    cantidad = Math.max(1, parseInt(cantidad));
    fetch(URL_BASE + 'carrito/actualizar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: 'id_producto=' + idProducto + '&cantidad=' + cantidad
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            const item = data.items.find(i => i.IdProducto == idProducto);
            if (item) {
                const sub = document.getElementById('sub-' + idProducto);
                if (sub) sub.textContent = fmtPrecio(item.PrecioVenta * item.Cantidad);
                const inp = document.getElementById('cant-' + idProducto);
                if (inp) inp.value = item.Cantidad;
            }
            actualizarResumen(data);
        }
    });
}

function eliminarItem(idProducto) {
    if (!confirm('¿Eliminás este producto del carrito?')) return;
    fetch(URL_BASE + 'carrito/eliminar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: 'id_producto=' + idProducto
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            const row = document.getElementById('item-' + idProducto);
            if (row) row.remove();
            actualizarResumen(data);
        }
    });
}

function vaciarCarrito() {
    if (!confirm('¿Vaciar todo el carrito?')) return;
    fetch(URL_BASE + 'carrito/vaciar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: ''
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) location.reload();
    });
}
</script>

<?php include INCLUDES . 'footer_cliente.php'; ?>