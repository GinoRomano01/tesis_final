<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<main class="content">
<style>
:root{--caoba:#5c2d0a;--caoba2:#7a3e14;--amb:#b8722a;--lino:#f7f0e6;--lino2:#ede4d4;--papel:#fdfaf6;--tinta:#2c1a0e;--tinta2:#4a3020;--g1:#8a7560;--borde:#d4c4aa;--borde2:#e8dcc8;--sombra:rgba(92,45,10,.08);--rojo:#c0392b;--verde:#2e7d32;}
*{box-sizing:border-box;}
.rt-page{font-family:'Source Sans 3',Georgia,sans-serif;color:var(--tinta);}
.rt-hd{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:.5rem;}
.rt-hd h1{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--caoba);margin:0;}
.rt-sub{font-size:.85rem;color:var(--g1);margin-bottom:1.2rem;}
.rt-info{background:#e3f2fd;border:1.5px solid #90caf9;border-radius:8px;padding:.85rem 1rem;font-size:.85rem;color:#1565c0;margin-bottom:1.2rem;line-height:1.6;}
.btn-rt{display:inline-flex;align-items:center;gap:.4rem;padding:.48rem 1.1rem;border-radius:7px;font-size:.86rem;font-weight:600;font-family:inherit;text-decoration:none;border:none;cursor:pointer;transition:background .15s,transform .12s;}
.btn-rt:hover{transform:translateY(-1px);}
.btn-rt-primary{background:var(--caoba);color:#fff;}
.btn-rt-primary:hover{background:var(--caoba2);color:#fff;}
.btn-rt-secondary{background:var(--lino2);color:var(--caoba);border:1.5px solid var(--borde);}

.rt-repartidor{margin-bottom:1.2rem;max-width:320px;}
.rt-repartidor label{display:block;margin-bottom:.3rem;font-size:.78rem;font-weight:700;color:var(--tinta2);text-transform:uppercase;letter-spacing:.03em;}
.rt-repartidor select{width:100%;background:var(--papel);border:1.5px solid var(--borde);border-radius:7px;padding:.5rem .85rem;font-family:inherit;font-size:.9rem;color:var(--tinta);}

.rt-table-wrap{background:var(--papel);border:1.5px solid var(--borde);border-radius:10px;overflow:hidden;box-shadow:0 2px 12px var(--sombra);margin-bottom:1.2rem;}
.rt-table{width:100%;border-collapse:collapse;}
.rt-table thead tr{background:linear-gradient(to right,var(--caoba),var(--caoba2));}
.rt-table thead th{padding:.68rem .85rem;text-align:left;font-size:.72rem;font-weight:700;letter-spacing:.05em;color:rgba(255,255,255,.92);text-transform:uppercase;}
.rt-table tbody tr{border-bottom:1px solid var(--borde2);}
.rt-table tbody tr:hover{background:var(--lino);}
.rt-table tbody td{padding:.6rem .85rem;font-size:.86rem;color:var(--tinta2);vertical-align:middle;}
.rt-table input[type=checkbox]{width:16px;height:16px;accent-color:var(--caoba);cursor:pointer;}
.rt-table input.inp-orden{width:56px;background:var(--lino);border:1.5px solid var(--borde);border-radius:6px;padding:.3rem .4rem;font-family:inherit;font-size:.85rem;text-align:center;}
.td-main{font-weight:700;color:var(--caoba);}
.td-zona{display:inline-block;padding:2px 9px;border-radius:20px;font-size:.74rem;font-weight:700;background:#f3ebe0;color:#7a4e2d;border:1px solid #d4b896;}
.td-fecha{font-size:.78rem;color:var(--g1);}
.rt-vacio{text-align:center;padding:3rem 1rem;color:var(--g1);}
.rt-vacio span{font-size:2.5rem;opacity:.25;display:block;margin-bottom:.5rem;}
.rt-footer{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.6rem;}
</style>

<div class="rt-page">
    <div class="rt-hd">
        <h1>Iniciar entrega</h1>
        <a href="<?= URL ?>entrega" class="btn-rt btn-rt-secondary">← Volver a Entregas</a>
    </div>
    <div class="rt-sub"><?= count($entregas) ?> entrega<?= count($entregas) !== 1 ? 's' : '' ?> lista<?= count($entregas) !== 1 ? 's' : '' ?> para retirar y sin ruta asignada.</div>

    <?= Toast::flash() ?>

    <div class="rt-info">
        Acá solo aparecen entregas de <strong>envío a domicilio</strong> dentro de la zona de reparto (Córdoba Capital).
        Los "Retiro en sucursal" no arman ruta.
        <br><strong>Orden sugerido:</strong> por zona (localidad) y, dentro de la misma zona, por antigüedad
        (la venta más vieja primero). Podés cambiar el número de "Orden" de cualquier fila a mano antes de confirmar.
    </div>

    <?php if (empty($entregas)): ?>
        <div class="rt-vacio">
            <span>—</span>
            <p>No hay entregas listas para armar una ruta en este momento.</p>
        </div>
    <?php else: ?>
    <form method="POST" action="<?= URL ?>entrega/confirmarruta" onsubmit="return prepararEnvio()">
        <input type="hidden" name="Orden" id="inputOrden" value="[]">

        <?php if (!empty($repartidores)): ?>
        <div class="rt-repartidor">
            <label>Asignar ruta a</label>
            <select name="IdRepartidor" required>
                <option value="">— Seleccioná un repartidor —</option>
                <?php foreach ($repartidores as $r): ?>
                <option value="<?= $r['Id'] ?>"><?= htmlspecialchars($r['Nombre'] . ' ' . $r['Apellido']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <div class="rt-table-wrap">
            <table class="rt-table">
                <thead>
                    <tr>
                        <th style="width:36px;"></th>
                        <th style="width:70px;">Orden</th>
                        <th>Cliente</th>
                        <th>Zona</th>
                        <th>Dirección</th>
                        <th>Código</th>
                        <th>Antigüedad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entregas as $i => $e): ?>
                    <tr class="fila-entrega">
                        <td><input type="checkbox" class="chk-entrega" value="<?= $e['Id'] ?>" checked onchange="actualizarContador()"></td>
                        <td><input type="number" class="inp-orden" value="<?= $i + 1 ?>" min="1"></td>
                        <td>
                            <div class="td-main"><?= htmlspecialchars($e['ClienteNombre'] . ' ' . $e['ClienteApellido']) ?></div>
                            <?php if (!empty($e['Productos'])): ?>
                            <div class="td-fecha"><?= htmlspecialchars($e['Productos']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td><span class="td-zona"><?= htmlspecialchars($e['Localidad'] ?? 'Sin localidad') ?></span></td>
                        <td style="font-size:.83rem;"><?= htmlspecialchars($e['Direccion'] ?? '—') ?></td>
                        <td style="font-family:monospace;font-size:.82rem;"><?= htmlspecialchars($e['CodigoEntrega'] ?? '—') ?></td>
                        <td class="td-fecha">
                            <?php if (!empty($e['FechadeEmision'])):
                                $dias = floor((time() - strtotime($e['FechadeEmision'])) / 86400);
                            ?>
                                <?= $dias ?> día<?= $dias !== 1 ? 's' : '' ?>
                            <?php else: ?>—<?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="rt-footer">
            <span style="font-size:.85rem;color:var(--g1);"><span id="contadorSel"><?= count($entregas) ?></span> seleccionada(s)</span>
            <button type="submit" class="btn-rt btn-rt-primary">Iniciar entrega con las seleccionadas</button>
        </div>
    </form>
    <?php endif; ?>
</div>

<script>
function actualizarContador() {
    document.getElementById('contadorSel').textContent = document.querySelectorAll('.chk-entrega:checked').length;
}

function prepararEnvio() {
    const filas = document.querySelectorAll('.fila-entrega');
    const seleccion = [];

    filas.forEach(function(fila) {
        const chk = fila.querySelector('.chk-entrega');
        if (chk.checked) {
            const orden = parseInt(fila.querySelector('.inp-orden').value) || 999;
            seleccion.push({ id: chk.value, orden: orden });
        }
    });

    if (seleccion.length === 0) {
        alert('Seleccioná al menos una entrega para armar la ruta.');
        return false;
    }

    seleccion.sort(function(a, b) { return a.orden - b.orden; });
    document.getElementById('inputOrden').value = JSON.stringify(seleccion.map(function(s) { return s.id; }));
    return true;
}
</script>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
