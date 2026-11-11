<?php include INCLUDES . 'admin' . DS . 'head_admin.php'; ?>
<?php include INCLUDES . 'admin' . DS . 'sidebar_admin.php'; ?>
<div class="main-area">
<?php include INCLUDES . 'admin' . DS . 'topbar_admin.php'; ?>
<main class="content">
<style>
:root{--caoba:#5c2d0a;--caoba2:#7a3e14;--amb:#b8722a;--lino:#f7f0e6;--lino2:#ede4d4;--papel:#fdfaf6;--tinta:#2c1a0e;--tinta2:#4a3020;--g1:#8a7560;--borde:#d4c4aa;--borde2:#e8dcc8;--sombra:rgba(92,45,10,.08);--rojo:#c0392b;--verde:#2e7d32;}
*{box-sizing:border-box;}
.mr-page{font-family:'Source Sans 3',Georgia,sans-serif;color:var(--tinta);}
.mr-hd{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:.4rem;}
.mr-hd h1{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--caoba);margin:0;}
.mr-sub{font-size:.85rem;color:var(--g1);margin-bottom:1.3rem;}

.mr-vacio{text-align:center;padding:3.5rem 1rem;color:var(--g1);background:var(--papel);border:1.5px solid var(--borde);border-radius:10px;}
.mr-vacio span{font-size:2.6rem;opacity:.25;display:block;margin-bottom:.6rem;}

