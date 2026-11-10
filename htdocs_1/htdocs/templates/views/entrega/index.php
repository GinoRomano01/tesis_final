<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<main class="content">

<style>
.eg-page { font-family: var(--font-body); color: var(--tinta); }

.eg-hd {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .75rem; margin-bottom: 1.2rem;
}
.eg-hd h1 {
    font-family: var(--font-display); font-size: 1.6rem;
    font-weight: 700; color: var(--caoba); margin: 0;
}

.badges-row { display: flex; gap: .6rem; flex-wrap: wrap; margin-bottom: 1.2rem; }
.eg-badge {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .32rem .9rem; border-radius: var(--radius-pill);
    font-size: .8rem; font-weight: 700; border: 1.5px solid transparent;
    text-decoration: none; cursor: pointer; transition: all var(--trans-fast);
    font-family: var(--font-body);
}
.eg-badge.all         { background: var(--lino2); color: var(--tinta2); border-color: var(--borde); }
.eg-badge.all.active,
.eg-badge.all:hover   { background: var(--caoba); color: #fff; border-color: var(--caoba); }
.eg-badge.pendiente   { background: #fff8e1; color: var(--amarillo); border-color: #ffe082; }
.eg-badge.pendiente.active,
.eg-badge.pendiente:hover { background: var(--amarillo); color: #fff; }
.eg-badge.en-curso    { background: #e3f2fd; color: var(--azul); border-color: #90caf9; }
.eg-badge.en-curso.active,
.eg-badge.en-curso:hover  { background: var(--azul); color: #fff; }
.eg-badge.finalizada  { background: #e8f5e9; color: var(--verde2); border-color: #a5d6a7; }
.eg-badge.finalizada.active,
.eg-badge.finalizada:hover { background: var(--verde2); color: #fff; }
.badge-count { background: rgba(0,0,0,.12); border-radius: 10px; padding: 1px 7px; font-size: .73rem; }

.eg-filters {
    display: flex; gap: .5rem; flex-wrap: wrap;
    margin-bottom: 1.2rem; align-items: center;
}
.eg-filters input {
    flex: 1; min-width: 220px;
    background: var(--papel); border: 1.5px solid var(--borde);
    border-radius: var(--radius-sm); padding: .46rem 1rem;
    font-family: var(--font-body); font-size: .9rem; color: var(--tinta);
    transition: border-color var(--trans-fast);
}
.eg-filters input:focus {
    outline: none; border-color: var(--amb);
    box-shadow: 0 0 0 3px rgba(184,114,42,.1);
}

.btn-eg {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .46rem 1rem; border-radius: var(--radius-sm);
    font-size: .86rem; font-weight: 600; font-family: var(--font-body);
    text-decoration: none; border: none; cursor: pointer;
    transition: background var(--trans-fast), transform var(--trans-fast);
}
.btn-eg:hover { transform: translateY(-1px); }
.btn-eg-primary   { background: var(--caoba); color: #fff; }
.btn-eg-primary:hover { background: var(--caoba2); color: #fff; }
.btn-eg-secondary { background: var(--lino2); color: var(--caoba); border: 1.5px solid var(--borde); }
.btn-eg-secondary:hover { background: var(--borde); }
.btn-eg-sm { padding: .26rem .6rem; font-size: .78rem; }

.eg-table-wrap {
    background: var(--papel); border: 1.5px solid var(--borde);
    border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm);
}
.eg-table { width: 100%; border-collapse: collapse; }
.eg-table thead tr { background: linear-gradient(to right, var(--caoba), var(--caoba2)); }
.eg-table thead th {
    padding: .72rem 1rem; text-align: left;
    font-size: .74rem; font-weight: 700; letter-spacing: .06em;
    color: rgba(255,255,255,.9); text-transform: uppercase;
    border-right: 1px solid rgba(255,255,255,.12);
}
.eg-table thead th:last-child { border-right: none; }
.eg-table tbody tr { border-bottom: 1px solid var(--borde2); transition: background var(--trans-fast); }
.eg-table tbody tr:last-child { border-bottom: none; }
.eg-table tbody tr:hover { background: var(--lino); }
.eg-table tbody td {
    padding: .65rem 1rem; font-size: .86rem; color: var(--tinta2);
    border-right: 1px solid var(--borde2); vertical-align: middle;
}
.eg-table tbody td:last-child { border-right: none; }

.td-main  { font-weight: 700; color: var(--caoba); }
.td-tag   { display: inline-block; padding: 2px 9px; border-radius: var(--radius-pill); font-size: .72rem; font-weight: 700; background: var(--lino2); color: var(--tinta2); border: 1px solid var(--borde); }
.td-acc   { display: flex; gap: .3rem; flex-wrap: wrap; }
.td-fecha { font-size: .8rem; color: var(--g1); }
.td-codigo { font-family: monospace; font-size: .83rem; background: var(--lino2); padding: 2px 7px; border-radius: var(--radius-sm); border: 1px solid var(--borde); }
.td-monto { font-family: var(--font-display); font-size: .93rem; color: var(--verde2); font-weight: 700; }

.pill { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: var(--radius-pill); font-size: .73rem; font-weight: 700; font-family: var(--font-body); }
.pill-pendiente  { background: #fff8e1; color: var(--amarillo); border: 1px solid #ffe082; }
.pill-en-curso   { background: #e3f2fd; color: var(--azul);     border: 1px solid #90caf9; }
.pill-finalizada { background: #e8f5e9; color: var(--verde2);   border: 1px solid #a5d6a7; }

.eg-vacio { text-align: center; padding: 3.5rem 1rem; color: var(--g1); }
.eg-vacio span { font-size: 2.5rem; opacity: .2; display: block; margin-bottom: .6rem; }

.modal-bg { display: none; position: fixed; inset: 0; background: rgba(44,26,14,.48); z-index: 1000; align-items: center; justify-content: center; padding: 1rem; overflow-y: auto; }
.modal-bg.open { display: flex; }
.modal { background: var(--papel); border-radius: var(--radius-lg); width: 100%; max-width: 540px; box-shadow: 0 8px 40px rgba(0,0,0,.28); overflow: hidden; animation: mIn .2s ease; margin: auto; }
@keyframes mIn { from { opacity:0; transform:translateY(-14px); } to { opacity:1; transform:translateY(0); } }
.modal-hd { background: linear-gradient(to right, var(--caoba), var(--caoba2)); padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; }
.modal-hd h3 { font-family: var(--font-display); font-size: 1.05rem; font-weight: 700; color: #fff; margin: 0; }
.modal-close { background: none; border: none; color: rgba(255,255,255,.7); font-size: 1.3rem; cursor: pointer; padding: 0; line-height: 1; transition: color var(--trans-fast); }
.modal-close:hover { color: #fff; }
.modal-body { padding: 1.25rem; }
.modal-ft { padding: .9rem 1.25rem; border-top: 1px solid var(--borde2); background: var(--lino); display: flex; gap: .5rem; justify-content: flex-end; }

.fld { margin-bottom: 1rem; }
.fld label { display: block; margin-bottom: .3rem; font-size: .77rem; font-weight: 700; color: var(--tinta2); text-transform: uppercase; letter-spacing: .03em; }
.fld input,
.fld select,
.fld textarea {
    width: 100%; background: var(--lino); border: 1.5px solid var(--borde);
    border-radius: var(--radius-sm); padding: .5rem .85rem;
    font-family: var(--font-body); font-size: .9rem; color: var(--tinta);
    transition: border-color var(--trans-fast), background var(--trans-fast);
}
.fld input:focus,
.fld select:focus,
.fld textarea:focus { outline: none; border-color: var(--amb); background: #fff; box-shadow: 0 0 0 3px rgba(184,114,42,.1); }
.fld-row  { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }

.modal-info { background: var(--lino2); border: 1px solid var(--borde); border-radius: var(--radius-sm); padding: .75rem 1rem; margin-bottom: 1rem; font-size: .84rem; color: var(--tinta2); }
.modal-info strong { color: var(--caoba); }
.modal-divider { font-size: .73rem; text-transform: uppercase; letter-spacing: .07em; color: var(--g1); font-weight: 700; margin: .25rem 0 .75rem; border-bottom: 1px solid var(--borde2); padding-bottom: .3rem; }
</style>

<div class="eg-page">

    <div class="eg-hd">
        <h1>Entregas</h1>
    </div>

    <?php
    $claseEstado = function(string $nombre): string {
        $n = mb_strtolower($nombre);
        if (str_contains($n, 'pendiente')) return 'pendiente';
        if (str_contains($n, 'curso'))     return 'en-curso';
        if (str_contains($n, 'finaliz'))   return 'finalizada';
        return 'all';
    };
    $totalGeneral = array_sum($conteos);
    ?>

    <div class="badges-row">
        <a href="<?= URL ?>entrega<?= !empty($buscar) ? '?buscar='.urlencode($buscar) : '' ?>"
           class="eg-badge all <?= $idEstado === 0 ? 'active' : '' ?>">
            Todas <span class="badge-count"><?= $totalGeneral ?></span>
        </a>
        <?php foreach ($estados as $est):
            $cls   = $claseEstado($est['Nombre']);
            $count = $conteos[$est['Nombre']] ?? 0;
            $href  = URL . 'entrega?estado=' . $est['Id'] . (!empty($buscar) ? '&buscar='.urlencode($buscar) : '');
        ?>
        <a href="<?= $href ?>"
           class="eg-badge <?= $cls ?> <?= $idEstado === (int)$est['Id'] ? 'active' : '' ?>">
            <?= htmlspecialchars($est['Nombre']) ?>
            <span class="badge-count"><?= $count ?></span>
        </a>
        <?php endforeach; ?>
    </div>

    <form class="eg-filters" method="GET" action="<?= URL ?>entrega">
        <?php if ($idEstado > 0): ?>
        <input type="hidden" name="estado" value="<?= $idEstado ?>">
        <?php endif; ?>
        <input type="text" name="buscar"
               value="<?= htmlspecialchars($buscar) ?>"
               placeholder="Buscar por cliente, producto o codigo de envio">
        <button type="submit" class="btn-eg btn-eg-primary">Buscar</button>
        <?php if (!empty($buscar) || $idEstado > 0): ?>
        <a href="<?= URL ?>entrega" class="btn-eg btn-eg-secondary">Limpiar</a>
        <?php endif; ?>
    </form>

    <div class="eg-table-wrap">
        <table class="eg-table">
            <thead>
                <tr>
                    
                    <th>Codigo envio</th>
                    <th>Cliente</th>
                    <th>Producto(s)</th>
                    <th>Estado</th>
                    <th>Tipo</th>
                    <th>Fecha entrega</th>
                    <th>Costo envio ($)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($entregas)): ?>
                <tr>
                    <td colspan="9">
                        <div class="eg-vacio">
                            <span>—</span>
                            <p>No se encontraron entregas<?= !empty($buscar) ? ' para "'.htmlspecialchars($buscar).'"' : '' ?>.</p>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($entregas as $e):
                    $estadoNom = $e['EstadoNombre'] ?? '';
                    $cls       = $claseEstado($estadoNom);
                ?>
                <tr>
                    

                    <td>
                        <?php if (!empty($e['CodigoEntrega'])): ?>
                        <span class="td-codigo"><?= htmlspecialchars($e['CodigoEntrega']) ?></span>
                        <?php else: ?>
                        <span style="color:var(--g1);font-size:.8rem;">—</span>
                        <?php endif; ?>
                    </td>

                    <td class="td-main">
                        <?= htmlspecialchars($e['ClienteNombre'] . ' ' . $e['ClienteApellido']) ?>
                        <?php if (!empty($e['ClienteTelefono'])): ?>
                        <div style="font-size:.76rem;font-weight:400;color:var(--g1);">
                            <?= htmlspecialchars($e['ClienteTelefono']) ?>
                        </div>
                        <?php endif; ?>
                    </td>

                    <td style="max-width:200px;font-size:.83rem;">
                        <?= htmlspecialchars($e['Productos'] ?? '—') ?>
                    </td>

                    <td>
                        <span class="pill pill-<?= $cls ?>">
                            <?= htmlspecialchars($estadoNom) ?>
                        </span>
                    </td>

                    <td style="font-size:.83rem;">
                        <?= htmlspecialchars($e['TipoEntregaNombre'] ?? '—') ?>
                    </td>

                    <td class="td-fecha">
                        <?= !empty($e['FechadeEntrega']) ? date('d/m/Y', strtotime($e['FechadeEntrega'])) : '—' ?>
                    </td>

                    <td class="td-monto">
                        <?php if ($e['CostoEnvio'] > 0): ?>
                            $<?= number_format($e['CostoEnvio'], 2, ',', '.') ?>
                        <?php else: ?>
                            <span style="color:var(--g1);font-size:.8rem;">Gratis</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <div class="td-acc">
                            <button class="btn-eg btn-eg-secondary btn-eg-sm"
                                    onclick="abrirModal(<?= htmlspecialchars(json_encode($e)) ?>)">
                                Editar
                            </button>
                            <form method="GET" action="<?= URL ?>entrega/estado/<?= $e['Id'] ?>" style="display:inline-flex;gap:.25rem;">
                                <select name="s" onchange="this.form.submit()"
                                        style="font-size:.76rem;padding:3px 6px;border-radius:var(--radius-sm);border:1.5px solid var(--borde);background:var(--lino);cursor:pointer;font-family:var(--font-body);">
                                    <?php foreach ($estados as $est): ?>
                                    <option value="<?= $est['Id'] ?>"
                                        <?= (int)$e['IdEstadosdeEntrega'] === (int)$est['Id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($est['Nombre']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>


<div class="modal-bg" id="modalBg">
    <div class="modal">
        <div class="modal-hd">
            <h3 id="modalTitulo">Editar entrega</h3>
            <button class="modal-close" onclick="cerrarModal()">✕</button>
        </div>
        <form method="POST" action="<?= URL ?>entrega/guardar">
            <input type="hidden" name="Id" id="fId" value="">
            <div class="modal-body">

                <div class="modal-info" id="infoCliente">
                    <strong>Cliente:</strong> <span id="infoNombre">—</span>
                    &nbsp;|&nbsp;
                    <strong>Venta:</strong> <span id="infoVenta">—</span>
                </div>

                <div class="modal-divider">Datos de la entrega</div>

                <div class="fld-row">
                    <div class="fld">
                        <label>Estado</label>
                        <select name="IdEstadosdeEntrega" id="fEstado" required>
                            <?php foreach ($estados as $est): ?>
                            <option value="<?= $est['Id'] ?>"><?= htmlspecialchars($est['Nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="fld">
                        <label>Tipo de entrega</label>
                        <select name="IdTipodeEntrega" id="fTipo" required>
                            <option value="">Seleccionar</option>
                            <?php foreach ($tipos as $t): ?>
                            <option value="<?= $t['Id'] ?>"><?= htmlspecialchars($t['Nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="fld">
                    <label>Codigo de envio</label>
                    <input type="text" name="CodigoEntrega" id="fCodigo" placeholder="SP-000123" maxlength="20">
                </div>

                <div class="fld">
                    <label>Direccion de entrega</label>
                    <input type="text" name="Direccion" id="fDireccion" placeholder="Calle 123, Ciudad" maxlength="200">
                </div>

                <div class="fld-row">
                    <div class="fld">
                        <label>Fecha de entrega</label>
                        <input type="date" name="FechadeEntrega" id="fFecha">
                    </div>
                    <div class="fld">
                        <label>Costo de envio ($)</label>
                        <input type="number" name="CostoEnvio" id="fCosto" min="0" step="0.01" placeholder="0.00">
                    </div>
                </div>

            </div>
            <div class="modal-ft">
                <button type="button" class="btn-eg btn-eg-secondary" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn-eg btn-eg-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModal(e) {
    document.getElementById('fId').value        = e.Id;
    document.getElementById('fEstado').value    = e.IdEstadosdeEntrega ?? '';
    document.getElementById('fTipo').value      = e.IdTipodeEntrega    ?? '';
    document.getElementById('fCodigo').value    = e.CodigoEntrega      ?? '';
    document.getElementById('fDireccion').value = e.Direccion          ?? '';
    document.getElementById('fCosto').value     = e.CostoEnvio         ?? '0';
    document.getElementById('fFecha').value     = (e.FechadeEntrega ?? '').substring(0, 10);

    document.getElementById('infoNombre').textContent =
        (e.ClienteNombre ?? '') + ' ' + (e.ClienteApellido ?? '');
    document.getElementById('infoVenta').textContent = e.NumerodeVenta ?? '—';
    document.getElementById('modalTitulo').textContent = 'Editar entrega ' + e.Id;
    document.getElementById('modalBg').classList.add('open');
}

function cerrarModal() {
    document.getElementById('modalBg').classList.remove('open');
}

document.getElementById('modalBg').addEventListener('click', function (ev) {
    if (ev.target === this) cerrarModal();
});
</script>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
<?php include INCLUDES . 'admin' . DS . 'modals_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'scripts_admin.php'; ?>