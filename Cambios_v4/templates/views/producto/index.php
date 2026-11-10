<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script></style>
<main class="content">

<div class="pg-hd">
    <div>
        <h1>Productos</h1>
        <small>Gestion del catalogo — <?= count($productos ?? []) ?> registros</small>
    </div>
    <a href="<?= URL ?>producto/crear" class="btn-cta">
        <?= $creaProductosDirecto ? 'Agregar producto' : 'Sugerir producto' ?>
    </a>
</div>

<?php if (!empty($puedeAprobarProductos) && !empty($pendientesCount)): ?>
<div class="modal-info" style="margin-bottom:1rem;background:#fff8e1;border:1px solid #ffe082;color:#8a6d1f;">
    Hay <strong><?= $pendientesCount ?></strong> producto<?= $pendientesCount > 1 ? 's' : '' ?> sugerido<?= $pendientesCount > 1 ? 's' : '' ?> esperando aprobación —
    <a href="<?= URL ?>producto/pendientes" style="color:#5c2d0a;font-weight:700;">revisar ahora</a>.
</div>
<?php endif; ?>

<?= Toast::flash() ?>

<?php if (empty($productos)): ?>
<div class="vacio">
    <span class="v-icono" style="font-size:2.5rem;opacity:.2;display:block;margin-bottom:.6rem;">—</span>
    <p>No hay productos cargados en el catalogo todavia.</p>
    <a href="<?= URL ?>producto/crear" class="btn-cta"><?= $creaProductosDirecto ? 'Crear el primer producto' : 'Sugerir el primer producto' ?></a>
</div>
<?php else: ?>

<div class="tw">
<table class="ts" id="tablaProductos">
    <thead>
        <tr>
            <th style="width:52px"></th>
            <th>Nombre del producto</th>
            <th>Categoria</th>
            <th>Diseno / Acabado</th>
            <th style="width:86px" class="text-center">Tiempo (hs)</th>
            <th class="text-end">Materiales ($)</th>
            <th class="text-end">Precio venta ($)</th>
            <th style="width:96px" class="text-center">Estado</th>
            <th style="width:76px" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $estadoCls = [
        'Aprobado'  => 'background:#e8f5e9;color:#2e7d32;border:1px solid #a5d6a7;',
        'Pendiente' => 'background:#fff8e1;color:#8a6d1f;border:1px solid #ffe082;',
        'Rechazado' => 'background:#fdf0f0;color:#c0392b;border:1px solid #f5c6c6;',
    ];
    ?>
    <?php foreach ($productos as $p): ?>
        <tr>
            <td>
                <?php if (!empty($p['URLImagen'])): ?>
                    <img src="<?= htmlspecialchars($p['URLImagen']) ?>" class="c-img"
                         alt="<?= htmlspecialchars($p['NombredelProducto']) ?>">
                <?php else: ?>
                    <div class="c-ph">—</div>
                <?php endif; ?>
            </td>
            <td>
                <span class="c-nom"><?= htmlspecialchars($p['NombredelProducto']) ?></span>
                <?php if (!empty($p['Descripcion'])): ?>
                    <span class="c-desc"><?= htmlspecialchars(mb_strimwidth($p['Descripcion'], 0, 60, '…')) ?></span>
                <?php endif; ?>
            </td>
            <td>
                <?php if (!empty($p['NombreCategoria'])): ?>
                    <span class="c-cat"><?= htmlspecialchars($p['NombreCategoria']) ?></span>
                <?php else: ?>
                    <span style="color:var(--g2)">—</span>
                <?php endif; ?>
            </td>
            <td>
                <span class="c-dis"><?= htmlspecialchars($p['NombreTipoDiseño'] ?? '—') ?></span>
                <?php if (!empty($p['NombreTipoAcabado'])): ?>
                    <br><span class="c-aca"><?= htmlspecialchars($p['NombreTipoAcabado']) ?></span>
                <?php endif; ?>
            </td>
            <td class="text-center">
                <?php if (!empty($p['TiempoFabricacionHoras']) && $p['TiempoFabricacionHoras'] > 0): ?>
                    <span class="c-badge"><?= number_format($p['TiempoFabricacionHoras'], 1) ?> hs</span>
                <?php else: ?>
                    <span style="color:var(--g2)">—</span>
                <?php endif; ?>
            </td>
            <td class="text-end">
                <?php if (!empty($p['CostoTotalMateriales']) && $p['CostoTotalMateriales'] > 0): ?>
                    <span class="c-mat">$<?= number_format($p['CostoTotalMateriales'], 2, ',', '.') ?></span>
                <?php else: ?>
                    <span style="color:var(--g2)">—</span>
                <?php endif; ?>
            </td>
            <td class="text-end">
                <span class="c-prx">$<?= number_format($p['PrecioVenta'] ?? 0, 2, ',', '.') ?></span>
            </td>
            <td class="text-center">
                <?php $est = $p['EstadoAprobacion'] ?? 'Aprobado'; ?>
                <span style="display:inline-block;padding:2px 9px;border-radius:20px;font-size:.72rem;font-weight:700;<?= $estadoCls[$est] ?? '' ?>"
                      title="<?= $est === 'Rechazado' && !empty($p['MotivoRechazo']) ? htmlspecialchars($p['MotivoRechazo']) : '' ?>">
                    <?= htmlspecialchars($est) ?>
                </span>
                <?php if (!empty($p['SugeridoPor']) && $est !== 'Aprobado'): ?>
                <div style="font-size:.7rem;color:var(--g2);margin-top:2px;">por <?= htmlspecialchars($p['SugeridoPor']) ?></div>
                <?php endif; ?>
            </td>
            <td class="text-center" style="white-space:nowrap;">
                <a href="<?= URL ?>producto/editar/<?= $p['Id'] ?>" class="ba ba-ed" title="Editar">✏</a>
                <?php if (puedeEliminar()): ?>
                <button type="button" class="ba ba-del" title="Eliminar"
                        onclick="abrirDlg(<?= $p['Id'] ?>, '<?= htmlspecialchars(addslashes($p['NombredelProducto'])) ?>')">
                    ✕
                </button>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="tw-foot">
    <span><?= count($productos) ?> producto<?= count($productos) !== 1 ? 's' : '' ?></span>
    <strong>San Placido — Carpinteria</strong>