.mr-stop{display:flex;gap:1rem;align-items:flex-start;background:var(--papel);border:1.5px solid var(--borde);border-radius:10px;padding:1rem 1.1rem;margin-bottom:.8rem;}
.mr-stop.entregada{opacity:.55;}
.mr-num{width:34px;height:34px;border-radius:50%;background:var(--caoba);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;flex-shrink:0;font-family:'Playfair Display',serif;}
.mr-stop.entregada .mr-num{background:var(--verde);}
.mr-info{flex:1;min-width:0;}
.mr-nom{font-weight:700;color:var(--caoba);font-size:1rem;}
.mr-meta{font-size:.83rem;color:var(--g1);margin-top:2px;line-height:1.5;}
.mr-zona{display:inline-block;padding:2px 9px;border-radius:20px;font-size:.72rem;font-weight:700;background:#f3ebe0;color:#7a4e2d;border:1px solid #d4b896;margin-top:4px;}
.mr-codigo{font-family:monospace;font-size:.8rem;background:var(--lino2);padding:2px 7px;border-radius:4px;border:1px solid var(--borde);}

.btn-mr{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1rem;border-radius:7px;font-size:.85rem;font-weight:600;font-family:inherit;text-decoration:none;border:none;cursor:pointer;white-space:nowrap;flex-shrink:0;transition:transform .1s ease,background .15s;}
.btn-mr:hover{transform:translateY(-1px);}
.btn-mr-verde{background:var(--verde);color:#fff;}
.btn-mr-verde:hover{background:#256428;}
.btn-mr-hecho{background:var(--lino2);color:var(--verde);border:1.5px solid #a5d6a7;}
.btn-mr-secondary{background:var(--lino2);color:var(--caoba);border:1.5px solid var(--borde);text-decoration:none;}

.mr-progreso{background:var(--lino2);border:1px solid var(--borde);border-radius:8px;padding:.6rem 1rem;margin-bottom:1.2rem;font-size:.85rem;color:var(--tinta2);}
</style>

<div class="mr-page">
    <div class="mr-hd">
        <h1>Mi ruta</h1>
        <a href="<?= URL ?>entrega/iniciarentrega" class="btn-mr btn-mr-secondary">+ Armar otra ruta</a>
    </div>

    <?= Toast::flash() ?>

    <?php if (!$ruta): ?>
        <div class="mr-vacio">
            <span>🚚</span>
            <p>No tenés ninguna ruta activa en este momento.</p>
            <a href="<?= URL ?>entrega/iniciarentrega" class="btn-mr btn-mr-verde" style="margin-top:.6rem;display:inline-flex;">Armar una ruta nueva</a>
        </div>
    <?php else: ?>
        <?php
            $total     = count($entregas);
            $entregadas = count(array_filter($entregas, fn($e) => (int)$e['IdEstadosdeEntrega'] === 5));
        ?>
        <div class="mr-progreso">
            Ruta iniciada el <?= date('d/m/Y H:i', strtotime($ruta['FechaInicio'])) ?> —
            <strong><?= $entregadas ?> de <?= $total ?></strong> paradas entregadas.
        </div>

        <?php foreach ($entregas as $e): $yaEntregada = (int)$e['IdEstadosdeEntrega'] === 5; ?>
        <div class="mr-stop <?= $yaEntregada ? 'entregada' : '' ?>">
            <div class="mr-num"><?= $yaEntregada ? '✓' : $e['OrdenEnRuta'] ?></div>
            <div class="mr-info">
                <div class="mr-nom"><?= htmlspecialchars($e['ClienteNombre'] . ' ' . $e['ClienteApellido']) ?></div>
                <div class="mr-meta">
                    <?= htmlspecialchars($e['Direccion'] ?? 'Sin dirección') ?>
                    <?php if (!empty($e['Telefono'])): ?> · <?= htmlspecialchars($e['Telefono']) ?><?php endif; ?>
                    <?php if (!empty($e['Productos'])): ?><br><?= htmlspecialchars($e['Productos']) ?><?php endif; ?>
                </div>
                <span class="mr-zona"><?= htmlspecialchars($e['Localidad'] ?? 'Sin localidad') ?></span>
                <span class="mr-codigo"><?= htmlspecialchars($e['CodigoEntrega'] ?? '—') ?></span>
            </div>
            <?php if (!$yaEntregada): ?>
            <form method="POST" action="<?= URL ?>entrega/marcarentregado/<?= $e['Id'] ?>"
                  onsubmit="return confirm('¿Confirmás que ya se entregó a <?= addslashes(htmlspecialchars($e['ClienteNombre'])) ?>?')">
                <button type="submit" class="btn-mr btn-mr-verde">✓ Marcar entregado</button>
            </form>
            <?php else: ?>
            <span class="btn-mr btn-mr-hecho">Entregado</span>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if ($ruta && $entregadas < $total): ?>
<div id="gpsAviso" style="position:fixed;bottom:1rem;left:1rem;right:1rem;max-width:420px;margin:0 auto;
     background:var(--papel);border:1.5px solid var(--borde);border-radius:8px;padding:.6rem .9rem;
     font-size:.8rem;color:var(--g1);box-shadow:0 4px 16px rgba(0,0,0,.12);display:none;z-index:500;">
    📍 Compartiendo tu ubicación para que los clientes puedan seguir el envío…
</div>
<script>
    
(function() {
    if (!navigator.geolocation) return;

    function enviarUbicacion() {
        navigator.geolocation.getCurrentPosition(function(pos) {
            const aviso = document.getElementById('gpsAviso');
            fetch('<?= URL ?>entrega/actualizarubicacion', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'lat=' + pos.coords.latitude + '&lng=' + pos.coords.longitude
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                aviso.style.display = 'block';
                if (data.ok) {
                    aviso.style.color = 'var(--g1)';
                    aviso.textContent = '📍 Compartiendo tu ubicación para que los clientes puedan seguir el envío…';
                } else {
                    aviso.style.color = 'var(--rojo)';
                    aviso.textContent = '⚠️ No se pudo guardar tu ubicación: ' + (data.error || 'error desconocido');
                }
            })
            .catch(function() {
                aviso.style.display = 'block';
                aviso.style.color = 'var(--rojo)';
                aviso.textContent = '⚠️ Error de conexión al mandar la ubicación.';
            });
        }, function() {
            const aviso = document.getElementById('gpsAviso');
            aviso.style.display = 'block';
            aviso.style.color = 'var(--rojo)';
            aviso.textContent = '⚠️ No se pudo obtener el GPS (¿diste permiso de ubicación al navegador?).';
        }, { enableHighAccuracy: true, timeout: 8000 });
    }

    enviarUbicacion();
    setInterval(enviarUbicacion, 12000);
})();
</script>
<?php endif; ?>

</main>
<?php include INCLUDES . 'admin' . DS . 'footer_admin.php'; ?>
</div>
