<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<main class="content">

<div class="pg-hd">
    <div>
        <h1>Productos</h1>
        <small>Gestion del catalogo — <?= count($productos ?? []) ?> registros</small>
    </div>
    <a href="<?= URL ?>producto/crear" class="btn-cta">Agregar producto</a>
</div>

<?= Toast::flash() ?>

<?php if (empty($productos)): ?>
<div class="vacio">
    <span class="v-icono" style="font-size:2.5rem;opacity:.2;display:block;margin-bottom:.6rem;">—</span>
    <p>No hay productos cargados en el catalogo todavia.</p>
    <a href="<?= URL ?>producto/crear" class="btn-cta">Crear el primer producto</a>
</div>
<?php else: ?>

<div class="tw">
<table class="ts">
    <thead>
        <tr>
            <th style="width:52px"></th>
            <th>Nombre del producto</th>
            <th>Categoria</th>
            <th>Diseno / Acabado</th>
            <th style="width:86px" class="text-center">Tiempo (hs)</th>
            <th class="text-end">Materiales ($)</th>
            <th class="text-end">Precio venta ($)</th>
            <th style="width:76px" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
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
            <td class="text-center" style="white-space:nowrap;">
                <a href="<?= URL ?>producto/editar/<?= $p['Id'] ?>" class="ba ba-ed" title="Editar">✏</a>
                <button type="button" class="ba ba-del" title="Eliminar"
                        onclick="abrirDlg(<?= $p['Id'] ?>, '<?= htmlspecialchars(addslashes($p['NombredelProducto'])) ?>')">
                    ✕
                </button>
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
</script>