</div>
</div>

<?php endif; ?>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>

<div id="dlgEl">
    <div class="dlg">
        <p class="dlg-t">Eliminar producto</p>
        <p class="dlg-p">Eliminar <strong id="dlgN"></strong>? Esta accion no se puede deshacer.</p>
        <div class="dlg-ac">
            <button class="btn-nc" onclick="cerrarDlg()">Cancelar</button>
            <a id="dlgU" href="#" class="btn-del-ok">Si, eliminar</a>
        </div>
    </div>
</div>

<?php include INCLUDES . 'admin' . DS . 'scripts_admin.php'; ?>
<script>
function abrirDlg(id, nombre) {
    document.getElementById('dlgN').textContent = nombre;
    document.getElementById('dlgU').href = '<?= URL ?>producto/eliminar/' + id;
    document.getElementById('dlgEl').style.display = 'flex';
}
function cerrarDlg() {
    document.getElementById('dlgEl').style.display = 'none';
}
document.getElementById('dlgEl').addEventListener('click', function(e) {
    if (e.target === this) cerrarDlg();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cerrarDlg();
});

$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#tablaProductos')) {
        $('#tablaProductos').DataTable().clear().destroy();
    }

    $('#tablaProductos').DataTable({
        language: {
            processing:     "Procesando...",
            search:         "Buscar:",
            lengthMenu:     "Mostrar _MENU_ registros",
            info:           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            infoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
            infoFiltered:   "(filtrado de un total de _MAX_ registros)",
            loadingRecords: "Cargando...",
            zeroRecords:    "No se encontraron resultados",
            emptyTable:     "No hay productos cargados en el catalogo todavia.",
            paginate: {
                first:      "Primero",
                previous:   "Anterior",
                next:       "Siguiente",
                last:       "Último"
            }
        },
        pageLength: 10,
        searching: false,    
        lengthChange: false, 
        paging: true,
        info: false,         
        autoWidth: false,
        dom: 'tp',          
        columnDefs: [
            { orderable: false, targets: [0, 8] } 
        ]
    });
});
</script>