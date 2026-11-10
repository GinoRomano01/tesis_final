<?php

$resumenResenas = $resumenResenas ?? ['total'=>0,'promedio'=>0];
$resenas        = $resenas        ?? [];
$prom           = (float) $resumenResenas['promedio'];
$totalR         = (int)   $resumenResenas['total'];
$sesionUsuario  = !empty($_SESSION['usuario_id']); 
?>
<section class="resenas-producto" style="margin:40px auto;max-width:1100px;padding:0 16px;">

    <h3 style="margin:0 0 8px 0;">Reseñas de clientes</h3>

    
    <div style="display:flex;align-items:center;gap:14px;margin-bottom:24px;">
        <div style="font-size:2.2rem;font-weight:700;line-height:1;">
            <?= number_format($prom, 1, ',', '.') ?>
        </div>
        <div>
            <div style="color:#f5b301;font-size:1.2rem;letter-spacing:2px;">
                <?php for ($i=1; $i<=5; $i++): ?>
                    <?= $i <= round($prom) ? '★' : '☆' ?>
                <?php endfor; ?>
            </div>
            <div style="color:#666;font-size:.9rem;">
                <?= $totalR ?> reseña<?= $totalR !== 1 ? 's' : '' ?>
            </div>
        </div>
    </div>

    
    <?php if ($sesionUsuario): ?>
    <form method="POST" action="<?= URL ?>resena/guardar"
          style="background:#fafafa;border:1px solid #e5e5e5;border-radius:10px;padding:18px;margin-bottom:32px;">
        <input type="hidden" name="id_producto" value="<?= (int)$producto['Id'] ?>">

        <div style="margin-bottom:10px;">
            <label style="font-weight:600;display:block;margin-bottom:6px;">Tu puntuación</label>
            <div class="estrellas-input" style="font-size:1.8rem;color:#ccc;cursor:pointer;display:inline-flex;gap:2px;user-select:none;">
                <?php for ($i=1; $i<=5; $i++): ?>
                    <input type="radio" name="puntuacion" value="<?= $i ?>" id="star<?= $i ?>"
                           style="display:none;" <?= $i===5?'checked':'' ?>>
                    <label for="star<?= $i ?>" data-val="<?= $i ?>" style="padding:0 1px;transition:color .12s;">★</label>
                <?php endfor; ?>
            </div>
        </div>

        <div style="margin-bottom:10px;">
            <label style="font-weight:600;display:block;margin-bottom:6px;">Título (opcional)</label>
            <input type="text" name="titulo" maxlength="150"
                   style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
        </div>

        <div style="margin-bottom:10px;">
            <label style="font-weight:600;display:block;margin-bottom:6px;">Tu reseña</label>
            <textarea name="contenido" rows="4" maxlength="<?= RESENA_MAX_CARACTERES ?>" required
                      style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;resize:vertical;"
                      placeholder="Contanos qué te pareció el producto…"></textarea>
            <small style="color:#888;">
                Máx <?= RESENA_MAX_CARACTERES ?> caracteres · La redacción se corrige automáticamente .
            </small>
        </div>

        <button type="submit"
                style="background:#222;color:#fff;border:0;padding:10px 22px;border-radius:6px;cursor:pointer;font-weight:600;">
            Publicar reseña
        </button>
    </form>

    <script>
    (function() {
        const cont = document.querySelector('.estrellas-input');
        if (!cont) return;
        const labels = cont.querySelectorAll('label');

        function pintar(v) {
            labels.forEach(x => {
                x.style.color = (+x.dataset.val <= v) ? '#f5b301' : '#ccc';
            });
        }

        labels.forEach(l => {
            l.addEventListener('mouseenter', e => pintar(+e.target.dataset.val));
            l.addEventListener('click', e => {
                const v = +e.target.dataset.val;
                document.getElementById('star'+v).checked = true;
                pintar(v);
            });
        });

        cont.addEventListener('mouseleave', () => {
            const checked = cont.querySelector('input:checked');
            pintar(checked ? +checked.value : 0);
        });

        const checked = cont.querySelector('input:checked');
        pintar(checked ? +checked.value : 0);
    })();
    </script>

    <?php else: ?>
        <div style="background:#fff8e1;border:1px solid #ffe082;padding:14px;border-radius:8px;margin-bottom:24px;">
            <a href="<?= URL ?>login" style="font-weight:600;">Iniciá sesión</a> para dejar tu reseña.
        </div>
    <?php endif; ?>

    
    <?php if (empty($resenas)): ?>
        <p style="color:#777;">Todavía no hay reseñas para este producto. ¡Sé el primero!</p>
    <?php else: ?>
        <div class="lista-resenas" style="display:flex;flex-direction:column;gap:18px;">
        <?php foreach ($resenas as $r): ?>
            <article style="border:1px solid #eee;border-radius:10px;padding:16px;background:#fff;">
                <header style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                    <strong><?= htmlspecialchars($r['AutorNombre']) ?></strong>
                    <span style="color:#f5b301;letter-spacing:2px;">
                        <?php for ($i=1; $i<=5; $i++): ?>
                            <?= $i <= (int)$r['Puntuacion'] ? '★' : '☆' ?>
                        <?php endfor; ?>
                    </span>
                </header>
                <?php if (!empty($r['Titulo'])): ?>
                    <h5 style="margin:4px 0;"><?= htmlspecialchars($r['Titulo']) ?></h5>
                <?php endif; ?>
                <p style="margin:6px 0;color:#333;line-height:1.5;">
                    <?= nl2br(htmlspecialchars($r['ContenidoPublicado'])) ?>
                </p>
                <small style="color:#999;">
                    <?= date('d/m/Y', strtotime($r['FechaCreacion'])) ?>
                </small>
            </article>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>