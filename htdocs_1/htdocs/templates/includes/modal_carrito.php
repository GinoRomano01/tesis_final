<style>
.modal-carrito-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(28,14,5,.52);
    z-index: 2000;
    backdrop-filter: blur(3px);
    align-items: flex-start;
    justify-content: flex-end;
}
.modal-carrito-overlay.abierto { display: flex; }

.modal-carrito-panel {
    width: 420px;
    max-width: 100vw;
    height: 100vh;
    background: #fdfaf6;
    display: flex;
    flex-direction: column;
    box-shadow: -8px 0 40px rgba(28,14,5,.22);
    transform: translateX(100%);
    transition: transform .3s cubic-bezier(.4,0,.2,1);
}
.modal-carrito-overlay.abierto .modal-carrito-panel {
    transform: translateX(0);
}

.mcar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.3rem;
    border-bottom: 1.5px solid #d4c4aa;
    background: linear-gradient(to right, #5c2d0a, #7a3e14);
    flex-shrink: 0;
}
.mcar-titulo {
    display: flex;
    align-items: center;
    gap: .55rem;
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: #fff;
}
.mcar-titulo i { font-size: .95rem; color: #c9a84c; }
.mcar-badge {
    background: #b8722a;
    color: #fff;
    font-size: .72rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    font-family: 'Source Sans 3', sans-serif;
}
.mcar-cerrar {
    width: 34px; height: 34px;
    display: flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 7px;
    color: #fff;
    cursor: pointer;
    font-size: .9rem;
    transition: background .15s;
}
.mcar-cerrar:hover { background: rgba(255,255,255,.22); }

.mcar-body {
    flex: 1;
    overflow-y: auto;
    padding: .5rem 0;
}
.mcar-body::-webkit-scrollbar { width: 4px; }
.mcar-body::-webkit-scrollbar-thumb { background: #d4c4aa; border-radius: 2px; }

.mcar-loading {
    display: flex; align-items: center; justify-content: center;
    padding: 3rem;
    color: #8a7560;
    font-family: 'Source Sans 3', sans-serif;
    gap: .6rem;
}

.mcar-vacio {
    text-align: center;
    padding: 3rem 1.5rem;
    color: #8a7560;
    font-family: 'Source Sans 3', sans-serif;
}
.mcar-vacio .mv-icon { font-size: 3.5rem; opacity: .25; display: block; margin-bottom: .8rem; }
.mcar-vacio p { font-size: .95rem; margin: 0; }

.mcar-item {
    display: grid;
    grid-template-columns: 64px 1fr auto;
    gap: .8rem;
    align-items: center;
    padding: .85rem 1.2rem;
    border-bottom: 1px solid #ede4d4;
    transition: background .12s;
}
.mcar-item:last-child { border-bottom: none; }
.mcar-item:hover { background: #f7f0e6; }

.mi-img {
    width: 64px; height: 56px;
    border-radius: 7px;
    object-fit: cover;
    background: #ede4d4;
    flex-shrink: 0;
}
.mi-img-ph {
    width: 64px; height: 56px;
    border-radius: 7px;
    background: #ede4d4;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; opacity: .4;
}

.mi-info {}
.mi-nombre {
    font-family: 'Playfair Display', serif;
    font-size: .9rem;
    font-weight: 600;
    color: #2c1a0e;
    margin-bottom: .1rem;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.mi-pu {
    font-size: .76rem;
    color: #8a7560;
    margin-bottom: .25rem;
}
.mi-sub {
    font-family: 'Playfair Display', serif;
    font-size: .95rem;
    font-weight: 700;
    color: #5c2d0a;
}

.mi-ctrl {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .3rem;
}
.mi-cant {
    display: flex;
    align-items: center;
    border: 1.5px solid #d4c4aa;
    border-radius: 7px;
    overflow: hidden;
    background: #fff;
}
.mi-cant button {
    width: 26px; height: 28px;
    border: none; background: none;
    cursor: pointer; font-size: .95rem;
    color: #5c2d0a;
    transition: background .12s;
}
.mi-cant button:hover { background: #ede4d4; }
.mi-cant span {
    min-width: 26px;
    text-align: center;
    font-size: .85rem;
    font-weight: 700;
    color: #2c1a0e;
    border-left: 1px solid #e8dcc8;
    border-right: 1px solid #e8dcc8;
    padding: 0 2px;
    line-height: 28px;
}
.mi-del {
    background: none;
    border: none;
    cursor: pointer;
    color: #b5a08a;
    font-size: .78rem;
    padding: .15rem .4rem;
    border-radius: 4px;
    transition: color .12s, background .12s;
    font-family: 'Source Sans 3', sans-serif;
}
.mi-del:hover { color: #b53030; background: rgba(181,48,48,.07); }

.mcar-footer {
    border-top: 1.5px solid #d4c4aa;
    padding: 1rem 1.2rem;
    background: #fff;
    flex-shrink: 0;
}
.mcar-subtotal {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: .4rem;
    font-family: 'Source Sans 3', sans-serif;
    font-size: .88rem;
    color: #4a3020;
}
.mcar-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: .9rem;
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem;
    font-weight: 700;
    color: #5c2d0a;
    padding-top: .5rem;
    border-top: 1.5px solid #ede4d4;
}
.mcar-btns {
    display: flex;
    flex-direction: column;
    gap: .45rem;
}
.btn-mcar-pagar {
    display: flex; align-items: center; justify-content: center; gap: .4rem;
    padding: .72rem;
    background: #5c2d0a;
    color: #fff;
    border: none;
    border-radius: 9px;
    font-family: 'Source Sans 3', sans-serif;
    font-size: .95rem;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    transition: background .15s;
    box-shadow: 0 2px 8px rgba(92,45,10,.25);
}
.btn-mcar-pagar:hover { background: #7a3e14; color: #fff; }
.btn-mcar-ver {
    display: flex; align-items: center; justify-content: center; gap: .4rem;
    padding: .65rem;
    background: none;
    color: #5c2d0a;
    border: 1.5px solid #d4c4aa;
    border-radius: 9px;
    font-family: 'Source Sans 3', sans-serif;
    font-size: .9rem;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    transition: border-color .15s, background .15s;
}
.btn-mcar-ver:hover { border-color: #b8722a; background: #f7f0e6; color: #5c2d0a; }
.btn-mcar-vaciar {
    background: none;
    border: none;
    color: #b5a08a;
    font-size: .8rem;
    font-family: 'Source Sans 3', sans-serif;
    cursor: pointer;
    text-align: center;
    padding: .2rem;
    transition: color .12s;
}
.btn-mcar-vaciar:hover { color: #b53030; }

@media (max-width: 480px) {
    .modal-carrito-panel { width: 100vw; }
}
</style>

<div class="modal-carrito-overlay" id="modalCarritoOverlay" onclick="cerrarModalSiOverlay(event)">
    <div class="modal-carrito-panel" id="modalCarritoPanel">

        <div class="mcar-header">
            <div class="mcar-titulo">
                <i class="fas fa-shopping-cart"></i>
                Mi Carrito
                <span class="mcar-badge" id="mcarBadge">0</span>
            </div>
            <button class="mcar-cerrar" onclick="cerrarCarritoModal()" title="Cerrar">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="mcar-body" id="mcarBody">
            <div class="mcar-loading">
                <i class="fas fa-spinner fa-spin"></i> Cargando…
            </div>
        </div>

        <div class="mcar-footer" id="mcarFooter" style="display:none;">
            <div class="mcar-subtotal">
                <span>Subtotal</span>
                <span id="mcarSubtotal">$0</span>
            </div>
            <div class="mcar-total">
                <span>Total</span>
                <span id="mcarTotal">$0</span>
            </div>
            <div class="mcar-btns">
                <a href="<?= URL ?>checkout/entrega" class="btn-mcar-pagar">
                    <i class="fas fa-lock"></i> Proceder al pago
                </a>
                <a href="<?= URL ?>carrito" class="btn-mcar-ver">
                    <i class="fas fa-shopping-cart"></i> Ver carrito completo
                </a>
                <button class="btn-mcar-vaciar" onclick="vaciarDesdeModal()">
                    <i class="fas fa-trash"></i> Vaciar carrito
                </button>
            </div>
        </div>

    </div>
</div>

<script>
(function () {
    const URL_BASE = '<?= URL ?>';

    function fmt(n) {
        return '$' + parseFloat(n || 0).toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function renderItems(items) {
        const body = document.getElementById('mcarBody');
        const footer = document.getElementById('mcarFooter');

        if (!items || items.length === 0) {
            body.innerHTML = `
                <div class="mcar-vacio">
                    <span class="mv-icon">🛒</span>
                    <p>Tu carrito está vacío.</p>
                </div>`;
            footer.style.display = 'none';
            return;
        }

        footer.style.display = 'block';

        body.innerHTML = items.map(item => {
            const img = item.URLImagen
                ? `<img class="mi-img" src="${item.URLImagen}" alt="${esc(item.NombredelProducto)}" loading="lazy">`
                : `<div class="mi-img-ph">🪑</div>`;

            return `
            <div class="mcar-item" id="mci-${item.IdProducto}">
                ${img}
                <div class="mi-info">
                    <div class="mi-nombre">${esc(item.NombredelProducto)}</div>
                    <div class="mi-pu">${fmt(item.PrecioVenta)} c/u</div>
                    <div class="mi-sub" id="misub-${item.IdProducto}">${fmt(item.PrecioVenta * item.Cantidad)}</div>
                </div>
                <div class="mi-ctrl">
                    <div class="mi-cant">
                        <button onclick="modalCantidad(${item.IdProducto}, -1, ${item.PrecioVenta})">−</button>
                        <span id="micant-${item.IdProducto}">${item.Cantidad}</span>
                        <button onclick="modalCantidad(${item.IdProducto}, 1, ${item.PrecioVenta})">+</button>
                    </div>
                    <button class="mi-del" onclick="modalEliminar(${item.IdProducto})">
                        <i class="fas fa-trash"></i> quitar
                    </button>
                </div>
            </div>`;
        }).join('');
    }

    function esc(s) {
        const d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    function actualizarTotales(subtotal, total, totalItems) {
        document.getElementById('mcarSubtotal').textContent = fmt(subtotal);
        document.getElementById('mcarTotal').textContent    = fmt(total ?? subtotal);
        document.getElementById('mcarBadge').textContent   = totalItems || 0;

        const badge = document.querySelector('.badge-cart');
        if (badge) {
            if (totalItems > 0) {
                badge.textContent = totalItems;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        window._carritoTotal = totalItems;
    }

    function cargarCarrito() {
        document.getElementById('mcarBody').innerHTML =
            '<div class="mcar-loading"><i class="fas fa-spinner fa-spin"></i> Cargando…</div>';

        fetch(URL_BASE + 'carrito/datos', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                renderItems(data.items);
                actualizarTotales(data.subtotal, data.total, data.cantidad);
            }
        })
        .catch(() => {
            document.getElementById('mcarBody').innerHTML =
                '<div class="mcar-vacio"><span class="mv-icon">⚠️</span><p>Error al cargar el carrito.</p></div>';
        });
    }

    window.openCarritoModal = function () {
        document.getElementById('modalCarritoOverlay').classList.add('abierto');
        document.body.style.overflow = 'hidden';
        cargarCarrito();
    };

    window.cerrarCarritoModal = function () {
        document.getElementById('modalCarritoOverlay').classList.remove('abierto');
        document.body.style.overflow = '';
    };

    window.cerrarModalSiOverlay = function (e) {
        if (e.target === document.getElementById('modalCarritoOverlay')) {
            cerrarCarritoModal();
        }
    };

    window.modalCantidad = function (idProducto, delta, precio) {
        const span = document.getElementById('micant-' + idProducto);
        let val = parseInt(span.textContent) + delta;
        if (val < 1) val = 1;
        span.textContent = val;
        document.getElementById('misub-' + idProducto).textContent = fmt(precio * val);

        fetch(URL_BASE + 'carrito/actualizar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
            body: 'id_producto=' + idProducto + '&cantidad=' + val
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                actualizarTotales(data.subtotal, data.subtotal, data.total_items);
                if (!data.items || data.items.length === 0) renderItems([]);
            }
        });
    };

    window.modalEliminar = function (idProducto) {
        fetch(URL_BASE + 'carrito/eliminar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
            body: 'id_producto=' + idProducto
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                const row = document.getElementById('mci-' + idProducto);
                if (row) row.remove();
                actualizarTotales(data.subtotal, data.subtotal, data.total_items);
                if (!data.items || data.items.length === 0) renderItems([]);
            }
        });
    };

    window.vaciarDesdeModal = function () {
        if (!confirm('¿Vaciar todo el carrito?')) return;
        fetch(URL_BASE + 'carrito/vaciar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
            body: ''
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                renderItems([]);
                actualizarTotales(0, 0, 0);
            }
        });
    };

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') cerrarCarritoModal();
    });
})();
</script>