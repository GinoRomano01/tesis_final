<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<main class="content">

<style>
.ap-page { font-family: var(--font-body, 'Source Sans 3', sans-serif); color: var(--tinta, #2c1a0e); }
.ap-hd { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.75rem; margin-bottom:1.2rem; }
.ap-hd h1 { font-family: var(--font-display, 'Playfair Display', serif); font-size:1.6rem; font-weight:700; color:var(--caoba, #5c2d0a); margin:0; }
.ap-hd small { color:var(--g1, #8a7560); }
.ap-search{display:flex;gap:.5rem;margin-bottom:1.2rem;flex-wrap:wrap;}
.ap-search input{flex:1;min-width:200px;background:var(--papel,#fff);border:1.5px solid var(--borde,#d4c4aa);border-radius:7px;padding:.46rem 1rem;font-family:'Source Sans 3',sans-serif;font-size:.9rem;color:var(--tinta,#2c1a0e);}
.ap-search input:focus{outline:none;border-color:var(--amb,#b8722a);}
.ap-table-wrap{background:var(--papel,#fff);border:1.5px solid var(--borde,#d4c4aa);border-radius:10px;overflow:hidden;box-shadow:0 2px 12px rgba(92,45,10,.08);}
.ap-table{width:100%;border-collapse:collapse;}
.ap-table thead tr{background:linear-gradient(to right,var(--caoba,#5c2d0a),var(--caoba2,#7a3e14));}
.ap-table thead th{padding:.72rem 1rem;text-align:left;font-size:.74rem;font-weight:700;letter-spacing:.06em;color:rgba(255,255,255,.92);text-transform:uppercase;border-right:1px solid rgba(255,255,255,.12);}
.ap-table thead th:last-child{border-right:none;}
.ap-table tbody tr{border-bottom:1px solid var(--borde2,#e8dcc8);transition:background .12s;}
.ap-table tbody tr:hover{background:var(--lino,#f7f0e6);}
.ap-table tbody td{padding:.6rem 1rem;font-size:.87rem;color:var(--tinta2,#4a3020);border-right:1px solid var(--borde2,#e8dcc8);vertical-align:middle;}
.ap-table tbody td:last-child{border-right:none;}
.ap-thumb{width:52px;height:52px;object-fit:cover;border-radius:8px;background:var(--lino2,#ede4d4);display:block;}
.ap-ph{width:52px;height:52px;border-radius:8px;background:var(--lino2,#ede4d4);display:flex;align-items:center;justify-content:center;color:var(--g1,#8a7560);font-size:1.1rem;}
.ap-nom{font-weight:700;color:var(--caoba,#5c2d0a);}
.ap-meta{font-size:.78rem;color:var(--g1,#8a7560);margin-top:2px;}
.ap-precio{font-family:'Playfair Display',serif;color:#2e7d32;font-weight:700;}
.ap-acc{display:flex;gap:.3rem;flex-wrap:wrap;}
.ap-vacio { text-align:center; padding:3.5rem 1rem; color:var(--g1, #8a7560); }
.ap-vacio span { font-size:2.5rem; opacity:.25; display:block; margin-bottom:.6rem; }
.btn-ap { display:inline-flex; align-items:center; justify-content:center; gap:.4rem; padding:.36rem .75rem; border-radius:7px;
          font-size:.8rem; font-weight:600; border:none; cursor:pointer; text-decoration:none; white-space:nowrap;
          transition:transform .1s ease, background .15s; font-family:inherit; }
.btn-ap:hover { transform:translateY(-1px); }
.btn-ap-ver      { background:var(--lino2, #ede4d4); color:var(--caoba, #5c2d0a); border:1.5px solid var(--borde, #d4c4aa); }
.btn-ap-aprobar  { background:#2e7d32; color:#fff; }
.btn-ap-aprobar:hover { background:#256428; }
.btn-ap-rechazar { background:#fdf0f0; color:#c0392b; border:1.5px solid #f5c6c6; }
.btn-ap-rechazar:hover { background:#fad7d7; }
.modal-bg { display:none; position:fixed; inset:0; background:rgba(44,26,14,.48); z-index:1000; align-items:center; justify-content:center; padding:1rem; }
.modal-bg.open { display:flex; }
.modal { display:block; background:var(--papel, #fff); border-radius:12px; width:100%; max-width:440px; box-shadow:0 8px 40px rgba(0,0,0,.25); overflow:hidden; animation:apIn .15s ease; }
@keyframes apIn { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }
.modal-hd { padding:1rem 1.25rem; display:flex; align-items:center; justify-content:space-between; }
.modal-hd.hd-aprobar  { background:linear-gradient(to right,#2e7d32,#3f9645); }
.modal-hd.hd-rechazar { background:linear-gradient(to right,#c0392b,#d9564a); }
.modal-hd h3 { font-family: var(--font-display, 'Playfair Display', serif); font-size:1.05rem; font-weight:700; color:#fff; margin:0; }
.modal-close { background:none; border:none; color:rgba(255,255,255,.75); font-size:1.2rem; cursor:pointer; line-height:1; padding:0; }
.modal-close:hover { color:#fff; }
.modal-body { padding:1.25rem; font-size:.92rem; line-height:1.6; }
.modal-body strong { color:var(--caoba, #5c2d0a); }
.modal-ft { padding:.9rem 1.25rem; border-top:1px solid var(--borde2, #e8dcc8); background:var(--lino, #f7f0e6); display:flex; gap:.5rem; justify-content:flex-end; }
.modal-body textarea { width:100%; margin-top:.6rem; border:1.5px solid var(--borde, #d4c4aa); border-radius:7px; padding:.55rem .75rem;
                        font-family:inherit; font-size:.88rem; background:var(--lino, #f7f0e6); resize:vertical; min-height:70px; }
.modal-body textarea:focus { outline:none; border-color:var(--amb, #b8722a); background:#fff; }
</style>

<div class="ap-page">

    <div class="ap-hd">
        <div>
            <h1>Aprobar productos</h1>
            <small><?= count($productos ?? []) ?> sugerencia<?= count($productos ?? []) !== 1 ? 's' : '' ?> pendiente<?= count($productos ?? []) !== 1 ? 's' : '' ?> de revisión</small>
        </div>
        <a href="<?= URL ?>producto" class="btn-ap btn-ap-ver">← Volver a Productos</a>
    </div>

    <?= Toast::flash() ?>

    <form class="ap-search" method="GET" action="<?= URL ?>producto/pendientes">
        <input type="text" name="buscar" value="<?= htmlspecialchars($buscar ?? '') ?>" placeholder="Buscar producto sugerido…">
        <button type="submit" class="btn-ap btn-ap-ver">Buscar</button>
        <?php if (!empty($buscar)): ?>
            <a href="<?= URL ?>producto/pendientes" class="btn-ap btn-ap-ver">Limpiar</a>
        <?php endif; ?>
    </form>

    <div class="ap-table-wrap">
        <table class="ap-table" id="tablaPendientes">
            <thead>
                <tr>
                    <th style="width:60px;"></th>
                    <th>Producto</th>
                    <th>Categoría / Tipo</th>
                    <th>Sugerido por</th>
                    <th>Fecha</th>
                    <th class="text-end">Precio</th>
                    <th style="width:230px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $p): ?>
                <tr>
                    <td>
                        <?php if (!empty($p['URLImagen'])): ?>
                            <img src="<?= htmlspecialchars($p['URLImagen']) ?>" class="ap-thumb" alt="<?= htmlspecialchars($p['NombredelProducto']) ?>">
                        <?php else: ?>
                            <div class="ap-ph">—</div>
                        <?php endif; ?>
                    </td>
                    <td class="ap-nom"><?= htmlspecialchars($p['NombredelProducto']) ?></td>
                    <td style="font-size:.85rem;">
                        <?= htmlspecialchars($p['NombreCategoria'] ?? 'Sin categoría') ?>
                        <?php if (!empty($p['NombreTipoProducto'])): ?>
                        <div class="ap-meta"><?= htmlspecialchars($p['NombreTipoProducto']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:.85rem;"><?= htmlspecialchars($p['SugeridoPor'] ?? '—') ?></td>
                    <td class="ap-meta"><?= !empty($p['FechaCreacion']) ? date('d/m/Y H:i', strtotime($p['FechaCreacion'])) : '—' ?></td>
                    <td class="ap-precio text-end">$<?= number_format($p['PrecioVenta'] ?? 0, 2, ',', '.') ?></td>
                    <td>
                        <div class="ap-acc">
                            <a href="<?= URL ?>producto/editar/<?= $p['Id'] ?>" class="btn-fila">Ver/Editar</a>
                            <button type="button" class="btn-fila ok"
                                    onclick="abrirAprobar(<?= $p['Id'] ?>, '<?= htmlspecialchars(addslashes($p['NombredelProducto'])) ?>')">
                                Aprobar
                            </button>
                            <button type="button" class="btn-fila peligro"
                                    onclick="abrirRechazar(<?= $p['Id'] ?>, '<?= htmlspecialchars(addslashes($p['NombredelProducto'])) ?>')">
                                Rechazar
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<div class="modal-bg" id="modalAprobar">
    <div class="modal">
        <div class="modal-hd hd-aprobar">
            <h3>Aprobar producto</h3>
            <button class="modal-close" onclick="cerrarAprobar()">✕</button>
        </div>
        <form method="POST" id="formAprobar">
            <div class="modal-body">
                ¿Confirmás aprobar <strong id="apNombreAprobar"></strong>? Pasa a estar visible en el catálogo público inmediatamente.
            </div>
            <div class="modal-ft">
                <button type="button" class="btn-ap btn-ap-ver" onclick="cerrarAprobar()">Cancelar</button>
                <button type="submit" class="btn-ap btn-ap-aprobar">Sí, aprobar</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-bg" id="modalRechazar">
    <div class="modal">
        <div class="modal-hd hd-rechazar">
            <h3>Rechazar producto</h3>
            <button class="modal-close" onclick="cerrarRechazar()">✕</button>
        </div>
        <form method="POST" id="formRechazar">
            <div class="modal-body">
                ¿Confirmás rechazar <strong id="apNombreRechazar"></strong>?
                <textarea name="MotivoRechazo" placeholder="Motivo del rechazo (opcional, lo va a ver quien lo sugirió)"></textarea>
            </div>
            <div class="modal-ft">
                <button type="button" class="btn-ap btn-ap-ver" onclick="cerrarRechazar()">Cancelar</button>
                <button type="submit" class="btn-ap btn-ap-rechazar">Sí, rechazar</button>
            </div>
        </form>
    </div>
</div>

<?php include INCLUDES . 'admin' . DS . 'scripts_admin.php'; ?>
<script>
function abrirAprobar(id, nombre) {
    document.getElementById('apNombreAprobar').textContent = nombre;
    document.getElementById('formAprobar').action = '<?= URL ?>producto/aprobar/' + id;
    document.getElementById('modalAprobar').classList.add('open');
}
function cerrarAprobar() {
    document.getElementById('modalAprobar').classList.remove('open');
}

function abrirRechazar(id, nombre) {
    document.getElementById('apNombreRechazar').textContent = nombre;
    document.getElementById('formRechazar').action = '<?= URL ?>producto/rechazar/' + id;
    document.getElementById('modalRechazar').classList.add('open');
}
function cerrarRechazar() {
    document.getElementById('modalRechazar').classList.remove('open');
}

['modalAprobar', 'modalRechazar'].forEach(function(id) {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('open');
    });
});

$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#tablaPendientes')) {
        $('#tablaPendientes').DataTable().clear().destroy();
    }

    $('#tablaPendientes').DataTable({
        language: {
            processing:     "Procesando...",
            search:         "Buscar:",
            lengthMenu:     "Mostrar _MENU_ registros",
            info:           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            infoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
            infoFiltered:   "(filtrado de un total de _MAX_ registros)",
            loadingRecords: "Cargando...",
            zeroRecords:    "No se encontraron resultados",
            emptyTable:     "No hay productos esperando aprobación por ahora.",
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
            { orderable: false, targets: [0, 6] } 
        ]
    });
});
</script>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
