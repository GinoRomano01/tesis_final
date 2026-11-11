<?php
// Filtro de fechas que comparten todas las pantallas de estadísticas.
// Usa $rango, que arma EstadisticasController::rango(), y envía por GET a la misma sección.
// También define las funciones que arman las etiquetas de período de las tarjetas.
if (!function_exists('ffFecha')) {

    function ffFecha(string $f): string {
        return date('d/m/Y', strtotime($f));
    }

    function ffPeriodo(string $desde, string $hasta): string {
        return $desde === $hasta ? ffFecha($desde) : ffFecha($desde) . ' – ' . ffFecha($hasta);
    }

    function ffDelAl(string $desde, string $hasta): string {
        return $desde === $hasta ? 'El ' . ffFecha($desde) : 'Del ' . ffFecha($desde) . ' al ' . ffFecha($hasta);
    }

    function ffIcono(): string {
        return '<svg class="ff-icono" viewBox="0 0 16 16" aria-hidden="true"><rect x="2" y="3" width="12" height="11" rx="1.5"/><path d="M2 6.5h12M5.5 1.5v3M10.5 1.5v3"/></svg>';
    }

    // Aviso cuando el período comparado no dura lo mismo que el principal.
    function ffAvisoDuracion(int $dias, array $cmp): string {
        return $cmp['distinta'] ? ' <span class="ff-aviso">(' . $dias . ' vs ' . $cmp['dias'] . ' días)</span>' : '';
    }

    // Etiqueta del período de una tarjeta. Si hay comparación activa, la agrega
    // indicando cómo se ve: línea punteada, barra azul o columna "Comparado".
    function ffChip(array $rango, bool $conCmp = false, string $cmpComo = 'linea'): string {
        $propio = !empty($rango['propio']);
        $html = '<div class="chart-periodo"><span class="chip-periodo' . ($propio ? ' propio' : '') . '"'
              . ($propio ? ' title="Período propio de este gráfico"' : '') . '>' . ffIcono()
              . ffPeriodo($rango['desde'], $rango['hasta']) . '</span>';
        $cmp = $rango['cmp'] ?? null;
        if ($conCmp && $cmp && $cmp['activo']) {
            [$marca, $title] = match ($cmpComo) {
                'barra' => ['<i class="barra-cmp" aria-hidden="true"></i>', 'Barra azul del gráfico'],
                'tabla' => ['', 'Columna "Comparado" de la tabla'],
                'nube'  => ['', 'Marcas ▲ ▼ y "nuevo" de cada término'],
                default => ['<i class="linea-cmp" aria-hidden="true"></i>', 'Línea punteada del gráfico'],
            };
            $html .= '<span class="chip-periodo cmp" title="' . $title . '">'
                   . $marca . 'vs ' . ffPeriodo($cmp['desde'], $cmp['hasta'])
                   . ffAvisoDuracion($rango['dias'], $cmp) . '</span>';
        }
        return $html . '</div>';
    }

    // Variación de una fila contra el período comparado: "▲ 12%", "▼ 5%", "= 0%" o "nuevo".
    function ffVariacion(array $fila): string {
        if (!empty($fila['cmp_nuevo'])) {
            return '<span class="var-cmp nuevo" title="No aparecía en el período comparado">nuevo</span>';
        }
        $v = (float)$fila['cmp_var'];
        [$cls, $flecha] = $v > 0 ? ['sube', '▲'] : ($v < 0 ? ['baja', '▼'] : ['igual', '=']);
        return '<span class="var-cmp ' . $cls . '">' . $flecha . ' ' . number_format(abs($v), 1, ',', '.') . '%</span>';
    }

    // Celda "Comparado" de una tabla: valor del período comparado y su variación.
    function ffCeldaCmp(array $fila, string $fmt = 'num'): string {
        $v = (float)($fila['cmp'] ?? 0);
        $valor = $fmt === 'pesos' ? '$' . number_format($v, 0, ',', '.') : number_format($v, 0, ',', '.');
        return '<td class="num td-cmp"><span class="td-cmp-val">' . $valor . '</span>' . ffVariacion($fila) . '</td>';
    }

    // Botón "Período" de un gráfico, con su propio rango de fechas y comparación.
    function ffFiltroGrafico(array $rg): void {
        $base  = URL . 'estadisticas' . (METHOD !== 'index' ? '/' . METHOD : '');
        $ancla = '#g-' . $rg['clave'];
        $hoy   = date('Y-m-d');
        ?>
        <details class="fg">
            <summary class="fg-boton<?= $rg['propio'] ? ' propio' : '' ?>"
                     title="<?= $rg['propio'] ? 'Este gráfico tiene su propio período' : 'Elegir un período solo para este gráfico' ?>">
                <?= ffIcono() ?><span>Período</span>
            </summary>
            <form class="fg-panel" method="GET" action="<?= $base . $ancla ?>">
                <input type="hidden" name="g" value="<?= htmlspecialchars($rg['clave']) ?>">
                <div class="fg-titulo">
                    Período de este gráfico
                    <small><?= $rg['propio'] ? 'Propio, no sigue al filtro general' : 'Ahora sigue al filtro general' ?></small>
                </div>
                <label class="ff-campo">
                    <span>Desde</span>
                    <input type="date" name="g_desde" value="<?= htmlspecialchars($rg['desde']) ?>" max="<?= $hoy ?>" required>
                </label>
                <label class="ff-campo">
                    <span>Hasta</span>
                    <input type="date" name="g_hasta" value="<?= htmlspecialchars($rg['hasta']) ?>" max="<?= $hoy ?>" required>
                </label>
                <?php ffBloqueComparar('g_', $rg['cmp']); ?>
                <div class="fg-acciones">
                    <button type="submit" class="btn-filtrar">Aplicar</button>
                    <?php if ($rg['propio']): ?>
                    <a href="<?= $base ?>?g_igualar=<?= urlencode($rg['clave']) . $ancla ?>" class="btn-limpiar">Seguir filtro general</a>
                    <?php endif; ?>
                </div>
            </form>
        </details>
        <?php
    }

    // Etiqueta para tarjetas que no dependen del filtro (datos de hoy, pendientes, acumulados).
    function ffChipFijo(string $texto, string $title = ''): string {
        $t = $title !== '' ? ' title="' . htmlspecialchars($title) . '"' : '';
        return '<div class="chart-periodo"><span class="chip-periodo fijo"' . $t . '>' . ffIcono()
             . htmlspecialchars($texto) . '</span></div>';
    }

    // Controles "Comparar con".
    function ffBloqueComparar(string $p, array $cmp): void {
        $modos = ['anterior' => 'Período anterior', 'anio' => 'Mismo período del año pasado', 'medida' => 'A medida'];
        $hoy   = date('Y-m-d');
        ?>
        <div class="ff-comparar">
            <input type="hidden" name="<?= $p ?>cmp_on" value="0">
            <label class="ff-check">
                <input type="checkbox" name="<?= $p ?>cmp_on" value="1" data-ff-toggle<?= $cmp['activo'] ? ' checked' : '' ?>>
                <span>Comparar con</span>
            </label>
            <div class="ff-cmp-opciones" data-ff-opciones<?= $cmp['activo'] ? '' : ' hidden' ?>>
                <div class="ff-modos" role="radiogroup" aria-label="Período de comparación">
                    <?php foreach ($modos as $valor => $texto): ?>
                    <label class="ff-modo">
                        <input type="radio" name="<?= $p ?>cmp" value="<?= $valor ?>" data-ff-modo<?= $cmp['modo'] === $valor ? ' checked' : '' ?>>
                        <span><?= $texto ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
                <div class="ff-cmp-medida" data-ff-medida<?= $cmp['modo'] === 'medida' ? '' : ' hidden' ?>>
                    <label class="ff-campo">
                        <span>Desde</span>
                        <input type="date" name="<?= $p ?>cmp_desde" value="<?= htmlspecialchars($cmp['desde']) ?>" max="<?= $hoy ?>">
                    </label>
                    <label class="ff-campo">
                        <span>Hasta</span>
                        <input type="date" name="<?= $p ?>cmp_hasta" value="<?= htmlspecialchars($cmp['hasta']) ?>" max="<?= $hoy ?>">
                    </label>
                </div>
            </div>
        </div>
        <?php
    }
}

