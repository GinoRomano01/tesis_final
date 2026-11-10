<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script></style>
<main class="content">
<style>
:root {
  --caoba: #5c2d0a; --caoba2: #7a3e14; --amb: #b8722a;
  --lino: #f7f0e6; --lino2: #ede4d4; --papel: #fdfaf6;
  --tinta: #2c1a0e; --tinta2: #4a3020; --g1: #8a7560;
  --borde: #d4c4aa; --borde2: #e8dcc8; --sombra: rgba(92,45,10,.08);
  --rojo: #c0392b; --verde: #2e7d32; --azul: #1565c0;
}
*{box-sizing:border-box;}
.us-page{font-family:'Source Sans 3',Georgia,sans-serif;color:var(--tinta);}
.us-hd{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1.2rem;}
.us-hd h1{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--caoba);margin:0;}
.badges-row{display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:1.2rem;}
.us-badge{display:inline-flex;align-items:center;gap:.35rem;padding:.32rem .9rem;border-radius:20px;font-size:.8rem;font-weight:700;border:1.5px solid transparent;text-decoration:none;transition:all .15s;cursor:pointer;}
.us-badge.all{background:var(--lino2);color:var(--tinta2);border-color:var(--borde);}
.us-badge.all.active,.us-badge.all:hover{background:var(--caoba);color:#fff;border-color:var(--caoba);}
.us-badge.tipo{background:#e3f2fd;color:var(--azul);border-color:#90caf9;}
.us-badge.tipo.active,.us-badge.tipo:hover{background:var(--azul);color:#fff;}
.us-badge.bajas{background:#fdf0f0;color:var(--rojo);border-color:#f5c6c6;}
.us-badge.bajas.active,.us-badge.bajas:hover{background:var(--rojo);color:#fff;}
.badge-count{background:rgba(0,0,0,.12);border-radius:10px;padding:1px 7px;font-size:.73rem;}
.us-filters{display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1.2rem;align-items:center;}
.us-filters input{flex:1;min-width:220px;background:var(--papel);border:1.5px solid var(--borde);border-radius:7px;padding:.46rem 1rem;font-family:'Source Sans 3',sans-serif;font-size:.9rem;color:var(--tinta);}
.us-filters input:focus{outline:none;border-color:var(--amb);box-shadow:0 0 0 3px rgba(184,114,42,.1);}
.btn-us{display:inline-flex;align-items:center;gap:.4rem;padding:.46rem 1rem;border-radius:7px;font-size:.86rem;font-weight:600;font-family:'Source Sans 3',sans-serif;text-decoration:none;border:none;cursor:pointer;transition:background .15s,transform .12s;}
.btn-us:hover{transform:translateY(-1px);}
.btn-us-primary{background:var(--caoba);color:#fff;}
.btn-us-primary:hover{background:var(--caoba2);color:#fff;}
.btn-us-secondary{background:var(--lino2);color:var(--caoba);border:1.5px solid var(--borde);}
.btn-us-secondary:hover{background:var(--borde);}
.btn-us-danger{background:#fdf0f0;color:var(--rojo);border:1.5px solid #f5c6c6;}
.btn-us-danger:hover{background:#fad7d7;}
.btn-us-success{background:#e8f5e9;color:var(--verde);border:1.5px solid #a5d6a7;}
.btn-us-success:hover{background:var(--verde);color:#fff;}
.btn-us-sm{padding:.26rem .6rem;font-size:.78rem;}
.us-table-wrap{background:var(--papel);border:1.5px solid var(--borde);border-radius:10px;overflow:hidden;box-shadow:0 2px 12px var(--sombra);}
.us-table{width:100%;border-collapse:collapse;}
.us-table thead tr{background:linear-gradient(to right,var(--caoba),var(--caoba2));}
.us-table thead th{padding:.72rem 1rem;text-align:left;font-size:.74rem;font-weight:700;letter-spacing:.06em;color:rgba(255,255,255,.9);text-transform:uppercase;border-right:1px solid rgba(255,255,255,.12);}
.us-table thead th:last-child{border-right:none;}
.us-table tbody tr{border-bottom:1px solid var(--borde2);transition:background .12s;}
.us-table tbody tr:last-child{border-bottom:none;}
.us-table tbody tr:hover{background:var(--lino);}
.us-table tbody tr.baja-row{opacity:.6;background:#fdf5f5;}
.us-table tbody td{padding:.65rem 1rem;font-size:.86rem;color:var(--tinta2);border-right:1px solid var(--borde2);vertical-align:middle;}
.us-table tbody td:last-child{border-right:none;}
.td-main{font-weight:700;color:var(--caoba);}
.td-acc{display:flex;gap:.3rem;flex-wrap:wrap;}
.pill{display:inline-flex;align-items:center;gap:.25rem;padding:3px 10px;border-radius:20px;font-size:.73rem;font-weight:700;}
.pill-admin{background:#e3f2fd;color:var(--azul);border:1px solid #90caf9;}
.pill-cliente{background:#e8f5e9;color:var(--verde);border:1px solid #a5d6a7;}
.us-vacio{text-align:center;padding:3.5rem 1rem;color:var(--g1);}
.us-vacio span{font-size:2.8rem;opacity:.22;display:block;margin-bottom:.6rem;}
.modal-bg{display:none;position:fixed;inset:0;background:rgba(44,26,14,.48);z-index:1000;align-items:center;justify-content:center;padding:1rem;overflow-y:auto;}
.modal-bg.open{display:flex;}
.modal{background:var(--papel);border-radius:12px;width:100%;max-width:560px;box-shadow:0 8px 40px rgba(0,0,0,.28);overflow:hidden;animation:mIn .2s ease;margin:auto;}
@keyframes mIn{from{opacity:0;transform:translateY(-14px)}to{opacity:1;transform:translateY(0)}}
.modal-hd{background:linear-gradient(to right,var(--caoba),var(--caoba2));padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;}
.modal-hd h3{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:#fff;margin:0;}
.modal-close{background:none;border:none;color:rgba(255,255,255,.7);font-size:1.3rem;cursor:pointer;padding:0;line-height:1;}
.modal-close:hover{color:#fff;}
.modal-body{padding:1.25rem;max-height:75vh;overflow-y:auto;}
.modal-ft{padding:.9rem 1.25rem;border-top:1px solid var(--borde2);background:var(--lino);display:flex;gap:.5rem;justify-content:flex-end;}
.fld{margin-bottom:.9rem;}
.fld label{display:block;margin-bottom:.28rem;font-size:.77rem;font-weight:700;color:var(--tinta2);text-transform:uppercase;letter-spacing:.03em;}
.fld input,.fld select{width:100%;background:var(--lino);border:1.5px solid var(--borde);border-radius:7px;padding:.48rem .85rem;font-family:'Source Sans 3',sans-serif;font-size:.9rem;color:var(--tinta);transition:border-color .15s;}
.fld input:focus,.fld select:focus{outline:none;border-color:var(--amb);background:#fff;box-shadow:0 0 0 3px rgba(184,114,42,.1);}
.fld-row{display:grid;grid-template-columns:1fr 1fr;gap:.75rem;}
.modal-divider{font-size:.73rem;text-transform:uppercase;letter-spacing:.07em;color:var(--g1);font-weight:700;margin:.1rem 0 .75rem;border-bottom:1px solid var(--borde2);padding-bottom:.3rem;}
.hint{font-size:.74rem;color:var(--g1);margin-top:.2rem;}
.alerta-mail{display:none;font-size:.78rem;color:var(--rojo);margin-top:.25rem;font-weight:600;}
.alerta-mail.show{display:block;}
</style>

<?php
  $esBajas  = $mostrarBajas;
  $totalGen = array_sum($conteos);
?>

<div class="us-page">

  <div class="us-hd">
    <h1>Usuarios<?= $esBajas ? ' — Dados de baja' : '' ?></h1>
    <?php if (!$esBajas): ?>
      <button class="btn-us btn-us-primary" onclick="abrirCrear()">Nuevo usuario</button>
    <?php endif; ?>
  </div>

  <div class="badges-row">
    <?php if (!$esBajas): ?>
      <a href="<?= URL ?>usuarioadmin<?= !empty($buscar) ? '?buscar='.urlencode($buscar) : '' ?>"
         class="us-badge all <?= $idTipoUsuario === 0 ? 'active' : '' ?>">
         Todos <span class="badge-count"><?= $totalGen ?></span>
      </a>
      <?php foreach ($tiposUsuario as $tu):
          $count = $conteos[$tu['Nombre']] ?? 0;
          $href  = URL . 'usuarioadmin?tipo=' . $tu['Id'] . (!empty($buscar) ? '&buscar='.urlencode($buscar) : '');
      ?>
      <a href="<?= $href ?>"
         class="us-badge tipo <?= $idTipoUsuario === (int)$tu['Id'] ? 'active' : '' ?>">
         <?= htmlspecialchars($tu['Nombre']) ?>
        <span class="badge-count"><?= $count ?></span>
      </a>
      <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($esBajas): ?>
      <a href="<?= URL ?>usuarioadmin" class="us-badge all active">← Volver a activos</a>
    <?php else: ?>
      <a href="<?= URL ?>usuarioadmin?bajas" class="us-badge bajas">Ver dados de baja</a>
    <?php endif; ?>
  </div>

  <form class="us-filters" method="GET" action="<?= URL ?>usuarioadmin">
    <?php if ($idTipoUsuario > 0): ?>
      <input type="hidden" name="tipo" value="<?= $idTipoUsuario ?>">
    <?php endif; ?>
    <?php if ($esBajas): ?>
      <input type="hidden" name="bajas" value="1">
    <?php endif; ?>
    <input type="text" name="buscar"
           value="<?= htmlspecialchars($buscar) ?>"
           placeholder="Buscar por nombre, apellido o correo…">
    <button type="submit" class="btn-us btn-us-primary">Buscar</button>
    <?php if (!empty($buscar)): ?>
      <a href="<?= URL ?>usuarioadmin<?= $esBajas ? '?bajas' : '' ?>"
         class="btn-us btn-us-secondary">✕ Limpiar</a>
    <?php endif; ?>
  </form>

  <div class="us-table-wrap">
    <table class="us-table" id="tablaUsuarios">
      <thead>
        <tr>
          <th>Nombre completo</th>
          <th>Usuario / Correo</th>
          <th>Tipo usuario</th>
          <th>Rol</th>
          <th>Localidad</th>
          <?php if ($esBajas): ?><th>Baja</th><?php endif; ?>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!empty($usuarios)): ?>
        <?php foreach ($usuarios as $u): ?>
        <tr <?= $esBajas ? 'class="baja-row"' : '' ?>>

          <td class="td-main">
            <?php
              $nombreDisplay   = mb_convert_case($u['ClienteNombre']   ?? '', MB_CASE_TITLE, 'UTF-8');
              $apellidoDisplay = mb_convert_case($u['ClienteApellido'] ?? '', MB_CASE_TITLE, 'UTF-8');
            ?>
            <?= htmlspecialchars($nombreDisplay . ' ' . $apellidoDisplay) ?>
            <?php if (!empty($u['DNI'])): ?>
              <div style="font-size:.75rem;font-weight:400;color:var(--g1);">
                DNI: <?= htmlspecialchars($u['DNI']) ?>
              </div>
            <?php endif; ?>
          </td>

          <td>
            <div style="font-weight:600;"><?= htmlspecialchars($u['NombredeUsuario']) ?></div>
            <div style="font-size:.78rem;color:var(--g1);"><?= htmlspecialchars($u['CorreoElectronico']) ?></div>
          </td>

          <td>
            <?php
              $nomTu = $u['TipoUsuarioNombre'] ?? '—';
              $clsTu = mb_strtolower($nomTu) === 'admin' ? 'pill-admin' : 'pill-cliente';
            ?>
            <span class="pill <?= $clsTu ?>"><?= htmlspecialchars($nomTu) ?></span>
          </td>

          <td style="font-size:.83rem;"><?= htmlspecialchars($u['TipoRolNombre'] ?? '—') ?></td>

          <td style="font-size:.82rem;"><?= htmlspecialchars($u['LocalidadNombre'] ?? '—') ?></td>

          <?php if ($esBajas): ?>
          <td style="font-size:.78rem;">
            <?= !empty($u['FechaBorrado']) ? date('d/m/Y', strtotime($u['FechaBorrado'])) : '—' ?>
          </td>
          <?php endif; ?>

          <td>
            <div class="td-acc">
              <?php if ($esBajas): ?>
                <a href="<?= URL ?>usuarioadmin/restaurar/<?= $u['Id'] ?>"
                   class="btn-us btn-us-success btn-us-sm"
                   onclick="return confirm('¿Restaurar este usuario?')">
                   Restaurar
                </a>
              <?php else: ?>
                <button class="btn-us btn-us-secondary btn-us-sm"
                        onclick="abrirEditar(<?= htmlspecialchars(json_encode($u)) ?>)">
                  ✏️ Editar
                </button>
                <?php if ($u['Id'] != ($_SESSION['usuario_id'] ?? 0)): ?>
                  <a href="<?= URL ?>usuarioadmin/baja/<?= $u['Id'] ?>"
                     class="btn-us btn-us-danger btn-us-sm"
                     onclick="return confirm('¿Dar de baja a <?= addslashes(mb_convert_case($u['ClienteNombre'] ?? '', MB_CASE_TITLE, 'UTF-8')) ?>?')">
                    🗑️ Baja
                  </a>
                <?php endif; ?>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="modal-bg" id="modalUsuario">
  <div class="modal">
    <div class="modal-hd">
      <h3 id="modalTitulo">Nuevo usuario</h3>
      <button class="modal-close" onclick="cerrarModal()">✕</button>
    </div>
    <form method="POST" action="<?= URL ?>usuarioadmin/guardar" id="formUsuario">
      <input type="hidden" name="Id" id="fId" value="0">
      <div class="modal-body">

        <div class="modal-divider">Datos personales</div>
        <div class="fld-row">
          <div class="fld">
            <label>Nombre *</label>
            <input type="text" name="Nombre" id="fNombre" required maxlength="30"
                   style="text-transform:capitalize;">
          </div>
          <div class="fld">
            <label>Apellido *</label>
            <input type="text" name="Apellido" id="fApellido" required maxlength="30"
                   style="text-transform:capitalize;">
          </div>
        </div>
        <div class="fld-row">
          <div class="fld">
            <label>DNI</label>
            <input type="text" name="DNI" id="fDNI" maxlength="20" placeholder="Opcional">
          </div>
          <div class="fld">
            <label>Teléfono</label>
            <input type="text" name="Telefono" id="fTelefono" maxlength="20" placeholder="Opcional">
          </div>
        </div>
        <div class="fld-row">
          <div class="fld">
            <label>Localidad</label>
            <select name="IdLocalidad" id="fLocalidad">
              <option value="">— Sin especificar —</option>
              <?php foreach ($localidades as $loc): ?>
                <option value="<?= $loc['Id'] ?>"><?= htmlspecialchars($loc['Nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="fld">
            <label>Código postal</label>
            <input type="text" name="CodigoPostal" id="fCodigoPostal"
                   maxlength="10" placeholder="Ej: 5000">
          </div>
        </div>

        <div class="modal-divider" style="margin-top:.5rem;">Acceso</div>
        <div class="fld">
          <label>Nombre de usuario *</label>
          <input type="text" name="NombredeUsuario" id="fUsername" required maxlength="40">
        </div>
        <div class="fld">
          <label>Correo electrónico *</label>
          <input type="email" name="CorreoElectronico" id="fCorreo" required maxlength="50"
                 oninput="limpiarAlertaMail()">
          <div class="alerta-mail" id="alertaMail">⚠️ Ese correo ya está registrado</div>
        </div>
        <div class="fld">
          <label>Contraseña
            <span style="font-weight:400;text-transform:none;font-size:.74rem;color:var(--g1);">
              (obligatoria al crear)
            </span>
          </label>
          <input type="password" name="Contrasena" id="fPass" maxlength="100"
                 placeholder="Dejar vacío para no cambiar (edición)">
          <div class="hint" id="passHintEdit" style="display:none;">
            Dejá en blanco para mantener la contraseña actual.
          </div>
        </div>

        <div class="modal-divider" style="margin-top:.5rem;">Permisos</div>
        <div class="fld-row">
          <div class="fld">
            <label>Tipo de usuario *</label>
            <select name="IdTipodeUsuario" id="fTipoUsuario" required>
              <?php foreach ($tiposUsuario as $tu): ?>
                <option value="<?= $tu['Id'] ?>"><?= htmlspecialchars($tu['Nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="fld">
            <label>Rol *</label>
            <select name="IdTipodeRol" id="fRol" required>
              <?php foreach ($tiposRol as $tr): ?>
                <option value="<?= $tr['Id'] ?>"><?= htmlspecialchars($tr['Nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

      </div>
      <div class="modal-ft">
        <button type="button" class="btn-us btn-us-secondary" onclick="cerrarModal()">Cancelar</button>
        <button type="submit" class="btn-us btn-us-primary" id="btnGuardar">💾 Guardar</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#tablaUsuarios').DataTable({
        language: {
            processing: "Procesando...",
            info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            infoEmpty: "Mostrando registros del 0 al 0 de un total de 0",
            infoFiltered: "(filtrado de un total de _MAX_ registros)",
            loadingRecords: "Cargando...",
            zeroRecords: "No se encontraron resultados",
            emptyTable: "Ningún dato disponible en esta tabla",
            paginate: {
                first: "Primero",
                previous: "Anterior",
                next: "Siguiente",
                last: "Último"
            },
            aria: {
                sortAscending: ": Activar para ordenar la columna de manera ascendente",
                sortDescending: ": Activar para ordenar la columna de manera descendente"
            }
        },
        pageLength: 10,
        searching: false,
        lengthChange: false,
        order: [[0, 'asc']],
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 }
        ]
    });
});

const correoOriginal = { value: '' };

['fNombre', 'fApellido'].forEach(id => {
    document.getElementById(id).addEventListener('input', function () {
        const pos = this.selectionStart;
        this.value = this.value.replace(/\b\w/g, c => c.toUpperCase());
        this.setSelectionRange(pos, pos);
    });
});

function capitalize(str) {
    if (!str) return '';
    return str.toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
}

function abrirCrear() {
    document.getElementById('modalTitulo').textContent      = 'Nuevo usuario';
    document.getElementById('fId').value                    = 0;
    document.getElementById('fNombre').value                = '';
    document.getElementById('fApellido').value              = '';
    document.getElementById('fDNI').value                   = '';
    document.getElementById('fTelefono').value              = '';
    document.getElementById('fLocalidad').value             = '';
    document.getElementById('fCodigoPostal').value          = '';
    document.getElementById('fUsername').value              = '';
    document.getElementById('fCorreo').value                = '';
    document.getElementById('fPass').value                  = '';
    document.getElementById('fTipoUsuario').value           = '';
    document.getElementById('fRol').value                   = '';
    document.getElementById('passHintEdit').style.display   = 'none';
    document.getElementById('fPass').placeholder            = 'Contraseña obligatoria';
    document.getElementById('btnGuardar').disabled          = false;
    limpiarAlertaMail();
    correoOriginal.value = '';
    document.getElementById('modalUsuario').classList.add('open');
}

function abrirEditar(u) {
    document.getElementById('modalTitulo').textContent      = 'Editar usuario #' + u.Id;
    document.getElementById('fId').value                    = u.Id;
    document.getElementById('fNombre').value                = capitalize(u.ClienteNombre    ?? '');
    document.getElementById('fApellido').value              = capitalize(u.ClienteApellido ?? '');
    document.getElementById('fDNI').value                   = u.DNI            ?? '';
    document.getElementById('fTelefono').value              = u.Telefono       ?? '';
    document.getElementById('fLocalidad').value             = u.IdLocalidad    ?? '';
    document.getElementById('fCodigoPostal').value          = u.CodigoPostal   ?? '';
    document.getElementById('fUsername').value              = u.NombredeUsuario   ?? '';
    document.getElementById('fCorreo').value                = u.CorreoElectronico ?? '';
    document.getElementById('fPass').value                  = '';
    document.getElementById('fTipoUsuario').value           = u.IdTipodeUsuario   ?? '';
    document.getElementById('fRol').value                   = u.IdTipodeRol       ?? '';
    document.getElementById('passHintEdit').style.display   = 'block';
    document.getElementById('fPass').placeholder            = 'Dejar vacío para no cambiar';
    document.getElementById('btnGuardar').disabled          = false;
    limpiarAlertaMail();
    correoOriginal.value = u.CorreoElectronico ?? '';
    document.getElementById('modalUsuario').classList.add('open');
}

function cerrarModal() {
    document.getElementById('modalUsuario').classList.remove('open');
}

function limpiarAlertaMail() {
    document.getElementById('alertaMail').classList.remove('show');
}

document.getElementById('fCorreo').addEventListener('blur', function () {
    const correo = this.value.trim();
    const id     = parseInt(document.getElementById('fId').value) || 0;
    if (!correo || correo === correoOriginal.value) return;

    fetch('<?= URL ?>usuarioadmin/checkcorreo', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'correo=' + encodeURIComponent(correo) + '&id=' + id
    })
    .then(r => r.json())
    .then(data => {
        if (data.existe) {
            document.getElementById('alertaMail').classList.add('show');
            document.getElementById('btnGuardar').disabled = true;
        } else {
            limpiarAlertaMail();
            document.getElementById('btnGuardar').disabled = false;
        }
    })
    .catch(() => {});
});

document.getElementById('modalUsuario').addEventListener('click', function (e) {
    if (e.target === this) cerrarModal();
});
</script>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
<?php include INCLUDES . 'admin' . DS . 'modals_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'scripts_admin.php'; ?>