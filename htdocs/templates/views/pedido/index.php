<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<main class="content">

<link rel="stylesheet" href="<?= URL ?>templates/assets/css/pedidos.css?v=1">

<?php
$clsBadge = function(string $e): string {
    return match($e) {
        'Pendiente'     => 'pendiente',
        'En producción' => 'en-curso',
        'Listo'         => 'finalizada',
        'Cancelado'     => 'cancelada',
        default         => 'all',
    };
};
$totalGen = array_sum(array_intersect_key($conteos, array_flip($estadosKanban)));
$estadosAsignables = puedeCancelarPedido() ? $estadosKanban : array_diff($estadosKanban, ['Cancelado']);

$ordenesDisponibles = [
    'fecha_desc'  => 'Fecha: más nueva primero',
    'fecha_asc'   => 'Fecha: más antigua primero',
    'precio_desc' => 'Precio: mayor a menor',
    'precio_asc'  => 'Precio: menor a mayor',
    'tiempo_desc' => 'Tiempo de fabricación: mayor a menor',
    'tiempo_asc'  => 'Tiempo de fabricación: menor a mayor',
];

$urlFiltro = function(string $est = '') use ($buscar, $orden): string {
    $qs = [];
    if ($est !== '') $qs['estado'] = $est;
    if ($buscar !== '') $qs['buscar'] = $buscar;
    if ($orden !== 'fecha_desc') $qs['orden'] = $orden;
    return URL . 'pedido' . (!empty($qs) ? '?' . http_build_query($qs) : '');
};

$urlPagCol = function(string $claveCol, int $pagina) use ($buscar, $orden): string {
    $qs = [];
    if ($buscar !== '') $qs['buscar'] = $buscar;
    if ($orden !== 'fecha_desc') $qs['orden'] = $orden;
    foreach (['pendiente', 'en_produccion', 'cancelado', 'listo'] as $k) {
        $key = 'p_' . $k;
        if ($k === $claveCol) {
            $qs[$key] = $pagina;
        } elseif (!empty($_GET[$key])) {
            $qs[$key] = $_GET[$key];
        }
    }
    return URL . 'pedido?' . http_build_query($qs);
};

$urlPagGeneral = function(int $pagina) use ($buscar, $estadoFiltro, $orden): string {
    $qs = ['estado' => $estadoFiltro, 'pagina' => $pagina];
    if ($buscar !== '') $qs['buscar'] = $buscar;
    if ($orden !== 'fecha_desc') $qs['orden'] = $orden;
    return URL . 'pedido?' . http_build_query($qs);
};

$renderCard = function(array $p) use ($clsBadge) {
    $dias = floor(($p['HorasDesdeVenta'] ?? 0) / 24);
    $tiempoFab = (float)($p['TiempoFabricacionTotal'] ?? 0);
    $pillCls = $clsBadge($p['Estado'] ?? '');
    ?>
    <div class="ped-card" draggable="true" data-id="<?= $p['Id'] ?>" data-estado="<?= htmlspecialchars($p['Estado']) ?>"
         onclick="abrirEditar(<?= htmlspecialchars(json_encode($p)) ?>)">
        <input type="checkbox" class="row-checkbox ped-card-check" value="<?= $p['Id'] ?>"
               onclick="event.stopPropagation()" onchange="actualizarBarraMasiva()">
        <div class="ped-card-top">
            <div class="ped-card-cliente"><?= htmlspecialchars($p['ClienteNombre'] . ' ' . $p['ClienteApellido']) ?></div>
            <span class="ped-pill <?= $pillCls ?>"><?= htmlspecialchars($p['Estado']) ?></span>
        </div>
        <div class="ped-card-rows">
            <div class="ped-card-row"><span class="k">Venta</span><span class="v">#<?= htmlspecialchars($p['NumerodeVenta']) ?></span></div>
            <div class="ped-card-row"><span class="k">Productos</span><span class="v" title="<?= htmlspecialchars($p['Productos'] ?? '—') ?>"><?= htmlspecialchars($p['Productos'] ?? '—') ?></span></div>
            <?php if ($tiempoFab > 0): ?>
            <div class="ped-card-row"><span class="k">Fabricación</span><span class="v"><?= number_format($tiempoFab, 0) ?>h</span></div>
            <?php endif; ?>
            <div class="ped-card-row"><span class="k">Hace</span><span class="v"><?= $dias ?> día<?= $dias !== 1 ? 's' : '' ?></span></div>
            <div class="ped-card-row"><span class="k">Total</span><span class="v monto">$<?= number_format($p['MontoTotal'] ?? 0, 0, ',', '.') ?></span></div>
        </div>
    </div>
    <?php
};