$ffBase   = URL . 'estadisticas' . (METHOD !== 'index' ? '/' . METHOD : '');
$ffAtajos = [7 => '7 días', 30 => '30 días', 90 => '90 días', 'mes' => 'Este mes', 365 => '1 año'];
$ffCmp    = $rango['cmp'];
$ffPropios = count(array_filter($graf ?? [], fn($g) => $g['propio']));
?>
<form class="filtro-fechas" method="GET" action="<?= $ffBase ?>">
    <div class="ff-atajos" role="group" aria-label="Períodos rápidos">
        <?php foreach ($ffAtajos as $valor => $texto):
            $href   = $ffBase . ($valor === 'mes' ? '?mes=1' : '?dias=' . $valor);
            $activo = ($rango['atajo'] ?? null) === $valor;
        ?>
        <a href="<?= $href ?>" class="<?= $activo ? 'active' : '' ?>"<?= $activo ? ' aria-current="true"' : '' ?>><?= $texto ?></a>
        <?php endforeach; ?>
    </div>

    <div class="ff-rango">
        <label class="ff-campo">
            <span>Desde</span>
            <input type="date" name="desde" value="<?= htmlspecialchars($rango['desde']) ?>" max="<?= date('Y-m-d') ?>" required>
        </label>
        <label class="ff-campo">
            <span>Hasta</span>
            <input type="date" name="hasta" value="<?= htmlspecialchars($rango['hasta']) ?>" max="<?= date('Y-m-d') ?>" required>
        </label>
        <button type="submit" class="btn-filtrar">Aplicar</button>
        <a href="<?= $ffBase ?>?limpiar=1" class="btn-limpiar" title="Volver a los últimos 30 días, sin comparar">Limpiar</a>
    </div>

    <?php ffBloqueComparar('', $ffCmp); ?>

    <div class="ff-resumen">
        Mostrando del <strong><?= ffFecha($rango['desde']) ?></strong>
        al <strong><?= ffFecha($rango['hasta']) ?></strong>
        · <?= $rango['dias'] ?> <?= $rango['dias'] === 1 ? 'día' : 'días' ?>
        <?php if ($ffCmp['activo']): ?>
        · comparando con <strong><?= ffPeriodo($ffCmp['desde'], $ffCmp['hasta']) ?></strong><?= ffAvisoDuracion($rango['dias'], $ffCmp) ?>
        <?php endif; ?>
        <?php if ($ffPropios): ?>
        <span class="ff-propios">
            · <?= $ffPropios ?> <?= $ffPropios === 1 ? 'gráfico tiene' : 'gráficos tienen' ?> período propio y no siguen este filtro
            (<a href="<?= $ffBase ?>?g_igualar=todos">igualar todos</a>)
        </span>
        <?php endif; ?>
    </div>
</form>

<script>
// Muestra u oculta las opciones de "Comparar con"
document.addEventListener('change', function (e) {
    const caja = e.target.closest('.ff-comparar');
    if (!caja) return;
    if (e.target.matches('[data-ff-toggle]')) {
        caja.querySelector('[data-ff-opciones]').hidden = !e.target.checked;
    }
    if (e.target.matches('[data-ff-modo]')) {
        caja.querySelector('[data-ff-medida]').hidden = e.target.value !== 'medida';
    }
});

// Panel "Período" de cada gráfico: se abre de a uno y se cierra con clic afuera o Escape
document.addEventListener('toggle', function (e) {
    if (!e.target.matches('details.fg') || !e.target.open) return;
    document.querySelectorAll('details.fg[open]').forEach(d => { if (d !== e.target) d.open = false; });
}, true);
document.addEventListener('click', function (e) {
    document.querySelectorAll('details.fg[open]').forEach(d => { if (!d.contains(e.target)) d.open = false; });
});
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') document.querySelectorAll('details.fg[open]').forEach(d => d.open = false);
});
</script>
