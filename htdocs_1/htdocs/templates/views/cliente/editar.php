<?php include INCLUDES . 'header_cliente.php'; ?>

<style>
:root{
  --lino:#f7f0e6;--lino2:#ede4d4;--papel:#fdfaf6;
  --caoba:#5c2d0a;--caoba2:#7a3e14;--amb:#b8722a;
  --tinta:#2c1a0e;--tinta2:#4a3020;--g1:#8a7560;
  --borde:#d4c4aa;--borde2:#e8dcc8;--verde:#2e6b3a;
  --sombra:rgba(92,45,10,.08);
}
.perfil-page{background:var(--lino);min-height:100vh;padding:2.5rem 0 4rem;font-family:'Source Sans 3',Georgia,sans-serif;}
.perfil-inner{max-width:960px;margin:0 auto;padding:0 1.5rem;display:grid;grid-template-columns:220px 1fr;gap:1.5rem;align-items:start;}
.perfil-sidebar{background:var(--papel);border:1.5px solid var(--borde);border-radius:12px;overflow:hidden;box-shadow:0 2px 12px var(--sombra);position:sticky;top:1rem;}
.perfil-sidebar-top{background:linear-gradient(to bottom right,var(--caoba),var(--caoba2));padding:1.4rem 1rem;text-align:center;}
.perfil-avatar{width:60px;height:60px;background:rgba(255,255,255,.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .7rem;font-size:1.6rem;color:rgba(255,255,255,.85);}
.perfil-nombre{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:#fff;margin-bottom:.15rem;}
.perfil-dni{font-size:.78rem;color:rgba(255,255,255,.65);}
.perfil-nav{padding:.5rem 0;}
.perfil-nav a{display:flex;align-items:center;gap:.6rem;padding:.65rem 1.1rem;font-size:.88rem;color:var(--tinta2);text-decoration:none;transition:background .12s,color .12s;border-left:3px solid transparent;}
.perfil-nav a:hover{background:var(--lino);color:var(--caoba);}
.perfil-nav a.active{background:var(--lino2);color:var(--caoba);font-weight:700;border-left-color:var(--caoba);}
.perfil-nav a.danger{color:#c0392b;}
.perfil-nav a.danger:hover{background:#fdf0f0;}
.perfil-nav-sep{height:1px;background:var(--borde2);margin:.4rem .8rem;}
.perfil-panel{background:var(--papel);border:1.5px solid var(--borde);border-radius:12px;overflow:hidden;box-shadow:0 2px 12px var(--sombra);}
.perfil-panel-hd{background:linear-gradient(to right,var(--caoba),var(--caoba2));padding:1rem 1.4rem;}
.perfil-panel-hd h2{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:#fff;margin:0;}
.perfil-panel-bd{padding:1.5rem;}
.ep-seccion{margin-bottom:1.4rem;}
.ep-titulo{font-family:'Playfair Display',serif;font-size:.95rem;font-weight:700;color:var(--caoba);margin-bottom:1rem;padding-bottom:.5rem;border-bottom:1.5px solid var(--borde2);}
.ep-grid{display:grid;grid-template-columns:1fr 1fr;gap:.85rem;}
.ep-full{grid-column:1/-1;}
.fld{display:flex;flex-direction:column;gap:.28rem;}
.fld label{font-size:.77rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--tinta2);}
.fld input,.fld select{background:var(--lino);border:1.5px solid var(--borde);border-radius:8px;padding:.52rem .9rem;font-family:'Source Sans 3',sans-serif;font-size:.9rem;color:var(--tinta);transition:border-color .15s,background .15s;}
.fld input:focus,.fld select:focus{outline:none;border-color:var(--amb);background:#fff;box-shadow:0 0 0 3px rgba(184,114,42,.1);}
.fld input:disabled{background:var(--lino2);color:var(--g1);cursor:not-allowed;}
.fld-hint{font-size:.74rem;color:var(--g1);}
#camposDomicilio{margin-top:.6rem;}
.ep-footer{display:flex;align-items:center;justify-content:flex-end;gap:.75rem;padding-top:1.2rem;border-top:1.5px solid var(--borde2);margin-top:1.2rem;}
.btn-guardar{display:inline-flex;align-items:center;gap:.4rem;padding:.6rem 1.3rem;background:var(--caoba);color:#fff;border:none;border-radius:8px;font-size:.9rem;font-weight:700;cursor:pointer;transition:background .15s;font-family:'Source Sans 3',sans-serif;}
.btn-guardar:hover{background:var(--caoba2);}
.btn-cancelar{display:inline-flex;align-items:center;gap:.4rem;padding:.6rem 1.1rem;background:var(--lino2);color:var(--caoba);border:1.5px solid var(--borde);border-radius:8px;font-size:.9rem;font-weight:600;text-decoration:none;transition:background .15s;font-family:'Source Sans 3',sans-serif;}
.btn-cancelar:hover{background:var(--borde);color:var(--caoba);}
.ep-msg{padding:.7rem 1rem;border-radius:8px;font-size:.86rem;margin-bottom:1rem;display:none;}
.ep-msg.ok{background:#e8f5e9;border:1.5px solid #a5d6a7;color:var(--verde);}
.ep-msg.error{background:#fdf0f0;border:1.5px solid #f5c6c6;color:#c0392b;}
.ep-msg.show{display:block;}
@media(max-width:680px){.perfil-inner{grid-template-columns:1fr;}.perfil-sidebar{position:static;}.ep-grid{grid-template-columns:1fr;}.ep-full{grid-column:1;}}
</style>

<div class="perfil-page">
    <div class="perfil-inner">

        <aside class="perfil-sidebar">
            <div class="perfil-sidebar-top">
                <div class="perfil-avatar"><i class="fas fa-user"></i></div>
                <div class="perfil-nombre">
                    <?= htmlspecialchars(mb_convert_case($cliente['Nombre'], MB_CASE_TITLE, 'UTF-8') . ' ' . mb_convert_case($cliente['Apellido'], MB_CASE_TITLE, 'UTF-8')) ?>
                </div>
                <div class="perfil-dni">DNI <?= htmlspecialchars($cliente['DNI']) ?></div>
            </div>
            <nav class="perfil-nav">
                <a href="<?= URL ?>cliente/perfil">
                    <i class="fas fa-user" style="width:16px;text-align:center;"></i> Mi Perfil
                </a>
                <a href="<?= URL ?>pedidocliente">
                    <i class="fas fa-shopping-bag" style="width:16px;text-align:center;"></i> Mis Pedidos
                </a>
                <a href="<?= URL ?>cliente/editar" class="active">
                    <i class="fas fa-edit" style="width:16px;text-align:center;"></i> Editar Perfil
                </a>
                <div class="perfil-nav-sep"></div>
                <a href="<?= URL ?>login/logout" class="danger">
                    <i class="fas fa-sign-out-alt" style="width:16px;text-align:center;"></i> Cerrar Sesión
                </a>
            </nav>
        </aside>

        <div class="perfil-panel">
            <div class="perfil-panel-hd">
                <h2>Editar Perfil</h2>
            </div>
            <div class="perfil-panel-bd">

                <?= Toast::flash() ?>
                <div class="ep-msg" id="epMsg"></div>

                <form id="formEditar">

                    <div class="ep-seccion">
                        <div class="ep-titulo">Datos personales</div>
                        <div class="ep-grid">
                            <div class="fld">
                                <label>Nombre *</label>
                                <input type="text" name="nombre" id="epNombre" maxlength="30" required
                                       style="text-transform:capitalize;"
                                       value="<?= htmlspecialchars(mb_convert_case($cliente['Nombre'], MB_CASE_TITLE, 'UTF-8')) ?>">
                            </div>
                            <div class="fld">
                                <label>Apellido *</label>
                                <input type="text" name="apellido" id="epApellido" maxlength="30" required
                                       style="text-transform:capitalize;"
                                       value="<?= htmlspecialchars(mb_convert_case($cliente['Apellido'], MB_CASE_TITLE, 'UTF-8')) ?>">
                            </div>
                            <div class="fld">
                                <label>DNI</label>
                                <input type="text" value="<?= htmlspecialchars($cliente['DNI']) ?>" disabled>
                                <span class="fld-hint">El DNI no puede modificarse</span>
                            </div>
                            <div class="fld">
                                <label>Teléfono</label>
                                <input type="text" name="telefono" maxlength="20"
                                       value="<?= htmlspecialchars($cliente['Telefono'] ?? '') ?>"
                                       placeholder="Ej: 351-1234567">
                            </div>
                            <div class="fld ep-full">
                                <label>Localidad *</label>
                                <select name="localidad" required>
                                    <option value="">— Seleccioná —</option>
                                    <?php foreach ($localidades as $l): ?>
                                        <option value="<?= $l['Id'] ?>"
                                            <?= $cliente['IdLocalidad'] == $l['Id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($l['Nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="ep-seccion">
                        <div class="ep-titulo">Domicilio</div>
                        <div class="fld" style="margin-bottom:.85rem;">
                            <label>Tipo de domicilio *</label>
                            <select name="tipo_domicilio" id="tipoDomicilio" required
                                    onchange="mostrarCamposDomicilio(this.value)">
                                <option value="">— Seleccioná —</option>
                                <?php foreach ($tiposDomicilio as $td): ?>
                                    <option value="<?= $td['Id'] ?>"
                                        <?= $cliente['IdTipodomicilio'] == $td['Id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($td['Nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div id="camposDomicilio"></div>
                    </div>

                    <div class="ep-footer">
                        <a href="<?= URL ?>cliente/perfil" class="btn-cancelar">Cancelar</a>
                        <button type="submit" class="btn-guardar" id="btnGuardar">
                            <i class="fas fa-save"></i> Guardar cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<script>
const clienteActual = {
    calle:        <?= json_encode($cliente['Calle']        ?? '') ?>,
    numero:       <?= json_encode($cliente['Numero']       ?? '') ?>,
    piso:         <?= json_encode($cliente['Piso']         ?? '') ?>,
    numeroPiso:   <?= json_encode($cliente['numeroPiso']   ?? '') ?>,
    barrio:       <?= json_encode($cliente['Barrio']       ?? '') ?>,
    country:      <?= json_encode($cliente['Country']      ?? '') ?>,
    departamento: <?= json_encode($cliente['Departamento'] ?? '') ?>,
    codigoPostal: <?= json_encode($cliente['CodigoPostal'] ?? '') ?>,
};

['epNombre', 'epApellido'].forEach(id => {
    const el = document.getElementById(id);
    if (!el) return;
    el.addEventListener('input', function () {
        const pos = this.selectionStart;
        this.value = this.value.replace(/\b\w/g, c => c.toUpperCase());
        this.setSelectionRange(pos, pos);
    });
});

const campoCp = (val = '') => `
    <div class="fld">
        <label>Código postal</label>
        <input type="text" name="nd_codigo_postal" maxlength="10"
               placeholder="Ej: 5000" value="${val}">
    </div>`;

function mostrarCamposDomicilio(tipo, precargar = false) {
    const cont = document.getElementById('camposDomicilio');
    const c    = precargar ? clienteActual : {};
    let html   = '';

    if (tipo === '1') {
        html = `
        <div class="ep-grid">
            <div class="fld">
                <label>Calle *</label>
                <input type="text" name="nd_calle" required value="${c.calle ?? ''}">
            </div>
            <div class="fld">
                <label>Número *</label>
                <input type="number" name="nd_numero" required value="${c.numero ?? ''}">
            </div>
            ${campoCp(c.codigoPostal ?? '')}
        </div>`;
    } else if (tipo === '2') {
        html = `
        <div class="ep-grid">
            <div class="fld">
                <label>Calle *</label>
                <input type="text" name="nd_calle" required value="${c.calle ?? ''}">
            </div>
            <div class="fld">
                <label>Número *</label>
                <input type="number" name="nd_numero" required value="${c.numero ?? ''}">
            </div>
            <div class="fld">
                <label>Piso</label>
                <input type="number" name="nd_piso" value="${c.piso ?? ''}">
            </div>
            <div class="fld">
                <label>N° Departamento</label>
                <input type="text" name="nd_numero_piso" value="${c.numeroPiso ?? ''}">
            </div>
            ${campoCp(c.codigoPostal ?? '')}
        </div>`;
    } else if (tipo === '3') {
        html = `
        <div class="ep-grid">
            <div class="fld ep-full">
                <label>Nombre del barrio / country *</label>
                <input type="text" name="nd_country" required value="${c.country ?? ''}">
            </div>
            <div class="fld ep-full">
                <label>Manzana / Barrio</label>
                <input type="text" name="nd_barrio" value="${c.barrio ?? ''}">
            </div>
            <div class="fld">
                <label>Calle *</label>
                <input type="text" name="nd_calle" required value="${c.calle ?? ''}">
            </div>
            <div class="fld">
                <label>Número *</label>
                <input type="number" name="nd_numero" required value="${c.numero ?? ''}">
            </div>
            ${campoCp(c.codigoPostal ?? '')}
        </div>`;
    }

    cont.innerHTML = html;
}

document.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('tipoDomicilio');
    if (sel.value) mostrarCamposDomicilio(sel.value, true);
});

document.getElementById('formEditar').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('btnGuardar');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando…';

    const msg = document.getElementById('epMsg');
    msg.className = 'ep-msg';

    try {
        const resp = await fetch('<?= URL ?>cliente/guardar', {
            method: 'POST',
            body: new FormData(e.target)
        });
        const data = await resp.json();

        if (data.success) {
            msg.textContent = data.mensaje || 'Perfil actualizado correctamente.';
            msg.className = 'ep-msg ok show';
            setTimeout(() => window.location.href = '<?= URL ?>cliente/perfil', 1500);
        } else {
            msg.textContent = data.error || 'Error al guardar los datos.';
            msg.className = 'ep-msg error show';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Guardar cambios';
        }
    } catch {
        msg.textContent = 'Error al comunicarse con el servidor.';
        msg.className = 'ep-msg error show';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Guardar cambios';
    }
});
</script>

<?php include INCLUDES . 'footer_cliente.php'; ?>