$configColumnas = [
    'pendiente'     => 'Pendiente',
    'en_produccion' => 'En producción',
    'cancelado'     => 'Cancelado',
    'listo'         => 'Listo',
];
?>

<div class="eg-page">

    <div class="eg-hd">
        <h1>Pedidos</h1>
        <button class="btn-eg btn-eg-primary" onclick="abrirCrear()">Nuevo pedido</button>
    </div>

    <?= Toast::flash() ?>

    <?php if (!empty($ventasElegibles)): ?>
    <div class="modal-info pd-aviso-ventas">
        Hay <strong><?= count($ventasElegibles) ?></strong>
        venta<?= count($ventasElegibles) > 1 ? 's' : '' ?> disponible<?= count($ventasElegibles) > 1 ? 's' : '' ?>
        para convertir en pedido.
    </div>
    <?php endif; ?>

    <div class="stats-row">
        <a href="<?= $urlFiltro() ?>" class="stat-card c-todos <?= $estadoFiltro === '' ? 'activo' : '' ?>">
            <div class="stat-card-icon">📋</div>
            <div><div class="stat-card-num"><?= $totalGen ?></div><div class="stat-card-label">Todos</div></div>
        </a>
        <?php
        $iconosEstado = ['Pendiente' => '⏳', 'En producción' => '🔨', 'Cancelado' => '✕', 'Listo' => '✓'];
        $clasesEstado = ['Pendiente' => 'c-pendiente', 'En producción' => 'c-produccion', 'Cancelado' => 'c-cancelado', 'Listo' => 'c-listo'];
        foreach ($estadosKanban as $est):
        ?>
        <a href="<?= $urlFiltro($est) ?>" class="stat-card <?= $clasesEstado[$est] ?> <?= $estadoFiltro === $est ? 'activo' : '' ?>">
            <div class="stat-card-icon"><?= $iconosEstado[$est] ?></div>
            <div><div class="stat-card-num"><?= $conteos[$est] ?? 0 ?></div><div class="stat-card-label"><?= htmlspecialchars($est) ?></div></div>
        </a>
        <?php endforeach; ?>
    </div>

    <form class="eg-filters" method="GET" action="<?= URL ?>pedido">
        <?php if ($estadoFiltro !== ''): ?><input type="hidden" name="estado" value="<?= htmlspecialchars($estadoFiltro) ?>"><?php endif; ?>
        <input type="text" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
               placeholder="Buscar por cliente, producto, responsable o número de venta...">
        <label for="selOrden">Ordenar por</label>
        <select name="orden" id="selOrden" onchange="this.form.submit()">
            <?php foreach ($ordenesDisponibles as $val => $label): ?>
            <option value="<?= $val ?>" <?= $orden === $val ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-eg btn-eg-primary">Buscar</button>
        <?php if (!empty($buscar) || $estadoFiltro !== '' || $orden !== 'fecha_desc'): ?>
        <a href="<?= URL ?>pedido" class="btn-eg btn-eg-secondary">Limpiar</a>
        <?php endif; ?>
    </form>

    <div class="pd-seleccion">
        <button type="button" class="btn-eg btn-eg-secondary btn-eg-sm" id="btnToggleSelection" onclick="toggleSelectionMode()">
            Selección múltiple
        </button>
    </div>

    <div class="bulk-actions-bar" id="bulkActionsBar">
        <div class="bulk-count">
            <span id="selectedCount">0</span> elemento(s) seleccionado(s)
        </div>
        <div class="bulk-botones">
            <button type="button" class="btn-eg btn-eg-primary btn-eg-sm" onclick="ejecutarAccionMasiva('cambiar_estado')">Cambiar estado</button>
            <?php if (puedeEliminar()): ?>
            <button type="button" class="btn-eg btn-eg-peligro btn-eg-sm" onclick="ejecutarAccionMasiva('baja')">Dar de baja</button>
            <?php endif; ?>
        </div>
    </div>

    <div id="zonaSeleccion">
    <?php if ($estadoFiltro !== ''): ?>

        <?php if (empty($items)): ?>
            <div class="eg-vacio"><span>—</span><p>No hay pedidos en "<?= htmlspecialchars($estadoFiltro) ?>"<?= !empty($buscar) ? ' para "'.htmlspecialchars($buscar).'"' : '' ?>.</p></div>
        <?php else: ?>
            <div class="seccion-unica-grid">
                <?php foreach ($items as $p) $renderCard($p); ?>
            </div>
            <?php if ($totalPaginas > 1): ?>
            <nav class="pag-general">
                <?php if ($pagina > 1): ?><a href="<?= $urlPagGeneral($pagina - 1) ?>">‹</a><?php endif; ?>
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <?php if ($i === $pagina): ?><span class="actual"><?= $i ?></span>
                    <?php else: ?><a href="<?= $urlPagGeneral($i) ?>"><?= $i ?></a><?php endif; ?>
                <?php endfor; ?>
                <?php if ($pagina < $totalPaginas): ?><a href="<?= $urlPagGeneral($pagina + 1) ?>">›</a><?php endif; ?>
            </nav>
            <?php endif; ?>
        <?php endif; ?>

    <?php else: ?>

        <p class="drag-hint">💡 Arrastrá una tarjeta a otra columna (o a "Listos") para cambiarle el estado.</p>

        <div class="kanban-row">
            <?php foreach ($estadosColumnas as $est):
                $clave = array_search($est, $configColumnas);
                $col = $columnas[$est];
            ?>
            <div class="kanban-col" data-estado="<?= htmlspecialchars($est) ?>" data-clave="<?= $clave ?>">
                <div class="kanban-col-hd">
                    <span class="kanban-col-hd-title"><?= htmlspecialchars($est) ?></span>
                    <span class="kanban-col-hd-count"><?= $col['total'] ?></span>
                </div>
                <div class="kanban-col-cards">
                    <?php if (empty($col['items'])): ?>
                        <div class="kanban-col-vacio">Sin pedidos acá</div>
                    <?php else: ?>
                        <?php foreach ($col['items'] as $p) $renderCard($p); ?>
                    <?php endif; ?>
                </div>
                <?php if ($col['totalPaginas'] > 1): ?>
                <nav class="kanban-col-pag">
                    <?php if ($col['pagina'] > 1): ?><a href="<?= $urlPagCol($clave, $col['pagina'] - 1) ?>">‹</a><?php endif; ?>
                    <span class="actual"><?= $col['pagina'] ?>/<?= $col['totalPaginas'] ?></span>
                    <?php if ($col['pagina'] < $col['totalPaginas']): ?><a href="<?= $urlPagCol($clave, $col['pagina'] + 1) ?>">›</a><?php endif; ?>
                </nav>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="seccion-listo" data-estado="Listo" data-clave="listo">
            <div class="seccion-listo-hd">
                <div>
                    <span class="seccion-listo-hd-title">Listos</span>
                    <small>Producción terminada — a la espera de generar la entrega</small>
                </div>
                <span class="kanban-col-hd-count"><?= $seccionListo['total'] ?></span>
            </div>
            <div class="seccion-listo-grid">
                <?php if (empty($seccionListo['items'])): ?>
                    <div class="seccion-listo-vacio">No hay pedidos listos por ahora</div>
                <?php else: ?>
                    <?php foreach ($seccionListo['items'] as $p) $renderCard($p); ?>
                <?php endif; ?>
            </div>
            <?php if ($seccionListo['totalPaginas'] > 1): ?>
            <nav class="pag-general">
                <?php if ($seccionListo['pagina'] > 1): ?><a href="<?= $urlPagCol('listo', $seccionListo['pagina'] - 1) ?>">‹</a><?php endif; ?>
                <?php for ($i = 1; $i <= $seccionListo['totalPaginas']; $i++): ?>
                    <?php if ($i === $seccionListo['pagina']): ?><span class="actual"><?= $i ?></span>
                    <?php else: ?><a href="<?= $urlPagCol('listo', $i) ?>"><?= $i ?></a><?php endif; ?>
                <?php endfor; ?>
                <?php if ($seccionListo['pagina'] < $seccionListo['totalPaginas']): ?><a href="<?= $urlPagCol('listo', $seccionListo['pagina'] + 1) ?>">›</a><?php endif; ?>
            </nav>
            <?php endif; ?>
        </div>

    <?php endif; ?>
    </div>

