<?php
require_once 'app/config/core_config.php';
require_once 'app/classes/Autoloader.php';
Autoloader::init();
require_once 'app/classes/Controller.php';
require_once 'app/classes/Model.php';
?>


<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script></style>

<main class="content">
<style>
:root{
  --caoba:#5c2d0a;--caoba2:#7a3e14;--amb:#b8722a;
  --lino:#f7f0e6;--lino2:#ede4d4;--papel:#fdfaf6;
  --tinta:#2c1a0e;--tinta2:#4a3020;--g1:#8a7560;
  --borde:#d4c4aa;--borde2:#e8dcc8;--sombra:rgba(92,45,10,.08);
  --rojo:#c0392b;--verde:#2e7d32;--azul:#1565c0;--naranja:#e65100;
  --amarillo:#f57f17;
}
*{box-sizing:border-box;}
.vt-page{font-family:'Source Sans 3',Georgia,sans-serif;color:var(--tinta);}
.vt-hd{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1.2rem;}
.vt-hd h1{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--caoba);margin:0;}
.badges-row{display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:1.2rem;}
.vt-badge{display:inline-flex;align-items:center;gap:.35rem;padding:.32rem .9rem;border-radius:20px;font-size:.8rem;font-weight:700;border:1.5px solid transparent;text-decoration:none;transition:all .15s;}
.vt-badge.all{background:var(--lino2);color:var(--tinta2);border-color:var(--borde);}
.vt-badge.all.active,.vt-badge.all:hover{background:var(--caoba);color:#fff;border-color:var(--caoba);}
.vt-badge.aprobado{background:#e8f5e9;color:var(--verde);border-color:#a5d6a7;}
.vt-badge.aprobado.active,.vt-badge.aprobado:hover{background:var(--verde);color:#fff;}
.vt-badge.pendiente{background:#fff8e1;color:var(--amarillo);border-color:#ffe082;}
.vt-badge.pendiente.active,.vt-badge.pendiente:hover{background:var(--amarillo);color:#fff;}
.vt-badge.rechazado{background:#fdf0f0;color:var(--rojo);border-color:#f5c6c6;}
.vt-badge.rechazado.active,.vt-badge.rechazado:hover{background:var(--rojo);color:#fff;}
.badge-count{background:rgba(0,0,0,.12);border-radius:10px;padding:1px 7px;font-size:.73rem;}
.vt-filters{display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1.2rem;align-items:center;}
.vt-filters input{flex:1;min-width:220px;background:var(--papel);border:1.5px solid var(--borde);border-radius:7px;padding:.46rem 1rem;font-family:'Source Sans 3',sans-serif;font-size:.9rem;color:var(--tinta);}
.vt-filters input:focus{outline:none;border-color:var(--amb);box-shadow:0 0 0 3px rgba(184,114,42,.1);}
.btn-vt{display:inline-flex;align-items:center;gap:.4rem;padding:.46rem 1rem;border-radius:7px;font-size:.86rem;font-weight:600;font-family:'Source Sans 3',sans-serif;text-decoration:none;border:none;cursor:pointer;transition:background .15s,transform .12s;}
.btn-vt:hover{transform:translateY(-1px);}
.btn-vt-primary{background:var(--caoba);color:#fff;}
.btn-vt-primary:hover{background:var(--caoba2);color:#fff;}
.btn-vt-secondary{background:var(--lino2);color:var(--caoba);border:1.5px solid var(--borde);}
.btn-vt-secondary:hover{background:var(--borde);}
.btn-vt-sm{padding:.26rem .6rem;font-size:.78rem;}
.vt-table-wrap{background:var(--papel);border:1.5px solid var(--borde);border-radius:10px;overflow:hidden;box-shadow:0 2px 12px var(--sombra);}
.vt-table{width:100%;border-collapse:collapse;}
.vt-table thead tr{background:linear-gradient(to right,var(--caoba),var(--caoba2));}
.vt-table thead th{padding:.72rem 1rem;text-align:left;font-size:.74rem;font-weight:700;letter-spacing:.06em;color:rgba(255,255,255,.9);text-transform:uppercase;border-right:1px solid rgba(255,255,255,.12);}
.vt-table thead th:last-child{border-right:none;}
.vt-table tbody tr{border-bottom:1px solid var(--borde2);transition:background .12s;}
.vt-table tbody tr:last-child{border-bottom:none;}
.vt-table tbody tr:hover{background:var(--lino);}
.vt-table tbody td{padding:.65rem 1rem;font-size:.86rem;color:var(--tinta2);border-right:1px solid var(--borde2);vertical-align:middle;}
.vt-table tbody td:last-child{border-right:none;}
.td-main{font-weight:700;color:var(--caoba);}
.td-tag{display:inline-block;padding:2px 9px;border-radius:20px;font-size:.72rem;font-weight:700;background:var(--lino2);color:var(--tinta2);border:1px solid var(--borde);}
.td-fecha{font-size:.8rem;color:var(--g1);}
.td-monto{font-family:'Playfair Display',serif;font-size:.95rem;color:var(--verde);font-weight:700;}
.pill{display:inline-flex;align-items:center;gap:.25rem;padding:3px 10px;border-radius:20px;font-size:.73rem;font-weight:700;}
.pill-aprobado{background:#e8f5e9;color:var(--verde);border:1px solid #a5d6a7;}
.pill-pendiente{background:#fff8e1;color:var(--amarillo);border:1px solid #ffe082;}
.pill-rechazado{background:#fdf0f0;color:var(--rojo);border:1px solid #f5c6c6;}
.pill-otro{background:var(--lino2);color:var(--tinta2);border:1px solid var(--borde);}
.vt-vacio{text-align:center;padding:3.5rem 1rem;color:var(--g1);}
.vt-vacio i{font-size:2.8rem;opacity:.22;display:block;margin-bottom:.6rem;}
.modal-bg{display:none;position:fixed;inset:0;background:rgba(44,26,14,.48);z-index:1000;align-items:center;justify-content:center;padding:1rem;overflow-y:auto;}
.modal-bg.open{display:flex;}
.modal{background:var(--papel);border-radius:12px;width:100%;max-width:640px;box-shadow:0 8px 40px rgba(0,0,0,.28);overflow:hidden;animation:mIn .2s ease;margin:auto;}
@keyframes mIn{from{opacity:0;transform:translateY(-14px)}to{opacity:1;transform:translateY(0)}}
.modal-hd{background:linear-gradient(to right,var(--caoba),var(--caoba2));padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;}
.modal-hd h3{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:#fff;margin:0;}
.modal-close{background:none;border:none;color:rgba(255,255,255,.7);font-size:1.3rem;cursor:pointer;padding:0;line-height:1;}
.modal-close:hover{color:#fff;}
.modal-body{padding:1.25rem;max-height:80vh;overflow-y:auto;}
.modal-ft{padding:.9rem 1.25rem;border-top:1px solid var(--borde2);background:var(--lino);display:flex;gap:.5rem;justify-content:flex-end;align-items:center;}
.fld{margin-bottom:1rem;}
.fld label{display:block;margin-bottom:.3rem;font-size:.77rem;font-weight:700;color:var(--tinta2);text-transform:uppercase;letter-spacing:.03em;}
.fld input,.fld select{width:100%;background:var(--lino);border:1.5px solid var(--borde);border-radius:7px;padding:.5rem .85rem;font-family:'Source Sans 3',sans-serif;font-size:.9rem;color:var(--tinta);transition:border-color .15s;}
.fld input:focus,.fld select:focus{outline:none;border-color:var(--amb);background:#fff;box-shadow:0 0 0 3px rgba(184,114,42,.1);}
.fld-row{display:grid;grid-template-columns:1fr 1fr;gap:.75rem;}
.modal-divider{font-size:.73rem;text-transform:uppercase;letter-spacing:.07em;color:var(--g1);font-weight:700;margin:.25rem 0 .75rem;border-bottom:1px solid var(--borde2);padding-bottom:.3rem;}
.modal-info-grid{display:grid;grid-template-columns:1fr 1fr;gap:.5rem .75rem;background:var(--lino2);border:1px solid var(--borde);border-radius:8px;padding:.75rem 1rem;margin-bottom:1rem;font-size:.84rem;}
.mi-label{color:var(--g1);font-size:.74rem;font-weight:700;text-transform:uppercase;letter-spacing:.03em;}
.mi-value{color:var(--tinta);font-weight:600;}
.prod-table{width:100%;border-collapse:collapse;font-size:.84rem;margin-bottom:.75rem;}
.prod-table th{background:var(--lino2);padding:.4rem .6rem;font-size:.72rem;text-transform:uppercase;letter-spacing:.04em;color:var(--g1);font-weight:700;border-bottom:1px solid var(--borde);}
.prod-table td{padding:.4rem .6rem;border-bottom:1px solid var(--borde2);vertical-align:middle;}
.prod-table tr:last-child td{border-bottom:none;}
.prod-add-row{display:flex;gap:.4rem;align-items:flex-end;margin-bottom:.75rem;}
.prod-add-row select,.prod-add-row input{background:var(--lino);border:1.5px solid var(--borde);border-radius:7px;padding:.4rem .7rem;font-size:.87rem;font-family:'Source Sans 3',sans-serif;color:var(--tinta);}
.prod-add-row select{flex:2;}
.prod-add-row input{flex:1;min-width:70px;}
.prod-add-row button{flex-shrink:0;}
.resumen-totales{background:var(--lino2);border:1px solid var(--borde);border-radius:8px;padding:.7rem 1rem;margin-top:.5rem;}
.resumen-fila{display:flex;justify-content:space-between;align-items:center;font-size:.87rem;color:var(--tinta2);padding:.2rem 0;}
.resumen-fila.total{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:var(--verde);border-top:1.5px solid var(--borde);margin-top:.4rem;padding-top:.5rem;}
.resumen-fila.interes{color:var(--naranja);}
.cuota-info{font-size:.78rem;color:var(--g1);text-align:right;margin-top:.2rem;}
</style>

<div class="vt-page">

  <div class="vt-hd">
    <h1>Ventas</h1>
    <button class="btn-vt btn-vt-primary" onclick="abrirModalPresencial()">
      + Nueva venta presencial
    </button>
  </div>

  <?php
    $claseEstado = function(string $n): string {
        $n = mb_strtolower($n);
        if (str_contains($n, 'aprob'))  return 'aprobado';
        if (str_contains($n, 'pend'))   return 'pendiente';
        if (str_contains($n, 'rechaz')) return 'rechazado';
        return 'otro';
    };
    $totalGeneral = array_sum($conteos);
  ?>

  <div class="badges-row">
    <a href="<?= URL ?>venta<?= !empty($buscar) ? '?buscar='.urlencode($buscar) : '' ?>"
       class="vt-badge all <?= $idEstado === 0 ? 'active' : '' ?>">
      Todas <span class="badge-count"><?= $totalGeneral ?></span>
    </a>
    <?php foreach ($estados as $est):
        $cls   = $claseEstado($est['Nombre']);
        $count = $conteos[$est['Nombre']] ?? 0;
        $href  = URL . 'venta?estado=' . $est['Id'] . (!empty($buscar) ? '&buscar='.urlencode($buscar) : '');
    ?>
    <a href="<?= $href ?>" class="vt-badge <?= $cls ?> <?= $idEstado === (int)$est['Id'] ? 'active' : '' ?>">
      <?= htmlspecialchars($est['Nombre']) ?>
      <span class="badge-count"><?= $count ?></span>
    </a>
    <?php endforeach; ?>
  </div>

  <form class="vt-filters" method="GET" action="<?= URL ?>venta">
    <?php if ($idEstado > 0): ?>
      <input type="hidden" name="estado" value="<?= $idEstado ?>">
    <?php endif; ?>
    <input type="text" name="buscar"
           value="<?= htmlspecialchars($buscar) ?>"
           placeholder="Buscar por cliente, producto o número de venta…">
    <button type="submit" class="btn-vt btn-vt-primary">Buscar</button>
    <?php if (!empty($buscar) || $idEstado > 0): ?>
      <a href="<?= URL ?>venta" class="btn-vt btn-vt-secondary">Limpiar</a>
    <?php endif; ?>
  </form>

  <div class="vt-table-wrap">
   <table class="vt-table" id="tablaVentas">
      <thead>
        <tr>
          <th>N° Venta</th>
          <th>Cliente</th>
          <th>Productos</th>
          <th>Tipo pago</th>
          <th>Estado pago</th>
          <th>Entrega</th>
          <th>Fecha</th>
          <th>Total</th>
          <th>Detalle</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!empty($ventas)): ?>
        <?php foreach ($ventas as $v):
            $clsEp = $claseEstado($v['EstadoPagoNombre'] ?? '');
        ?>
        <tr>
          <td>
            <span class="td-tag"><?= $v['NumerodeVenta'] ?></span>
            <?php if ($v['NumeroComprobante']): ?>
              <div style="font-size:.74rem;color:var(--g1);margin-top:2px;">
                Fact. <?= $v['NumeroComprobante'] ?>
              </div>
            <?php endif; ?>
          </td>

          <td class="td-main">
            <?= htmlspecialchars($v['ClienteNombre'] . ' ' . $v['ClienteApellido']) ?>
            <?php if ($v['ClienteTelefono']): ?>
              <div style="font-size:.76rem;font-weight:400;color:var(--g1);">
                <?= htmlspecialchars($v['ClienteTelefono']) ?>
              </div>
            <?php endif; ?>
          </td>

          <td style="max-width:180px;font-size:.83rem;">
            <?= htmlspecialchars($v['Productos'] ?? '—') ?>
          </td>

          <td style="font-size:.83rem;">
            <?= htmlspecialchars($v['TipoPagoNombre'] ?? '—') ?>
            <?php if (!empty($v['MarcaTarjeta'])): ?>
              <div style="font-size:.74rem;color:var(--g1);">
                <?= htmlspecialchars($v['MarcaTarjeta']) ?>
                <?php if ($v['Cuotas'] > 1): ?> · <?= $v['Cuotas'] ?> cuotas<?php endif; ?>
              </div>
            <?php endif; ?>
          </td>

          <td>
            <span class="pill pill-<?= $clsEp ?>">
              <?= htmlspecialchars($v['EstadoPagoNombre'] ?? '—') ?>
            </span>
          </td>

          <td style="font-size:.82rem;">
            <?php if (!empty($v['CodigoEntrega'])): ?>
              <span style="font-family:monospace;background:var(--lino2);padding:2px 6px;border-radius:4px;border:1px solid var(--borde);">
                <?= htmlspecialchars($v['CodigoEntrega']) ?>
              </span>
              <?php if (!empty($v['EstadoEntregaNombre'])): ?>
                <div style="font-size:.74rem;color:var(--g1);margin-top:2px;">
                  <?= htmlspecialchars($v['EstadoEntregaNombre']) ?>
                </div>
              <?php endif; ?>
            <?php else: ?>
              <span style="color:var(--g1);">—</span>
            <?php endif; ?>
          </td>

          <td class="td-fecha">
            <?= !empty($v['FechadeEmision']) ? date('d/m/Y', strtotime($v['FechadeEmision'])) : '—' ?>
          </td>

          <td class="td-monto">
            $<?= number_format($v['MontoTotal'] ?? 0, 2, ',', '.') ?>
          </td>

          <td>
            <button class="btn-vt btn-vt-secondary btn-vt-sm"
                    onclick="abrirDetalle(<?= htmlspecialchars(json_encode($v)) ?>)">
              Ver
            </button>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="modal-bg" id="modalDetalle">
  <div class="modal">
    <div class="modal-hd">
      <h3 id="detTitulo">Detalle de venta</h3>
      <button class="modal-close" onclick="cerrarDetalle()">✕</button>
    </div>
    <div class="modal-body">
      <div class="modal-info-grid">
        <div><div class="mi-label">N° Venta</div><div class="mi-value" id="dNroVenta">—</div></div>
        <div><div class="mi-label">N° Factura</div><div class="mi-value" id="dNroFactura">—</div></div>
        <div><div class="mi-label">Cliente</div><div class="mi-value" id="dCliente">—</div></div>
        <div><div class="mi-label">Teléfono</div><div class="mi-value" id="dTelefono">—</div></div>
        <div><div class="mi-label">Fecha</div><div class="mi-value" id="dFecha">—</div></div>
        <div><div class="mi-label">Tipo de pago</div><div class="mi-value" id="dTipoPago">—</div></div>
        <div><div class="mi-label">Estado pago</div><div class="mi-value" id="dEstadoPago">—</div></div>
        <div><div class="mi-label">Cuotas</div><div class="mi-value" id="dCuotas">—</div></div>
        <div><div class="mi-label">Código entrega</div><div class="mi-value" id="dCodEntrega">—</div></div>
        <div><div class="mi-label">Estado entrega</div><div class="mi-value" id="dEstadoEntrega">—</div></div>
      </div>
      <div class="modal-divider">Productos</div>
      <div id="dProductos" style="font-size:.87rem;color:var(--tinta2);margin-bottom:.5rem;">—</div>
      <div class="resumen-totales">
        <div class="resumen-fila" id="dFilaInteres" style="display:none;">
          <span>Interés</span>
          <span id="dInteres">—</span>
        </div>
        <div class="resumen-fila total">
          <span>Total</span>
          <span>$<span id="dTotal">0</span></span>
        </div>
      </div>
    </div>
    <div class="modal-ft">
      <button class="btn-vt btn-vt-secondary" onclick="cerrarDetalle()">Cerrar</button>
    </div>
  </div>
</div>

<div class="modal-bg" id="modalPresencial">
  <div class="modal">
    <div class="modal-hd">
      <h3>Nueva venta presencial</h3>
      <button class="modal-close" onclick="cerrarPresencial()">✕</button>
    </div>

    <form method="POST" action="<?= URL ?>venta/presencial" id="formPresencial">

      <input type="hidden" name="items"           id="inputItems"        value="[]">
      <input type="hidden" name="cuotas"          id="inputCuotas"       value="1">
      <input type="hidden" name="marca_tarjeta"   id="inputMarca"        value="">
      <input type="hidden" name="interes"         id="inputInteres"      value="0">
      <input type="hidden" name="IdCliente"       id="inputIdCliente"    value="">
      <input type="hidden" name="tipo_entrega"    id="inputTipoEntrega"  value="1">
      <input type="hidden" name="usar_nueva_dir"  id="inputUsarNuevaDir" value="0">
      <input type="hidden" name="costo_envio"     id="inputCostoEnvio"   value="0">

      <div class="modal-body">

        <div class="fld">
          <label>Cliente *</label>
          <div style="position:relative;">
            <input
              type="text"
              id="clienteBuscador"
              placeholder="Buscá por DNI o nombre…"
              autocomplete="off"
              oninput="buscarCliente(this.value)"
              style="width:100%;padding-right:2.2rem;"
            >
            <span id="clienteSpinner" style="display:none;position:absolute;right:.7rem;
              top:50%;transform:translateY(-50%);color:var(--g1);font-size:.9rem;">
              <i class="fas fa-circle-notch fa-spin"></i>
            </span>
            <div id="clienteDropdown" style="
              display:none;position:absolute;top:100%;left:0;right:0;z-index:200;
              background:var(--papel);border:1.5px solid var(--amb);border-top:none;
              border-radius:0 0 8px 8px;max-height:220px;overflow-y:auto;
              box-shadow:0 6px 20px rgba(92,45,10,.15);
            "></div>
          </div>

          <div id="clienteSeleccionado" style="display:none;margin-top:.5rem;
            background:var(--lino2);border:1.5px solid var(--borde);border-radius:7px;
            padding:.5rem .85rem;align-items:center;justify-content:space-between;gap:.5rem;">
            <div>
              <span id="clienteNombreTag" style="font-weight:700;color:var(--caoba);font-size:.9rem;"></span>
              <span id="clienteDniTag" style="font-size:.78rem;color:var(--g1);margin-left:.5rem;"></span>
            </div>
            <button type="button" onclick="limpiarCliente()"
              style="background:none;border:none;cursor:pointer;color:var(--g1);
              font-size:.95rem;padding:0;line-height:1;">✕</button>
          </div>

          <div id="registroRapido" style="display:none;margin-top:.75rem;
            background:#fff8e1;border:1.5px solid #ffe082;border-radius:8px;padding:.85rem 1rem;">
            <div style="font-size:.83rem;font-weight:700;color:var(--amarillo);margin-bottom:.75rem;">
              <i class="fas fa-user-plus"></i> Cliente no encontrado — Registrar nuevo
            </div>
            <div class="fld-row" style="margin-bottom:.6rem;">
              <div class="fld" style="margin-bottom:0;">
                <label>DNI *</label>
                <input type="text" id="rrDni" placeholder="DNI" maxlength="8">
              </div>
              <div class="fld" style="margin-bottom:0;">
                <label>Tipo de DNI *</label>
                <select id="rrTipoDni">
                  <option value="">Seleccioná...</option>
                  <?php foreach ($tiposDni as $t): ?>
                    <option value="<?= $t['Id'] ?>"><?= htmlspecialchars($t['Nombre']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="fld-row" style="margin-bottom:.6rem;">
              <div class="fld" style="margin-bottom:0;">
                <label>Nombre *</label>
                <input type="text" id="rrNombre" placeholder="Nombre">
              </div>
              <div class="fld" style="margin-bottom:0;">
                <label>Apellido *</label>
                <input type="text" id="rrApellido" placeholder="Apellido">
              </div>
            </div>
            <div class="fld-row" style="margin-bottom:.6rem;">
              <div class="fld" style="margin-bottom:0;">
                <label>Teléfono</label>
                <input type="text" id="rrTelefono" placeholder="Teléfono" maxlength="20">
              </div>
              <div class="fld" style="margin-bottom:0;">
                <label>Localidad *</label>
                <select id="rrLocalidad">
                  <option value="">Seleccioná...</option>
                  <?php foreach ($localidades as $l): ?>
                    <option value="<?= $l['Id'] ?>"><?= htmlspecialchars($l['Nombre']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="fld" style="margin-bottom:.6rem;">
              <label>Tipo de domicilio *</label>
              <select id="rrTipoDomicilio" onchange="actualizarCamposDomicilioRr()">
                <option value="">Seleccioná...</option>
                <?php foreach ($tiposDomicilio as $td): ?>
                  <option value="<?= $td['Id'] ?>"><?= htmlspecialchars($td['Nombre']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div id="rrCamposDomicilio" style="margin-bottom:.6rem;"></div>
            <button type="button" class="btn-vt btn-vt-primary btn-vt-sm"
              onclick="registrarClienteRapido()">
              <i class="fas fa-save"></i> Guardar y seleccionar
            </button>
            <span id="rrError" style="display:none;font-size:.78rem;
              color:var(--rojo);margin-left:.5rem;"></span>
          </div>
        </div>

        <div class="fld">
          <label>Tipo de pago *</label>
          <select name="IdTipoPago" id="pTipoPago" required onchange="actualizarCamposTarjeta()">
            <?php foreach ($tiposPago as $tp): ?>
              <option value="<?= $tp['Id'] ?>"><?= htmlspecialchars($tp['Nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="fld-row" id="fila-tarjeta" style="display:none;">
          <div class="fld">
            <label>Marca</label>
            <select id="pMarca" onchange="sincronizarMarca()">
              <option value="">— Seleccioná —</option>
              <option value="visa">Visa</option>
              <option value="mastercard">Mastercard</option>
              <option value="amex">American Express</option>
              <option value="cabal">Cabal</option>
              <option value="naranja">Naranja</option>
            </select>
          </div>
          <div class="fld" id="campo-cuotas" style="display:none;">
            <label>Cuotas</label>
            <select id="pCuotas" onchange="recalcularTotal()">
              <option value="1">1 cuota (sin interés)</option>
              <option value="3">3 cuotas (+12%)</option>
              <option value="6">6 cuotas (+25%)</option>
              <option value="12">12 cuotas (+55%)</option>
            </select>
          </div>
        </div>

        <div class="modal-divider">Entrega</div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:.75rem;">
          <div id="cardRetiro" onclick="seleccionarEntrega(1)"
            style="border:2px solid var(--caoba);border-radius:10px;padding:.85rem;
            cursor:pointer;background:var(--lino);transition:all .15s;">
            <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.3rem;">
              <span style="font-size:1.1rem;">🏪</span>
              <span style="font-weight:700;color:var(--caoba);font-size:.88rem;">Retiro en sucursal</span>
            </div>
            <div style="font-size:.76rem;color:var(--g1);line-height:1.4;">
              El cliente retira en el local.
            </div>
            <div style="font-size:.78rem;font-weight:700;color:var(--verde);margin-top:.4rem;">
              ✓ Sin costo adicional
            </div>
          </div>

          <div id="cardEnvio" onclick="seleccionarEntrega(2)"
            style="border:2px solid var(--borde);border-radius:10px;padding:.85rem;
            cursor:pointer;background:#fff;transition:all .15s;">
            <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.3rem;">
              <span style="font-size:1.1rem;">🚚</span>
              <span style="font-weight:700;color:var(--tinta2);font-size:.88rem;">Envío a domicilio</span>
            </div>
            <div style="font-size:.76rem;color:var(--g1);line-height:1.4;">
              Se entrega en el domicilio del cliente.
            </div>
            <div style="font-size:.78rem;font-weight:700;color:var(--naranja);margin-top:.4rem;">
              + $20.000
            </div>
          </div>
        </div>

        <div id="seccionDirEntrega" style="display:none;margin-bottom:.75rem;
          background:var(--lino2);border:1.5px solid var(--borde);border-radius:8px;
          padding:.85rem 1rem;">

          <div id="dirClienteRegistrado" style="display:none;">
            <div style="font-size:.8rem;font-weight:700;color:var(--tinta2);margin-bottom:.4rem;">
              Domicilio registrado del cliente:
            </div>
            <div id="dirClienteTexto" style="font-size:.87rem;color:var(--tinta);
              margin-bottom:.6rem;background:#fff;border:1px solid var(--borde);
              border-radius:6px;padding:.45rem .75rem;">
            </div>
            <button type="button" onclick="mostrarNuevaDirEntrega()"
              class="btn-vt btn-vt-secondary btn-vt-sm">
              <i class="fas fa-edit"></i> Usar otra dirección
            </button>
          </div>

          <div id="dirNuevaEntrega" style="display:none;">
            <div style="font-size:.8rem;font-weight:700;color:var(--tinta2);margin-bottom:.6rem;">
              Dirección de entrega:
            </div>
            <div class="fld" style="margin-bottom:.6rem;">
              <label>Tipo de domicilio</label>
              <select id="ndTipoDomicilio" onchange="actualizarCamposDirEntrega()">
                <option value="">Seleccioná...</option>
                <?php foreach ($tiposDomicilio as $td): ?>
                  <option value="<?= $td['Id'] ?>"><?= htmlspecialchars($td['Nombre']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div id="ndCamposDomicilio"></div>
          </div>

        </div>

        <div class="modal-divider">Productos</div>

        <div class="fld" style="margin-bottom:.75rem;">
          <label>Buscar producto</label>
          <div style="position:relative;">
            <input
              type="text"
              id="productoBuscador"
              placeholder="Buscá por nombre…"
              autocomplete="off"
              oninput="buscarProducto(this.value)"
              style="width:100%;padding-right:2.2rem;"
            >
            <span id="productoSpinner" style="display:none;position:absolute;right:.7rem;
              top:50%;transform:translateY(-50%);color:var(--g1);font-size:.9rem;">
              <i class="fas fa-circle-notch fa-spin"></i>
            </span>
            <div id="productoDropdown" style="
              display:none;position:absolute;top:100%;left:0;right:0;z-index:200;
              background:var(--papel);border:1.5px solid var(--amb);border-top:none;
              border-radius:0 0 8px 8px;max-height:200px;overflow-y:auto;
              box-shadow:0 6px 20px rgba(92,45,10,.15);
            "></div>
          </div>
        </div>

        <div id="productoSeleccionadoRow" style="display:none;margin-bottom:.75rem;
          background:var(--lino2);border:1.5px solid var(--borde);border-radius:8px;
          padding:.6rem .85rem;align-items:center;gap:.75rem;flex-wrap:wrap;">
          <div style="flex:1;min-width:0;">
            <span id="productoNombreTag" style="font-weight:700;color:var(--caoba);font-size:.88rem;"></span>
            <span id="productoPrecioTag" style="font-size:.78rem;color:var(--g1);margin-left:.5rem;"></span>
          </div>
          <div style="display:flex;align-items:center;gap:.5rem;flex-shrink:0;">
            <label style="font-size:.78rem;font-weight:700;color:var(--tinta2);
              text-transform:uppercase;letter-spacing:.03em;margin:0;">Cant.</label>
            <input type="number" id="pCantidad" min="1" value="1"
              style="width:70px;background:var(--papel);border:1.5px solid var(--borde);
              border-radius:7px;padding:.35rem .6rem;font-size:.9rem;color:var(--tinta);
              font-family:'Source Sans 3',sans-serif;">
            <button type="button" class="btn-vt btn-vt-primary btn-vt-sm"
              onclick="agregarProducto()">+ Agregar</button>
            <button type="button" onclick="limpiarProducto()"
              style="background:none;border:none;cursor:pointer;color:var(--g1);
              font-size:1rem;padding:0;line-height:1;">✕</button>
          </div>
        </div>

        <table class="prod-table" id="tablaProductos">
          <thead>
            <tr>
              <th>Producto</th>
              <th>Cant.</th>
              <th>Precio unit.</th>
              <th>Subtotal</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="tbodyProductos">
            <tr id="trVacio">
              <td colspan="5" style="text-align:center;color:var(--g1);padding:.75rem;">
                Sin productos
              </td>
            </tr>
          </tbody>
        </table>

        <div class="resumen-totales">
          <div class="resumen-fila">
            <span>Subtotal productos</span>
            <span>$<span id="pSubtotal">0,00</span></span>
          </div>
          <div class="resumen-fila" id="pEnvioRow" style="display:none;">
            <span>Costo de envío</span>
            <span style="color:var(--naranja);font-weight:700;">+ $20.000</span>
          </div>
          <div class="resumen-fila interes" id="pInteresRow" style="display:none;">
            <span>Interés (<span id="pInteresPorc">0</span>%)</span>
            <span>+ $<span id="pInteresVal">0,00</span></span>
          </div>
          <div class="resumen-fila total">
            <span>Total</span>
            <span>$<span id="pTotal">0,00</span></span>
          </div>
        </div>
        <div class="cuota-info" id="pCuotaInfo" style="display:none;"></div>

      </div>

      <div class="modal-ft">
        <span style="font-size:.82rem;color:var(--g1);">
          La venta se registra como <strong>Aprobada</strong>
        </span>
        <button type="button" class="btn-vt btn-vt-secondary" onclick="cerrarPresencial()">
          Cancelar
        </button>
        <button type="submit" class="btn-vt btn-vt-primary" id="btnGuardar" disabled>
          Registrar venta
        </button>
      </div>
    </form>
  </div>
</div>

<script>
const productosData  = <?= json_encode(array_column($productos, null, 'Id')) ?>;
const INTERESES_PRES = { 1: 0.00, 3: 0.12, 6: 0.25, 12: 0.55 };
const ID_CREDITO     = '1';
const ID_DEBITO      = '2';
const COSTO_ENVIO    = 20000;

let itemsPresencial      = [];
let clienteTimer         = null;
let clienteElegido       = false;
let productoTimer        = null;
let productoSeleccionado = null;
let tipoEntregaActual    = 1;
let domicilioCliente     = null;

function abrirDetalle(v) {
  document.getElementById('detTitulo').textContent      = 'Venta ' + v.NumerodeVenta;
  document.getElementById('dNroVenta').textContent      = '' + v.NumerodeVenta;
  document.getElementById('dNroFactura').textContent    = v.NumeroComprobante ? '' + v.NumeroComprobante : '—';
  document.getElementById('dCliente').textContent       = (v.ClienteNombre ?? '') + ' ' + (v.ClienteApellido ?? '');
  document.getElementById('dTelefono').textContent      = v.ClienteTelefono ?? '—';
  document.getElementById('dFecha').textContent         = v.FechadeEmision
    ? v.FechadeEmision.substring(0, 10).split('-').reverse().join('/') : '—';
  document.getElementById('dTipoPago').textContent      = v.TipoPagoNombre ?? '—';
  document.getElementById('dEstadoPago').textContent    = v.EstadoPagoNombre ?? '—';
  document.getElementById('dCuotas').textContent        = v.Cuotas > 1 ? v.Cuotas + ' cuotas' : 'Pago único';
  document.getElementById('dCodEntrega').textContent    = v.CodigoEntrega ?? '—';
  document.getElementById('dEstadoEntrega').textContent = v.EstadoEntregaNombre ?? '—';
  document.getElementById('dProductos').textContent     = v.Productos ?? '—';
  document.getElementById('dTotal').textContent         =
    parseFloat(v.MontoTotal || 0).toLocaleString('es-AR', { minimumFractionDigits: 2 });

  const interes     = parseFloat(v.Interes || 0);
  const filaInteres = document.getElementById('dFilaInteres');
  if (interes > 0) {
    document.getElementById('dInteres').textContent =
      '+ $' + interes.toLocaleString('es-AR', { minimumFractionDigits: 2 });
    filaInteres.style.display = '';
  } else {
    filaInteres.style.display = 'none';
  }

  document.getElementById('modalDetalle').classList.add('open');
}

function cerrarDetalle() {
  document.getElementById('modalDetalle').classList.remove('open');
}

function abrirModalPresencial() {
  itemsPresencial   = [];
  domicilioCliente  = null;
  tipoEntregaActual = 1;

  limpiarCliente();
  limpiarProducto();

  document.getElementById('pTipoPago').selectedIndex    = 0;
  document.getElementById('pMarca').value               = '';
  document.getElementById('pCuotas').value              = '1';
  document.getElementById('fila-tarjeta').style.display = 'none';
  document.getElementById('campo-cuotas').style.display = 'none';
  seleccionarEntrega(1);
  document.getElementById('ndCamposDomicilio').innerHTML = '';
  document.getElementById('ndTipoDomicilio').value       = '';

  renderTabla();
  document.getElementById('modalPresencial').classList.add('open');
}

function cerrarPresencial() {
  document.getElementById('modalPresencial').classList.remove('open');
}

function buscarCliente(valor) {
  clearTimeout(clienteTimer);

  const dropdown = document.getElementById('clienteDropdown');
  const spinner  = document.getElementById('clienteSpinner');
  const registro = document.getElementById('registroRapido');

  if (valor.trim().length < 2) {
    dropdown.style.display = 'none';
    registro.style.display = 'none';
    return;
  }

  spinner.style.display = '';

  clienteTimer = setTimeout(async () => {
    try {
      const res  = await fetch(`<?= URL ?>venta/buscarCliente?q=` + encodeURIComponent(valor.trim()));
      const data = await res.json();

      spinner.style.display = 'none';
      dropdown.innerHTML    = '';

      if (data.length === 0) {
        dropdown.style.display = 'none';
        registro.style.display = '';
        if (/^\d+$/.test(valor.trim())) {
          document.getElementById('rrDni').value = valor.trim();
        }
        return;
      }

      registro.style.display = 'none';

      data.forEach(c => {
        const item = document.createElement('div');
        item.style.cssText = `
          padding:.55rem 1rem;cursor:pointer;font-size:.88rem;color:var(--tinta2);
          border-bottom:1px solid var(--borde2);transition:background .1s;
        `;
        item.innerHTML = `
          <strong style="color:var(--caoba);">${c.Apellido}, ${c.Nombre}</strong>
          <span style="color:var(--g1);margin-left:.5rem;font-size:.78rem;">DNI ${c.DNI}</span>
          ${c.Telefono
            ? `<span style="color:var(--g1);margin-left:.4rem;font-size:.76rem;">· ${c.Telefono}</span>`
            : ''}
        `;
        item.addEventListener('mouseenter', () => item.style.background = 'var(--lino)');
        item.addEventListener('mouseleave', () => item.style.background = '');
        item.addEventListener('click',      () => seleccionarCliente(c));
        dropdown.appendChild(item);
      });

      dropdown.style.display = '';

    } catch (e) {
      spinner.style.display  = 'none';
      dropdown.style.display = 'none';
    }
  }, 350);
}

function seleccionarCliente(c) {
  document.getElementById('inputIdCliente').value         = c.Id;
  document.getElementById('clienteNombreTag').textContent = c.Apellido + ', ' + c.Nombre;
  document.getElementById('clienteDniTag').textContent    = 'DNI ' + c.DNI;

  document.getElementById('clienteSeleccionado').style.display = 'flex';
  document.getElementById('clienteBuscador').style.display     = 'none';
  document.getElementById('clienteDropdown').style.display     = 'none';
  document.getElementById('registroRapido').style.display      = 'none';
  clienteElegido = true;
  cargarDomicilioCliente(c.Id);

  validarBotonGuardar();
}

async function cargarDomicilioCliente(idCliente) {
  domicilioCliente = null;
  try {
    const res  = await fetch(`<?= URL ?>venta/getDomicilioCliente?id=` + idCliente);
    const data = await res.json();
    domicilioCliente = data ?? null;
  } catch (e) {
    domicilioCliente = null;
  }

  if (tipoEntregaActual === 2) {
    mostrarDomicilioEntrega();
  }
}

function limpiarCliente() {
  document.getElementById('inputIdCliente').value              = '';
  document.getElementById('clienteBuscador').value             = '';
  document.getElementById('clienteBuscador').style.display     = '';
  document.getElementById('clienteSeleccionado').style.display = 'none';
  document.getElementById('clienteDropdown').style.display     = 'none';
  document.getElementById('registroRapido').style.display      = 'none';
  document.getElementById('rrNombre').value                    = '';
  document.getElementById('rrApellido').value                  = '';
  document.getElementById('rrDni').value                       = '';
  document.getElementById('rrTipoDni').value                   = '';
  document.getElementById('rrTelefono').value                  = '';
  document.getElementById('rrLocalidad').value                 = '';
  document.getElementById('rrTipoDomicilio').value             = '';
  document.getElementById('rrCamposDomicilio').innerHTML       = '';
  document.getElementById('rrError').style.display             = 'none';
  domicilioCliente = null;
  clienteElegido   = false;
  validarBotonGuardar();
}

function seleccionarEntrega(tipo) {
  tipoEntregaActual = tipo;

  const cardRetiro = document.getElementById('cardRetiro');
  const cardEnvio  = document.getElementById('cardEnvio');
  const secDir     = document.getElementById('seccionDirEntrega');
  const envioRow   = document.getElementById('pEnvioRow');

  if (tipo === 1) {
    cardRetiro.style.border     = '2px solid var(--caoba)';
    cardRetiro.style.background = 'var(--lino)';
    cardEnvio.style.border      = '2px solid var(--borde)';
    cardEnvio.style.background  = '#fff';
    secDir.style.display        = 'none';
    envioRow.style.display      = 'none';
    document.getElementById('inputTipoEntrega').value  = '1';
    document.getElementById('inputCostoEnvio').value   = '0';
    document.getElementById('inputUsarNuevaDir').value = '0';
  } else {
    cardEnvio.style.border      = '2px solid var(--caoba)';
    cardEnvio.style.background  = 'var(--lino)';
    cardRetiro.style.border     = '2px solid var(--borde)';
    cardRetiro.style.background = '#fff';
    secDir.style.display        = '';
    envioRow.style.display      = '';
    document.getElementById('inputTipoEntrega').value  = '2';
    document.getElementById('inputCostoEnvio').value   = COSTO_ENVIO;
    mostrarDomicilioEntrega();
  }

  recalcularTotal();
}

function mostrarDomicilioEntrega() {
  const divRegistrado = document.getElementById('dirClienteRegistrado');
  const divNueva      = document.getElementById('dirNuevaEntrega');

  if (domicilioCliente && domicilioCliente.Calle) {
    let texto = domicilioCliente.Calle + ' ' + (domicilioCliente.Numero ?? '');
    if (domicilioCliente.Barrio) texto += ' — ' + domicilioCliente.Barrio;
    document.getElementById('dirClienteTexto').textContent = texto;
    divRegistrado.style.display = '';
    divNueva.style.display      = 'none';
    document.getElementById('inputUsarNuevaDir').value = '0';
  } else {
    divRegistrado.style.display = 'none';
    divNueva.style.display      = '';
    document.getElementById('inputUsarNuevaDir').value = '1';
  }
}

function mostrarNuevaDirEntrega() {
  document.getElementById('dirClienteRegistrado').style.display = 'none';
  document.getElementById('dirNuevaEntrega').style.display      = '';
  document.getElementById('inputUsarNuevaDir').value            = '1';
}

function actualizarCamposDirEntrega() {
  const tipo = parseInt(document.getElementById('ndTipoDomicilio').value);
  const cont = document.getElementById('ndCamposDomicilio');

  if (!tipo) { cont.innerHTML = ''; return; }

  let html = `
    <div class="fld-row" style="margin-bottom:.6rem;">
      <div class="fld" style="margin-bottom:0;">
        <label>Calle</label>
        <input type="text" id="ndCalle" name="nd_calle" placeholder="Calle">
      </div>
      <div class="fld" style="margin-bottom:0;">
        <label>Número</label>
        <input type="number" id="ndNumero" name="nd_numero" placeholder="Número" min="0">
      </div>
    </div>
  `;

  if (tipo === 2) {
    html += `
      <div class="fld-row" style="margin-bottom:.6rem;">
        <div class="fld" style="margin-bottom:0;">
          <label>Piso</label>
          <input type="number" id="ndPiso" name="nd_piso" placeholder="Piso" min="0">
        </div>
        <div class="fld" style="margin-bottom:0;">
          <label>N° de departamento</label>
          <input type="number" id="ndNumeroPiso" name="nd_numero_piso" placeholder="Depto." min="0">
        </div>
      </div>
    `;
  }

  if (tipo === 3) {
    html += `
      <div class="fld-row" style="margin-bottom:.6rem;">
        <div class="fld" style="margin-bottom:0;">
          <label>Country / Barrio privado</label>
          <input type="text" id="ndCountry" name="nd_country" placeholder="Nombre del country">
        </div>
        <div class="fld" style="margin-bottom:0;">
          <label>Barrio / Manzana</label>
          <input type="text" id="ndBarrio" name="nd_barrio" placeholder="Manzana, sector…">
        </div>
      </div>
    `;
  }

  cont.innerHTML = html;
}

function buscarProducto(valor) {
  clearTimeout(productoTimer);

  const dropdown = document.getElementById('productoDropdown');
  const spinner  = document.getElementById('productoSpinner');

  if (valor.trim().length < 2) {
    dropdown.style.display = 'none';
    return;
  }

  spinner.style.display = '';

  productoTimer = setTimeout(() => {
    const q        = valor.trim().toLowerCase();
    const matching = Object.values(productosData).filter(p =>
      p.Nombre.toLowerCase().includes(q)
    );

    spinner.style.display = 'none';
    dropdown.innerHTML    = '';

    if (matching.length === 0) {
      const vacio = document.createElement('div');
      vacio.style.cssText = 'padding:.6rem 1rem;font-size:.85rem;color:var(--g1);';
      vacio.textContent   = 'Sin resultados';
      dropdown.appendChild(vacio);
      dropdown.style.display = '';
      return;
    }

    matching.forEach(p => {
      const item = document.createElement('div');
      item.style.cssText = `
        padding:.5rem 1rem;cursor:pointer;font-size:.87rem;color:var(--tinta2);
        border-bottom:1px solid var(--borde2);transition:background .1s;
        display:flex;justify-content:space-between;align-items:center;gap:.5rem;
      `;
      item.innerHTML = `
        <span style="font-weight:700;color:var(--caoba);">${p.Nombre}</span>
        <span style="color:var(--verde);font-family:'Playfair Display',serif;
          font-size:.85rem;white-space:nowrap;">
          $${parseFloat(p.PrecioVenta).toLocaleString('es-AR', { minimumFractionDigits: 2 })}
        </span>
      `;
      item.addEventListener('mouseenter', () => item.style.background = 'var(--lino)');
      item.addEventListener('mouseleave', () => item.style.background = '');
      item.addEventListener('click',      () => elegirProducto(p));
      dropdown.appendChild(item);
    });

    dropdown.style.display = '';
  }, 250);
}

function elegirProducto(p) {
  productoSeleccionado = {
    id_producto: parseInt(p.Id),
    nombre:      p.Nombre,
    precio:      parseFloat(p.PrecioVenta),
  };

  document.getElementById('productoNombreTag').textContent = p.Nombre;
  document.getElementById('productoPrecioTag').textContent =
    '$' + parseFloat(p.PrecioVenta).toLocaleString('es-AR', { minimumFractionDigits: 2 }) + ' c/u';
  document.getElementById('pCantidad').value = 1;

  document.getElementById('productoBuscador').style.display        = 'none';
  document.getElementById('productoDropdown').style.display        = 'none';
  document.getElementById('productoSeleccionadoRow').style.display = 'flex';
}

function limpiarProducto() {
  productoSeleccionado = null;
  document.getElementById('productoBuscador').value                = '';
  document.getElementById('productoBuscador').style.display        = '';
  document.getElementById('productoDropdown').style.display        = 'none';
  document.getElementById('productoSeleccionadoRow').style.display = 'none';
  document.getElementById('pCantidad').value = 1;
}

function agregarProducto() {
  if (!productoSeleccionado) {
    alert('Seleccioná un producto primero');
    return;
  }

  const cant = parseInt(document.getElementById('pCantidad').value) || 1;
  if (cant < 1) return;

  const existing = itemsPresencial.find(i => i.id_producto === productoSeleccionado.id_producto);
  if (existing) {
    existing.cantidad += cant;
  } else {
    itemsPresencial.push({ ...productoSeleccionado, cantidad: cant });
  }

  limpiarProducto();
  renderTabla();
}

function quitarProducto(idx) {
  itemsPresencial.splice(idx, 1);
  renderTabla();
}

function actualizarCamposDomicilioRr() {
  const tipo = parseInt(document.getElementById('rrTipoDomicilio').value);
  const cont = document.getElementById('rrCamposDomicilio');

  if (!tipo) { cont.innerHTML = ''; return; }

  let html = `
    <div class="fld-row" style="margin-bottom:.6rem;">
      <div class="fld" style="margin-bottom:0;">
        <label>Calle</label>
        <input type="text" id="rrCalle" placeholder="Calle">
      </div>
      <div class="fld" style="margin-bottom:0;">
        <label>Número</label>
        <input type="number" id="rrNumero" placeholder="Número" min="0">
      </div>
    </div>
  `;

  if (tipo === 2) {
    html += `
      <div class="fld-row" style="margin-bottom:.6rem;">
        <div class="fld" style="margin-bottom:0;">
          <label>Piso</label>
          <input type="number" id="rrPiso" placeholder="Piso" min="0">
        </div>
        <div class="fld" style="margin-bottom:0;">
          <label>N° de departamento</label>
          <input type="number" id="rrNumeroPiso" placeholder="Depto." min="0">
        </div>
      </div>
    `;
  }

  if (tipo === 3) {
    html += `
      <div class="fld-row" style="margin-bottom:.6rem;">
        <div class="fld" style="margin-bottom:0;">
          <label>Country / Barrio privado</label>
          <input type="text" id="rrCountry" placeholder="Nombre del country">
        </div>
        <div class="fld" style="margin-bottom:0;">
          <label>Barrio / Manzana</label>
          <input type="text" id="rrBarrio" placeholder="Manzana, sector…">
        </div>
      </div>
    `;
  }

  cont.innerHTML = html;
}

async function registrarClienteRapido() {
  const errSpan = document.getElementById('rrError');
  errSpan.style.display = 'none';

  const nombre      = document.getElementById('rrNombre').value.trim();
  const apellido    = document.getElementById('rrApellido').value.trim();
  const dni         = document.getElementById('rrDni').value.trim();
  const idTipoDni   = document.getElementById('rrTipoDni').value;
  const telefono    = document.getElementById('rrTelefono').value.trim();
  const idLocalidad = document.getElementById('rrLocalidad').value;
  const idTipoDom   = document.getElementById('rrTipoDomicilio').value;

  const calle      = document.getElementById('rrCalle')?.value.trim()   ?? '';
  const numero     = document.getElementById('rrNumero')?.value         ?? '';
  const piso       = document.getElementById('rrPiso')?.value           ?? '';
  const numeroPiso = document.getElementById('rrNumeroPiso')?.value     ?? '';
  const country    = document.getElementById('rrCountry')?.value.trim() ?? '';
  const barrio     = document.getElementById('rrBarrio')?.value.trim()  ?? '';

  if (!nombre || !apellido || !dni || !idTipoDni || !idLocalidad || !idTipoDom) {
    errSpan.textContent   = 'Completá todos los campos obligatorios.';
    errSpan.style.display = '';
    return;
  }

  const btn = document.querySelector('#registroRapido .btn-vt');
  btn.disabled  = true;
  btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Guardando…';

  try {
    const res = await fetch('<?= URL ?>venta/registrarCliente', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        nombre, apellido, dni,
        idTipoDni:       parseInt(idTipoDni),
        telefono,
        idLocalidad:     parseInt(idLocalidad),
        idTipoDomicilio: parseInt(idTipoDom),
        calle,
        numero:          parseInt(numero)     || 0,
        piso:            parseInt(piso)       || 0,
        numeroPiso:      parseInt(numeroPiso) || 0,
        country,
        barrio,
      }),
    });

    const data = await res.json();

    if (data.error) {
      errSpan.textContent   = data.error;
      errSpan.style.display = '';
      btn.disabled  = false;
      btn.innerHTML = '<i class="fas fa-save"></i> Guardar y seleccionar';
      return;
    }

    seleccionarCliente(data);

  } catch (e) {
    errSpan.textContent   = 'Error de conexión. Intentá de nuevo.';
    errSpan.style.display = '';
    btn.disabled  = false;
    btn.innerHTML = '<i class="fas fa-save"></i> Guardar y seleccionar';
  }
}

function actualizarCamposTarjeta() {
  const tipo      = document.getElementById('pTipoPago').value;
  const esTarjeta = tipo === ID_CREDITO || tipo === ID_DEBITO;
  const esCredito = tipo === ID_CREDITO;

  document.getElementById('fila-tarjeta').style.display = esTarjeta ? '' : 'none';
  document.getElementById('campo-cuotas').style.display = esCredito ? '' : 'none';

  if (!esCredito) {
    document.getElementById('pCuotas').value = '1';
  }
  sincronizarMarca();
  recalcularTotal();
}

function sincronizarMarca() {
  document.getElementById('inputMarca').value = document.getElementById('pMarca').value;
}

function renderTabla() {
  const tbody   = document.getElementById('tbodyProductos');
  const trVacio = document.getElementById('trVacio');

  tbody.innerHTML = '';

  if (itemsPresencial.length === 0) {
    tbody.appendChild(trVacio);
    document.getElementById('inputItems').value = '[]';
    recalcularTotal();
    validarBotonGuardar();
    return;
  }

  itemsPresencial.forEach((item, idx) => {
    const subtotal = item.precio * item.cantidad;
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${item.nombre}</td>
      <td>${item.cantidad}</td>
      <td>$${item.precio.toLocaleString('es-AR', { minimumFractionDigits: 2 })}</td>
      <td>$${subtotal.toLocaleString('es-AR', { minimumFractionDigits: 2 })}</td>
      <td>
        <button type="button" onclick="quitarProducto(${idx})"
          style="background:none;border:none;cursor:pointer;color:var(--rojo);font-size:1rem;">✕</button>
      </td>
    `;
    tbody.appendChild(tr);
  });

  document.getElementById('inputItems').value = JSON.stringify(
    itemsPresencial.map(i => ({
      id_producto: i.id_producto,
      cantidad:    i.cantidad,
      precio:      i.precio,
    }))
  );

  recalcularTotal();
  validarBotonGuardar();
}

function recalcularTotal() {
  const subtotal   = itemsPresencial.reduce((s, i) => s + i.precio * i.cantidad, 0);
  const costoEnvio = tipoEntregaActual === 2 ? COSTO_ENVIO : 0;
  const base       = subtotal + costoEnvio;
  const cuotas     = parseInt(document.getElementById('pCuotas').value) || 1;
  const porc       = INTERESES_PRES[cuotas] ?? 0;
  const interes    = Math.round(base * porc * 100) / 100;
  const total      = Math.round((base + interes) * 100) / 100;
  const cuotaVal   = cuotas > 1 ? Math.round(total / cuotas * 100) / 100 : 0;

  document.getElementById('pSubtotal').textContent =
    subtotal.toLocaleString('es-AR', { minimumFractionDigits: 2 });
  document.getElementById('pTotal').textContent =
    total.toLocaleString('es-AR', { minimumFractionDigits: 2 });

  document.getElementById('inputCuotas').value  = cuotas;
  document.getElementById('inputInteres').value = interes.toFixed(2);

  const interesRow = document.getElementById('pInteresRow');
  const cuotaInfo  = document.getElementById('pCuotaInfo');

  if (interes > 0) {
    document.getElementById('pInteresPorc').textContent = Math.round(porc * 100);
    document.getElementById('pInteresVal').textContent  =
      interes.toLocaleString('es-AR', { minimumFractionDigits: 2 });
    interesRow.style.display = '';
    cuotaInfo.style.display  = '';
    cuotaInfo.textContent    =
      `${cuotas} cuotas de $${cuotaVal.toLocaleString('es-AR', { minimumFractionDigits: 2 })} c/u`;
  } else {
    interesRow.style.display = 'none';
    cuotaInfo.style.display  = 'none';
  }
}

function validarBotonGuardar() {
  const hayCliente   = clienteElegido && document.getElementById('inputIdCliente').value !== '';
  const hayProductos = itemsPresencial.length > 0;
  document.getElementById('btnGuardar').disabled = !(hayCliente && hayProductos);
}

document.addEventListener('click', e => {
  const buscadorC = document.getElementById('clienteBuscador');
  const dropdownC = document.getElementById('clienteDropdown');
  if (buscadorC && dropdownC &&
      !buscadorC.contains(e.target) && !dropdownC.contains(e.target)) {
    dropdownC.style.display = 'none';
  }

  const buscadorP = document.getElementById('productoBuscador');
  const dropdownP = document.getElementById('productoDropdown');
  if (buscadorP && dropdownP &&
      !buscadorP.contains(e.target) && !dropdownP.contains(e.target)) {
    dropdownP.style.display = 'none';
  }
});

['modalDetalle', 'modalPresencial'].forEach(id => {
  document.getElementById(id).addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('open');
  });
});
document.addEventListener('DOMContentLoaded', function () {
    const tablaVentas = $('#tablaVentas').DataTable({
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
        order: [[0, 'desc']], 
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 } 
        ]
    });
});
</script>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
<?php include INCLUDES . 'admin' . DS . 'modals_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'scripts_admin.php'; ?>