</div>

<div class="modal-bg" id="modalMasivoEstado">
    <div class="modal">
        <div class="modal-hd"><h3>Cambiar estado masivo</h3><button class="modal-close" onclick="cerrarMasivoEstado()">✕</button></div>
        <form method="POST" action="<?= URL ?>pedido/estadoMasivo">
            <input type="hidden" name="ids" id="masivoIds">
            <div class="modal-body">
                <div class="modal-info">Se actualizará el estado de <strong id="masivoCountText">0</strong> pedido(s) seleccionado(s).</div>
                <div class="fld">
                    <label>Nuevo Estado</label>
                    <select name="Estado" required>
                        <?php foreach ($estadosAsignables as $est): ?>
                        <option value="<?= htmlspecialchars($est) ?>"><?= htmlspecialchars($est) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-ft">
                <button type="button" class="btn-eg btn-eg-secondary" onclick="cerrarMasivoEstado()">Cancelar</button>
                <button type="submit" class="btn-eg btn-eg-primary">Aplicar cambios</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-bg" id="modalCrear">
    <div class="modal">
        <div class="modal-hd"><h3>Nuevo pedido</h3><button class="modal-close" onclick="cerrarCrear()">✕</button></div>
        <form method="POST" action="<?= URL ?>pedido/guardar">
            <input type="hidden" name="Id" value="0">
            <div class="modal-body">
                <?php if (empty($ventasElegibles)): ?>
                <div class="modal-vacio">No hay ventas aprobadas disponibles para asignar como pedido.</div>
                <?php else: ?>
                <div class="fld">
                    <label>Venta</label>
                    <select name="IdVenta" id="fVenta" required onchange="mostrarInfoVenta()">
                        <option value="">Seleccionar una venta</option>
                        <?php foreach ($ventasElegibles as $v):
                            $horas = (int)$v['HorasTranscurridas'];
                            $dias  = floor($horas / 24);
                            $label = $dias > 0 ? $dias . ' día' . ($dias !== 1 ? 's' : '') : $horas . 'hs';
                        ?>
                        <option value="<?= $v['Id'] ?>"
                                data-cliente="<?= htmlspecialchars($v['ClienteNombre'] . ' ' . $v['ClienteApellido']) ?>"
                                data-productos="<?= htmlspecialchars($v['Productos'] ?? '—') ?>"
                                data-monto="<?= number_format($v['MontoTotal'], 0, ',', '.') ?>"
                                data-dias="<?= $label ?>">
                            <?= $v['NumerodeVenta'] ?> — <?= htmlspecialchars($v['ClienteNombre'] . ' ' . $v['ClienteApellido']) ?> (hace <?= $label ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="modal-info" id="infoVenta" style="display:none;">
                    <strong>Cliente:</strong> <span id="iCliente">—</span><br>
                    <strong>Productos:</strong> <span id="iProductos">—</span><br>
                    <strong>Total:</strong> $<span id="iMonto">—</span>
                    &nbsp;·&nbsp;
                    <strong>Desde la venta:</strong> <span id="iDias">—</span>
                </div>
                <div class="fld">
                    <label>Estado inicial</label>
                    <select name="Estado" required>
                        <?php foreach ($estadosAsignables as $est): ?>
                        <option value="<?= htmlspecialchars($est) ?>" <?= $est === 'Pendiente' ? 'selected' : '' ?>><?= htmlspecialchars($est) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="fld">
                    <label>Responsable</label>
                    <input type="text" name="Responsable" maxlength="50" placeholder="Nombre del responsable (opcional)">
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-ft">
                <button type="button" class="btn-eg btn-eg-secondary" onclick="cerrarCrear()">Cancelar</button>
                <?php if (!empty($ventasElegibles)): ?><button type="submit" class="btn-eg btn-eg-primary">Crear pedido</button><?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="modal-bg" id="modalEditar">
    <div class="modal">
        <div class="modal-hd"><h3 id="editTitulo">Editar pedido</h3><button class="modal-close" onclick="cerrarEditar()">✕</button></div>
        <form method="POST" action="<?= URL ?>pedido/guardar">
            <input type="hidden" name="Id" id="eId">
            <input type="hidden" name="IdVenta" id="eIdVenta">
            <div class="modal-body">
                <div class="modal-info" id="editInfo"></div>
                <div class="ped-mat" id="editMateriales"></div>
                <div class="fld">
                    <label>Estado</label>
                    <select name="Estado" id="eEstado" required>
                        <?php foreach ($estadosAsignables as $est): ?>
                        <option value="<?= htmlspecialchars($est) ?>"><?= htmlspecialchars($est) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="fld">
                    <label>Responsable</label>
                    <input type="text" name="Responsable" id="eResponsable" maxlength="50" placeholder="Nombre del responsable">
                </div>
            </div>
            <div class="modal-ft">
                <?php if (puedeEliminar()): ?>
                <a href="#" id="eBajaLink" class="btn-eg btn-eg-peligro"
                   onclick="return confirm('¿Dar de baja el pedido?')">Dar de baja</a>
                <?php endif; ?>
                <button type="button" class="btn-eg btn-eg-secondary" onclick="cerrarEditar()">Cancelar</button>
                <button type="submit" class="btn-eg btn-eg-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirCrear() {
    var info = document.getElementById('infoVenta');
    if (info) info.style.display = 'none';
    document.getElementById('modalCrear').classList.add('open');
}
function cerrarCrear() { document.getElementById('modalCrear').classList.remove('open'); }

function mostrarInfoVenta() {
    var sel = document.getElementById('fVenta');
    var opt = sel.options[sel.selectedIndex];
    var info = document.getElementById('infoVenta');
    if (!sel.value) { info.style.display = 'none'; return; }
    document.getElementById('iCliente').textContent   = opt.dataset.cliente   ?? '—';
    document.getElementById('iProductos').textContent = opt.dataset.productos ?? '—';
    document.getElementById('iMonto').textContent     = opt.dataset.monto     ?? '—';
    document.getElementById('iDias').textContent      = opt.dataset.dias      ?? '—';
    info.style.display = 'block';
}

function abrirEditar(p) {
    document.getElementById('eId').value          = p.Id;
    document.getElementById('eIdVenta').value     = p.IdVenta;
    document.getElementById('eEstado').value      = p.Estado ?? 'Pendiente';
    document.getElementById('eResponsable').value = p.Responsable ?? '';
    document.getElementById('editTitulo').textContent = 'Pedido #' + p.NumerodeVenta;
    document.getElementById('editInfo').innerHTML =
        '<strong>Venta:</strong> ' + p.NumerodeVenta +
        ' &nbsp;&middot;&nbsp; <strong>Cliente:</strong> ' + (p.ClienteNombre ?? '') + ' ' + (p.ClienteApellido ?? '') +
        '<br><strong>Productos:</strong> ' + (p.Productos ?? '—') +
        '<br><strong>Total:</strong> $' + Number(p.MontoTotal ?? 0).toLocaleString('es-AR') +
        (p.CodigoEntrega ? '<br><strong>Entrega:</strong> ' + p.CodigoEntrega + ' (' + (p.EstadoEntrega ?? '—') + ')' : '');
    var bajaLink = document.getElementById('eBajaLink');
    if (bajaLink) bajaLink.href = '<?= URL ?>pedido/baja/' + p.Id;
    cargarMateriales(p.Id);
    document.getElementById('modalEditar').classList.add('open');
}

function escMat(t) {
    return String(t ?? '').replace(/[&<>"']/g, function (c) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
}
function numMat(n) {
    return Number(n).toLocaleString('es-AR', { maximumFractionDigits: 2 });
}
function cargarMateriales(idPedido) {
    var box = document.getElementById('editMateriales');
    box.innerHTML = '<div class="ped-mat-vacio">Cargando materiales…</div>';
    box.dataset.pedido = idPedido;

    fetch('<?= URL ?>pedido/materiales/' + encodeURIComponent(idPedido))
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (box.dataset.pedido != idPedido) return; 
            if (!d.ok) { box.innerHTML = '<div class="ped-mat-vacio">No se pudieron cargar los materiales.</div>'; return; }
            box.innerHTML = renderMateriales(d);
        })
        .catch(function () {
            box.innerHTML = '<div class="ped-mat-vacio">Error de conexión al cargar los materiales.</div>';
        });
}
function renderMateriales(d) {
    var nota = d.descontado ? '✔ Ya descontado del stock' : 'Se descuenta al pasar a En producción';
    var html = '<div class="ped-mat-hd"><span class="ped-mat-tit">Materiales</span>'
             + '<span class="ped-mat-nota">' + nota + '</span></div>';

    if (d.materiales.length) {
        html += '<div class="ped-mat-scroll"><table><thead><tr>'
              + '<th>Material</th><th class="num">Necesita</th><th class="num">En stock</th><th class="num"></th>'
              + '</tr></thead><tbody>';
        d.materiales.forEach(function (m) {
            var estado = m.falta > 0
                ? '<span class="falta" title="Hay que encargar ' + numMat(m.falta) + '">⚠ falta' + (m.falta != 1 ? 'n' : '') + ' ' + numMat(m.falta) + '</span>'
                : '<span class="ok" title="Alcanza">✓</span>';
            html += '<tr><td>' + escMat(m.material) + '<span class="prod">' + escMat(m.tipo) + ' · ' + escMat(m.productos) + '</span></td>'
                  + '<td class="num">' + numMat(m.necesario) + '</td>'
                  + '<td class="num' + (m.stock < 0 ? ' neg' : '') + '">' + numMat(m.stock) + '</td>'
                  + '<td class="num">' + estado + '</td></tr>';
        });
        html += '</tbody></table></div>';
    } else if (!d.sinMateriales.length) {
        html += '<div class="ped-mat-vacio">Este pedido no tiene productos con materiales cargados.</div>';
    }

    if (d.faltantes > 0) {
        html += '<div class="ped-mat-alerta">⚠ Falta' + (d.faltantes > 1 ? 'n ' + d.faltantes + ' materiales' : ' 1 material')
              + ': hay que encargarlo' + (d.faltantes > 1 ? 's' : '') + ' y cargar el ingreso en Stock.</div>';
    }
    if (d.sinMateriales.length) {
        html += '<div class="ped-mat-aviso">' + escMat(d.sinMateriales.join(', '))
              + ' no tiene' + (d.sinMateriales.length > 1 ? 'n' : '') + ' materiales cargados: no se descuenta stock por '
              + (d.sinMateriales.length > 1 ? 'esos productos' : 'ese producto') + '.</div>';
    }
    return html;
}
function cerrarEditar() { document.getElementById('modalEditar').classList.remove('open'); }

['modalCrear', 'modalEditar', 'modalMasivoEstado'].forEach(function (id) {
    document.getElementById(id).addEventListener('click', function (e) {
        if (e.target === this) this.classList.remove('open');
    });
});

function toggleSelectionMode() {
    const zona = document.getElementById('zonaSeleccion');
    const bar  = document.getElementById('bulkActionsBar');
    const btn  = document.getElementById('btnToggleSelection');
    zona.classList.toggle('selection-mode');
    if (!zona.classList.contains('selection-mode')) {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
        bar.classList.remove('active');
        btn.textContent = 'Selección múltiple';
    } else {
        btn.textContent = 'Cancelar selección';
    }
}
function actualizarBarraMasiva() {
    const checkboxes = document.querySelectorAll('.row-checkbox:checked');
    const bar = document.getElementById('bulkActionsBar');
    document.getElementById('selectedCount').textContent = checkboxes.length;
    bar.classList.toggle('active', checkboxes.length > 0);
}
function ejecutarAccionMasiva(accion) {
    const seleccionados = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
    if (seleccionados.length === 0) { alert('Por favor seleccioná al menos un pedido.'); return; }
    if (accion === 'baja') {
        if (confirm('¿Estás seguro de dar de baja los ' + seleccionados.length + ' pedidos seleccionados?')) {
            enviarAccionPorPost('<?= URL ?>pedido/bajaMasiva', seleccionados);
        }
    } else if (accion === 'cambiar_estado') {
        document.getElementById('masivoIds').value = JSON.stringify(seleccionados);
        document.getElementById('masivoCountText').textContent = seleccionados.length;
        document.getElementById('modalMasivoEstado').classList.add('open');
    }
}
function cerrarMasivoEstado() { document.getElementById('modalMasivoEstado').classList.remove('open'); }
function enviarAccionPorPost(url, ids) {
    const form = document.createElement('form');
    form.method = 'POST'; form.action = url;
    const input = document.createElement('input');
    input.type = 'hidden'; input.name = 'ids'; input.value = JSON.stringify(ids);
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

let cardArrastrada = null;

document.querySelectorAll('.ped-card').forEach(function (card) {
    card.addEventListener('dragstart', function (e) {
        if (document.getElementById('zonaSeleccion').classList.contains('selection-mode')) {
            e.preventDefault();
            return;
        }
        cardArrastrada = card;
        card.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
    });
    card.addEventListener('dragend', function () {
        card.classList.remove('dragging');
        cardArrastrada = null;
    });
});

document.querySelectorAll('.kanban-col, .seccion-listo').forEach(function (zona) {
    zona.addEventListener('dragover', function (e) {
        e.preventDefault();
        zona.classList.add('drag-over');
    });
    zona.addEventListener('dragleave', function () {
        zona.classList.remove('drag-over');
    });
    zona.addEventListener('drop', function (e) {
        e.preventDefault();
        zona.classList.remove('drag-over');
        if (!cardArrastrada) return;

        const idPedido     = cardArrastrada.dataset.id;
        const estadoActual = cardArrastrada.dataset.estado;
        const estadoNuevo  = zona.dataset.estado;

        if (estadoActual === estadoNuevo) return;

        fetch('<?= URL ?>pedido/cambiarEstadoRapido', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(idPedido) + '&estado=' + encodeURIComponent(estadoNuevo)
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                location.reload(); 
                alert(data.error || 'No se pudo cambiar el estado.');
            }
        })
        .catch(() => alert('Error de conexión al cambiar el estado.'));
    });
});
</script>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
<?php include INCLUDES . 'admin' . DS . 'modals_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'scripts_admin.php'; ?>